<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class IMetadataImplementationFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataImplementationFactory implements IMetadataImplementationFactory {

	/**
	 * @inheritdoc
	 */
	public function userDefinedField(IMetadata $metadata, int $ilias_id): IMetadataImplementation {
		return new UDF($metadata, $ilias_id);
	}


	/**
	 * @inheritdoc
	 */
	public function customMetadata(IMetadata $metadata, int $ilias_id): IMetadataImplementation {
		return new CustomMetadata($metadata, $ilias_id);
	}
}

