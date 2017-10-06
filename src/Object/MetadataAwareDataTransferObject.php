<?php namespace SRAG\Hub2\Object;

use SRAG\Hub2\Metadata\IMetadata;
use SRAG\Hub2\Metadata\IMetadataAware;

/**
 * Class MetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class MetadataAwareDataTransferObject extends DataTransferObject implements IMetadataAware {

	/**
	 * @var array
	 */
	protected $_metadata = array();


	/**
	 * @inheritDoc
	 */
	public function addMetadataForProcessing(IMetadata $metadata) {
		$this->_metadata[] = $metadata;
	}


	/**
	 * @inheritDoc
	 */
	public function getMetadataForProcessing() {
		return $this->_metadata;
	}
}