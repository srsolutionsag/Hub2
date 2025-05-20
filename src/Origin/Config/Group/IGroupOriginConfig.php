<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\Config\Group;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface IGroupOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupOriginConfig extends IOriginConfig
{
    public const REF_ID_NO_PARENT_ID_FOUND = 'ref_id_no_parent_id_found';

    /**
     * Get the ILIAS ref-ID acting as parent, only if hub was not able to find
     * the correct parent ref-ID. By default, the course will be created directly
     * in the repository (refId = 1).
     */
    public function getParentRefIdIfNoParentIdFound(): int;
}
