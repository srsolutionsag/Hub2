<?php

namespace srag\Plugins\Hub2\Sync;

use Exception;
use ilHub2Plugin;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use srag\Plugins\Hub2\Exception\AbortSyncException;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Sync\GlobalHook\GlobalHook;
use Throwable;

/**
 * Class Sync
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Sync implements ISync {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOrigin[]
	 */
	protected $origins = [];
	/**
	 * @var Exception[] array
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
		try {
			$global_hook = new GlobalHook();
			if (!$global_hook->beforeSync($this->origins)) {
				$global_hook->handleExceptions($this->exceptions);

				return;
			}
		} catch (Exception $e) {
			$this->exceptions[] = $e;
		}

		foreach ($this->origins as $origin) {
			if ($origin->getObjectType() == $skip_object_type) {
				continue;
			}
			$originSyncFactory = new OriginSyncFactory($origin);
			$originSync = $originSyncFactory->instance();
			try {
				$originSync->execute();
			} catch (AbortSyncException $e) {
				// This must abort the global sync, none following origin syncs are executed
				$this->exceptions = array_merge($this->exceptions, $originSync->getExceptions());
				break;
			} catch (AbortOriginSyncOfCurrentTypeException $e) {
				// This must abort all following origin syncs of the same object type
				$skip_object_type = $origin->getObjectType();
			} catch (Exception $e) {
				// Any other exception means that we abort the current origin sync and continue with the next origin
				$this->exceptions[] = $e;
			} catch (Throwable $e) {
				// Any other exception means that we abort the current origin sync and continue with the next origin
				$this->exceptions[] = $e;
			}
			$this->exceptions = array_merge($this->exceptions, $originSync->getExceptions());
		}
		try {
			$global_hook->afterSync($this->origins);
		} catch (Exception $e) {
			$this->exceptions[] = $e;
		}
		$global_hook->handleExceptions($this->exceptions);
	}


	/**
	 * @inheritdoc
	 */
	public function getExceptions() {
		return $this->exceptions;
	}
}
