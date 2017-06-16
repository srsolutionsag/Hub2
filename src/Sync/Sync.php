<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;

use SRAG\ILIAS\Plugins\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\ILIAS\Plugins\Exception\AbortSyncException;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectFactory;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectRepository;
use SRAG\ILIAS\Plugins\Hub2\Object\ObjectFactory;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOriginRepository;
use SRAG\ILIAS\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

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
		$skip_object_type = 0;
		foreach ($this->repository->allActive() as $origin) {
			if ($origin->getObjectType() == $skip_object_type) {
				continue;
			}
			$object_factory = new ObjectFactory($origin);
			$sync = new OriginSync(
				$origin,
				$this->getObjectRepository($origin),
				$object_factory,
				$this->getSyncProcessor($origin, $object_factory)
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
	 * @param IObjectFactory $factory
	 * @return IObjectSyncProcessor
	 */
	protected function getSyncProcessor(IOrigin $origin, IObjectFactory $factory) {
		$class = ucfirst($origin->getObjectType()) . 'SyncProcessor';
		return new $class($origin, $factory);
	}
}