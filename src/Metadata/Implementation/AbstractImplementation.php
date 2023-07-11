<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilHub2Plugin;
use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class CustomMetadata
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractImplementation implements IMetadataImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    private $ilias_id;
    /**
     * @var IMetadata
     */
    private $metadata;

    /**
     * UDF constructor
     */
    public function __construct(IMetadata $metadata, int $ilias_id)
    {
        $this->metadata = $metadata;
        $this->ilias_id = $ilias_id;
    }

    /**
     * @inheritdoc
     */
    abstract public function write();

    /**
     * @inheritdoc
     */
    public function getMetadata() : IMetadata
    {
        return $this->metadata;
    }

    /**
     * @inheritdoc
     */
    public function getIliasId() : int
    {
        return $this->ilias_id;
    }
}
