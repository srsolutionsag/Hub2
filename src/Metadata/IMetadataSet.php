<?php

namespace SRAG\Hub2\Metadata;

/**
 * Interface IMetadataSet
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataSet {

	/**
	 * @return string
	 */
	public function getTitle();


	/**
	 * @return \SRAG\Hub2\Metadata\IMetadata[]
	 */
	public function getMetadata();


	/**
	 * Returns the data as array for storage
	 *
	 * @return array
	 */
	public function getData();
}
