<?php namespace SRAG\Hub2\Exception;
use SRAG\Hub2\Object\IObject;

/**
 * Class ILIASObjectNotFoundException
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Exception
 */
class ILIASObjectNotFoundException extends HubException {

	/**
	 * @var IObject
	 */
	protected $object;

	/**
	 * @param IObject $object
	 */
	public function __construct(IObject $object) {
		parent::__construct("ILIAS object not found for: {$object}");
		$this->object = $object;
	}

	/**
	 * @return IObject
	 */
	public function getObject() {
		return $this->object;
	}

}