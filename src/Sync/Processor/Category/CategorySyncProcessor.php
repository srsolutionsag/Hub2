<?php

namespace srag\Plugins\Hub2\Sync\Processor\Category;

use ilContainerSortingSettings;
use ilObjCategory;
use ilObjectServiceSettingsGUI;
use ilRepUtil;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Sync\DidacticTemplateSyncProcessor;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;

/**
 * Class CategorySyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategorySyncProcessor extends ObjectSyncProcessor implements ICategorySyncProcessor
{

    use MetadataSyncProcessor;
    use TaxonomySyncProcessor;
    use DidacticTemplateSyncProcessor;

    /**
     * @var CategoryProperties
     */
    protected $props;
    /**
     * @var CategoryOriginConfig
     */
    protected $config;
    /**
     * @var array
     */
    protected static $properties
        = [
            'title',
            'description',
            'owner',
            'orderType',
        ];

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $transition
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param CategoryDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = new ilObjCategory();
        $ilObjCategory->setImportId($this->getImportId($dto));
        // Find the refId under which this course should be created
        $parentRefId = $this->determineParentRefId($dto);

        $ilObjCategory->create();
        $ilObjCategory->createReference();
        $ilObjCategory->putInTree($parentRefId);
        $ilObjCategory->setPermissions($parentRefId);
        $this->writeRBACLog($ilObjCategory->getRefId());
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjCategory->$setter($dto->$getter());
            }
        }
        if ($this->props->get(CategoryProperties::SHOW_NEWS)) {
            ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                $dto->isShowNews());
        }
        if ($this->props->get(CategoryProperties::SHOW_INFO_TAB)) {
            ilObjCategory::_writeContainerSetting($ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY, $dto->isShowInfoPage());
        }
        $ilObjCategory->update();

        $ilObjCategory->removeTranslations();
        $ilObjCategory->addTranslation($dto->getTitle(), $dto->getDescription(),
            self::dic()->language()->getDefaultLanguage(), true);
    }

    /**
     * @inheritdoc
     * @param CategoryDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = $this->findILIASCategory($ilias_id);
        if ($ilObjCategory === null) {
            return;
        }
        // Update some properties if they should be updated depending on the origin config
        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjCategory->$setter($dto->$getter());
            }
        }
        if ($this->props->updateDTOProperty('title')) {
            $ilObjCategory->removeTranslations();
            $ilObjCategory->addTranslation($dto->getTitle(), $dto->getDescription(),
                self::dic()->language()->getDefaultLanguage(), true);
        }
        if ($this->props->updateDTOProperty('showNews')) {
            ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                $dto->isShowNews());
        }
        if ($this->props->updateDTOProperty('showInfoPage')) {
            ilObjCategory::_writeContainerSetting($ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY, $dto->isShowInfoPage());
        }
        if ($this->props->updateDTOProperty('orderType')) {
            $sorting_settings = new ilContainerSortingSettings($ilObjCategory->getId());
            $sorting_settings->setSortMode($dto->getOrderType());
            $sorting_settings->setSortDirection($dto->getOrderDirection());
            $sorting_settings->setSortNewItemsPosition($dto->getNewItemsPosition());
            $sorting_settings->setSortNewItemsOrder($dto->getNewItemsOrderType());
            $sorting_settings->update();
        }
        if (!self::dic()->tree()->isInTree($ilObjCategory->getRefId())) {
            $parentRefId = $this->determineParentRefId($dto);
            if (!self::dic()->tree()->isInTree($ilObjCategory->getRefId())) {
                $parentRefId = $this->config->getParentRefIdIfNoParentIdFound() ?? 1;
            }

            $ilObjCategory->putInTree($parentRefId);
        } else {
            if ($this->props->get(CategoryProperties::MOVE_CATEGORY)) {
                $this->moveCategory($ilObjCategory, $dto);
            }
        }
        $ilObjCategory->update();
    }

    /**
     * @inheritdoc
     * @param CategoryDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = $this->findILIASCategory($ilias_id);
        if ($ilObjCategory === null) {
            return;
        }
        if ($this->props->get(CategoryProperties::DELETE_MODE) == CategoryProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(CategoryProperties::DELETE_MODE)) {
            case CategoryProperties::DELETE_MODE_MARK:
                $ilObjCategory->setTitle($ilObjCategory->getTitle() . ' ' . $this->props->get(CategoryProperties::DELETE_MODE_MARK_TEXT));
                $ilObjCategory->update();
                break;
            case CourseProperties::DELETE_MODE_DELETE:
                $ilObjCategory->delete();
                break;
        }
    }

    /**
     * @param CategoryDTO $category
     * @return int
     * @throws HubException
     */
    protected function determineParentRefId(CategoryDTO $category)
    {
        if ($category->getParentIdType() == CategoryDTO::PARENT_ID_TYPE_REF_ID) {
            if (self::dic()->tree()->isInTree($category->getParentId())) {
                return $category->getParentId();
            }
            // The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
            $parentRefId = $this->config->getParentRefIdIfNoParentIdFound();
            if (!self::dic()->tree()->isInTree($parentRefId)) {
                throw new HubException("Could not find the fallback parent ref-ID in tree: '{$parentRefId}'");
            }

            return $parentRefId;
        }
        if ($category->getParentIdType() == CategoryDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category of the same origin.
            // We must search the category and check if its ILIAS ID does exist.
            $objectFactory = new ObjectFactory($this->origin);
            $parentCategory = $objectFactory->category($category->getParentId());
            if (!$parentCategory->getILIASId()) {
                // The given parent-ID does not yet exist, we check if we find the fallback category
                $fallbackExtId = $this->config->getExternalParentIdIfNoParentIdFound();
                $parentCategory = $objectFactory->category($fallbackExtId);
                if (!$parentCategory->getILIASId()) {
                    throw new HubException("The linked category does not (yet) exist in ILIAS");
                }
            }

            return $parentCategory->getILIASId();
        }

        return (int) $this->config->getParentRefIdIfNoParentIdFound() ?? 1;
    }

    /**
     * @param int $iliasId
     * @return ilObjCategory|null
     */
    protected function findILIASCategory($iliasId)
    {
        if (!ilObjCategory::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjCategory($iliasId);
    }

    protected function moveCategory(ilObjCategory $ilObjCategory, CategoryDTO $category)
    {
        $parent_ref_id = $this->determineParentRefId($category);
        $current_ilias_ref_id = (int) $this->current_ilias_object->getRefId();
        if (self::dic()->tree()->isDeleted($current_ilias_ref_id)) {
            $ilRepUtil = new ilRepUtil();
            $node_data = self::dic()->tree()->getNodeTreeData($current_ilias_ref_id);
            $deleted_ref_id = (int) -$node_data['tree'];

            // if a parent node of the org unit was deleted, we first have to recover this parent
            if ($deleted_ref_id !== $current_ilias_ref_id) {
                $node_data_deleted_parent = self::dic()->tree()->getNodeTreeData($deleted_ref_id);
                $ilRepUtil->restoreObjects($node_data_deleted_parent['parent'], [$deleted_ref_id]);
                // then move the actual orgunit
                self::dic()->tree()->moveTree($current_ilias_ref_id, $parent_ref_id);
                // then delete the parent again
                self::dic()->tree()->moveToTrash($deleted_ref_id);
            } else {
                // recover and move the actual org unit
                $ilRepUtil->restoreObjects($parent_ref_id, [$current_ilias_ref_id]);
            }
        }
        $old_parent_id = (int) self::dic()->tree()->getParentId($current_ilias_ref_id);
        if ($old_parent_id === $parent_ref_id) {
            return;
        }
        self::dic()->tree()->moveTree($current_ilias_ref_id, $parent_ref_id);
        self::dic()->rbacadmin()->adjustMovedObjectPermissions($current_ilias_ref_id, $old_parent_id);
    }

}
