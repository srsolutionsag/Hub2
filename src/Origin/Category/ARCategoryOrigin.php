<?php
namespace SRAG\Plugins\Hub2\Origin\Category;

use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\CategoryOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\CategoryOriginProperties;

/**
 * Class ARCategoryOrigin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Origin
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