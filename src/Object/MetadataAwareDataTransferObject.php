<?php namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\IMetadata;
use SRAG\Plugins\Hub2\Metadata\IMetadataAware;
use SRAG\Plugins\Hub2\Metadata\Metadata;

/**
 * Class MetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class MetadataAwareDataTransferObject extends DataTransferObject implements IMetadataAware {

	/**
	 * @var IMetadata
	 */
	protected $_meta_data = array();


	/**
	 * @inheritDoc
	 */
	public function metadata(int $ilias_metadata_id): IMetadata {
		if (!isset($this->_meta_data[$ilias_metadata_id])) {
			$this->_meta_data[$ilias_metadata_id] = new Metadata($ilias_metadata_id, $this);
		}

		return $this->_meta_data[$ilias_metadata_id];
	}
}