<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Jobs\Result;

/**
 * Class OK
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OK extends AbstractResult
{
    protected function initStatus()
    {
        $this->setStatus(self::STATUS_OK);
    }
}
