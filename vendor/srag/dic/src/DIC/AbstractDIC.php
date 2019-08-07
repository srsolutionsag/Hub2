<?php

namespace srag\DIC\Hub2\DIC;

use srag\DIC\Hub2\Database\DatabaseDetector;
use srag\DIC\Hub2\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\Hub2\DIC
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractDIC implements DICInterface {

	/**
	 * AbstractDIC constructor
	 */
	protected function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function database(): DatabaseInterface {
		return DatabaseDetector::getInstance($this->databaseCore());
	}
}
