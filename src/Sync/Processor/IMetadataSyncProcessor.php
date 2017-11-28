<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Interface IMetadataSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataSyncProcessor {

	/**
	 * @param \SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject $dto
	 * @param \ilObject                                                      $object
	 *
	 * @return mixed
	 */
	public function handleMetadata(IMetadataAwareDataTransferObject $dto, \ilObject $object);
}
