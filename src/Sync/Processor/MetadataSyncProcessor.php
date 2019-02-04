<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilContainer;
use ilObject;
use ilObjectServiceSettingsGUI;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Class MetadataSyncProcessor
 *
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataSyncProcessor {

	/**
	 * @param IMetadataAwareDataTransferObject $dto
	 * @param ilObject                         $object
	 *
	 * @throws HubException
	 */
	public function handleMetadata(IMetadataAwareDataTransferObject $dto, ilObject $object) {
		if (count($dto->getMetaData()) > 0) {

			ilContainer::_writeContainerSetting($object->getId(), ilObjectServiceSettingsGUI::CUSTOM_METADATA, 1);
			$f = new MetadataImplementationFactory();

			foreach ($dto->getMetaData() as $metaDatum) {
				$f->getImplementationForDTO($dto, $metaDatum, (int)$object->getId())->write();
			}
		}
	}
}
