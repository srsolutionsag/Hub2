<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel;

use JsonSerializable;

/**
 * Interface ISkillLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ISkillLevel extends JsonSerializable
{

    /**
     * @return string
     */
    public function getExtId() : string;

    /**
     * @param string $ext_id
     * @return self
     */
    public function setExtId(string $ext_id) : self;

    /**
     * @return string
     */
    public function getTitle() : string;

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title) : self;

    /**
     * @return string
     */
    public function getDescription() : string;

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description) : self;

    /**
     * @return array
     */
    public function jsonSerialize() : array;
}
