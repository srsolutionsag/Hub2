<?php

namespace SRAG\Plugins\Hub2\Object;

use SRAG\Plugins\Hub2\Metadata\Metadata;

/**
 * Class ARMetadataAwareObject
 *
 * @package SRAG\Plugins\Hub2\Object
 */
abstract class ARMetadataAwareObject extends ARObject implements IMetadataAwareObject {

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


	/**
	 * @inheritDoc
	 */
	public function sleep($field_name) {
		switch ($field_name) {
			case "meta_data":
				$metadata = [];
				$IMetadata = $this->getMetaData();
				foreach ($IMetadata as $IMetadatum) {
					$metadata[$IMetadatum->getIdentifier()] = $IMetadatum->getValue();
				}

				$json_encode = json_encode($metadata);

				return $json_encode;
			default:
				return parent::sleep($field_name);
		}
	}


	/**
	 * @inheritDoc
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'meta_data':
				$json_decode = json_decode($field_value, true);
				$IMetadata = [];
				if (is_array($json_decode)) {
					foreach ($json_decode as $metaDatum) {
						$IMetadata[] = (new Metadata($metaDatum[0]))->setValue($metaDatum[1]);
					}
				}

				return $IMetadata;
			default:
				return parent::wakeUp($field_name, $field_value);
		}
	}
}