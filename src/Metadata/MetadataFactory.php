<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class IMetadataFactory
 *
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @param int $id
	 *
	 * @return IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata {
		return new Metadata($id);
	}
}
