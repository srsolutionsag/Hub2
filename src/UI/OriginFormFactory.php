<?php

namespace SRAG\Hub2\UI;

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

		return "SRAG\\Hub2\\UI\\" . ucfirst($type) . 'OriginConfigFormGUI';
	}
}
