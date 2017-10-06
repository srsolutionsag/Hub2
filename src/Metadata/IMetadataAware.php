<?php

namespace SRAG\Hub2\Metadata;

/**
 * Interface IMetadataAware
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAware {

	/**
	 * @return \SRAG\Hub2\Metadata\IMetadataSetCollection
	 */
	public function metadataCollection();


	/**
	 * @param \SRAG\Hub2\Metadata\IMetadata $metadata
	 */
	public function addMetadataForProcessing(IMetadata $metadata);


	/**
	 * @return \SRAG\Hub2\Metadata\IMetadata[]
	 */
	public function getMetadataForProcessing();
}
