<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Class IMetadataFactory
 *
 * @package SRAG\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata;


	/**
	 * @param string $title
	 *
	 * @return IMetadata
	 */
	public function getDTOWithFirstIliasIdForTitle(string $title): IMetadata;
}
