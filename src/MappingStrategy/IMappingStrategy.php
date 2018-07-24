<?php namespace SRAG\Plugins\Hub2\MappingStrategy;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IMappingStrategy
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategy {

	/**
	 * @param IDataTransferObject $dto
	 *
	 * @return int ILIAS ID which will be passed to the Processor.
	 * Return 0 if no mapping possible, therefore the Object will be created.
	 * Return an existing ILIAS ID which leads to an update of the Object
	 */
	public function map(IDataTransferObject $dto): int;
}
