<?php namespace SRAG\Hub2\Origin;

/**
 * Class OriginFactory
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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
		$sql = 'SELECT object_type FROM ' . AROrigin::returnDbTableName() . ' WHERE id = ' . $this->db->quote($id, 'integer');
		$set = $this->db->query($sql);
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
}