<?php

namespace SRAG\Plugins\Hub2\UI;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Origin\AROrigin;

/**
 * Class OriginFormFactory
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFormFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


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
