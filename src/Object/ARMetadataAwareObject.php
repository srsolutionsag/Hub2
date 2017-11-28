<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\Metadata;

/**
 * Class ARMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
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
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata[]
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