<?php

namespace srag\Plugins\Hub2\Utils;

use srag\Plugins\Hub2\Access\Access;
use srag\Plugins\Hub2\Access\Ilias;

/**
 * Trait Hub2Trait
 *
 * @package srag\Plugins\Hub2\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait Hub2Trait {

	/**
	 * @return Access
	 */
	protected static function access(): Access {
		return Access::getInstance();
	}


	/**
	 * @return Ilias
	 */
	protected static function ilias(): Ilias {
		return Ilias::getInstance();
	}
}
