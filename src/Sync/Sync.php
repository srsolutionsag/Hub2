<?php namespace SRAG\Hub2\Sync;
use SRAG\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\Hub2\Exception\AbortSyncException;
use SRAG\Hub2\Object\IObjectFactory;
use SRAG\Hub2\Object\IObjectRepository;
use SRAG\Hub2\Object\ObjectFactory;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginRepository;
use SRAG\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Hub2\Sync\Processor\SyncProcessorFactory;


/**
 * Class Sync
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
 */
class Sync implements ISync {

	/**
	 * @var IOriginRepository
	 */
	protected $repository;

	/**
	 * @var array
	 */
	protected $exceptions = [];

	/**
	 * @param IOriginRepository $repository
	 */
	public function __construct(IOriginRepository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @inheritdoc
	 */
	public function execute() {
		$skip_object_type = '';
		foreach ($this->repository->allActive() as $origin) {
			if ($origin->getObjectType() == $skip_object_type) {
				continue;
			}
			$transition = new ObjectStatusTransition($origin->config());
			$sync = new OriginSync(
				$origin,
				$this->getObjectRepository($origin),
				new ObjectFactory($origin),
				$this->getSyncProcessor($origin, $transition),
				$transition
			);
			try {
				$sync->execute();
			} catch (AbortSyncException $e) {
				// This must abort the global sync, none following origin syncs are executed
				$this->exceptions = array_merge($this->exceptions, $sync->getExceptions());
				break;
			} catch (AbortOriginSyncOfCurrentTypeException $e) {
				// This must abort all following origin syncs of the same object type
				$skip_object_type = $origin->getObjectType();
			} catch (\Throwable $e) {
				// Any other exception means that we abort the current origin sync and continue with the next origin
				$this->exceptions[] = $e;
			}
			$this->exceptions = array_merge($this->exceptions, $sync->getExceptions());
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getExceptions() {
		return $this->exceptions;
	}

	/**
	 * @param IOrigin $origin
	 * @return IObjectRepository
	 */
	protected function getObjectRepository(IOrigin $origin) {
		$class = ucfirst($origin->getObjectType()) . 'Repository';
		return new $class($origin);
	}

	/**
	 * @param IOrigin $origin
	 * @param IObjectStatusTransition $transition
	 * @return IObjectSyncProcessor
	 */
	protected function getSyncProcessor(IOrigin $origin, IObjectStatusTransition $transition) {
		$processorFactory = new SyncProcessorFactory($origin, $transition);
		$processor = $origin->getObjectType() . 'Processor';
		return $processorFactory->$processor();
	}
}