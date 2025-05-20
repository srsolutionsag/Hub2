<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\CompetenceManagement;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class ARCompetenceManagement
 * @package srag\Plugins\Hub2\Object\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ARCompetenceManagement extends ARObject implements ICompetenceManagement
{
    public const TABLE_NAME = "sr_hub2_comp_man";
}
