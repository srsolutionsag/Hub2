<?php

namespace srag\Plugins\Hub2\Metadata;

/**
 * Interface IMetadata
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata
{
    public const DEFAULT_RECORD_ID = 1;

    /**
     * @param string|string[] $value
     */
    public function setValue($value) : IMetadata;

    public function setIdentifier(int $identifier) : IMetadata;

    public function setLanguageCode(string $code) : IMetadata;

    public function getLanguageCode() : string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function getIdentifier();

    public function getRecordId() : int;

    public function __toString() : string;
}
