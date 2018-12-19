<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class OriginFactory
 *
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFactory implements IOriginFactory {

	use DICTrait;
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
		$origins = [];

		$sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' WHERE active = %s';
		$set = self::dic()->database()->queryF($sql, [ 'integer' ], [ 1 ]);
		while ($data = self::dic()->database()->fetchObject($set)) {
			$origins[] = $this->getById($data->id);
		}

		return $origins;
	}


	/**
	 * @inheritDoc
	 */
	public function getAll(): array {
		$origins = [];

		$sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME;
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
}
