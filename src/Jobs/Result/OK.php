<?php

namespace srag\Plugins\Hub2\Jobs\Result;

/**
 * Class OK
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OK extends AbstractResult
{
    /**
     * @inheritdoc
     */
    protected function initStatus()
    {
        $this->setStatus(self::STATUS_OK);
    }
}
