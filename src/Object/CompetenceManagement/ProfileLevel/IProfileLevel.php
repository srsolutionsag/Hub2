<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel;

use JsonSerializable;

/**
 * Interface IProfileLevel
 *
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IProfileLevel extends JsonSerializable
{

    /**
     * @var int
     */
    const SKILL_ID_TYPE_ILIAS_ID = 1;
    /**
     * @var int
     */
    const SKILL_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var int
     */
    const LEVEL_ID_TYPE_ILIAS_ID = 1;
    /**
     * @var int
     */
    const LEVEL_ID_TYPE_EXTERNAL_EXT_ID = 2;


    /**
     * @return string
     */
    public function getSkillId() : string;


    /**
     * @param string $skill_id
     *
     * @return self
     */
    public function setSkillId(string $skill_id) : self;


    /**
     * @return int
     */
    public function getSkillIdType() : int;


    /**
     * @param int $skill_id_type
     *
     * @return self
     */
    public function setSkillIdType(int $skill_id_type) : self;


    /**
     * @return string
     */
    public function getLevelId() : string;


    /**
     * @param string $level_id
     *
     * @return self
     */
    public function setLevelId(string $level_id) : self;


    /**
     * @return int
     */
    public function getLevelIdType() : int;


    /**
     * @param int $level_id_type
     *
     * @return self
     */
    public function setLevelIdType(int $level_id_type) : self;


    /**
     * @return array
     */
    public function jsonSerialize() : array;
}
