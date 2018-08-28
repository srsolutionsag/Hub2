<?php

namespace SRAG\Plugins\Hub2\MappingStrategy;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class IMappingStrategyAwareDataTransferObject
 *
 * @package SRAG\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategyAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @inheritDoc
	 */
	public function getMappingStrategy(): IMappingStrategy;


	/**
	 * @inheritDoc
	 */
	public function overrideMappingStrategy(IMappingStrategy $strategy): IDataTransferObject;
}
