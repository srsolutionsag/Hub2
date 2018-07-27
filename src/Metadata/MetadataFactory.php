<?php

namespace SRAG\Plugins\Hub2\Metadata;

use ILIAS\UI\NotImplementedException;

/**
 * Class IMetadataFactory
 *
 * @package SRAG\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata {
		return new Metadata($id);
	}
}
