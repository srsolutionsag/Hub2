<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use ilObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface ITaxonomySyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomySyncProcessor {

	/**
	 * @param ITaxonomyAwareDataTransferObject $dto
	 * @param ilObject                         $object
	 *
	 * @return mixed
	 */
	public function handleTaxonomies(ITaxonomyAwareDataTransferObject $dto, ilObject $object);
}
