<?php

namespace srag\Plugins\Hub2\Sync\Processor\Category;

use ilContainerSortingSettings;
use ilObjCategory;
use ilObjectServiceSettingsGUI;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Sync\Processor\DidacticTemplateSyncProcessor;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Sync\IDataTransferObjectSort;
use srag\Plugins\Hub2\Sync\Processor\ParentResolver\CategoryParentResolver;

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
     * @var CategoryParentResolver
     */
    protected $parent_resolver;
    /**
     * @var \ilLanguage
     */
    private $language;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->language = $DIC->language();
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
        $this->parent_resolver = new CategoryParentResolver(
            new ObjectFactory($this->origin),
            (int) $this->config->getParentRefIdIfNoParentIdFound(),
            $this->config->getExternalParentIdIfNoParentIdFound()
        );
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
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                $dto->isShowNews()
            );
        }
        if ($this->props->get(CategoryProperties::SHOW_INFO_TAB)) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY,
                $dto->isShowInfoPage()
            );
        }
        $ilObjCategory->update();

        $ilObjCategory->removeTranslations();
        $ilObjCategory->addTranslation(
            $dto->getTitle(),
            $dto->getDescription(),
            $this->language->getDefaultLanguage(),
            true
        );
    }

    /**
     * @inheritdoc
     * @param CategoryDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = $this->findILIASCategory($ilias_id);
        if (!$ilObjCategory instanceof \ilObjCategory) {
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
            $ilObjCategory->addTranslation(
                $dto->getTitle(),
                $dto->getDescription(),
                $this->language->getDefaultLanguage(),
                true
            );
        }
        if ($this->props->updateDTOProperty('showNews')) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                $dto->isShowNews()
            );
        }
        if ($this->props->updateDTOProperty('showInfoPage')) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY,
                $dto->isShowInfoPage()
            );
        }
        if ($this->props->updateDTOProperty('orderType')) {
            $sorting_settings = new ilContainerSortingSettings($ilObjCategory->getId());
            $sorting_settings->setSortMode($dto->getOrderType());
            $sorting_settings->setSortDirection($dto->getOrderDirection());
            $sorting_settings->setSortNewItemsPosition($dto->getNewItemsPosition());
            $sorting_settings->setSortNewItemsOrder($dto->getNewItemsOrderType());
            $sorting_settings->update();
        }

        // move/put in tree
        $parent_ref_id = $this->determineParentRefId($dto);
        $ref_id = (int) $ilObjCategory->getRefId();

        if ($this->parent_resolver->isRefIdDeleted($ref_id)) {
            $this->parent_resolver->restoreRefId($ref_id, $parent_ref_id);
        } elseif ($this->props->get(CategoryProperties::MOVE_CATEGORY)) {
            $this->parent_resolver->move($ref_id, $parent_ref_id);
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
        if (!$ilObjCategory instanceof \ilObjCategory) {
            return;
        }
        if ($this->props->get(CategoryProperties::DELETE_MODE) == CategoryProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(CategoryProperties::DELETE_MODE)) {
            case CategoryProperties::DELETE_MODE_MARK:
                $ilObjCategory->setTitle(
                    $ilObjCategory->getTitle() . ' ' . $this->props->get(CategoryProperties::DELETE_MODE_MARK_TEXT)
                );
                $ilObjCategory->update();
                break;
            case CourseProperties::DELETE_MODE_DELETE:
                $ilObjCategory->delete();
                break;
        }
    }

    /**
     * @throws HubException
     */
    protected function determineParentRefId(CategoryDTO $category) : int
    {
        return $this->parent_resolver->resolveParentRefId($category);
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
        $this->parent_resolver->move(
            $this->current_ilias_object->getRefId(),
            $this->determineParentRefId($category)
        );
    }

    /**
     * @inheritdoc
     */
    public function handleSort(array $sort_dtos) : bool
    {
        array_walk($sort_dtos, function (IDataTransferObjectSort $sort_dto) : void {
            $sort_dto->setLevel((int) $sort_dto->getDtoObject()->getPeriod());
        });

        return true;
    }
}
