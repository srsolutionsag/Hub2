<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface ITaxonomySyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomySyncProcessor {

	/**
	 * @param \SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject $dto
	 * @param \ilObject                                                      $object
	 *
	 * @return mixed
	 */
	public function handleTaxonomies(ITaxonomyAwareDataTransferObject $dto, \ilObject $object);
}
