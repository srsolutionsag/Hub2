<?php

namespace srag\Plugins\Hub2\Origin;

use ActiveRecord;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginFactory
 *
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFactory implements IOriginFactory {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 *
	 */
	public function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function getById($id) {
		$sql = 'SELECT object_type FROM ' . AROrigin::TABLE_NAME . ' WHERE id = %s';
		$set = self::dic()->database()->queryF($sql, [ 'integer' ], [ $id ]);
		$type = self::dic()->database()->fetchObject($set)->object_type;
		if (!$type) {
			//throw new HubException("Can not get type of origin id (probably deleted): ".$id);
			return NULL;
		}
		$class = $this->getClass($type);

		return $class::find((int)$id);
	}


	/**
	 * @inheritdoc
	 */
	public function createByType(string $type): IOrigin {
		$class = $this->getClass($type);

		return new $class();
	}


	/**
	 * @inheritdoc
	 */
	public function getAllActive(): array {
		$sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' WHERE active = %s ORDER BY sort';
		$set = self::dic()->database()->queryF($sql, [ 'integer' ], [ 1 ]);
		$origins = [];
		while ($data = self::dic()->database()->fetchObject($set)) {
			$origins[] = $this->getById($data->id);
		}

		return $origins;
	}


	/**
	 * @inheritdoc
	 */
	public function getAll(): array {
		$origins = [];

		$sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' ORDER BY sort';
		$set = self::dic()->database()->query($sql);
		while ($data = self::dic()->database()->fetchObject($set)) {
			$origins[] = $this->getById($data->id);
		}

		return $origins;
	}


	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function getClass($type) {
		$ucfirst = ucfirst($type);
		$class = "srag\\Plugins\\Hub2\\Origin\\{$ucfirst}\\AR{$ucfirst}Origin";

		return $class;
	}


	/**
	 * @param int $origin_id
	 */
	public function delete(int $origin_id)/*: void*/ {
		/**
		 * @var ActiveRecord $object
		 */

		foreach (DataTableGUI::$classes as $class) {
			foreach ($class::where([ "origin_id" => $origin_id ])->get() as $object) {
				$object->delete();
			}
		}

		$object = $this->getById($origin_id);
		$object->delete();
	}
}
