<?php namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\IMetadata;
use SRAG\Plugins\Hub2\Metadata\IMetadataAware;

/**
 * Class MetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class MetadataAwareDataTransferObject extends DataTransferObject implements IMetadataAware {

	/**
	 * @var array
	 */
	protected $_meta_data = array();


	/**
	 * @inheritDoc
	 */
	public function addMetadataForProcessing(IMetadata $metadata) {
		$this->_meta_data[] = $metadata;
	}


	/**
	 * @inheritDoc
	 */
	public function getMetadataForProcessing() {
		return $this->_meta_data;
	}
}