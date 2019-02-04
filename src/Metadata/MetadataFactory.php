<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class IMetadataFactory
 *
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithIliasId(int $ilas_id, int $record_id = 1): IMetadata {
		return new Metadata($ilas_id, $record_id);
	}
}
