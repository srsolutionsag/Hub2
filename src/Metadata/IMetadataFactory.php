<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Class IMetadataFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithIliasId(int $id): IMetadata;


	/**
	 * @param string $title
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithFirstIliasIdForTitle(string $title): IMetadata;
}
