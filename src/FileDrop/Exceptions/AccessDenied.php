<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\FileDrop\Exceptions;

/**
 * Class AccessDenied
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class AccessDenied extends \Exception
{
    protected $message = 'Access Denied';

    public function __construct($message)
    {
        parent::__construct($this->message . ': ' . $message);
    }
}
