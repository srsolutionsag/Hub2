<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadataAware
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAware {

	/**
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadataSetCollection
	 */
	public function metadataCollection();


	/**
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadata $metadata
	 */
	public function addMetadataForProcessing(IMetadata $metadata);


	/**
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata[]
	 */
	public function getMetadataForProcessing();
}
