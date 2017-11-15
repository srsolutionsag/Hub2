<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class IMetadataImplementationFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementationFactory {

	/**
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadata $metadata
	 * @param int                                   $ilias_id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\Implementation\IMetadataImplementation
	 */
	public function userDefinedField(IMetadata $metadata, int $ilias_id): IMetadataImplementation;


	/**
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadata $metadata
	 * @param int                                   $ilias_id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\Implementation\IMetadataImplementation
	 */
	public function customMetadata(IMetadata $metadata, int $ilias_id): IMetadataImplementation;
}

