<?php

namespace SRAG\Hub2\Metadata;

/**
 * Interface IMetadataFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface  IMetadataFactory {

	/**
	 * Return an array of available Metadata-Sets for the current Object-Type
	 *
	 * @return \SRAG\Hub2\Metadata\IMetadataSet[]
	 */
	public function course();


	/**
	 * Return an array of available Metadata-Sets for the current Object-Type
	 *
	 * @return \SRAG\Hub2\Metadata\IMetadataSet[]
	 */
	public function group();


	/**
	 * Return an array of available Metadata-Sets for the current Object-Type
	 *
	 * @return \SRAG\Hub2\Metadata\IMetadataSet[]
	 */
	public function category();
}
