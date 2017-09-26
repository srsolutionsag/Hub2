<?php namespace SRAG\Hub2\Origin;

/**
 * Class OriginFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
class OriginFactory implements IOriginFactory {

	/**
	 * @var \ilDB
	 */
	private $db;


	/**
	 * @param \ilDBInterface $db
	 */
	public function __construct(\ilDBInterface $db) {
		$this->db = $db;
	}


	/**
	 * @inheritdoc
	 */
	public function getById($id) {
		$sql = 'SELECT object_type FROM sr_hub2_origin WHERE id = %s';
		$set = $this->db->queryF($sql, [ 'integer' ], [ $id ]);
		$type = $this->db->fetchObject($set)->object_type;
		$class = 'SRAG\Hub2\Origin\AR' . ucfirst($type) . 'Origin';

		return $class::find((int)$id);
	}


	/**
	 * @inheritdoc
	 */
	public function createByType($type) {
		$class = 'SRAG\Hub2\Origin\AR' . $type . 'Origin';

		return new $class();
	}


	/**
	 * @inheritdoc
	 */
	public function getAllActive() {
		$origins = [];

		$sql = 'SELECT id FROM sr_hub2_origin WHERE active = %s';
		$set = $this->db->queryF($sql, [ 'integer' ], [ 1 ]);
		while ($data = $this->db->fetchObject($set)) {
			$origins[] = $this->getById($data->id);
		}

		return $origins;
	}
}