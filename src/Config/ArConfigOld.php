<?php

namespace SRAG\Plugins\Hub2\Config;

use ActiveRecord;
use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class ArConfigOld
 *
 * @package SRAG\Plugins\Hub2\Config
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfigOld extends ActiveRecord {

	use DICTrait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const TABLE_NAME = 'sr_hub2_config';
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       64
	 * @db_is_primary   true
	 *
	 * @var string
	 *
	 * @deprecated
	 */
	protected $identifier;
	/**
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 *
	 * @var string
	 *
	 * @deprecated
	 */
	protected $value;


	/**
	 * Get a config value by key.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 *
	 * @deprecated
	 */
	public static function getValueByKey($key) {
		/** @var ARConfig $config */
		$config = self::find($key);

		return ($config) ? $config->getValue() : NULL;
	}


	/**
	 * @param string $key
	 *
	 * @return ArConfigOld
	 *
	 * @deprecated
	 */
	public static function getInstanceByKey($key) {
		$instance = self::find($key);
		if ($instance === NULL) {
			$instance = new self();
			$instance->setKey($key);
		}

		return $instance;
	}


	/**
	 * Encode array data as JSON in database
	 *
	 * @param string $field_name
	 *
	 * @return mixed|string
	 *
	 * @deprecated
	 */
	public function sleep($field_name) {
		switch ($field_name) {
			case 'value':
				return (is_array($this->value)) ? json_encode($this->value) : $this->value;
		}

		return parent::sleep($field_name);
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getKey() {
		return $this->identifier;
	}


	/**
	 * @param string $key
	 *
	 * @deprecated
	 */
	public function setKey($key) {
		$this->identifier = $key;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getValue() {
		return $this->value;
	}


	/**
	 * @param string $value
	 *
	 * @deprecated
	 */
	public function setValue($value) {
		$this->value = $value;
	}
}
