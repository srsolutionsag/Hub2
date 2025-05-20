<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;

/**
 * Class Metadata
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Metadata implements IMetadata
{
    protected int $identifier;
    protected int $record_id = self::DEFAULT_RECORD_ID;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var string
     */
    protected $language_code = 'de';

    /**
     * Metadata constructor
     * @param int $identifier
     */
    public function __construct($identifier, string $record_id = self::DEFAULT_RECORD_ID)
    {
        $this->identifier = $identifier;
        $this->record_id = $record_id;
    }

    public function setValue($value): IMetadata
    {
        $this->value = $value;

        return $this;
    }

    public function setIdentifier(int $identifier): IMetadata
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function setLanguageCode(string $code): IMetadata
    {
        $this->language_code = $code;

        return $this;
    }

    public function getLanguageCode(): string
    {
        return $this->language_code;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getRecordId(): string
    {
        return $this->record_id ?? self::DEFAULT_RECORD_ID;
    }

    public function __toString(): string
    {
        return json_encode(
            [$this->getRecordId() => [$this->getIdentifier() => $this->getValue()]],
            JSON_THROW_ON_ERROR
        );
    }
}
