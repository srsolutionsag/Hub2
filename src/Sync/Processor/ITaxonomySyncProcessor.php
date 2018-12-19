<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface ITaxonomySyncProcessor
 *
 * @package srag\Plugins\Hub2\Sync\Processor
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
