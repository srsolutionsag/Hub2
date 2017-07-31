<?php namespace SRAG\Hub2\Origin;
use SRAG\Hub2\Origin\Config\CategoryOriginConfig;
use SRAG\Hub2\Origin\Properties\CategoryOriginProperties;

/**
 * Class ARCategoryOrigin
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
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