<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;

/**
 * Class Metadata
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Metadata implements IMetadata
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    protected $identifier = 0;
    /**
     * @var mixed
     */
    protected $value;

    protected string $record_id;
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

    /**
     * @inheritdoc
     */
    public function setValue($value) : IMetadata
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setIdentifier(int $identifier) : IMetadata
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function setLanguageCode(string $code) : IMetadata
    {
        $this->language_code = $code;

        return $this;
    }

    public function getLanguageCode() : string
    {
        return $this->language_code;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getRecordId() : string
    {
        return $this->record_id ?? self::DEFAULT_RECORD_ID;
    }

    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        return json_encode(
            [$this->getRecordId() => [$this->getIdentifier() => $this->getValue()]],
            JSON_THROW_ON_ERROR
        );
    }
}
