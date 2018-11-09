<?php

namespace srag\Plugins\Hub2\Access;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\Hub2\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Ilias constructor
	 */
	private function __construct() {

	}
}
