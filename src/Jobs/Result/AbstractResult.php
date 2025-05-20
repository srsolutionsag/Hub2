<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs\Result;

use ilCronJobResult;
use ilHub2Plugin;

/**
 * Class AbstractResult
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractResult extends ilCronJobResult
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * AbstractResult constructor
     */
    public function __construct(string $message)
    {
        $this->setMessage($message);
        $this->initStatus();
    }

    /**
     * inits the status to STATUS_OK or STATUS_CRASHED
     */
    abstract protected function initStatus();
}
