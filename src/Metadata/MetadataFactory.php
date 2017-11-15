<?php

namespace SRAG\Plugins\Hub2\Metadata;

use ILIAS\UI\NotImplementedException;

/**
 * Class IMetadataFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata {
		return new Metadata($id);
	}


	/**
	 * @param string $title
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithFirstIliasIdForTitle(string $title): IMetadata {
		throw new NotImplementedException('not yet implemented');
	}
}
