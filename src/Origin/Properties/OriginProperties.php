<?php namespace SRAG\Hub2\Origin\Properties;

/**
 * Class OriginProperties
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Properties
 */
abstract class OriginProperties implements IOriginProperties {

	/**
	 * @var array
	 */
	protected $data = [];


	/**
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
		$this->data = array_merge($this->data, $data);
	}


	/**
	 * @inheritdoc
	 */
	public function get($key) {
		return (isset($this->data[$key])) ? $this->data[$key] : null;
	}


	/**
	 * @inheritdoc
	 */
	public function updateDTOProperty($property) {
		return $this->get(self::PREFIX_UPDATE_DTO . $property);
	}


	/**
	 * @inheritdoc
	 */
	public function setData(array $data) {
		$this->data = array_merge($this->data, $data);
	}


	/**
	 * @inheritdoc
	 */
	public function getData() {
		return $this->data;
	}
}