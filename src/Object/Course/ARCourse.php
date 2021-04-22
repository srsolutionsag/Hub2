<?php

namespace srag\Plugins\Hub2\Object\Course;

use srag\Plugins\Hub2\Object\ARMetadataAwareObject;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\ARTaxonomyAwareObject;
use srag\Plugins\Hub2\Object\ARDidacticTemplateAwareObject;

/**
 * Class ARCourse
 *
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCourse extends ARObject implements ICourse
{

    use ARMetadataAwareObject;
    use ARTaxonomyAwareObject;
    use ARDidacticTemplateAwareObject;
    const TABLE_NAME = 'sr_hub2_course';
}
