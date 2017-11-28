<?php namespace SRAG\Plugins\Hub2\Object;

/**
 * Interface IMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
interface IMetadataAwareObject extends IObject {

	/**
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata[]
	 */
	public function getMetaData(): array;


	/**
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadata[] $metadata
	 *
	 * @return void
	 */
	public function setMetaData(array $metadata);
}