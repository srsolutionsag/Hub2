<?php namespace SRAG\ILIAS\Plugins\Hub2\Origin\Properties;

/**
 * Class OriginProperties
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Origin
 */
abstract class OriginProperties implements IOriginProperties {

	const PREFIX_UPDATE_DTO = 'hub2_update_dto_';

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
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