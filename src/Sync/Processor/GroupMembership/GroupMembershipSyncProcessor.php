<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\GroupMembership;

use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\Properties\GroupOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use SRAG\Plugins\Hub2\Sync\Processor\FakeIliasObject;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class GroupMembershipSyncProcessor
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Sync\Processor
 */
class GroupMembershipSyncProcessor extends ObjectSyncProcessor implements IGroupMembershipSyncProcessor {

	const SPLIT = "|||";
	/**
	 * @var GroupOriginProperties
	 */
	protected $props;
	/**
	 * @var GroupOriginConfig
	 */
	protected $config;


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
	 * @inheritdoc
	 */
	protected function handleCreate(IDataTransferObject $dto) {
		/**
		 * @var $dto \SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO
		 */
		$ilias_group_ref_id = $dto->getIliasGroupRefId();
		$group = $this->findILIASGroup($ilias_group_ref_id);
		if (!$group) {
			return null;
		}
		$user_id = $dto->getUserId();
		$group->getMembersObject()->add($user_id, $this->mapRole($dto));

		return new FakeIliasMembershipObject($ilias_group_ref_id, $user_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/**
		 * @var $dto \SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO
		 */
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

		$ilias_group_ref_id = $dto->getIliasGroupRefId();
		$user_id = $dto->getUserId();
		if (!$this->props->updateDTOProperty('role')) {
			return new FakeIliasMembershipObject($ilias_group_ref_id, $user_id);
		}

		$group = $this->findILIASGroup($ilias_group_ref_id);
		if (!$group) {
			return null;
		}

		$group->getMembersObject()
		      ->updateRoleAssignments($user_id, [ $this->getILIASRole($dto, $group) ]);

		$obj->setUserIdIlias($dto->getUserId());
		$obj->setContainerIdIlias($group->getRefId());
		$obj->initId();

		return $obj;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

		$group = $this->findILIASGroup($obj->getContainerIdIlias());
		$group->getMembersObject()->delete($obj->getUserIdIlias());

		return $obj;
	}


	/**
	 * @param int $iliasId
	 *
	 * @return \ilObjGroup|null
	 */
	protected function findILIASGroup($iliasId) {
		if (!\ilObject2::_exists($iliasId, true)) {
			return null;
		}

		return new \ilObjGroup($iliasId);
	}


	/**
	 * @param $object GroupMembershipDTO
	 *
	 * @return int
	 */
	protected function mapRole(GroupMembershipDTO $object) {
		switch ($object->getRole()) {
			case GroupMembershipDTO::ROLE_ADMIN:
				return IL_CRS_ADMIN;
			case GroupMembershipDTO::ROLE_TUTOR:
				return IL_CRS_TUTOR;
			case GroupMembershipDTO::ROLE_MEMBER:
				return IL_CRS_MEMBER;
			default:
				return IL_CRS_MEMBER;
		}
	}


	/**
	 * @param \SRAG\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO $object
	 * @param \ilObjGroup                                                  $group
	 *
	 * @return int
	 */
	protected function getILIASRole(GroupMembershipDTO $object, \ilObjGroup $group) {
		switch ($object->getRole()) {
			case GroupMembershipDTO::ROLE_ADMIN:
				return $group->getDefaultAdminRole();
			case GroupMembershipDTO::ROLE_TUTOR:
				return $group->getDefaultTutorRole();
			case GroupMembershipDTO::ROLE_MEMBER:
				return $group->getDefaultMemberRole();
			default:
				return $group->getDefaultMemberRole();
		}
	}
}