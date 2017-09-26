<?php namespace SRAG\Hub2\Object;

/**
 * Class ARObject
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
abstract class ARObject extends \ActiveRecord implements IObject {

	/**
	 * @var array
	 */
	protected static $available_status = [
		IObject::STATUS_NEW,
		IObject::STATUS_TO_CREATE,
		IObject::STATUS_CREATED,
		IObject::STATUS_UPDATED,
		IObject::STATUS_TO_UPDATE,
		IObject::STATUS_TO_DELETE,
		IObject::STATUS_DELETED,
		IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED,
		IObject::STATUS_IGNORED,
	];
	/**
	 * The primary ID is a composition of the origin-ID and ext_id
	 *
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_is_primary   true
	 * @db_fieldtype    text
	 * @db_length       255
	 */
	protected $id;
	/**
	 * @var int
	 *
	 * @db_has_field    true
	 * @db_fieldtype    integer
	 * @db_is_notnull   true
	 * @db_length       8
	 * @db_index        true
	 */
	protected $origin_id;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       255
	 * @db_index        true
	 */
	protected $ext_id = '';
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    timestamp
	 */
	protected $delivery_date;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    timestamp
	 */
	protected $processed_date;
	/**
	 * @var int
	 *
	 * @db_has_field    true
	 * @db_fieldtype    integer
	 * @db_length       8
	 */
	protected $ilias_id;
	/**
	 * @var int
	 *
	 * @db_has_field    true
	 * @db_fieldtype    integer
	 * @db_length       8
	 * @db_index        true
	 */
	protected $status = IObject::STATUS_NEW;
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       255
	 */
	protected $period = '';
	/**
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       512
	 */
	protected $hash_code;
	/**
	 * @var array
	 *
	 * @db_has_field    true
	 * @db_fieldtype    clob
	 */
	protected $data = array();


	/**
	 * @inheritdoc
	 */
	public function sleep($field_name) {
		switch ($field_name) {
			case 'data':
				return json_encode($this->getData());
		}

		return parent::sleep($field_name);
	}


	/**
	 * @inheritdoc
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'data':
				return json_decode($field_value, true);
		}

		return parent::wakeUp($field_name, $field_value);
	}


	/**
	 * @inheritdoc
	 */
	public function update() {
		$this->hash_code = $this->computeHashCode();
		parent::update();
	}


	/**
	 * @inheritdoc
	 */
	public function create() {
		if (!$this->origin_id) {
			throw new \Exception("Origin-ID is missing, cannot construct the primary key");
		}
		if (!$this->ext_id) {
			throw new \Exception("External-ID is missing");
		}
		$this->id = $this->origin_id . $this->ext_id;
		$this->hash_code = $this->computeHashCode();
		parent::create();
	}


	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @inheritdoc
	 */
	public function getExtId() {
		return $this->ext_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setExtId($id) {
		$this->ext_id = $id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getDeliveryDate() {
		return new \DateTime($this->delivery_date);
	}


	/**
	 * @inheritdoc
	 */
	public function getProcessedDate() {
		return new \DateTime($this->processed_date);
	}


	/**
	 * @inheritdoc
	 */
	public function setDeliveryDate($unix_timestamp) {
		$this->delivery_date = date('Y-m-d H:i:s', $unix_timestamp);
	}


	/**
	 * @inheritdoc
	 */
	public function setProcessedDate($unix_timestamp) {
		$this->processed_date = date('Y-m-d H:i:s', $unix_timestamp);
	}


	/**
	 * @inheritdoc
	 */
	public function getILIASId() {
		return $this->ilias_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setILIASId($id) {
		$this->ilias_id = (int)$id;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * @inheritdoc
	 */
	public function setStatus($status) {
		if (!in_array($status, self::$available_status)) {
			throw new \InvalidArgumentException("'{$status}' is not a valid status");
		}
		$this->status = $status;

		return $this;
	}


	/**
	 * @param int $origin_id
	 *
	 * @return $this
	 */
	public function setOriginId($origin_id) {
		$this->origin_id = $origin_id;

		return $this;
	}

	//	public function hasStatus($status) {
	//		return (bool)$status & $this->status;
	//	}
	//
	//	public function addStatus($status) {
	//		$this->status = $this->status | $status;
	//	}
	//
	//	public function removeStatus($status) {
	//		$this->status = $this->status & ~$status;
	//	}

	/**
	 * @inheritdoc
	 */
	public function getPeriod() {
		return $this->period;
	}


	/**
	 * @inheritdoc
	 */
	public function setPeriod($period) {
		$this->period = $period;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function computeHashCode() {
		$hash = '';
		foreach ($this->data as $property => $value) {
			$hash .= (is_array($value)) ? implode('', $value) : (string)$value;
		}

		return md5($hash);
	}


	/**
	 * @inheritdoc
	 */
	public function getHashCode() {
		return $this->hash_code;
	}


	/**
	 * @inheritdoc
	 */
	public function getData() {
		return $this->data;
	}


	/**
	 * @inheritdoc
	 */
	public function setData(array $data) {
		$this->data = $data;
		if (isset($data['period'])) {
			$this->period = $data['period'];
		}
	}


	/**
	 * @return string
	 */
	function __toString() {
		return implode(', ', [
			"origin_id: " . $this->origin_id,
			"type: " . get_class($this),
			"ext_id: " . $this->getExtId(),
			"ilias_id: " . $this->getILIASId(),
			"status: " . $this->getStatus(),
		]);
	}
}