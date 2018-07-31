<?php

namespace SRAG\Plugins\Hub2\Origin;

use ilDB;
use ilDBInterface;

/**
 * Class OriginFactory
 *
 * @package SRAG\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFactory implements IOriginFactory {

	/**
	 * @var ilDB
	 */
	private $db;


	/**
	 * @param ilDBInterface $db
	 */
	public function __construct(ilDBInterface $db) {
		$this->db = $db;
	}


	/**
	 * @inheritdoc
	 */
	public function getById($id) {
		$sql = 'SELECT object_type FROM ' . AROrigin::TABLE_NAME . ' WHERE id = %s';
		$set = $this->db->queryF($sql, [ 'integer' ], [ $id ]);
		$type = $this->db->fetchObject($set)->object_type;
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
		$set = $this->db->queryF($sql, [ 'integer' ], [ 1 ]);
		while ($data = $this->db->fetchObject($set)) {
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
		$set = $this->db->query($sql);
		while ($data = $this->db->fetchObject($set)) {
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
		$class = "SRAG\\Plugins\\Hub2\\Origin\\{$ucfirst}\\AR{$ucfirst}Origin";

		return $class;
	}
}
