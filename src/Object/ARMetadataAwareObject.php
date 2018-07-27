<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class ARMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARMetadataAwareObject {

	/**
	 * @var array
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 */
	protected $meta_data = array();


	/**
	 * @return IMetadata[]
	 */
	public function getMetaData(): array {
		return is_array($this->meta_data) ? $this->meta_data : array();
	}


	/**
	 * @param array $meta_data
	 */
	public function setMetaData(array $meta_data) {
		$this->meta_data = $meta_data;
	}
}
