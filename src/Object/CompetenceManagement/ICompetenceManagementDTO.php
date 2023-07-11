<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement;

use ilSkillTreeNode;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel\IProfileLevel;
use srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel\ISkillLevel;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface ICompetenceManagementDTO
 * @package srag\Plugins\Hub2\Object\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ICompetenceManagementDTO extends IDataTransferObject
{
    /**
     * @var int
     */
    public const PARENT_ID_TYPE_REF_ID = 1;
    /**
     * @var int
     */
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var int
     */
    public const TYPE_COMPETENCE = 1;
    /**
     * @var int
     */
    public const TYPE_CATEGORY = 2;
    /**
     * @var int
     */
    public const TYPE_COMPETENCE_TEMPLATE = 3;
    /**
     * @var int
     */
    public const TYPE_CATEGORY_TEMPLATE = 4;
    /**
     * @var int
     */
    public const TYPE_REFERENCE = 5;
    /**
     * @var int
     */
    public const TYPE_PROFILE = 6;
    /**
     * @var int
     */
    public const STATUS_PUBLISH = ilSkillTreeNode::STATUS_PUBLISH;
    /**
     * @var int
     */
    public const STATUS_DRAFT = ilSkillTreeNode::STATUS_DRAFT;
    /**
     * @var int
     */
    public const STATUS_OUTDATED = ilSkillTreeNode::STATUS_OUTDATED;

    public function getType() : int;

    public function setType(int $type) : self;

    public function getTitle() : string;

    public function setTitle(string $title) : self;

    public function getDescription() : string;

    /**
     * @throws HubException
     */
    public function setDescription(string $description) : self;

    public function getParentId() : string;

    /**
     * @throws HubException
     */
    public function setParentId(string $parent_id) : self;

    public function getParentIdType() : int;

    /**
     * @throws HubException
     */
    public function setParentIdType(int $parent_id_type) : self;

    public function getStatus() : int;

    /**
     * @throws HubException
     */
    public function setStatus(int $status) : self;

    public function getSelfEvaluation() : bool;

    /**
     * @throws HubException
     */
    public function setSelfEvaluation(bool $self_evaluation) : self;

    /**
     * @throws HubException
     */
    public function addSkillLevel(ISkillLevel $skill_level) : self;

    /**
     * @return ISkillLevel[]
     */
    public function getSkillLevels() : array;

    /**
     * @param ISkillLevel[] $skill_levels
     * @throws HubException
     */
    public function setSkillLevels(array $skill_levels) : self;

    /**
     * @throws HubException
     */
    public function addProfileLevel(IProfileLevel $profile_level) : self;

    /**
     * @return IProfileLevel[]
     */
    public function getProfileLevels() : array;

    /**
     * @param IProfileLevel[] $profile_levels
     * @throws HubException
     */
    public function setProfileLevels(array $profile_levels) : self;

    /**
     * @throws HubException
     */
    public function addProfileAssignedUser(int $user_id) : self;

    /**
     * @return int[]
     */
    public function getProfileAssignedUsers() : array;

    /**
     * @param int[] $user_ids
     * @throws HubException
     */
    public function setProfileAssignedUsers(array $user_ids) : self;

    public function getExtId() : string;

    public function setExtId(string $ext_id) : self;
}
