<?php namespace SRAG\Plugins\Hub2\Object;

/**
 * Interface IMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
interface IMetadataAwareObject extends IObject {

	/**
	 * @return array
	 */
	public function getMetaData(): array;


	/**
	 * @param array $metadata
	 *
	 * @return void
	 */
	public function setMetaData(array $metadata);
}