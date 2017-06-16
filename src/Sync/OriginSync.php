<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;

use SRAG\ILIAS\Plugins\Exception\AbortOriginSyncException;
use SRAG\ILIAS\Plugins\Exception\HubException;
use SRAG\ILIAS\Plugins\Hub2\Object\IObject;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectDTO;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectFactory;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectRepository;
use SRAG\ILIAS\Plugins\Hub2\Object\UserDTO;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;
use SRAG\ILIAS\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

/**
 * Class Sync
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
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
			$object->setDeliveryDateMicro(microtime(true));
			$object->setData($dto->getData());
			$object->setStatus($this->transition->finalToIntermediate($object));
			$this->processObject($object);
		}

		// Start SYNC of objects not being delivered --> DELETE
		// ======================================================================================================



		// Set the status TO_DELETE for all objects which have not been delivered and process deletion
//		$status->updateToDeleteStatus($this->objects);
//		foreach ($this->repository->getByStatus(IObject::STATUS_TO_DELETE) as $object) {
//			$this->processObject($object);
//		}

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
	 * @param IObject $object
	 * @throws AbortOriginSyncException
	 */
	protected function processObject(IObject $object) {
		try {
			$this->processor->process($object);
		} catch (\Exception $e) {
			$this->exceptions[] = $e;
			$object->save();
			// Origin implementation decides how to proceed
			$this->origin->implementation()->handleException($e);
		} catch (\Error $e) {
			// PHP 7: Throwable of type Error always lead to abort of the sync of current origin
			$this->exceptions[] = $e;
			$object->save();
			throw new AbortOriginSyncException($e->getMessage());
		}
	}
}