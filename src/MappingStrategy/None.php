<?php

namespace SRAG\Plugins\Hub2\MappingStrategy;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class None
 *
 * @package SRAG\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class None extends AMappingStrategy implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		return 0;
	}
}
