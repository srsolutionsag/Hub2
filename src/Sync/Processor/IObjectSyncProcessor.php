<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IDataTransferObject;

/**
 * Interface ObjectProcessor
 * @package SRAG\Hub2\Sync\Processor
 */
interface IObjectSyncProcessor {

	// This prefix MUST be used by the processors when setting the import ID on the ILIAS objects
	const IMPORT_PREFIX = 'srhub_';

	/**
	 * Process the given hub object and its corresponding DTO:
	 *
	 * 1. Depending on the status: Create, Update or Delete corresponding ILIAS object
	 * 2. Execute other actions based on the configuration of the origin.
	 * 3. Pass the DTO to the hooks of the origin
	 *
	 * @param IObject $object
	 * @param IDataTransferObject $dto
	 */
	public function process(IObject $object, IDataTransferObject $dto);

}