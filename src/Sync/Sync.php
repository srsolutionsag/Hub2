<?php namespace SRAG\Hub2\Sync;

use SRAG\Hub2\Config\HubConfig;
use SRAG\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\Hub2\Exception\AbortSyncException;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Log\OriginLog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\IObjectRepository;
use SRAG\Hub2\Object\ObjectFactory;
use SRAG\Hub2\Origin\Config\OriginImplementationFactory;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Hub2\Sync\Processor\SyncProcessorFactory;


/**
 * Class Sync
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
 */
class Sync implements ISync {

	/**
	 * @var IOrigin[]
	 */
	protected $origins = [];
	/**
	 * @var \Exception[] array
	 */
	protected $exceptions = [];
	/**
	 * @var OriginSync[] array
	 */
	protected $originSyncs = [];

	/**
	 * Execute the syncs of the given origins.
	 *
	 * Note: This class assumes that the origins are in the correct order, e.g. as returned by
	 * OriginRepository::allActive() --> [users > categories > courses > courseMemberships...]
	 *
	 * @param IOrigin[] $origins
	 */
	public function __construct($origins) {
		$this->origins = $origins;
	}

	/**
	 * @inheritdoc
	 */
	public function execute() {
		$skip_object_type = '';
		foreach ($this->origins as $origin) {
			if ($origin->getObjectType() == $skip_object_type) {
				continue;
			}
			$transition = new ObjectStatusTransition($origin->config());
			$originLog = new OriginLog($origin);
			$originNotifications = new OriginNotifications();
			$implementationFactory = new OriginImplementationFactory(new HubConfig(), $origin, $originLog, $originNotifications);
			$originImplementation = $implementationFactory->instance();
			$originSync = new OriginSync(
				$origin,
				$this->getObjectRepository($origin),
				new ObjectFactory($origin),
				$this->getSyncProcessor($origin, $originImplementation, $transition, $originLog, $originNotifications),
				$transition,
				$originImplementation,
				$originNotifications
			);
			$this->originSyncs[$origin->getId()] = $originSync;
			try {
				$originSync->execute();
			} catch (AbortSyncException $e) {
				// This must abort the global sync, none following origin syncs are executed
				$this->exceptions = array_merge($this->exceptions, $originSync->getExceptions());
				break;
			} catch (AbortOriginSyncOfCurrentTypeException $e) {
				// This must abort all following origin syncs of the same object type
				$skip_object_type = $origin->getObjectType();
			} catch (\Exception $e) {
				// Any other exception means that we abort the current origin sync and continue with the next origin
				$this->exceptions[] = $e;
			} catch (\Throwable $e) {
				// Any other exception means that we abort the current origin sync and continue with the next origin
				$this->exceptions[] = $e;
			}
			$this->exceptions = array_merge($this->exceptions, $originSync->getExceptions());
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
	public function getOriginSyncs() {
		return $this->originSyncs;
	}


	/**
	 * @param IOrigin $origin
	 * @return IObjectRepository
	 */
	protected function getObjectRepository(IOrigin $origin) {
		$class = "SRAG\\Hub2\\Object\\" . ucfirst($origin->getObjectType()) . 'Repository';
		return new $class($origin);
	}

	/**
	 * @param IOrigin $origin
	 * @param IOriginImplementation $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog $originLog
	 * @param OriginNotifications $originNotifications
	 * @return IObjectSyncProcessor
	 */
	protected function getSyncProcessor(IOrigin $origin,
	                                    IOriginImplementation $implementation,
	                                    IObjectStatusTransition $transition,
	                                    ILog $originLog,
	                                    OriginNotifications $originNotifications) {
		$processorFactory = new SyncProcessorFactory($origin, $implementation, $transition, $originLog, $originNotifications);
		$processor = $origin->getObjectType() . 'Processor';
		return $processorFactory->$processor();
	}
}