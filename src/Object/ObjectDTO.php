<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

/**
 * Class ObjectDTO
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
abstract class ObjectDTO implements IObjectDTO {

	/**
	 * @var string
	 */
	protected $ext_id = '';

	/**
	 * @var string
	 */
	protected $period = '';

	/**
	 * @param $ext_id
	 */
	public function __construct($ext_id) {
		$this->ext_id = $ext_id;
	}

	/**
	 * @inheritdoc
	 */
	public function getExtId() {
		return $this->ext_id;
	}

	/**
	 * @inheritdoc
	 */
	public function getPeriod() {
		return $this->period;
	}

	/**
	 * @inheritdoc
	 */
	public function setPeriod($period) {
		$this->period = $period;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getData() {
		$data = [];
		foreach ($this->getProperties() as $var) {
			$data[$var] = $this->{$var};
		}
		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function getProperties() {
		return array_keys(get_class_vars(get_class($this)));
	}

}