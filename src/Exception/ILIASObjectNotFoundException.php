<?php

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
    /**
     * @var IObject
     */
    protected $object;

    public function __construct(IObject $object)
    {
        parent::__construct("ILIAS object not found for: {$object}");
        $this->object = $object;
    }

    /**
     * @return IObject
     */
    public function getObject()
    {
        return $this->object;
    }
}
