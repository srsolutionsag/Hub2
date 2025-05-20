<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    /**
     * @readonly
     */
    private IMetadata $metadata;
    /**
     * @readonly
     */
    private int $ilias_id;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * UDF constructor
     */
    public function __construct(IMetadata $metadata, int $ilias_id)
    {
        $this->metadata = $metadata;
        $this->ilias_id = $ilias_id;
    }

    abstract public function write();

    public function getMetadata(): IMetadata
    {
        return $this->metadata;
    }

    public function getIliasId(): int
    {
        return $this->ilias_id;
    }
}
