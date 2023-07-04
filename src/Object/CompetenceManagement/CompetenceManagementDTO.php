<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement;

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel\IProfileLevel;
use srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel\ProfileLevel;
use srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel\ISkillLevel;
use srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel\SkillLevel;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\ICompetenceManagementProperties;

/**
 * Class CompetenceManagementDTO
 * @package srag\Plugins\Hub2\Object\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CompetenceManagementDTO extends DataTransferObject implements ICompetenceManagementDTO
{
    /**
     * @var int
     */
    protected $type = self::TYPE_COMPETENCE;
    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var string
     */
    protected $description = "";
    /**
     * @var string
     */
    protected $parent_id = "";
    /**
     * @var int
     */
    protected $parent_id_type = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var int
     */
    protected $status = self::STATUS_PUBLISH;
    /**
     * @var bool
     */
    protected $self_evaluation = false;
    /**
     * @var ISkillLevel[]
     */
    protected $skill_levels = [];
    /**
     * @var IProfileLevel[]
     */
    protected $profile_levels = [];
    /**
     * @var int[]
     */
    protected $profile_assigned_users = [];
    /**
     * @var string
     */
    protected $ext_id = "";

    /**
     * @inheritdoc
     */
    public function __construct(string $ext_id)
    {
        parent::__construct($ext_id);
        $this->ext_id = $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function setType(int $type): ICompetenceManagementDTO
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): ICompetenceManagementDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_PROFILE) {
            throw new HubException("Description are only supported for TYPE_PROFILE!");
        }

        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentId(): string
    {
        return $this->parent_id;
    }

    /**
     * @inheritdoc
     */
    public function setParentId(string $parent_id): ICompetenceManagementDTO
    {
        if ($this->getType() === self::TYPE_PROFILE) {
            throw new HubException("ParentId are not supported for TYPE_PROFILE!");
        }

        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentIdType(): int
    {
        return $this->parent_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setParentIdType(int $parent_id__type): ICompetenceManagementDTO
    {
        if ($this->getType() === self::TYPE_PROFILE) {
            throw new HubException("ParentIdType are not supported for TYPE_PROFILE!");
        }

        $this->parent_id_type = $parent_id__type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): ICompetenceManagementDTO
    {
        if ($this->getType() === self::TYPE_PROFILE) {
            throw new HubException("Status are not supported for TYPE_PROFILE!");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSelfEvaluation(): bool
    {
        return $this->self_evaluation;
    }

    /**
     * @inheritdoc
     */
    public function setSelfEvaluation(bool $self_evaluation): ICompetenceManagementDTO
    {
        if ($this->getType() === self::TYPE_PROFILE) {
            throw new HubException("SelfEvaluation are not supported for TYPE_PROFILE!");
        }

        $this->self_evaluation = $self_evaluation;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addSkillLevel(ISkillLevel $skill_level): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_COMPETENCE) {
            throw new HubException("SkillLevels are only supported for TYPE_COMPETENCE!");
        }

        $this->skill_levels[] = $skill_level;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSkillLevels(): array
    {
        return $this->skill_levels;
    }

    /**
     * @inheritdoc
     */
    public function setSkillLevels(array $skill_levels): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_COMPETENCE) {
            throw new HubException("SkillLevels are only supported for TYPE_COMPETENCE!");
        }

        $this->skill_levels = $skill_levels;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addProfileLevel(IProfileLevel $profile_level): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_PROFILE) {
            throw new HubException("ProfileLevels are only supported for TYPE_PROFILE!");
        }

        $this->profile_levels[] = $profile_level;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getProfileLevels(): array
    {
        return $this->profile_levels;
    }

    /**
     * @inheritdoc
     */
    public function setProfileLevels(array $profile_levels): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_PROFILE) {
            throw new HubException("ProfileLevels are only supported for TYPE_PROFILE!");
        }

        $this->profile_levels = $profile_levels;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addProfileAssignedUser(int $user_id): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_PROFILE) {
            throw new HubException("ProfileAssignedUsers are only supported for TYPE_PROFILE!");
        }

        $this->profile_assigned_users[] = $user_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getProfileAssignedUsers(): array
    {
        return $this->profile_assigned_users;
    }

    /**
     * @inheritdoc
     */
    public function setProfileAssignedUsers(array $user_ids): ICompetenceManagementDTO
    {
        if ($this->getType() !== self::TYPE_PROFILE) {
            throw new HubException("ProfileAssignedUsers are only supported for TYPE_PROFILE!");
        }

        $this->profile_assigned_users = $user_ids;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId(string $ext_id): ICompetenceManagementDTO
    {
        $this->ext_id = $ext_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function sleepValue(array &$data, string $key)
    {
        switch ($key) {
            case ICompetenceManagementProperties::PROP_SKILL_LEVELS:
            case ICompetenceManagementProperties::PROP_PROFILE_LEVELS:
                $data[$key] = json_decode(
                    json_encode($this->{$key}, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR
                );

            // no break
            default:
                parent::sleepValue($data, $key);
        }
    }

    /**
     * @inheritdoc
     */
    protected function wakeUpValue(array $data, string $key)
    {
        switch ($key) {
            case ICompetenceManagementProperties::PROP_SKILL_LEVELS:
                return array_map(
                    function ($skill_level): ISkillLevel {
                        $key = null;
                        if ($skill_level instanceof ISkillLevel) {
                            $this->{$key} = $skill_level;
                        }

                        return new SkillLevel(
                            $skill_level["ext_id"],
                            $skill_level["title"],
                            $skill_level["description"]
                        );
                    },
                    $data[$key]
                );

            case ICompetenceManagementProperties::PROP_PROFILE_LEVELS:
                return array_map(
                    function ($profile_level): IProfileLevel {
                        $key = null;
                        if ($profile_level instanceof IProfileLevel) {
                            $this->{$key} = $profile_level;
                        }

                        $this->{$key} = new ProfileLevel(
                            $profile_level["skill_id"],
                            $profile_level["skill_id_type"],
                            $profile_level["level_id"],
                            $profile_level["level_id_type"]
                        );
                    },
                    $data[$key]
                );

            default:
                return parent::wakeUpValue($data, $key);
        }
    }
}
