<?php

namespace srag\Plugins\Hub2\Object\Session;

use srag\Plugins\Hub2\Object\ARMetadataAwareObject;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARSession
 * @package srag\Plugins\Hub2\Object\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSession extends ARObject implements ISession
{
    use ARMetadataAwareObject;
    use ARTaxonomyAwareObject;

    public const TABLE_NAME = 'sr_hub2_session';
}
