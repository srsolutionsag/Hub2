<?php

namespace srag\Plugins\Hub2\Sync\Processor\CompetenceManagement;

use ilBasicSkill;
use ilBasicSkillTemplate;
use ilDBConstants;
use ilSkillCategory;
use ilSkillProfile;
use ilSkillTemplateCategory;
use ilSkillTemplateReference;
use ilSkillTree;
use ilSkillTreeNode;
use ilSkillTreeNodeFactory;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\CompetenceManagement\CompetenceManagementDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ProfileLevel\IProfileLevel;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\CompetenceManagement\ICompetenceManagementOrigin;
use srag\Plugins\Hub2\Origin\Config\CompetenceManagement\ICompetenceManagementOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\CompetenceManagement\ICompetenceManagementProperties;
use srag\Plugins\Hub2\Sync\IDataTransferObjectSort;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class CompetenceManagementSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\CompetenceManagement
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CompetenceManagementSyncProcessor extends ObjectSyncProcessor implements ICompetenceManagementSyncProcessor
{
    /**
     * @var ICompetenceManagementProperties
     */
    protected $props;
    /**
     * @var ICompetenceManagementOriginConfig
     */
    protected $config;
    /**
     * @var array
     */
    protected static $properties = [];
    /**
     * @var ilSkillTreeNode|ilSkillProfile|null
     */
    protected $current_ilias_object;
    /**
     * @var ilSkillTree
     */
    protected $skill_tree;
    /**
     * @var \ilDBInterface
     */
    private $db;

    /**
     * @param ICompetenceManagementOrigin $origin
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->db = $DIC->database();
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();

        $this->skill_tree = new ilSkillTree();
    }

    public static function getProperties() : array
    {
        return self::$properties;
    }

    /**
     * @param IDataTransferObjectSort[] $sort_dtos
     * @throws HubException
     */
    public function handleSort(array $sort_dtos) : bool
    {
        $sort_dtos = array_filter(
            $sort_dtos,
            function (IDataTransferObjectSort $sort_dto) : bool {
                /**
                 * @var ICompetenceManagementDTO $dto
                 */
                $dto = $sort_dto->getDtoObject();

                return ($dto->getParentIdType(
                    ) === ICompetenceManagementDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID && !$this->isRootId($dto));
            }
        );

        $dtos = array_reduce(
            $sort_dtos,
            function (array $dtos, IDataTransferObjectSort $sort_dto) : array {
                $dtos[$sort_dto->getDtoObject()->getExtId()] = $sort_dto->getDtoObject();

                return $dtos;
            },
            []
        );

        foreach ($sort_dtos as $sort_dto) {
            /**
             * @var ICompetenceManagementDTO $parent_dto
             */
            $parent_dto = $dtos[$sort_dto->getDtoObject()->getParentId()];

            $level = 1;

            while (!empty($parent_dto) && !$this->isRootId(
                $parent_dto
            ) && $level <= IDataTransferObjectSort::MAX_LEVEL) {
                $sort_dto->setLevel(++$level);

                $parent_dto = $dtos[$parent_dto->getParentId()];
            }
        }

        return true;
    }

    /**
     * @throws HubException
     */
    private function isRootId(ICompetenceManagementDTO $dto) : bool
    {
        //$parent_id = $this->getParentId($dto);

        return (empty($dto->getParentId()));
        //	|| ($parent_id === $this->config->getIdIfNoParentId()
        //	|| $parent_id === $this->skill_tree->getRootId()));
    }

    /**
     * @inheritdoc
     * @param ICompetenceManagementDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        switch ($dto->getType()) {
            case CompetenceManagementDTO::TYPE_PROFILE:
                $this->current_ilias_object = new ilSkillProfile();

                //$this->current_ilias_object->setImportId($this->getImportId($dto));

                $this->current_ilias_object->setTitle($dto->getTitle());

                $this->current_ilias_object->setDescription($dto->getDescription());

                $this->handleProfileLevels($dto);

                $this->handleProfileAssignedUsers($dto);

                $this->current_ilias_object->create();
                break;

            default:
                switch ($dto->getType()) {
                    case ICompetenceManagementDTO::TYPE_COMPETENCE:
                        $this->current_ilias_object = new ilBasicSkill();
                        break;

                    case ICompetenceManagementDTO::TYPE_CATEGORY:
                        $this->current_ilias_object = new ilSkillCategory();
                        break;

                    case ICompetenceManagementDTO::TYPE_COMPETENCE_TEMPLATE:
                        $this->current_ilias_object = new ilBasicSkillTemplate();
                        break;

                    case ICompetenceManagementDTO::TYPE_CATEGORY_TEMPLATE:
                        $this->current_ilias_object = new ilSkillTemplateCategory();
                        break;

                    case ICompetenceManagementDTO::TYPE_REFERENCE:
                        $this->current_ilias_object = new ilSkillTemplateReference();
                        break;

                    default:
                        return;
                }

                $this->current_ilias_object->setImportId($this->getImportId($dto));

                $this->current_ilias_object->setTitle($dto->getTitle());

                $this->current_ilias_object->setStatus($dto->getStatus());

                $this->current_ilias_object->setSelfEvaluation($dto->getSelfEvaluation());

                $parent_id = $this->getParentId($dto);

                $this->current_ilias_object->setOrderNr($this->skill_tree->getMaxOrderNr($parent_id) + 10);

                $this->current_ilias_object->create();

                ilSkillTreeNode::putInTree($this->current_ilias_object, $parent_id, IL_LAST_NODE);

                $this->handleSkillLevels($dto);
                break;
        }
    }

    /**
     * @inheritdoc
     * @param ICompetenceManagementDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        switch ($dto->getType()) {
            case CompetenceManagementDTO::TYPE_PROFILE:
                $this->current_ilias_object = new ilSkillProfile($ilias_id);

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_TITLE)) {
                    $this->current_ilias_object->setTitle($dto->getTitle());
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_DESCRIPTION)) {
                    $this->current_ilias_object->setDescription($dto->getDescription());
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_PROFILE_LEVELS)) {
                    $this->handleProfileLevels($dto);
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_PROFILE_ASSIGNED_USERS)) {
                    $this->handleProfileAssignedUsers($dto);
                }

                $this->current_ilias_object->update();
                break;

            default:
                $this->current_ilias_object = $this->getSkillObject($ilias_id);

                if (!$this->current_ilias_object instanceof \ilSkillTreeNode) {
                    return;
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_TITLE)) {
                    $this->current_ilias_object->setTitle($dto->getTitle());
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_STATUS)) {
                    $this->current_ilias_object->setStatus($dto->getStatus());
                }

                if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_SELF_EVALUATION)) {
                    $this->current_ilias_object->setSelfEvaluation($dto->getSelfEvaluation());
                }

                $this->current_ilias_object->update();

                if ($this->props->get(ICompetenceManagementProperties::MOVE) && ($this->props->updateDTOProperty(
                    ICompetenceManagementProperties::PROP_PARENT_ID
                )
                        || $this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_PARENT_ID_TYPE))) {
                    if ($this->skill_tree->isInTree($ilias_id)) {
                        $this->skill_tree->deleteTree($this->skill_tree->getNodeData($ilias_id));
                    }
                    $parent_id = $this->getParentId($dto);
                    ilSkillTreeNode::putInTree($this->current_ilias_object, $parent_id, IL_LAST_NODE);
                    if ($this->props->updateDTOProperty(ICompetenceManagementProperties::PROP_SKILL_LEVELS)) {
                        $this->handleSkillLevels($dto);
                    }
                }
                break;
        }
    }

    /**
     * @inheritdoc
     * @param ICompetenceManagementDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        switch ($this->props->get(ICompetenceManagementProperties::DELETE_MODE)) {
            case ICompetenceManagementProperties::DELETE_MODE_DELETE:
                switch ($dto->getType()) {
                    case CompetenceManagementDTO::TYPE_PROFILE:
                        $this->current_ilias_object = new ilSkillProfile($ilias_id);

                        $this->current_ilias_object->delete();
                        break;

                    default:
                        $this->current_ilias_object = $this->getSkillObject($ilias_id);

                        if (!$this->current_ilias_object instanceof \ilSkillTreeNode) {
                            return;
                        }

                        $this->current_ilias_object->delete();

                        if ($this->skill_tree->isInTree($ilias_id)) {
                            $this->skill_tree->deleteTree($this->skill_tree->getNodeData($ilias_id));
                        }
                        break;
                }
                break;

            case ICompetenceManagementProperties::DELETE_MODE_NONE:
            default:
                break;
        }
    }

    /**
     * @throws HubException
     */
    protected function handleSkillLevels(ICompetenceManagementDTO $dto)/*: void*/
    {
        foreach ($dto->getSkillLevels() as $skill_level) {
            if (!($this->current_ilias_object instanceof ilBasicSkill) || $dto->getType(
                ) !== ICompetenceManagementDTO::TYPE_COMPETENCE) {
                throw new HubException("SkillLevels are only supported for TYPE_COMPETENCE!");
            }

            $result = $this->db
                ->queryF(
                    "SELECT id FROM skl_level WHERE import_id=%s",
                    [ilDBConstants::T_TEXT],
                    [$this->getSkillLevelImportId($dto->getExtId(), $skill_level->getExtId())]
                );

            if (($row = $result->fetchAssoc()) !== false) {
                ilBasicSkill::writeLevelTitle($row["id"], $skill_level->getTitle());
                ilBasicSkill::writeLevelDescription($row["id"], $skill_level->getDescription());
            } else {
                $this->current_ilias_object->addLevel(
                    $skill_level->getTitle(),
                    $skill_level->getDescription(),
                    $this->getSkillLevelImportId($dto->getExtId(), $skill_level->getExtId())
                );
            }
        }
    }

    /**
     * @return ilSkillTreeNode|null
     */
    protected function getSkillObject(int $obj_id)
    {
        $skill = ilSkillTreeNodeFactory::getInstance($obj_id);

        if (!empty($skill) && $skill instanceof ilSkillTreeNode) {
            return $skill;
        } else {
            return null;
        }
    }

    /**
     * @throws HubException
     */
    protected function getParentId(ICompetenceManagementDTO $dto) : int
    {
        $parent_id = 0;

        switch ($dto->getParentIdType()) {
            case ICompetenceManagementDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID:
                $ext_id = $dto->getParentId();
                if (!empty($ext_id)) {
                    $object_factory = new ObjectFactory($this->origin);

                    $parent_competence_management = $object_factory->competenceManagement($ext_id);

                    $parent_id = $parent_competence_management->getILIASId();

                    if (empty($parent_id) || !$this->getSkillObject($parent_id) instanceof \ilSkillTreeNode) {
                        throw new HubException("External ID {$ext_id} not found!");
                    }
                }
                break;

            case ICompetenceManagementDTO::PARENT_ID_TYPE_REF_ID:
            default:
                $parent_id = (int) $dto->getParentId();
                break;
        }

        if (empty($parent_id)) {
            $parent_id = $this->config->getIdIfNoParentId();
        }
        if (empty($parent_id)) {
            $parent_id = (int) $this->skill_tree->getRootId();
        }

        return $parent_id;
    }

    protected function getSkillLevelImportId(string $skill_id, string $level_id) : string
    {
        return self::IMPORT_PREFIX . $this->origin->getId() . "_" . $skill_id . "_" . $level_id;
    }

    /**
     * @throws HubException
     */
    protected function handleProfileLevels(ICompetenceManagementDTO $dto)/*: void*/
    {
        foreach ($dto->getProfileLevels() as $profile_level) {
            if (!($this->current_ilias_object instanceof ilSkillProfile) || $dto->getType(
                ) !== ICompetenceManagementDTO::TYPE_PROFILE) {
                throw new HubException("ProfileLevels are only supported for TYPE_PROFILE!");
            }

            $skill_id = $this->getSkillId($profile_level);

            $level_id = $this->getLevelId($skill_id, $profile_level);

            $this->current_ilias_object->addSkillLevel($skill_id, 0, $level_id);
        }
    }

    /**
     * @throws HubException
     */
    protected function handleProfileAssignedUsers(ICompetenceManagementDTO $dto)/*: void*/
    {
        foreach ($dto->getProfileAssignedUsers() as $user_id) {
            if (!($this->current_ilias_object instanceof ilSkillProfile) || $dto->getType(
                ) !== ICompetenceManagementDTO::TYPE_PROFILE) {
                throw new HubException("ProfileAssignedUsers are only supported for TYPE_PROFILE!");
            }

            $this->current_ilias_object->addUserToProfile($user_id);
        }
    }

    /**
     * @throws HubException
     */
    protected function getSkillId(IProfileLevel $level) : int
    {
        $skill_id = 0;

        switch ($level->getSkillIdType()) {
            case IProfileLevel::SKILL_ID_TYPE_EXTERNAL_EXT_ID:
                $ext_id = $level->getSkillId();
                if (!empty($ext_id)) {
                    $object_factory = new ObjectFactory($this->origin);

                    $skill_competence_management = $object_factory->competenceManagement($ext_id);

                    $skill_id = $skill_competence_management->getILIASId();

                    if (empty($skill_id) || !$this->getSkillObject($skill_id) instanceof \ilSkillTreeNode) {
                        throw new HubException("Skill ID {$ext_id} not found!");
                    }
                }
                break;

            case IProfileLevel::SKILL_ID_TYPE_ILIAS_ID:
            default:
                $skill_id = (int) $level->getSkillId();
                break;
        }

        return $skill_id;
    }

    /**
     * @throws HubException
     */
    protected function getLevelId(int $skill_id, IProfileLevel $level) : int
    {
        $level_id = 0;

        switch ($level->getLevelIdType()) {
            case IProfileLevel::LEVEL_ID_TYPE_EXTERNAL_EXT_ID:
                $ext_id = $level->getLevelId();
                if (!empty($ext_id)) {
                    $result = $this->db
                        ->queryF(
                            "SELECT id FROM skl_level WHERE import_id=%s",
                            [ilDBConstants::T_TEXT],
                            [$this->getSkillLevelImportId($skill_id, $ext_id)]
                        );

                    if (($row = $result->fetchAssoc()) !== false) {
                        $level_id = (int) $row["id"];
                    } else {
                        throw new HubException("Level ID {$ext_id} not found!");
                    }
                }
                break;

            case IProfileLevel::LEVEL_ID_TYPE_ILIAS_ID:
            default:
                $level_id = (int) $level->getLevelId();
                break;
        }

        return $level_id;
    }
}
