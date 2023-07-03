<?php

namespace srag\Plugins\Hub2\Jobs\Result;

/**
 * Class Error
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Error extends AbstractResult
{
    /**
     * @inheritdoc
     */
    protected function initStatus()
    {
        $this->setStatus(self::STATUS_CRASHED);
    }
}
