<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Config;

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
     * @readonly
     */
    private string $table_name;
    /**
     * @readonly
     */
    private array $fields;
    /**
     * @deprecated
     */
    private static ?\srag\Plugins\Hub2\Config\ActiveRecordConfigRepository $instance = null;

    /**
     *
     * @deprecated
     */
    public static function getInstance(string $table_name, array $fields): self
    {
        if (!self::$instance instanceof \srag\Plugins\Hub2\Config\ActiveRecordConfigRepository) {
            self::$instance = new self($table_name, $fields);
        }

        return self::$instance;
    }

    /**
     * ActiveRecordConfigRepository constructor
     *
     *
     * @deprecated
     */
    protected function __construct(
        string $table_name,
        array $fields
    ) {
        /**
         * @deprecated
         */
        $this->table_name = $table_name;
        /**
         * @deprecated
         */
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
    public function factory(): AbstractFactory
    {
        return ActiveRecordConfigFactory::getInstance();
    }

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getFields(): array
    {
        return $this->fields;
    }
}
