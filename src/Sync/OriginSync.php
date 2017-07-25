<?php namespace SRAG\Hub2\Sync;

use SRAG\Hub2\Exception\AbortOriginSyncException;
use SRAG\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\Hub2\Exception\AbortSyncException;
use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Object\IObjectFactory;
use SRAG\Hub2\Object\IObjectRepository;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Sync\Processor\IObjectSyncProcessor;


/**
 * Class Sync
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync
 */
class OriginSync implements IOriginSync {

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
	protected $dtos = [];

	/**
	 * @var array
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
	 * @var int
	 */
	protected $count_delivered = 0;

	/**
	 * @var array
	 */
	protected $count_processed = [
		IObject::STATUS_CREATED => 0,
		IObject::STATUS_UPDATED => 0,
		IObject::STATUS_DELETED => 0,
		IObject::STATUS_IGNORED => 0,
	];

	/**
	 * @param IOrigin $origin
	 * @param IObjectRepository $repository
	 * @param IObjectFactory $factory
	 * @param IObjectSyncProcessor $processor
	 * @param IObjectStatusTransition $transition
	 */
	public function __construct(IOrigin $origin,
	                            IObjectRepository $repository,
	                            IObjectFactory $factory,
	                            IObjectSyncProcessor $processor,
	                            IObjectStatusTransition $transition
	) {
		$this->origin = $origin;
		$this->repository = $repository;
		$this->factory = $factory;
		$this->processor = $processor;
		$this->statusTransition = $transition;
	}


	public function execute() {
		// Any exception during the three stages (connect/parse/build hub objects) is forwarded to the global sync
		// as the sync of this origin cannot continue.
		$implementation = $this->origin->implementation();
		try {
			$implementation->beforeSync();
			$implementation->connect();
			$count = $implementation->parseData();
			$this->count_delivered = $count;
			// Check if the origin aborts its sync if the amount of delivered data is not enough
			if ($this->origin->config()->getCheckAmountData()) {
				$threshold = $this->origin->config()->getCheckAmountDataPercentage();
				$total = $this->repository->count();
				$percentage = ($total > 0 && $count > 0) ? (100 / $total * $count) : 0;
				if ($percentage < $threshold) {
					$msg = "Amount of delivered data not sufficient: Got {$count} datasets, 
					which is " . number_format($percentage, 2) . "% of the existing data in hub, 
					need at least {$threshold}% according to origin config";
					throw new AbortOriginSyncException($msg);
				}
			}
			$this->dtos = $implementation->buildObjects();
		} catch (HubException $e) {
			$this->exceptions[] = $e;
			throw $e;
		} catch (\Throwable $e) {
			// Note: Should not happen in the stages above, as only exceptions of type HubException should be raised.
			// Throwable collects any exceptions AND Errors from PHP 7
			$this->exceptions[] = $e;
			throw $e;
		}

		// Start SYNC of delivered objects --> CREATE & UPDATE
		// ======================================================================================================
		// 1. Update current status to an intermediate status so the processor knows if it must CREATE/UPDATE/DELETE
		// 2. Let the processor process the corresponding ILIAS object

		$ext_ids_delivered = [];
		$type = $this->origin->getObjectType();
		foreach ($this->dtos as $dto) {
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
			$object->setStatus(IObject::STATUS_TO_DELETE);
			// There is no DTO available / needed for the deletion process (data has not been delivered)
			$this->processObject($object, null);
		}

		try {
			$implementation->afterSync();
		} catch (\Throwable $e) {
			$this->exceptions[] = $e;
			throw $e;
		}
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
		return $this->count_processed[$status];
	}

	/**
	 * @inheritdoc
	 */
	public function getCountProcessedTotal() {
		return array_sum($this->count_processed);
	}

	/**
	 * @inheritdoc
	 */
	public function getCountDelivered() {
		return $this->count_delivered;
	}

	/**
	 * @param IObject $object
	 * @param IDataTransferObject $dto
	 * @throws AbortOriginSyncException
	 * @throws HubException
	 */
	protected function processObject(IObject $object, IDataTransferObject $dto) {
		try {
			$this->processor->process($object, $dto);
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
		} catch (\Exception $e) {
			// General exceptions during processing the ILIAS objects are forwarded to the origin implementation,
			// which decides how to proceed, e.g. continue or abort
			$this->exceptions[] = $e;
			$object->save();
			$this->origin->implementation()->handleException($e);
		} catch (\Error $e) {
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
		$this->count_processed[$status]++;
	}
}