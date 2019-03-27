<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilContainer;
use ilObject;
use ilObjectServiceSettingsGUI;
use srag\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;

/**
 * Class MetadataSyncProcessor
 *
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataSyncProcessor {

	/**
	 * @param IMetadataAwareDataTransferObject $dto
	 * @param IMetadataAwareObject             $iobject
	 * @param ilObject                         $ilias_object
	 */
	public function handleMetadata(IMetadataAwareDataTransferObject $dto, IMetadataAwareObject $iobject, ilObject $ilias_object) {
		if (count($dto->getMetaData()) > 0) {
			$this->handleDTOSpecificSettings($dto, $ilias_object);
			$f = new MetadataImplementationFactory();
			$flat_existing_md = [];
			foreach ($iobject->getMetaData() as $md) {
				$flat_existing_md[$md->getRecordId()][$md->getIdentifier()] = $md->getValue();
			}

			foreach ($dto->getMetaData() as $metaDatum) {
				if (!isset($flat_existing_md[$metaDatum->getRecordId()]) || !isset($flat_existing_md[$metaDatum->getRecordId()][$metaDatum->getIdentifier()])) {
					continue;
				}
				if ($flat_existing_md[$metaDatum->getRecordId()][$metaDatum->getIdentifier()] !== $metaDatum->getValue()) {
					$f->getImplementationForDTO($dto, $metaDatum, (int)$ilias_object->getId())->write();
				}
			}
		}
	}


	/**
	 * @param IMetadataAwareDataTransferObject $dto
	 * @param ilObject                         $object
	 */
	private function handleDTOSpecificSettings(IMetadataAwareDataTransferObject $dto, ilObject $object) {
		switch (true) {
			case $dto instanceof CourseDTO:
			case $dto instanceof CategoryDTO:
			case $dto instanceof GroupDTO:
				ilContainer::_writeContainerSetting($object->getId(), ilObjectServiceSettingsGUI::CUSTOM_METADATA, 1);
				break;
		}
	}
}
