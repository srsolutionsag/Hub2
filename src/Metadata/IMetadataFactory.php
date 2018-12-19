<?php

namespace srag\Plugins\Hub2\Metadata;

/**
 * Class IMetadataFactory
 *
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata;
}
