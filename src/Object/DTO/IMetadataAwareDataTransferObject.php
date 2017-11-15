<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataAwareDataTransferObject extends IDataTransferObject {

	/**
	 * @param \SRAG\Plugins\Hub2\Metadata\IMetadata $IMetadata
	 *
	 * @return \SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject
	 */
	public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject;


	/**
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata[]
	 */
	public function getMetaData(): array;
}
