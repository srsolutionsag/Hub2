<?php

namespace srag\Plugins\Hub2\Origin\Config;

/**
 * Class CategoryOriginConfig
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Origin\Config
 */
class CategoryOriginConfig extends OriginConfig implements ICategoryOriginConfig {

	/**
	 * @var array
	 */
	protected $categoryData = [
		self::REF_ID_NO_PARENT_ID_FOUND => 1,
		self::EXT_ID_NO_PARENT_ID_FOUND => '',
	];


	public function __construct(array $data) {
		parent::__construct(array_merge($this->categoryData, $data));
	}


	/**
	 * @inheritdoc
	 */
	public function getParentRefIdIfNoParentIdFound() {
		return $this->data[self::REF_ID_NO_PARENT_ID_FOUND];
	}


	/**
	 * @inheritdoc
	 */
	public function getExternalParentIdIfNoParentIdFound() {
		return $this->data[self::EXT_ID_NO_PARENT_ID_FOUND];
	}
}
