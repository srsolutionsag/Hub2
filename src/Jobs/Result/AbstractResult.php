<?php

namespace srag\Plugins\Hub2\Jobs\Result;

use ilCronJobResult;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class AbstractResult
 *
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractResult extends ilCronJobResult
{

    use DICTrait;
    use Hub2Trait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    const STATUS_OK = 3;
    const STATUS_CRASHED = 4;


    /**
     * AbstractResult constructor
     *
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
