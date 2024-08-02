<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel;

/**
 * Class ProfileLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProfileLevel implements IProfileLevel
{
    protected string $skill_id;
    protected int $skill_id_type;
    protected string $level_id;
    protected int $level_id_type;

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


    public function getSkillId(): string
    {
        return $this->skill_id;
    }


    public function setSkillId(string $skill_id): IProfileLevel
    {
        $this->skill_id = $skill_id;

        return $this;
    }


    public function getSkillIdType(): int
    {
        return $this->skill_id_type;
    }


    public function setSkillIdType(int $skill_id_type): IProfileLevel
    {
        $this->skill_id_type = $skill_id_type;

        return $this;
    }


    public function getLevelId(): string
    {
        return $this->level_id;
    }


    public function setLevelId(string $level_id): IProfileLevel
    {
        $this->level_id = $level_id;

        return $this;
    }


    public function getLevelIdType(): int
    {
        return $this->level_id_type;
    }


    public function setLevelIdType(int $level_id_type): IProfileLevel
    {
        $this->level_id_type = $level_id_type;

        return $this;
    }

    /**
     * @inheritdoc
     * @return array{skill_id: string, skill_id_type: int, level_id: string, level_id_type: int}
     */
    public function jsonSerialize(): array
    {
        return [
            "skill_id" => $this->skill_id,
            "skill_id_type" => $this->skill_id_type,
            "level_id" => $this->level_id,
            "level_id_type" => $this->level_id_type,
        ];
    }
}
