<?php

namespace SRAG\Plugins\Hub2\Object;

/**
 * Class ARMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
abstract class ARMetadataAwareObject extends ARObject {

	/**
	 * @var array
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 */
	protected $meta_data = array();


	/**
	 * @return array
	 */
	public function getMetaData(): array {
		return $this->meta_data;
	}


	/**
	 * @param array $meta_data
	 */
	public function setMetaData(array $meta_data) {
		$this->meta_data = $meta_data;
	}
}