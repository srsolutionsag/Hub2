<?php

namespace SRAG\Plugins\Hub2\Object\Course;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARCourse
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARCourse extends ARObject implements ICourse {

	use ARMetadataAwareObject;
	use ARTaxonomyAwareObject;


	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_course';
	}
}