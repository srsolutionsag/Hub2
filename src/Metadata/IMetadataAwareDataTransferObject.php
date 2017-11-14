<?php

namespace SRAG\Plugins\Hub2\Metadata;

use SRAG\Plugins\Hub2\Object\IDataTransferObject;

/**
 * Interface IMetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @param int $ilias_metadata_id The internal ID of the corresponding field in ILIAS (e.g. UDF
	 *                               or Custom Metadata)
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function metadata(int $ilias_metadata_id): IMetadata;
}
