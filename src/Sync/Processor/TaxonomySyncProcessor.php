<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilContainer;
use ilObject;
use ilObjectServiceSettingsGUI;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Taxonomy\Implementation\TaxonomyImplementationFactory;

/**
 * Class TaxonomySyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomySyncProcessor
{
    public function handleTaxonomies(
        ITaxonomyAwareDataTransferObject $dto,
        ITaxonomyAwareObject $iobject,
        ilObject $ilias_object
    ) : void {
        if ($dto->getTaxonomies() !== []) {
            $this->handleDTOSpecificTaxonomySettings($dto, $ilias_object);

            $f = new TaxonomyImplementationFactory();
            foreach ($dto->getTaxonomies() as $taxonomy) {
                $f->taxonomy($taxonomy, $ilias_object)->write();
            }
        }
    }

    private function handleDTOSpecificTaxonomySettings(ITaxonomyAwareDataTransferObject $dto, ilObject $object) : void
    {
        switch (true) {
            case $dto instanceof CourseDTO:
            case $dto instanceof CategoryDTO:
            case $dto instanceof GroupDTO:
                ilContainer::_writeContainerSetting($object->getId(), ilObjectServiceSettingsGUI::TAXONOMIES, 1);
                break;
        }
    }
}
