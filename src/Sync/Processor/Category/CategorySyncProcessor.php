<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Category;

use ilObjCategory;
use ilObjectServiceSettingsGUI;
use ilRepUtil;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\Category\CategoryDTO;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Origin\Config\CategoryOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\Properties\CategoryOriginProperties;
use SRAG\Plugins\Hub2\Origin\Properties\CourseOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;

/**
 * Class CategorySyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategorySyncProcessor extends ObjectSyncProcessor implements ICategorySyncProcessor {

	use MetadataSyncProcessor;
	use TaxonomySyncProcessor;
	/**
	 * @var CategoryOriginProperties
	 */
	protected $props;
	/**
	 * @var CategoryOriginConfig
	 */
	protected $config;
	/**
	 * @var array
	 */
	protected static $properties = [
		'title',
		'description',
		'owner',
		'orderType',
	];


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications) {
		parent::__construct($origin, $implementation, $transition, $originLog, $originNotifications);
		$this->props = $origin->properties();
		$this->config = $origin->config();
	}


	/**
	 * @return array
	 */
	public static function getProperties() {
		return self::$properties;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleCreate(IDataTransferObject $dto) {
		/** @var CategoryDTO $dto */
		$ilObjCategory = new ilObjCategory();
		$ilObjCategory->setImportId($this->getImportId($dto));
		// Find the refId under which this course should be created
		$parentRefId = $this->determineParentRefId($dto);

		$ilObjCategory->create();
		$ilObjCategory->createReference();
		$ilObjCategory->putInTree($parentRefId);
		$ilObjCategory->setPermissions($parentRefId);
		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($dto->$getter() !== NULL) {
				$ilObjCategory->$setter($dto->$getter());
			}
		}
		if ($this->props->get(CategoryOriginProperties::SHOW_NEWS)) {
			ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::NEWS_VISIBILITY, $dto->isShowNews());
		}
		if ($this->props->get(CategoryOriginProperties::SHOW_INFO_TAB)) {
			ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY, $dto->isShowInfoPage());
		}
		$ilObjCategory->update();

		$ilObjCategory->removeTranslations();
		$ilObjCategory->addTranslation($dto->getTitle(), $dto->getDescription(), self::dic()->language()->getDefaultLanguage(), true);

		return $ilObjCategory;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/** @var CategoryDTO $dto */
		$ilObjCategory = $this->findILIASCategory($ilias_id);
		if ($ilObjCategory === NULL) {
			return NULL;
		}
		// Update some properties if they should be updated depending on the origin config
		foreach (self::getProperties() as $property) {
			if (!$this->props->updateDTOProperty($property)) {
				continue;
			}
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($dto->$getter() !== NULL) {
				$ilObjCategory->$setter($dto->$getter());
			}
		}
		if ($this->props->updateDTOProperty('title')) {
			$ilObjCategory->removeTranslations();
			$ilObjCategory->addTranslation($dto->getTitle(), $dto->getDescription(), self::dic()->language()->getDefaultLanguage(), true);
		}
		if ($this->props->updateDTOProperty('showNews')) {
			ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::NEWS_VISIBILITY, $dto->isShowNews());
		}
		if ($this->props->updateDTOProperty('showInfoPage')) {
			ilObjCategory::_writeContainerSetting($ilObjCategory->getId(), ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY, $dto->isShowInfoPage());
		}
		if ($this->props->get(CategoryOriginProperties::MOVE_CATEGORY)) {
			$this->moveCategory($ilObjCategory, $dto);
		}

		return $ilObjCategory;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$ilObjCategory = $this->findILIASCategory($ilias_id);
		if ($ilObjCategory === NULL) {
			return NULL;
		}
		if ($this->props->get(CategoryOriginProperties::DELETE_MODE) == CategoryOriginProperties::DELETE_MODE_NONE) {
			return $ilObjCategory;
		}
		switch ($this->props->get(CategoryOriginProperties::DELETE_MODE)) {
			case CategoryOriginProperties::DELETE_MODE_MARK:
				$ilObjCategory->setTitle($ilObjCategory->getTitle() . ' ' . $this->props->get(CategoryOriginProperties::DELETE_MODE_MARK_TEXT));
				$ilObjCategory->update();
				break;
			case CourseOriginProperties::DELETE_MODE_DELETE:
				$ilObjCategory->delete();
				break;
		}

		return $ilObjCategory;
	}


	/**
	 * @param CategoryDTO $category
	 *
	 * @return int
	 * @throws HubException
	 */
	protected function determineParentRefId(CategoryDTO $category) {
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

		return 0;
	}


	/**
	 * @param int $iliasId
	 *
	 * @return ilObjCategory|null
	 */
	protected function findILIASCategory($iliasId) {
		if (!ilObjCategory::_exists($iliasId, true)) {
			return NULL;
		}

		return new ilObjCategory($iliasId);
	}


	/**
	 * Move the category to a new parent.
	 *
	 * @param ilObjCategory $ilObjCategory
	 * @param CategoryDTO   $category
	 */
	protected function moveCategory(ilObjCategory $ilObjCategory, CategoryDTO $category) {
		$parentRefId = $this->determineParentRefId($category);
		if (self::dic()->tree()->isDeleted($ilObjCategory->getRefId())) {
			$ilRepUtil = new ilRepUtil();
			$ilRepUtil->restoreObjects($parentRefId, [ $ilObjCategory->getRefId() ]);
		}
		$oldParentRefId = self::dic()->tree()->getParentId($ilObjCategory->getRefId());
		if ($oldParentRefId == $parentRefId) {
			return;
		}
		self::dic()->tree()->moveTree($ilObjCategory->getRefId(), $parentRefId);
		self::dic()->rbacadmin()->adjustMovedObjectPermissions($ilObjCategory->getRefId(), $oldParentRefId);
	}
}
