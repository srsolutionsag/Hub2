<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel;

use JsonSerializable;

/**
 * Interface IProfileLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IProfileLevel extends JsonSerializable
{
    /**
     * @var int
     */
    public const SKILL_ID_TYPE_ILIAS_ID = 1;
    /**
     * @var int
     */
    public const SKILL_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var int
     */
    public const LEVEL_ID_TYPE_ILIAS_ID = 1;
    /**
     * @var int
     */
    public const LEVEL_ID_TYPE_EXTERNAL_EXT_ID = 2;

    public function getSkillId() : string;

    public function setSkillId(string $skill_id) : self;

    public function getSkillIdType() : int;

    public function setSkillIdType(int $skill_id_type) : self;

    public function getLevelId() : string;

    public function setLevelId(string $level_id) : self;

    public function getLevelIdType() : int;

    public function setLevelIdType(int $level_id_type) : self;

    public function jsonSerialize() : array;
}
