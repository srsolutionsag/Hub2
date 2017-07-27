<?php namespace SRAG\Hub2\Config;

/**
 * Class ArConfig
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Config
 */
class ArConfig extends \ActiveRecord {

	/**
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       64
	 * @db_is_primary   true
	 *
	 * @var string
	 */
	protected $identifier;

	/**
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 *
	 * @var string
	 */
	protected $value;

	/**
	 * Get a config value by key.
	 *
	 * @param $key
	 * @return mixed
	 */
	public static function getValueByKey($key) {
		/** @var ARConfig $config */
		$config = self::find($key);
		return ($config) ? $config->getValue() : null;
	}

	/**
	 * @param string $key
	 * @return ArConfig
	 */
	public static function getInstanceByKey($key) {
		$instance = self::find($key);
		if ($instance === null) {
			$instance = new self();
			$instance->setKey($key);
		}
		return $instance;
	}

	/**
	 * Encode array data as JSON in database
	 *
	 * @param string $field_name
	 * @return mixed|string
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
	 */
	public function getKey() {
		return $this->identifier;
	}

	/**
	 * @param string $key
	 */
	public function setKey($key) {
		$this->identifier = $key;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}


	public static function returnDbTableName() {
		return 'sr_hub2_config';
	}

}