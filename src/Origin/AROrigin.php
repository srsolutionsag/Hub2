<?php

namespace SRAG\Plugins\Hub2\Origin;

use ActiveRecord;
use InvalidArgumentException;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * ILIAS ActiveRecord implementation of an Origin
 *
 * @package SRAG\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AROrigin extends ActiveRecord implements IOrigin {

	const TABLE_NAME = 'sr_hub2_origin';
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
		IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP,
		IOrigin::OBJECT_TYPE_ORGNUNIT,
		IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP
	];


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


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
	 * @db_fieldtype           text
	 * @db_length              256
	 */
	protected $implementation_namespace = IOrigin::ORIGIN_MAIN_NAMESPACE;
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
	/**
	 * @var string
	 *
	 * @db_has_field           true
	 * @db_fieldtype           timestamp
	 */
	protected $last_run;
	/**
	 * @var bool
	 */
	protected $force_update = false;


	/**
	 *
	 */
	public function create() {
		$this->created_at = date('Y-m-d H:i:s');
		$this->setObjectType($this->parseObjectType());
		parent::create();
	}


	/**
	 *
	 */
	public function update() {
		$this->updated_at = date('Y-m-d H:i:s');
		parent::update();
	}


	/**
	 * @inheritdoc
	 */
	public function sleep($field_name) {
		switch ($field_name) {
			case 'config':
				if ($this->_config === NULL) {
					$config = $this->getOriginConfig([]);

					return json_encode($config->getData());
				} else {
					return json_encode($this->config()->getData());
				}
			case 'properties':
				if ($this->_properties === NULL) {
					$properties = $this->getOriginProperties([]);

					return json_encode($properties->getData());
				} else {
					return json_encode($this->properties()->getData());
				}
		}

		return parent::sleep($field_name);
	}


	/**
	 * @inheritdoc
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'config':
			case 'properties':
				return json_decode($field_value, true);
		}

		return parent::wakeUp($field_name, $field_value);
	}


	/**
	 *
	 */
	public function afterObjectLoad() {
		$this->_config = $this->getOriginConfig($this->getConfigData());
		$this->_properties = $this->getOriginProperties($this->getPropertiesData());
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
		return (bool)$this->active;
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
	 * @return string
	 */
	public function getImplementationNamespace() {
		return $this->implementation_namespace ? $this->implementation_namespace : IOrigin::ORIGIN_MAIN_NAMESPACE;
	}


	/**
	 * @param string $implementation_namespace
	 */
	public function setImplementationNamespace($implementation_namespace) {
		$this->implementation_namespace = $implementation_namespace;
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
	 * @return string
	 */
	public function getLastRun() {
		return $this->last_run;
	}


	/**
	 * @param string $last_run
	 */
	public function setLastRun($last_run) {
		$this->last_run = $last_run;
	}


	/**
	 * @inheritdoc
	 */
	public function setObjectType($type) {
		if (!in_array($type, self::$object_types)) {
			throw new InvalidArgumentException("'$type' is not a valid hub object type");
		}
		$this->object_type = $type;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function config() {
		return $this->_config;
	}


	/**
	 * @inheritdoc
	 */
	public function properties() {
		return $this->_properties;
	}

	//	/**
	//	 * @inheritdoc
	//	 */
	//	public function implementation() {
	//		$factory = new OriginImplementationFactory(new HubConfig(), $this);
	//		return $factory->instance();
	//	}

	/**
	 * Return the concrete implementation of the IOriginConfig.
	 *
	 * @param array $data
	 *
	 * @return IOriginConfig
	 */
	abstract protected function getOriginConfig(array $data);


	/**
	 * Return the concrete implementation of the origin properties.
	 *
	 * @param array $data
	 *
	 * @return IOriginProperties
	 */
	abstract protected function getOriginProperties(array $data);


	/**
	 * @return string
	 */
	private function parseObjectType() {
		$out = [];
		preg_match('%AR(.*)Origin$%', get_class($this), $out);

		return lcfirst($out[1]);
	}


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


	/**
	 * Run Sync without Hash comparison
	 */
	public function forceUpdate() {
		$this->force_update = true;
	}


	/**
	 * @return bool
	 */
	public function isUpdateForced(): bool {
		return $this->force_update;
	}
}
