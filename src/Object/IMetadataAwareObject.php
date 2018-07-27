<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareObject extends IObject {

	/**
	 * @return IMetadata[]
	 */
	public function getMetaData(): array;


	/**
	 * @param IMetadata[] $metadata
	 *
	 * @return void
	 */
	public function setMetaData(array $metadata);
}
