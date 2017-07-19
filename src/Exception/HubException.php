<?php namespace SRAG\Hub2\Exception;

//require_once('./Services/Exceptions/classes/class.ilException.php');
require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))))) . '/Services/Exceptions/classes/class.ilException.php');

/**
 * Class HubException
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Exception
 */
class HubException extends \ilException {

	/**
	 * @param string $message
	 */
	public function __construct($message) {
		parent::__construct($message, 0);
	}

}