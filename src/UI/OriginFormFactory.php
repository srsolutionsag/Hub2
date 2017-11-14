<?php

namespace SRAG\Plugins\Hub2\UI;

/**
 * Class OriginFormFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFormFactory {

	/**
	 * @param $origin
	 *
	 * @return string
	 */
	public function getFormClassNameByOrigin($origin) {
		$type = $origin->getObjectType();

		return "SRAG\\Plugins\\Hub2\\UI\\" . ucfirst($type) . 'OriginConfigFormGUI';
	}
}
