<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

/**
 * Class FakeIliasObject
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class FakeIliasObject {

	/**
	 * @var string
	 */
	protected $id;


	/**
	 * FakeIliasObject constructor.
	 *
	 * @param string $id
	 */
	public function __construct($id = "") {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}


	/**
	 * @param string $id
	 */
	public function setId(string $id) {
		$this->id = $id;
	}


	/**
	 * @return mixed
	 */
	public abstract function initId();
}
