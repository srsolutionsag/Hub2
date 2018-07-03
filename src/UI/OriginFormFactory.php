<?php

namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Origin\AROrigin;

/**
 * Class OriginFormFactory
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFormFactory {

	/**
	 * @param AROrigin $origin
	 *
	 * @return string
	 */
	public function getFormClassNameByOrigin(AROrigin $origin) {
		$type = $origin->getObjectType();

		return "SRAG\\Plugins\\Hub2\\UI\\" . ucfirst($type) . 'OriginConfigFormGUI';
	}
}
