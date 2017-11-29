<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Taxonomy\Implementation\TaxonomyImplementationFactory;

/**
 * Class TaxonomySyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomySyncProcessor {

	/**
	 * @param \SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject $dto
	 * @param \ilObject                                                      $object
	 */
	public function handleTaxonomies(ITaxonomyAwareDataTransferObject $dto, \ilObject $object) {
		if (count($dto->getTaxonomies()) > 0) {
			\ilContainer::_writeContainerSetting($object->getId(), \ilObjectServiceSettingsGUI::TAXONOMIES, 1);

			$f = new TaxonomyImplementationFactory();
			foreach ($dto->getTaxonomies() as $taxonomy) {
				$f->taxonomy($taxonomy, $object)->write();
			}
		}
	}
}
