<?php namespace SRAG\Plugins\Hub2\Shortlink;

use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\User\ARUser;

/**
 * Class NullLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractBaseLink implements IObjectLink {

	/**
	 * @var ARObject
	 */
	protected $object;


	/**
	 * AbstractBaseLink constructor.
	 *
	 * @param ARObject $object
	 */
	public function __construct(ARObject $object) { $this->object = $object; }


	/**
	 * @inheritDoc
	 */
	public function getNonExistingLink(): string {
		return "index.php";
	}
}
