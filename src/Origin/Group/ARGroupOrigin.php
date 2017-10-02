<?php
namespace SRAG\Hub2\Origin\Group;

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Hub2\Origin\Properties\CourseOriginProperties;
use SRAG\Hub2\Origin\Properties\GroupOriginProperties;

/**
 * Class ARGroupOrigin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupOrigin extends AROrigin implements IGroupOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new GroupOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new GroupOriginProperties($data);
	}
}