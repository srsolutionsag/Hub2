<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use DateTime;
use Exception;
use ilHub2Plugin;
use InvalidArgumentException;
use srag\ActiveRecordConfig\ActiveRecordConfig;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Metadata\Metadata;
use srag\Plugins\Hub2\Taxonomy\ITaxonomy;
use srag\Plugins\Hub2\Taxonomy\Node\Node;
use srag\Plugins\Hub2\Taxonomy\Taxonomy;

/**
 * Class ARObject
 *
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ARObject extends ActiveRecord implements IObject {

	use DICTrait;
	/**
	 * @abstract
	 */
	const TABLE_NAME = '';
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return static::TABLE_NAME;
	}


	/**
	 * @var array
	 */
	protected static $status_allowed_to_update_to = [
		IObject::STATUS_NOTHING_TO_UPDATE,
	];
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
		IObject::STATUS_NOTHING_TO_UPDATE,
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
	 * @var string
	 *
	 * @db_has_field    true
	 * @db_fieldtype    text
	 * @db_length       256
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
			case "meta_data":
				/**
				 * @var IMetadataAwareObject $this
				 */
				$metadataObjects = [];
				$metadata = $this->getMetaData();
				foreach ($metadata as $metadatum) {
					$metadataObjects[$metadatum->getIdentifier()] = $metadatum->getValue();
				}

				$json_encode = json_encode($metadataObjects);

				return $json_encode;
			case "taxonomies":
				/**
				 * @var ITaxonomyAwareObject $this
				 */
				$taxonomyObjects = [];
				$taxonomies = $this->getTaxonomies();
				foreach ($taxonomies as $tax) {
					$nodes = [];
					foreach ($tax->getNodes() as $node) {
						$nodes[] = $node->getTitle();
					}
					$taxonomyObjects[$tax->getTitle()] = $nodes;
				}

				$json_encode = json_encode($taxonomyObjects);

				return $json_encode;
		}

		return parent::sleep($field_name);
	}


	/**
	 * @inheritdoc
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'data':
				$data = json_decode($field_value, true);
				if (!is_array($data)) {
					$data = [];
				}

				return $data;
			case 'meta_data':
				if (is_null($field_value)) {
					return [];
				}
				$json_decode = json_decode($field_value, true);
				$IMetadata = [];
				if (is_array($json_decode)) {
					foreach ($json_decode as $key => $value) {
						$IMetadata[] = (new Metadata($key))->setValue($value);
					}
				}

				return $IMetadata;
			case 'taxonomies':
				if (is_null($field_value)) {
					return [];
				}
				$json_decode = json_decode($field_value, true);
				$taxonomies = [];
				foreach ($json_decode as $tax_title => $nodes) {
					$taxonomy = new Taxonomy($tax_title, ITaxonomy::MODE_CREATE);
					foreach ($nodes as $node) {
						$taxonomy->attach(new Node($node));
					}
					$taxonomies[] = $taxonomy;
				}

				return $taxonomies;
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
			throw new Exception("Origin-ID is missing, cannot construct the primary key");
		}
		if (!$this->ext_id) {
			throw new Exception("External-ID is missing");
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
		return new DateTime($this->delivery_date);
	}


	/**
	 * @inheritdoc
	 */
	public function getProcessedDate() {
		return new DateTime($this->processed_date);
	}


	/**
	 * @inheritdoc
	 */
	public function setDeliveryDate($unix_timestamp) {
		$this->delivery_date = date(ActiveRecordConfig::SQL_DATE_FORMAT, $unix_timestamp);
	}


	/**
	 * @inheritdoc
	 */
	public function setProcessedDate($unix_timestamp) {
		$this->processed_date = date(ActiveRecordConfig::SQL_DATE_FORMAT, $unix_timestamp);
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
		$this->ilias_id = $id;

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
			throw new InvalidArgumentException("'{$status}' is not a valid status");
		}
		$this->status = $status;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function updateStatus($status) {
		if (!in_array($status, self::$status_allowed_to_update_to)) {
			throw new InvalidArgumentException("'{$status}' is not valid to switch to");
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

		if (isset($this->meta_data)) {
			foreach ($this->meta_data as $property => $value) {
				$hash .= (is_array($value)) ? implode('', $value) : (string)$value;
			}
		}

		if (isset($this->taxonomies)) {
			foreach ($this->taxonomies as $property => $value) {
				$hash .= (is_array($value)) ? implode('', $value) : (string)$value;
			}
		}

		return md5($hash); // TODO: Use other not depcreated, safer hash algo (Like `hash("sha256", $hash)`). But this will cause update all origins!
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
