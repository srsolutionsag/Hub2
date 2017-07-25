<?php namespace SRAG\Hub2\Origin\Config;

/**
 * Class CourseOriginConfig
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Config
 */
class CourseOriginConfig extends OriginConfig implements ICourseOriginConfig {

	/**
	 * @var array
	 */
	protected $course_data = [
		self::REF_ID_NO_PARENT_ID_FOUND => 1,
	];

	public function __construct(array $data) {
		parent::__construct(array_merge($this->course_data, $data));
	}

	/**
	 * @inheritdoc
	 */
	public function getParentRefIdIfNoParentIdFound() {
		return $this->data[self::REF_ID_NO_PARENT_ID_FOUND];
	}

}