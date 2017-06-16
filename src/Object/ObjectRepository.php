<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;

/**
 * Class ObjectRepository
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
abstract class ObjectRepository implements IObjectRepository {

	/**
	 * @var IOrigin
	 */
	protected $origin;

	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}

}