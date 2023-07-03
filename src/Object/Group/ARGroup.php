<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\Object\ARDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\ARMetadataAwareObject;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARGroup
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroup extends ARObject implements IGroup
{
    use ARMetadataAwareObject;
    use ARTaxonomyAwareObject;
    use ARDidacticTemplateAwareObject;

    public const TABLE_NAME = 'sr_hub2_group';
}
