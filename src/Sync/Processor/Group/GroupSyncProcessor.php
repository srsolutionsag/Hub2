<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Group;

use ilDate;
use ilObjGroup;
use ilRepUtil;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\Group\GroupDTO;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\OriginRepository;
use SRAG\Plugins\Hub2\Origin\Properties\GroupOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;

/**
 * Class GroupSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessor extends ObjectSyncProcessor implements IGroupSyncProcessor {

	use TaxonomySyncProcessor;
	use MetadataSyncProcessor;
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
		"title",
		"description",
		"information",
		"groupType",
		"owner",
		"viewMode",
		"registrationStart",
		"registrationEnd",
		"password",
		"registerMode",
		"minMembers",
		"maxMembers",
		"waitingListAutoFill",
		"cancellationEnd",
		"start",
		"end",
		"latitude",
		"longitude",
		"locationzoom",
		"registrationAccessCode",
		"enableGroupMap",
	];
	/**
	 * @var array
	 */
	protected static $ildate_fields = array(
		"cancellationEnd",
		"start",
		"end",
		"registrationStart",
		"registrationEnd",
	);


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 * @param IGroupActivities        $groupActivities
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
	protected function handleCreate(IDataTransferObject $dto) {
		/** @var GroupDTO $dto */
		$ilObjGroup = new ilObjGroup();
		$ilObjGroup->setImportId($this->getImportId($dto));
		// Find the refId under which this group should be created
		$parentRefId = $this->determineParentRefId($dto);
		// Pass properties from DTO to ilObjUser

		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($dto->$getter() !== NULL) {
				$var = $dto->$getter();
				if (in_array($property, self::$ildate_fields)) {
					$var = new ilDate($var, IL_CAL_UNIX);
				}

				$ilObjGroup->$setter($var);
			}
		}

		if ($dto->getRegUnlimited() !== NULL) {
			$ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
		}

		if ($dto->getRegMembershipLimitation() !== NULL) {
			$ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());
		}

		if ($dto->getWaitingList() !== NULL) {
			$ilObjGroup->enableWaitingList($dto->getWaitingList());
		}

		if ($dto->getRegAccessCodeEnabled() !== NULL) {
			$ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());
		}

		$ilObjGroup->create();
		$ilObjGroup->createReference();
		$ilObjGroup->putInTree($parentRefId);
		$ilObjGroup->setPermissions($parentRefId);

		return $ilObjGroup;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/** @var GroupDTO $dto */
		$ilObjGroup = $this->findILIASGroup($ilias_id);
		if ($ilObjGroup === NULL) {
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
				$var = $dto->$getter();
				if (in_array($property, self::$ildate_fields)) {
					$var = new ilDate($var, IL_CAL_UNIX);
				}

				$ilObjGroup->$setter($var);
			}
		}
		if ($this->props->updateDTOProperty("registrationMode")
			&& $dto->getRegisterMode() !== NULL) {
			$ilObjGroup->setRegisterMode($dto->getRegisterMode());
		}

		if ($this->props->updateDTOProperty("regUnlimited")
			&& $dto->getRegUnlimited() !== NULL) {
			$ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
		}

		if ($this->props->updateDTOProperty("regMembershipLimitation")
			&& $dto->getRegMembershipLimitation() !== NULL) {
			$ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());
		}

		if ($this->props->updateDTOProperty("waitingList") && $dto->getWaitingList() !== NULL) {
			$ilObjGroup->enableWaitingList($dto->getWaitingList());
		}

		if ($this->props->updateDTOProperty("regAccessCodeEnabled")
			&& $dto->getRegAccessCodeEnabled() !== NULL) {
			$ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());
		}

		if ($this->props->updateDTOProperty("regUnlimited")
			&& $dto->getRegUnlimited() !== NULL) {
			$ilObjGroup->enableUnlimitedRegistration($dto->getRegisterMode());
		}

		if ($this->props->get(GroupOriginProperties::MOVE_GROUP)) {
			$this->moveGroup($ilObjGroup, $dto);
		}
		$ilObjGroup->update();

		return $ilObjGroup;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$ilObjGroup = $this->findILIASGroup($ilias_id);
		if ($ilObjGroup === NULL) {
			return NULL;
		}
		if ($this->props->get(GroupOriginProperties::DELETE_MODE) == GroupOriginProperties::DELETE_MODE_NONE) {
			return $ilObjGroup;
		}
		switch ($this->props->get(GroupOriginProperties::DELETE_MODE)) {
			case GroupOriginProperties::DELETE_MODE_CLOSED:
				$ilObjGroup->setGroupStatus(2);
				$ilObjGroup->update();
				break;
			case GroupOriginProperties::DELETE_MODE_DELETE:
				$ilObjGroup->delete();
				break;
			case GroupOriginProperties::DELETE_MODE_MOVE_TO_TRASH:
				$this->tree()->moveToTrash($ilObjGroup->getRefId(), true);
				break;
			case GroupOriginProperties::DELETE_MODE_DELETE_OR_CLOSE:
				if ($this->groupActivities->hasActivities($ilObjGroup)) {
					$ilObjGroup->setGroupStatus(2);
					$ilObjGroup->update();
				} else {
					$this->tree()->moveToTrash($ilObjGroup->getRefId(), true);
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
		if ($group->getParentIdType() == GroupDTO::PARENT_ID_TYPE_REF_ID) {
			if ($this->tree()->isInTree($group->getParentId())) {
				return $group->getParentId();
			}
			// The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
			$parentRefId = $this->config->getParentRefIdIfNoParentIdFound();
			if (!$this->tree()->isInTree($parentRefId)) {
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
			$possible_parents = array_merge($originRepository->categories(), $originRepository->courses());
			$origin = array_pop(array_filter($possible_parents, function ($origin) use ($linkedOriginId) {
				/** @var IOrigin $origin */
				return $origin->getId() == $linkedOriginId;
			}));
			if ($origin === NULL) {
				$msg = "The linked origin syncing categories or courses was not found,
				please check that the correct origin is linked";
				throw new HubException($msg);
			}

			$objectFactory = new ObjectFactory($origin);

			if ($origin instanceof ARCourseOrigin) {
				$parent = $objectFactory->course($group->getParentId());
			} else {
				$parent = $objectFactory->category($group->getParentId());
			}

			if (!$parent->getILIASId()) {
				throw new HubException("The linked category or course does not (yet) exist in ILIAS");
			}
			if (!$this->tree()->isInTree($parent->getILIASId())) {
				throw new HubException("Could not find the ref-ID of the parent category or course in the tree: '{$parent->getILIASId()}'");
			}

			return $parent->getILIASId();
		}

		return 0;
	}


	/**
	 * @param int $iliasId
	 *
	 * @return ilObjGroup|null
	 */
	protected function findILIASGroup($iliasId) {
		if (!ilObjGroup::_exists($iliasId, true)) {
			return NULL;
		}

		return new ilObjGroup($iliasId);
	}


	/**
	 * Move the group to a new parent.
	 * Note: May also create the dependence categories
	 *
	 * @param ilObjGroup $ilObjGroup
	 * @param GroupDTO   $group
	 */
	protected function moveGroup(ilObjGroup $ilObjGroup, GroupDTO $group) {
		$parentRefId = $this->determineParentRefId($group);
		if ($this->tree()->isDeleted($ilObjGroup->getRefId())) {
			$ilRepUtil = new ilRepUtil();
			$ilRepUtil->restoreObjects($parentRefId, [ $ilObjGroup->getRefId() ]);
		}
		$oldParentRefId = $this->tree()->getParentId($ilObjGroup->getRefId());
		if ($oldParentRefId == $parentRefId) {
			return;
		}
		$this->tree()->moveTree($ilObjGroup->getRefId(), $parentRefId);
		$this->rbac()->admin()->adjustMovedObjectPermissions($ilObjGroup->getRefId(), $oldParentRefId);
	}
}
