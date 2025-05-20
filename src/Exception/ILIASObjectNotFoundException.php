<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Exception;

use srag\Plugins\Hub2\Object\IObject;

/**
 * Class ILIASObjectNotFoundException
 * @package srag\Plugins\Hub2\Exception
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ILIASObjectNotFoundException extends HubException
{
    protected IObject $object;

    public function __construct(IObject $object)
    {
        parent::__construct("ILIAS object not found for: {$object}");
        $this->object = $object;
    }

    public function getObject(): IObject
    {
        return $this->object;
    }
}
