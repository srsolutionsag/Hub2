<?php

namespace srag\Plugins\Hub2\FileDrop\Exceptions;

/**
 * Class NotFound
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class NotFound extends \Exception
{
    protected $message = 'Not Found';

    public function __construct($message)
    {
        parent::__construct($this->message . ': ' . $message);
    }
}
