<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use ilContainer;
use ilObject;
use ilObjectServiceSettingsGUI;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Taxonomy\Implementation\TaxonomyImplementationFactory;

/**
 * Class TaxonomySyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomySyncProcessor {

	/**
	 * @param ITaxonomyAwareDataTransferObject $dto
	 * @param ilObject                         $object
	 */
	public function handleTaxonomies(ITaxonomyAwareDataTransferObject $dto, ilObject $object) {
		if (count($dto->getTaxonomies()) > 0) {
			ilContainer::_writeContainerSetting($object->getId(), ilObjectServiceSettingsGUI::TAXONOMIES, 1);

			$f = new TaxonomyImplementationFactory();
			foreach ($dto->getTaxonomies() as $taxonomy) {
				$f->taxonomy($taxonomy, $object)->write();
			}
		}
	}
}
