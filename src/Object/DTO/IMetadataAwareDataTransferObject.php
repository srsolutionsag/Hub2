<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareDataTransferObject
 *
 * @package SRAG\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @param IMetadata $IMetadata
	 *
	 * @return IMetadataAwareDataTransferObject
	 */
	public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject;


	/**
	 * @return IMetadata[]
	 */
	public function getMetaData(): array;
}
