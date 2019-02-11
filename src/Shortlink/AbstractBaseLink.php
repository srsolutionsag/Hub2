<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class AbstractBaseLink
 *
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractBaseLink implements IObjectLink {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var ARObject
	 */
	protected $object;


	/**
	 * AbstractBaseLink constructor
	 *
	 * @param ARObject $object
	 */
	public function __construct(ARObject $object) { $this->object = $object; }


	/**
	 * @inheritdoc
	 */
	public function getNonExistingLink(): string {
		return "index.php";
	}
}
