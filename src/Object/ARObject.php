<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use arConnector;
use DateTime;
use Exception;
use ilHub2Plugin;
use InvalidArgumentException;
use srag\Plugins\Hub2\Config\ActiveRecordConfig;
use srag\Plugins\Hub2\Metadata\Metadata;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Taxonomy\ITaxonomy;
use srag\Plugins\Hub2\Taxonomy\Node\Node;
use srag\Plugins\Hub2\Taxonomy\Taxonomy;
use srag\Plugins\Hub2\Sync\Processor\HashCodeComputer;
use srag\Plugins\Hub2\Log\Repository as LogRepository;
use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class ARObject
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ARObject extends ActiveRecord implements IObject
{
    use HashCodeComputer;

    /**
     * @abstract
     */
    public const TABLE_NAME = '';
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var \srag\Plugins\Hub2\Log\IRepository
     */
    protected $log_repo;
    /**
     * a clone of this object made before any changes happened; used to compare when persisting
     * @var static
     */
    protected $clone;

    /**
     * ARObject constructor.
     * @param int              $primary_key
     * @param arConnector|null $connector
     */
    public function __construct($primary_key = 0, arConnector $connector = null)
    {
        parent::__construct($primary_key, $connector);
        $this->clone = clone $this;
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @return string
     * @deprecated
     */
    public static function returnDbTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @var array
     */
    public static $available_status
        = [
            IObject::STATUS_NEW => "new",
            IObject::STATUS_TO_CREATE => "to_create",
            IObject::STATUS_CREATED => "created",
            IObject::STATUS_UPDATED => "updated",
            IObject::STATUS_TO_UPDATE => "to_update",
            IObject::STATUS_TO_OUTDATED => "to_outdated",
            IObject::STATUS_OUTDATED => "outdated",
            IObject::STATUS_TO_RESTORE => "to_restore",
            IObject::STATUS_IGNORED => "ignored",
            IObject::STATUS_FAILED => "failed",
        ];
    /**
     * fields whose changes trigger the creation of a log entry
     * @var array
     */
    public static $observed_fields
        = [
            'External ID' => 'ext_id',
            'ILIAS ID' => 'ilias_id',
            'Status' => 'status',
            'Period' => 'period',
            'Data' => 'data',
        ];
    /**
     * The primary ID is a composition of the origin-ID and ext_id
     * @var string
     * @db_has_field    true
     * @db_is_primary   true
     * @db_fieldtype    text
     * @db_length       255
     */
    protected $id;
    /**
     * @var int
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_is_notnull   true
     * @db_length       8
     * @db_index        true
     */
    protected $origin_id;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     * @db_index        true
     */
    protected $ext_id = '';
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $delivery_date;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $processed_date;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       256
     */
    protected $ilias_id;
    /**
     * @var int
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     * @db_index        true
     */
    protected $status = IObject::STATUS_NEW;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     */
    protected $period = '';
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       512
     */
    protected $hash_code;
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected $data = [];

    /**
     *
     */
    public function afterObjectLoad() : void
    {
        $this->clone = clone $this;
    }

    /**
     * @inheritdoc
     */
    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'data':
                return json_encode($this->getData(), JSON_THROW_ON_ERROR);
            case "meta_data":
                $metadataObjects = [];
                /** @var IMetadata[] $metadata */
                $metadata = $this->getMetaData();
                foreach ($metadata as $metadatum) {
                    $key = $metadatum->getRecordId() . '_' . $metadatum->getIdentifier();
                    $metadataObjects[$key] = $metadatum->getValue();
                }

                return json_encode($metadataObjects, JSON_THROW_ON_ERROR, 8);
            case "taxonomies":
                $taxonomyObjects = [];
                $taxonomies = $this->getTaxonomies();
                foreach ($taxonomies as $tax) {
                    $nodes = [];
                    foreach ($tax->getNodes() as $node) {
                        $nodes[] = $node->getTitle();
                    }
                    $taxonomyObjects[$tax->getTitle()] = $nodes;
                }

                return json_encode($taxonomyObjects, JSON_THROW_ON_ERROR);
        }

        return parent::sleep($field_name);
    }

    /**
     * @inheritdoc
     */
    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'data':
                $data = json_decode($field_value, true, 8, JSON_THROW_ON_ERROR);
                if (!is_array($data)) {
                    $data = [];
                }

                return $data;
            case 'meta_data':
                if (is_null($field_value)) {
                    return [];
                }
                $json_decode = json_decode($field_value, true, 8, JSON_THROW_ON_ERROR);
                $IMetadata = [];
                if (is_array($json_decode)) {
                    foreach ($json_decode as $record_id => $records) {
                        if (!is_array($records)) {
                            continue;
                        }
                        foreach (array_keys($records) as $mid) {
                            $IMetadata[$record_id . '_' . $mid] = (new Metadata($mid, $record_id))->setValue($records);
                        }
                    }
                }

                return $IMetadata;
            case 'taxonomies':
                if (is_null($field_value)) {
                    return [];
                }
                $json_decode = json_decode($field_value, true, 8, JSON_THROW_ON_ERROR);
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
    public function update() : void
    {
        $this->hash_code = $this->computeHashCode();
        parent::update();
        $this->logUpdate();
    }

    /**
     *
     */
    protected function logUpdate()
    {
        $messages = [];
        foreach (self::$observed_fields as $title => $field) {
            if ($this->clone->$field !== $this->$field) {
                $messages[] = $title . " updated";
            }
        }
        if ($messages !== []) {
            $this->log_repo->factory()->originLog((new OriginFactory())->getById($this->origin_id), $this)
                           ->write(implode('<br>', $messages));
        }
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        if ($this->origin_id === 0) {
            throw new Exception("Origin-ID is missing, cannot construct the primary key");
        }
        if ($this->ext_id === '' || $this->ext_id === '0') {
            throw new Exception("External-ID is missing");
        }
        $this->id = $this->origin_id . $this->ext_id;
        $this->hash_code = $this->computeHashCode();
        parent::create();
        $this->log_repo->factory()->originLog((new OriginFactory())->getById($this->origin_id), $this)
                       ->write("Created");
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getExtId()
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId($id)
    {
        $this->ext_id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryDate() : DateTime
    {
        return new DateTime($this->delivery_date);
    }

    /**
     * @inheritdoc
     */
    public function getProcessedDate() : DateTime
    {
        return new DateTime($this->processed_date);
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryDate(int $unix_timestamp) : void
    {
        $this->delivery_date = date(ActiveRecordConfig::SQL_DATE_FORMAT, $unix_timestamp);
    }

    /**
     * @inheritdoc
     */
    public function setProcessedDate(int $unix_timestamp) : void
    {
        $this->processed_date = date(ActiveRecordConfig::SQL_DATE_FORMAT, $unix_timestamp);
    }

    /**
     * @inheritdoc
     */
    public function getILIASId()
    {
        return $this->ilias_id;
    }

    /**
     * @inheritdoc
     */
    public function setILIASId($id)
    {
        $this->ilias_id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status)
    {
        if (!isset(self::$available_status[$status])) {
            throw new InvalidArgumentException("'{$status}' is not a valid status");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @param int $origin_id
     * @return $this
     */
    public function setOriginId($origin_id)
    {
        $this->origin_id = $origin_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @inheritdoc
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHashCode()
    {
        return $this->hash_code;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function setData(array $data) : void
    {
        $this->data = $data;
        if (isset($data['period'])) {
            $this->period = $data['period'];
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(
            ', ',
            [
                "origin_id: " . $this->origin_id,
                "type: " . get_class($this),
                "ext_id: " . $this->getExtId(),
                "ilias_id: " . $this->getILIASId(),
                "status: " . $this->getStatus(),
            ]
        );
    }
}
