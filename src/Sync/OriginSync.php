<?php namespace SRAG\Hub2\Sync;

use SRAG\Hub2\Exception\AbortOriginSyncException;
use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IObjectDTO;
use SRAG\Hub2\Object\IObjectFactory;
use SRAG\Hub2\Object\IObjectRepository;
use SRAG\Hub2\Origin\IOrigin;
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
	 * @var IObjectDTO[]
	 */
	protected $objects = [];

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
	protected $transition;

	/**
	 * @var int
	 */
	protected $count_delivered = 0;

	/**
	 * @var array
	 */
	protected $count_processed_status = [
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
		$this->transition = $transition;
	}


	public function execute() {
		$implementation = $this->origin->implementation();
		// Any exception during the three stages (connect/parse/build hub objects) is forwarded to the global sync
		// as the sync of this origin cannot continue.
		try {
			$implementation->beforeSync();
			$implementation->connect();
			$count = $implementation->parseData();
			$this->count_delivered = $count;
			// Check if the origin aborts its sync if the amount of delivered data is not enough
			if ($this->origin->config()->getCheckAmountData()) {
				$threshold = $this->origin->config()->getCheckAmountDataPercentage();
				$total = $this->repository->count();
				$percentage = 100 / $total * $count;
				if ($percentage < $threshold) {
					$msg = "Amount of delivered data not sufficient: Got {$count} data, 
					which is " . number_format($percentage, 2) . "% of the existing data in hub, 
					need at least {$threshold}% according to origin config";
					throw new AbortOriginSyncException($msg);
				}
			}
			$this->objects = $implementation->buildHubDTOs();
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
		// 1. Pass new data from DTO
		// 2. Update current status to an intermediate status so the processor knows if it must CREATE/UPDATE/DELETE
		// 3. Let the processor process the corresponding ILIAS object

		foreach ($this->objects as $dto) {
			$object = $this->factory->objectFromDTO($dto);
			$object->setDeliveryDate(time());
			$object->setData($dto->getData());
			$object->setStatus($this->transition->finalToIntermediate($object));
			$this->processObject($object);
		}

		// Start SYNC of objects not being delivered --> DELETE
		// ======================================================================================================
		$ext_ids_delivered = array_map(function ($object) {
			/** @var $object IObject */
			return $object->getExtId();
		}, $this->objects);
		foreach ($this->repository->getToDelete($ext_ids_delivered) as $object) {
			$object->setStatus(IObject::STATUS_TO_DELETE);
			$this->processObject($object);
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
		return $this->count_processed_status[$status];
	}

	/**
	 * @inheritdoc
	 */
	public function getCountProcessedTotal() {
		$sum = 0;
		foreach ($this->count_processed_status as $count) {
			$sum += $count;
		}
		return $sum;
	}

	/**
	 * @inheritdoc
	 */
	public function getCountDelivered() {
		return $this->count_delivered;
	}

	/**
	 * @param IObject $object
	 * @throws AbortOriginSyncException
	 * @throws HubException
	 */
	protected function processObject(IObject $object) {
		try {
			$this->processor->process($object);
			$this->incrementProcessed($object->getStatus());
		} catch (HubException $e) {
			// Origin implementation could throw HubExceptions, e.g. aborting current sync
			// Exception is forwarded to global sync
			$this->exceptions[] = $e;
			$object->save();
			throw $e;
		} catch (\Exception $e) {
			// General exceptions during processing the ILIAS objects are forwarded to the origin implementation,
			// which decides how to proceed
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
		$this->count_processed_status[$status]++;
	}
}