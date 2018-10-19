<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class MetadataAwareDataTransferObject
 *
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataAwareDataTransferObject {

	/**
	 * @var IMetadata[]
	 */
	private $_meta_data = array();


	/**
	 * @inheritdoc
	 */
	public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject {
		$this->_meta_data[$IMetadata->getIdentifier()] = $IMetadata;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function getMetaData(): array {
		$IMetadata = is_array($this->_meta_data) ? $this->_meta_data : array();

		return $IMetadata;
	}
}
