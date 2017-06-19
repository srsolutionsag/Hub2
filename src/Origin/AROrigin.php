<?php namespace SRAG\Hub2\Origin;
use SRAG\Hub2\Origin\Config\IOriginConfig;
use SRAG\Hub2\Origin\Properties\IOriginProperties;

/**
 * ILIAS ActiveRecord implementation of an Origin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
abstract class AROrigin extends \ActiveRecord implements IOrigin {

	/**
	 * @var array
	 */
	static $object_types = [
		IOrigin::OBJECT_TYPE_USER,
		IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP,
		IOrigin::OBJECT_TYPE_COURSE,
		IOrigin::OBJECT_TYPE_CATEGORY,
		IOrigin::OBJECT_TYPE_GROUP,
		IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP,
		IOrigin::OBJECT_TYPE_SESSION,
	];

	/**
	 * @var int
	 *
	 * @db_has_field          true
	 * @db_is_unique          true
	 * @db_is_primary         true
	 * @db_fieldtype          integer
	 * @db_length             8
	 * @db_sequence           true
	 */
	protected $id = 0;

	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_fieldtype           text
	 * @db_length              32
	 * @db_is_notnull
	 */
	protected $object_type;

	/**
	 * @var bool
	 *
	 * @db_has_field           true
	 * @db_fieldtype           integer
	 * @db_length              1
	 */
	protected $active = 0;

	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_is_notnull          true
	 * @db_fieldtype           text
	 * @db_length              2048
	 */
	protected $title;

	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           2048
	 */
	protected $description;

	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_fieldtype           text
	 * @db_length              256
	 * @db_is_notnull          true
	 */
	protected $implementation_class_name = '';

	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_fieldtype           timestamp
	 */
	protected $updated_at;

	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_fieldtype           timestamp
	 */
	protected $created_at;

	/**
	 * @var array
	 *
	 * @db_has_field        true
	 * @db_fieldtype        clob
	 * @db_length           4000
	 */
	protected $config = array();

	/**
	 * @var array
	 *
	 * @db_has_field        true
	 * @db_fieldtype        clob
	 * @db_length           4000
	 */
	protected $properties = array();

	/**
	 * @var IOriginConfig
	 */
	protected $_config;

	/**
	 * @var IOriginProperties
	 */
	protected $_properties;

	public function create() {
		$this->created_at = date('Y-m-d H:i:s');
//		$this->setObjectType($this->returnObjectType());
		parent::create();
	}

	public function update() {
		$this->updated_at = date('Y-m-d H:i:s');
//		$this->setObjectType($this->returnObjectType());
		parent::update();
	}

	public function sleep($field_name) {
		switch ($field_name) {
			case 'config':
				return json_encode($this->config()->getData());
			case 'properties':
				return json_encode($this->properties()->getData());
		}
		return parent::sleep($field_name);
	}

	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'config':
			case 'properties':
				return json_decode($field_value, true);
		}
		return parent::wakeUp($field_name, $field_value);
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
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @inheritdoc
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @inheritdoc
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @inheritdoc
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * @inheritdoc
	 */
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getImplementationClassName() {
		return $this->implementation_class_name;
	}

	/**
	 * @inheritdoc
	 */
	public function setImplementationClassName($name) {
		$this->implementation_class_name = $name;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @inheritdoc
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * @inheritdoc
	 */
	public function getObjectType() {
		return $this->object_type;
	}

	/**
	 * @inheritdoc
	 */
	public function setObjectType($type) {
		if (!in_array($type, self::$object_types)) {
			throw new \InvalidArgumentException("'$type' is not a valid hub object type");
		}
		$this->object_type = $type;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function config() {
		if ($this->_config === null) {
			$this->_config = $this->getOriginConfig($this->getConfigData());
		}
		return $this->_config;
	}

	/**
	 * @inheritdoc
	 */
	public function properties() {
		if ($this->_properties === null) {
			$this->_properties = $this->getOriginProperties($this->getPropertiesData());
		}
		return $this->_properties;
	}

	/**
	 * @inheritdoc
	 */
	public function implementation() {
		// TODO: Implement implementation() method.
	}


	public static function returnDbTableName() {
		return 'sr_hub2_origin';
	}

	/**
	 * Return the concrete implementation of the IOriginConfig.
	 *
	 * @param array $data
	 * @return IOriginConfig
	 */
	abstract protected function getOriginConfig(array $data);

	/**
	 * Return the concrete implementation of the origin properties.
	 *
	 * @param array $data
	 * @return IOriginProperties
	 */
	abstract protected function getOriginProperties(array $data);

	/**
	 * Return the object type of this origin.
	 *
	 * @return int
	 */
//	abstract protected function returnObjectType();

	/**
	 * @return array
	 */
	protected function getConfigData() {
		return $this->config;
	}

	/**
	 * @return array
	 */
	protected function getPropertiesData() {
		return $this->properties;
	}

}