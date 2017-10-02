<?php
namespace SRAG\Hub2\Sync\Processor\Group;

use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\Group\GroupDTO;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Object\ObjectFactory;
use SRAG\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Origin\OriginRepository;
use SRAG\Hub2\Origin\Properties\GroupOriginProperties;
use SRAG\Hub2\Sync\IObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class GroupSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessor extends ObjectSyncProcessor implements IGroupSyncProcessor {

	/**
	 * @var GroupOriginProperties
	 */
	protected $props;
	/**
	 * @var GroupOriginConfig
	 */
	protected $config;
	/**
	 * @var IGroupActivities
	 */
	protected $groupActivities;
	/**
	 * @var array
	 */
	protected static $properties = [
		'title',
		'description',
		'importantInformation',
		'contactResponsibility',
		'contactEmail',
		'owner',
		'subscriptionLimitationType',
		'viewMode',
		'contactName',
		'syllabus',
		'contactConsultation',
		'contactPhone',
		'activationType',
	];


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 * @param IGroupActivities       $groupActivities
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications, IGroupActivities $groupActivities) {
		parent::__construct($origin, $implementation, $transition, $originLog, $originNotifications);
		$this->props = $origin->properties();
		$this->config = $origin->config();
		$this->groupActivities = $groupActivities;
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
	protected function handleCreate(IDataTransferObject $object) {
		/** @var GroupDTO $object */
		$ilObjGroup = new \ilObjGroup();
		$ilObjGroup->setImportId($this->getImportId($object));
		// Find the refId under which this group should be created
		$parentRefId = $this->determineParentRefId($object);
		// Check if we should create some dependence categories
		$parentRefId = $this->buildDependenceCategories($object, $parentRefId);
		$ilObjGroup->create();
		$ilObjGroup->createReference();
		$ilObjGroup->putInTree($parentRefId);
		$ilObjGroup->setPermissions($parentRefId);
		// Pass properties from DTO to ilObjUser
		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjGroup->$setter($object->$getter());
			}
		}
		if ($this->props->get(GroupOriginProperties::SET_ONLINE)) {
			$ilObjGroup->setOfflineStatus(false);
			$ilObjGroup->setActivationType(IL_CRS_ACTIVATION_UNLIMITED);
		}
		if ($this->props->get(GroupOriginProperties::CREATE_ICON)) {
			// TODO
			//			$this->updateIcon($this->ilias_object);
			//			$this->ilias_object->update();
		}
		if ($this->props->get(GroupOriginProperties::SEND_CREATE_NOTIFICATION)) {
			// TODO
		}
		$ilObjGroup->update();

		return $ilObjGroup;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $object, $ilias_id) {
		/** @var GroupDTO $object */
		$ilObjGroup = $this->findILIASGroup($ilias_id);
		if ($ilObjGroup === null) {
			return null;
		}
		// Update some properties if they should be updated depending on the origin config
		foreach (self::getProperties() as $property) {
			if (!$this->props->updateDTOProperty($property)) {
				continue;
			}
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjGroup->$setter($object->$getter());
			}
		}
		if ($this->props->get(GroupOriginProperties::SET_ONLINE_AGAIN)) {
			$ilObjGroup->setOfflineStatus(false);
			$ilObjGroup->setActivationType(IL_CRS_ACTIVATION_UNLIMITED);
		}
		if ($this->props->get(GroupOriginProperties::MOVE_GROUP)) {
			$this->moveGroup($ilObjGroup, $object);
		}
		$ilObjGroup->update();

		return $ilObjGroup;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$ilObjGroup = $this->findILIASGroup($ilias_id);
		if ($ilObjGroup === null) {
			return null;
		}
		if ($this->props->get(GroupOriginProperties::DELETE_MODE)
		    == GroupOriginProperties::DELETE_MODE_NONE) {
			return $ilObjGroup;
		}
		global $DIC;
		$tree = $DIC->repositoryTree();
		switch ($this->props->get(GroupOriginProperties::DELETE_MODE)) {
			case GroupOriginProperties::DELETE_MODE_OFFLINE:
				$ilObjGroup->setOfflineStatus(true);
				$ilObjGroup->update();
				break;
			case GroupOriginProperties::DELETE_MODE_DELETE:
				$ilObjGroup->delete();
				break;
			case GroupOriginProperties::DELETE_MODE_MOVE_TO_TRASH:
				$tree->moveToTrash($ilObjGroup->getRefId(), true);
				break;
			case GroupOriginProperties::DELETE_MODE_DELETE_OR_OFFLINE:
				if ($this->groupActivities->hasActivities($ilObjGroup)) {
					$ilObjGroup->setOfflineStatus(true);
					$ilObjGroup->update();
				} else {
					$tree->moveToTrash($ilObjGroup->getRefId(), true);
				}
				break;
		}

		return $ilObjGroup;
	}


	/**
	 * @param GroupDTO $group
	 *
	 * @return int
	 * @throws HubException
	 */
	protected function determineParentRefId(GroupDTO $group) {
		global $DIC;
		$tree = $DIC->repositoryTree();
		if ($group->getParentIdType() == GroupDTO::PARENT_ID_TYPE_REF_ID) {
			if ($tree->isInTree($group->getParentId())) {
				return $group->getParentId();
			}
			// The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
			$parentRefId = $this->config->getParentRefIdIfNoParentIdFound();
			if (!$tree->isInTree($parentRefId)) {
				throw new HubException("Could not find the fallback parent ref-ID in tree: '{$parentRefId}'");
			}

			return $parentRefId;
		}
		if ($group->getParentIdType() == GroupDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
			// The stored parent-ID is an external-ID from a category.
			// We must search the parent ref-ID from a category object synced by a linked origin.
			// --> Get an instance of the linked origin and lookup the category by the given external ID.
			$linkedOriginId = $this->config->getLinkedOriginId();
			if (!$linkedOriginId) {
				throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
			}
			$originRepository = new OriginRepository();
			$origin = array_pop(array_filter($originRepository->categories(), function ($origin) use ($linkedOriginId) {
				/** @var $origin IOrigin */
				return $origin->getId() == $linkedOriginId;
			}));
			if ($origin === null) {
				$msg = "The linked origin syncing categories was not found, please check that the correct origin is linked";
				throw new HubException($msg);
			}
			$objectFactory = new ObjectFactory($origin);
			$category = $objectFactory->category($group->getParentId());
			if (!$category->getILIASId()) {
				throw new HubException("The linked category does not (yet) exist in ILIAS");
			}
			if (!$tree->isInTree($category->getILIASId())) {
				throw new HubException("Could not find the ref-ID of the parent category in the tree: '{$category->getILIASId()}'");
			}

			return $category->getILIASId();
		}

		return 0;
	}


	/**
	 * @param GroupDTO $object
	 * @param           $parentRefId
	 *
	 * @return int
	 */
	protected function buildDependenceCategories(GroupDTO $object, $parentRefId) {
		if ($object->getFirstDependenceCategory() !== null) {
			$parentRefId = $this->buildDependenceCategory($object->getFirstDependenceCategory(), $parentRefId, 1);
		}
		if ($object->getFirstDependenceCategory() !== null
		    && $object->getSecondDependenceCategory() !== null) {
			$parentRefId = $this->buildDependenceCategory($object->getSecondDependenceCategory(), $parentRefId, 2);
		}
		if ($object->getFirstDependenceCategory() !== null
		    && $object->getSecondDependenceCategory() !== null
		    && $object->getThirdDependenceCategory() !== null) {
			$parentRefId = $this->buildDependenceCategory($object->getThirdDependenceCategory(), $parentRefId, 3);
		}

		return $parentRefId;
	}


	/**
	 * Creates a category under the given $parentRefId if it does not yet exist.
	 * Note that this implementation is copied over from the old hub plugin: We check if we
	 * find a category having the same title. If not, a new category is created.
	 * It would be better to identify the category over the unique import ID and then update
	 * the title of the category, if necessary.
	 *
	 * @param string $title
	 * @param int    $parentRefId
	 * @param int    $level
	 *
	 * @return int
	 */
	protected function buildDependenceCategory($title, $parentRefId, $level) {
		global $DIC;
		static $cache = [];
		// We use a cache for created dependence categories to save some SQL queries
		$cacheKey = md5($title . $parentRefId . $level);
		if (isset($cache[$cacheKey])) {
			return $cache[$cacheKey];
		}
		$categories = $DIC->repositoryTree()->getChildsByType($parentRefId, 'cat');
		$matches = array_filter($categories, function ($category) use ($title) {
			return $category['title'] == $title;
		});
		if (count($matches) > 0) {
			$category = array_pop($matches);
			$cache[$cacheKey] = $category['ref_id'];

			return $category['ref_id'];
		}
		// No category with the given title found, create it!
		$importId = implode('_', [
			'srhub',
			$this->origin->getId(),
			$parentRefId,
			'depth',
			$level,
		]);
		$ilObjCategory = new \ilObjCategory();
		$ilObjCategory->setTitle($title);
		$ilObjCategory->setImportId($importId);
		$ilObjCategory->create();
		$ilObjCategory->createReference();
		$ilObjCategory->putInTree($parentRefId);
		$ilObjCategory->setPermissions($parentRefId);
		$cache[$cacheKey] = $ilObjCategory->getRefId();

		return $ilObjCategory->getRefId();
	}


	/**
	 * @param int $iliasId
	 *
	 * @return \ilObjGroup|null
	 */
	protected function findILIASGroup($iliasId) {
		if (!\ilObjGroup::_exists($iliasId, true)) {
			return null;
		}

		return new \ilObjGroup($iliasId);
	}


	/**
	 * Move the group to a new parent.
	 * Note: May also create the dependence categories
	 *
	 * @param           $ilObjGroup
	 * @param GroupDTO $group
	 */
	protected function moveGroup(\ilObjGroup $ilObjGroup, GroupDTO $group) {
		global $DIC;
		$parentRefId = $this->determineParentRefId($group);
		$parentRefId = $this->buildDependenceCategories($group, $parentRefId);
		if ($DIC->repositoryTree()->isDeleted($ilObjGroup->getRefId())) {
			$ilRepUtil = new \ilRepUtil();
			$ilRepUtil->restoreObjects($parentRefId, [ $ilObjGroup->getRefId() ]);
		}
		$oldParentRefId = $DIC->repositoryTree()->getParentId($ilObjGroup->getRefId());
		if ($oldParentRefId == $parentRefId) {
			return;
		}
		$DIC->repositoryTree()->moveTree($ilObjGroup->getRefId(), $parentRefId);
		$DIC->rbac()
		    ->admin()
		    ->adjustMovedObjectPermissions($ilObjGroup->getRefId(), $oldParentRefId);
		//			hubLog::getInstance()->write($str);
		//			hubOriginNotification::addMessage($this->getSrHubOriginId(), $str, 'Moved:');
	}
}