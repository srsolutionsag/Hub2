<?php

namespace SRAG\Plugins\Hub2\Origin\Config;

/**
 * Class CourseOriginConfig
 *
 * @package SRAG\Plugins\Hub2\Origin\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginConfig extends OriginConfig implements ICourseOriginConfig {

	/**
	 * @var array
	 */
	protected $courseData = [
		self::REF_ID_NO_PARENT_ID_FOUND => 1,
	];


	public function __construct(array $data) {
		parent::__construct(array_merge($this->courseData, $data));
	}


	/**
	 * @inheritdoc
	 */
	public function getParentRefIdIfNoParentIdFound() {
		return $this->data[self::REF_ID_NO_PARENT_ID_FOUND];
	}
}
