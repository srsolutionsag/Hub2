<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata {

	/**
	 * @param $value
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadataAwareDataTransferObject
	 */
	public function setValue($value): IMetadataAwareDataTransferObject;


	/**
	 * @param int $identifier
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadataAwareDataTransferObject
	 */
	public function setIdentifier(int $identifier): IMetadataAwareDataTransferObject;


	/**
	 * @return mixed
	 */
	public function getValue();


	/**
	 * @return mixed
	 */
	public function getIdentifier();
}
