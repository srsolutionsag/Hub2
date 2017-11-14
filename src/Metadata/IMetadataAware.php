<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadataAware
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAware {

	/**
	 * @param int $ilias_metadata_id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function metadata(int $ilias_metadata_id): IMetadata;
}
