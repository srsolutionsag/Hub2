<?php

namespace srag\Plugins\Hub2\Exception;

use ilException;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class HubException
 *
 * @package srag\Plugins\Hub2\Exception
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HubException extends ilException
{

    use DICTrait;
    use Hub2Trait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 0);
    }
}
