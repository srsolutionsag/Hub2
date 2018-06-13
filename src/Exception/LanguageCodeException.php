<?php namespace SRAG\Plugins\Hub2\Exception;

/**
 * Class BuildObjectsFailedException
 *
 * This exception is thrown if an unkown language code is passed to some dto
 *
 * @author  Timon Amstutz
 * @package SRAG\ILIAS\Plugins\Exception
 */
class LanguageCodeException extends HubException {
    /**
     * LanguageCodeException constructor.
     * @param string $code
     */
    public function __construct($code = "") {
        parent::__construct("Language Code does not exist, ID: '$code'");
    }

}