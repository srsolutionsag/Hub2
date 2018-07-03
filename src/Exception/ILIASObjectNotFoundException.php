<?php

namespace SRAG\Plugins\Hub2\Exception;

use SRAG\Plugins\Hub2\Object\IObject;

/**
 * Class ILIASObjectNotFoundException
 *
 * @package SRAG\Plugins\Hub2\Exception
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
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
