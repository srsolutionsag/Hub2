<?php namespace SRAG\Hub2\Origin\Properties;

/**
 * Class DTOProperty
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Properties
 */
class DTOProperty {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $descriptionKey;

	/**
	 * @param string $name
	 * @param string $descriptionKey
	 */
	public function __construct($name, $descriptionKey = '') {
		$this->name = $name;
		$this->descriptionKey = $descriptionKey;
	}
}