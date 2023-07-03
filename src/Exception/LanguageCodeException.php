<?php

namespace srag\Plugins\Hub2\Exception;

/**
 * Class BuildObjectsFailedException
 * This exception is thrown if an unkown language code is passed to some dto
 * @package srag\Plugins\Hub2\Exception
 * @author  Timon Amstutz
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class LanguageCodeException extends HubException
{
    /**
     * LanguageCodeException constructor
     * @param string $code
     */
    public function __construct($code = "")
    {
        parent::__construct("Language Code does not exist, ID: '$code'");
    }
}
