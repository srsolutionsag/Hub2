<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel;

/**
 * Class ProfileLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProfileLevel implements IProfileLevel
{
    /**
     * @var string
     */
    protected $skill_id = "";
    /**
     * @var int
     */
    protected $skill_id_type = self::SKILL_ID_TYPE_ILIAS_ID;
    /**
     * @var string
     */
    protected $level_id = "";
    /**
     * @var int
     */
    protected $level_id_type = self::LEVEL_ID_TYPE_ILIAS_ID;

    /**
     * ProfileLevel constructor
     */
    public function __construct(
        string $skill_id = "",
        int $skill_id_type = self::SKILL_ID_TYPE_ILIAS_ID,
        string $level_id = "",
        int $level_id_type = self::LEVEL_ID_TYPE_ILIAS_ID
    ) {
        $this->skill_id = $skill_id;
        $this->skill_id_type = $skill_id_type;
        $this->level_id = $level_id;
        $this->level_id_type = $level_id_type;
    }

    /**
     * @inheritdoc
     */
    public function getSkillId() : string
    {
        return $this->skill_id;
    }

    /**
     * @inheritdoc
     */
    public function setSkillId(string $skill_id) : IProfileLevel
    {
        $this->skill_id = $skill_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSkillIdType() : int
    {
        return $this->skill_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setSkillIdType(int $skill_id_type) : IProfileLevel
    {
        $this->skill_id_type = $skill_id_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLevelId() : string
    {
        return $this->level_id;
    }

    /**
     * @inheritdoc
     */
    public function setLevelId(string $level_id) : IProfileLevel
    {
        $this->level_id = $level_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLevelIdType() : int
    {
        return $this->level_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setLevelIdType(int $level_id_type) : IProfileLevel
    {
        $this->level_id_type = $level_id_type;

        return $this;
    }

    /**
     * @inheritdoc
     * @return array{skill_id: string, skill_id_type: int, level_id: string, level_id_type: int}
     */
    public function jsonSerialize() : array
    {
        return [
            "skill_id" => $this->skill_id,
            "skill_id_type" => $this->skill_id_type,
            "level_id" => $this->level_id,
            "level_id_type" => $this->level_id_type,
        ];
    }
}
