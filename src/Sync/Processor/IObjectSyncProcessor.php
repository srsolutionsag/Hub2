<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Object\IObject;

/**
 * Interface ObjectProcessor
 * @package SRAG\Hub2\Sync\Processor
 */
interface IObjectSyncProcessor {

	// This prefix MUST be used by the processors when setting the import ID on the ILIAS objects
	const IMPORT_PREFIX = 'srhub_';

	/**
	 * Process the given hub object, meaning:
	 * Create/update/delete the corresponding ILIAS object based on the status.
	 * Execute other actions based on the configuration of the origin.
	 *
	 * @param IObject $object
	 */
	public function process(IObject $object);

}