<?php

namespace SRAG\Plugins\Hub2\Sync;

use Error;
use Exception;
use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Exception\AbortOriginSyncException;
use SRAG\Plugins\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\Plugins\Hub2\Exception\AbortSyncException;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\NullDTO;
use SRAG\Plugins\Hub2\Object\IObject;
use SRAG\Plugins\Hub2\Object\IObjectFactory;
use SRAG\Plugins\Hub2\Object\IObjectRepository;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use Throwable;

/**
 * Class Sync
 *
 * @package SRAG\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSync implements IOriginSync {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IObjectRepository
	 */
	protected $repository;
	/**
	 * @var IObjectFactory
	 */
	protected $factory;
	/**
	 * @var IDataTransferObject[]
	 */
	protected $dtoObjects = [];
	/**
	 * @var Exception[] array
	 */
	protected $exceptions = [];
	/**
	 * @var IObjectSyncProcessor
	 */
	protected $processor;
	/**
	 * @var IObjectStatusTransition
	 */
	protected $statusTransition;
	/**
	 * @var IOriginImplementation
	 */
	protected $implementation;
	/**
	 * @var int
	 */
	protected $countDelivered = 0;
	/**
	 * @var array
	 */
	protected $countProcessed = [
		IObject::STATUS_CREATED => 0,
		IObject::STATUS_UPDATED => 0,
		IObject::STATUS_DELETED => 0,
		IObject::STATUS_IGNORED => 0,
		IObject::STATUS_NOTHING_TO_UPDATE => 0
	];
	/**
	 * @var OriginNotifications
	 */
	protected $notifications;


	/**
	 * @param IOrigin                 $origin
	 * @param IObjectRepository       $repository
	 * @param IObjectFactory          $factory
	 * @param IObjectSyncProcessor    $processor
	 * @param IObjectStatusTransition $transition
	 * @param IOriginImplementation   $implementation
	 * @param OriginNotifications     $notifications
	 */
	public function __construct(IOrigin $origin, IObjectRepository $repository, IObjectFactory $factory, IObjectSyncProcessor $processor, IObjectStatusTransition $transition, IOriginImplementation $implementation, OriginNotifications $notifications) {
		$this->origin = $origin;
		$this->repository = $repository;
		$this->factory = $factory;
		$this->processor = $processor;
		$this->statusTransition = $transition;
		$this->implementation = $implementation;
		$this->notifications = $notifications;
	}


	/**
	 * @throws AbortOriginSyncException
	 * @throws HubException
	 * @throws Throwable
	 */
	public function execute() {
		// Any exception during the three stages (connect/parse/build hub objects) is forwarded to the global sync
		// as the sync of this origin cannot continue.
		try {
			$this->implementation->beforeSync();
			$this->implementation->connect();
			$count = $this->implementation->parseData();
			$this->countDelivered = $count;
			// Check if the origin aborts its sync if the amount of delivered data is not enough
			if ($this->origin->config()->getCheckAmountData()) {
				$threshold = $this->origin->config()->getCheckAmountDataPercentage();
				$total = $this->repository->count();
				$percentage = ($total > 0 && $count > 0) ? (100 / $total * $count) : 0;
				if ($total > 0 && ($percentage < $threshold)) {
					$msg = "Amount of delivered data not sufficient: Got {$count} datasets, 
					which is " . number_format($percentage, 2) . "% of the existing data in hub, 
					need at least {$threshold}% according to origin config";
					throw new AbortOriginSyncException($msg);
				}
			}
			$this->dtoObjects = $this->implementation->buildObjects();
		} catch (HubException $e) {
			$this->exceptions[] = $e;
			throw $e;
		} catch (Throwable $e) {
			// Note: Should not happen in the stages above, as only exceptions of type HubException should be raised.
			// Throwable collects any exceptions AND Errors from PHP 7
			$this->exceptions[] = $e;
			throw $e;
		}

		// Sort dto objects
		$this->dtoObjects = $this->sortDtoObjects($this->dtoObjects);

		// Start SYNC of delivered objects --> CREATE & UPDATE
		// ======================================================================================================
		// 1. Update current status to an intermediate status so the processor knows if it must CREATE/UPDATE/DELETE
		// 2. Let the processor process the corresponding ILIAS object

		$ext_ids_delivered = [];
		$type = $this->origin->getObjectType();
		foreach ($this->dtoObjects as $dto) {
			$ext_ids_delivered[] = $dto->getExtId();
			/** @var IObject $object */
			$object = $this->factory->$type($dto->getExtId());
			$object->setDeliveryDate(time());
			// We merge the existing data with the new data
			$data = array_merge($object->getData(), $dto->getData());
			$dto->setData($data);
			// Set the intermediate status before processing the ILIAS object
			$object->setStatus($this->statusTransition->finalToIntermediate($object));
			$this->processObject($object, $dto);
		}

		// Start SYNC of objects not being delivered --> DELETE
		// ======================================================================================================

		foreach ($this->repository->getToDelete($ext_ids_delivered) as $object) {
			$nullDTO = new NullDTO($object->getExtId()); // There is no DTO available / needed for the deletion process (data has not been delivered)
			$object->setStatus(IObject::STATUS_TO_DELETE);
			$this->processObject($object, $nullDTO);
		}

		try {
			$this->implementation->afterSync();
		} catch (Throwable $e) {
			$this->exceptions[] = $e;
			throw $e;
		}
		$this->getOrigin()->setLastRun(date(DATE_ATOM));

		$this->getOrigin()->update();
	}


	/**
	 * @param IDataTransferObject[] $dtos
	 *
	 * @return IDataTransferObject[]
	 */
	protected function sortDtoObjects(array $dtos): array {
		// Create IDataTransferObjectSort objects
		$sort_dtos = array_map(function (IDataTransferObject $dto): IDataTransferObjectSort {
			return new DataTransferObjectSort($dto);
		}, $dtos);

		// Request processor to set sort levels
		if ($this->processor->handleSort($sort_dtos)) {
			// Sort by level
			usort($sort_dtos, function (IDataTransferObjectSort $sort_dto1, IDataTransferObjectSort $sort_dto2): int {
				return ($sort_dto1->getLevel() - $sort_dto2->getLevel());
			});

			// Back to IDataTransferObject objects
			$dtos = array_map(function (IDataTransferObjectSort $sort_dto): IDataTransferObject {
				return $sort_dto->getDtoObject();
			}, $sort_dtos);
		}

		return $dtos;
	}


	/**
	 * @inheritdoc
	 */
	public function getExceptions() {
		return $this->exceptions;
	}


	/**
	 * @inheritdoc
	 */
	public function getCountProcessedByStatus($status) {
		return $this->countProcessed[$status];
	}


	/**
	 * @inheritdoc
	 */
	public function getCountProcessedTotal() {
		return array_sum($this->countProcessed);
	}


	/**
	 * @inheritdoc
	 */
	public function getCountDelivered() {
		return $this->countDelivered;
	}


	/**
	 * @inheritdoc
	 */
	public function getNotifications() {
		return $this->notifications;
	}


	/**
	 * @param IObject             $object
	 * @param IDataTransferObject $dto
	 *
	 * @throws AbortOriginSyncException
	 * @throws HubException
	 */
	protected function processObject(IObject $object, IDataTransferObject $dto) {
		try {
			$this->processor->process($object, $dto, $this->origin->isUpdateForced());
			$this->incrementProcessed($object->getStatus());
		} catch (AbortSyncException $e) {
			// Any exceptions aborting the global or current sync are forwarded to global sync
			$this->exceptions[] = $e;
			$object->save();
			throw $e;
		} catch (AbortOriginSyncOfCurrentTypeException $e) {
			$this->exceptions[] = $e;
			$object->save();
			throw $e;
		} catch (AbortOriginSyncException $e) {
			$this->exceptions[] = $e;
			$object->save();
			throw $e;
		} catch (Exception $e) {
			// General exceptions during processing the ILIAS objects are forwarded to the origin implementation,
			// which decides how to proceed, e.g. continue or abort
			$this->exceptions[] = $e;
			$object->save();
			$this->implementation->handleException($e);
		} catch (Error $e) {
			// PHP 7: Throwable of type Error always lead to abort of the sync of current origin
			$this->exceptions[] = $e;
			$object->save();
			throw new AbortOriginSyncException($e->getMessage());
		}
	}


	/**
	 * @param int $status
	 */
	protected function incrementProcessed($status) {
		$this->countProcessed[$status] ++;
	}


	/**
	 * @inheritDoc
	 */
	public function getOrigin() {
		return $this->origin;
	}
}
