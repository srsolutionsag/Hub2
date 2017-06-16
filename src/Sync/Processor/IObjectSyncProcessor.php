<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync\Processor;

use SRAG\ILIAS\Plugins\Hub2\Object\IObject;

/**
 * Interface ObjectProcessor
 * @package SRAG\ILIAS\Plugins\Hub2\Sync\Processor
 */
interface IObjectSyncProcessor {

	/**
	 * Process the given hub object, meaning:
	 * Create/update/delete the corresponding ILIAS object based on the status.
	 * Execute other actions based on the configuration of the origin.
	 *
	 * @param IObject $object
	 */
	public function process(IObject $object);

//	/**
//	 * Update all properties on the hub object from the given DTO object.
//	 * Note: Do NOT modify the status, as this is globally handled by the sync. Also you don't
//	 * need to persist the data, e.g. calling IObject::save(), this is also handled afterwards.
//	 *
//	 * Return the corresponding IObject with updated properties from the DTO.
//	 *
//	 * @param IObjectDTO $object
//	 * @return IObject
//	 */
//	public function updateObjectFromDTO(IObjectDTO $object);

}