<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Interface IMetadataSyncProcessor
 *
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataSyncProcessor {

	/**
	 * @param IMetadataAwareDataTransferObject $dto
	 * @param ilObject                         $object
	 *
	 * @return mixed
	 */
	public function handleMetadata(IMetadataAwareDataTransferObject $dto, ilObject $object);
}
