<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
