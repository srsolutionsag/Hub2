<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilDBInterface;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\OriginFactory;

/**
 * Class ObjectLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectLink {

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
	 * ObjectLink constructor
	 *
	 * @param string $ext_id
	 */
	public function __construct(string $ext_id, ilDBInterface $db) {
		$this->ext_id = $ext_id;
		$this->origin_factory = new OriginFactory();
		$this->determineObject();
	}


	private function determineObject() {
		$object = NULL;
		foreach ($this->origin_factory->getAllActive() as $origin) {
			$f = new ObjectFactory($origin);
			$object = $f->undefined($this->ext_id);
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

		$this->object = $object;
	}


	/**
	 * @return bool
	 */
	public function exists(): bool {
		return ($this->object !== NULL && $this->object instanceof ARObject && (int)$this->object->getILIASId() > 0);
	}
}
