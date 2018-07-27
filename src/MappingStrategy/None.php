<?php namespace SRAG\Plugins\Hub2\MappingStrategy;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class None
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class None implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		return 0;
	}
}
