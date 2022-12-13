<?php

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
