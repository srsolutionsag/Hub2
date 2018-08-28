<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\Category\ARCategory;
use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Object\User\ARUser;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\OriginFactory;

/**
 * Interface ObjectLinkFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectLinkFactory {

	/**
	 * @var OriginFactory
	 */
	private $origin_factory;
	/**
	 * @var ARObject
	 */
	protected $object;
	/**
	 * @var string
	 */
	protected $ext_id;


	/**
	 * ObjectLinkFactory constructor.
	 */
	public function __construct() {
		$this->origin_factory = new OriginFactory();
	}


	/**
	 * @param string $ext_id
	 *
	 * @return IObjectLink
	 */
	public function findByExtId(string $ext_id): IObjectLink {
		$object = NULL;
		foreach ($this->origin_factory->getAllActive() as $origin) {
			$f = new ObjectFactory($origin);
			$object = $f->undefined($ext_id);
			switch (true) {
				case ($object instanceof ARSession):
				case ($object instanceof ARCategory):
				case ($object instanceof ARCourse):
				case ($object instanceof ARGroup):
				case ($object instanceof ARUser):
					if ($object->getILIASId()) {
						break 2;
					} else {
						$object = NULL;
					}
			}
		}
		if ($object instanceof ARObject) {
			return $this->findByObject($object);
		}

		return new NullLink();
	}


	/**
	 * @param string  $ext_id
	 * @param IOrigin $origin
	 *
	 * @return IObjectLink
	 */
	public function findByExtIdAndOrigin(string $ext_id, IOrigin $origin): IObjectLink {
		$f = new ObjectFactory($origin);
		$object = $f->undefined($ext_id);
		switch (true) {
			case ($object instanceof ARSession):
			case ($object instanceof ARCategory):
			case ($object instanceof ARCourse):
			case ($object instanceof ARGroup):
			case ($object instanceof ARUser):
				if ($object->getILIASId()) {
					break;
				} else {
					$object = NULL;
				}
		}

		if ($object instanceof ARObject) {
			return $this->findByObject($object);
		}

		return new NullLink();
	}


	/**
	 * @param ARObject $object
	 *
	 * @return IObjectLink
	 */
	public function findByObject(ARObject $object): IObjectLink {
		switch (true) {
			case ($object instanceof ARSession):
				return new SessionLink($object);
			case ($object instanceof ARCategory):
				return new CategoryLink($object);
			case ($object instanceof ARCourse):
				return new CourseLink($object);
			case ($object instanceof ARGroup):
				return new GroupLink($object);
			case ($object instanceof ARUser):
				return new UserLink($object);
			default:
				return new NullLink();
		}
	}
}
