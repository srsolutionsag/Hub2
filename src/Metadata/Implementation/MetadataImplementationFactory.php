<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Metadata\IMetadata;
use SRAG\Plugins\Hub2\Object\Category\CategoryDTO;
use SRAG\Plugins\Hub2\Object\Course\CourseDTO;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\Group\GroupDTO;
use SRAG\Plugins\Hub2\Object\Session\SessionDTO;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class IMetadataImplementationFactory
 *
 * @package SRAG\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataImplementationFactory implements IMetadataImplementationFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public function userDefinedField(IMetadata $metadata, int $ilias_id): IMetadataImplementation {
		return new UDF($metadata, $ilias_id);
	}


	/**
	 * @inheritdoc
	 */
	public function customMetadata(IMetadata $metadata, int $ilias_id): IMetadataImplementation {
		return new CustomMetadata($metadata, $ilias_id);
	}


	/**
	 * @inheritDoc
	 */
	public function getImplementationForDTO(IMetadataAwareDataTransferObject $dto, IMetadata $metadata, int $ilias_id): IMetadataImplementation {
		switch (true) {
			case is_a($dto, GroupDTO::class):
			case is_a($dto, CourseDTO::class):
			case is_a($dto, CategoryDTO::class):
			case is_a($dto, SessionDTO::class):
				return $this->customMetadata($metadata, $ilias_id);
			case is_a($dto, UserDTO::class):
				return $this->userDefinedField($metadata, $ilias_id);
		}
	}
}
