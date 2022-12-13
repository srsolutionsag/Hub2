<?php

namespace srag\Plugins\Hub2\FileDrop\Exceptions;

/**
 * Class Success
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Success extends \Exception
{
    protected $message = 'Success';

    public function __construct($message)
    {
        parent::__construct($this->message . ': ' . $message);
    }
}
