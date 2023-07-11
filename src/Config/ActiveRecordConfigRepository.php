<?php

namespace srag\Plugins\Hub2\Config;

use srag\Plugins\Hub2\Exception\ActiveRecordConfigException;

/**
 * Class ActiveRecordConfigRepository
 *
 * @package    srag\ActiveRecordConfig\Hub2
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Do not use - only used for be compatible with old version
 */
final class ActiveRecordConfigRepository extends AbstractRepository
{

    /**
     * @var self|null
     *
     * @deprecated
     */
    protected static $instance;

    /**
     *
     * @deprecated
     */
    public static function getInstance(string $table_name, array $fields) : self
    {
        if (!self::$instance instanceof \srag\Plugins\Hub2\Config\ActiveRecordConfigRepository) {
            self::$instance = new self($table_name, $fields);
        }

        return self::$instance;
    }

    /**
     * @var string
     *
     * @deprecated
     */
    protected $table_name;
    /**
     * @var array
     *
     * @deprecated
     */
    protected $fields;

    /**
     * ActiveRecordConfigRepository constructor
     *
     *
     * @deprecated
     */
    protected function __construct(string $table_name, array $fields)
    {
        $this->table_name = $table_name;
        $this->fields = $fields;

        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @return ActiveRecordConfigFactory
     *
     * @deprecated
     */
    public function factory() : AbstractFactory
    {
        return ActiveRecordConfigFactory::getInstance();
    }

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getTableName() : string
    {
        return $this->table_name;
    }

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getFields() : array
    {
        return $this->fields;
    }
}
