<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;

/**
 * Class MetadataSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataSyncProcessor {

	/**
	 * @param \SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject $dto
	 * @param \ilObject                                                      $object
	 *
	 * @throws \SRAG\Plugins\Hub2\Exception\HubException
	 */
	public function handleMetadata(IMetadataAwareDataTransferObject $dto, \ilObject $object) {
		if (count($dto->getMetaData()) > 0) {
			\ilContainer::_writeContainerSetting($object->getId(), \ilObjectServiceSettingsGUI::CUSTOM_METADATA, 1);
			$f = new MetadataImplementationFactory();
			foreach ($dto->getMetaData() as $metaDatum) {
				$f->getImplementationForDTO($dto, $metaDatum, (int)$object->getId())->write();
			}
		}
	}
}
