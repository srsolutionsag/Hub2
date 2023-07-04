<?php

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
    public const STATUS_OK = 3;
    public const STATUS_CRASHED = 4;

    /**
     * AbstractResult constructor
     * @param string $message
     */
    public function __construct($message)
    {
        $this->setMessage($message);
        $this->initStatus();
    }

    /**
     * inits the status to STATUS_OK or STATUS_CRASHED
     */
    abstract protected function initStatus();
}
