<?php

namespace srag\Plugins\Hub2\Origin\Category;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CategoryOriginProperties;

/**
 * Class ARCategoryOrigin
 *
 * @package srag\Plugins\Hub2\Origin\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCategoryOrigin extends AROrigin implements ICategoryOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new CategoryOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new CategoryOriginProperties($data);
	}
}
