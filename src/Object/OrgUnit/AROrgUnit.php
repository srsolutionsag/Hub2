<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\OrgUnit;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class AROrgUnit
 * @package srag\Plugins\Hub2\Object\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnit extends ARObject implements IOrgUnit
{
    public const TABLE_NAME = "sr_hub2_org_unit";
}
