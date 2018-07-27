<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

use SRAG\Plugins\Hub2\Metadata\IMetadata;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Class IMetadataImplementationFactory
 *
 * @package SRAG\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementationFactory {

	/**
	 * @param IMetadata $metadata
	 * @param int       $ilias_id
	 *
	 * @return IMetadataImplementation
	 */
	public function userDefinedField(IMetadata $metadata, int $ilias_id): IMetadataImplementation;


	/**
	 * @param IMetadata $metadata
	 * @param int       $ilias_id
	 *
	 * @return IMetadataImplementation
	 */
	public function customMetadata(IMetadata $metadata, int $ilias_id): IMetadataImplementation;


	/**
	 * @param IMetadataAwareDataTransferObject $dto
	 * @param IMetadata                        $metadata
	 * @param int                              $ilias_id
	 *
	 * @return IMetadataImplementation
	 */
	public function getImplementationForDTO(IMetadataAwareDataTransferObject $dto, IMetadata $metadata, int $ilias_id): IMetadataImplementation;
}
