<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\SessionMembership;

use ilObject2;
use ilObjSession;
use ilObjUser;
use ilSessionParticipants;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use SRAG\Plugins\Hub2\Origin\Config\SessionMembershipOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\OriginRepository;
use SRAG\Plugins\Hub2\Origin\Properties\SessionMembershipOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class SessionMembershipSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipSyncProcessor extends ObjectSyncProcessor implements ISessionMembershipSyncProcessor {

	/**
	 * @var SessionMembershipOriginProperties
	 */
	private $props;
	/**
	 * @var SessionMembershipOriginConfig
	 */
	private $config;
	/**
	 * @var array
	 */
	protected static $properties = array();


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
		/** @var SessionMembershipDTO $dto */

		$session_ref_id = $this->buildParentRefId($dto);
		$ilObjSession = $this->findILIASObject($session_ref_id);
		$this->handleMembership($ilObjSession, $dto);

		return new FakeIliasMembershipObject($session_ref_id, $dto->getUserId());
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/** @var SessionMembershipDTO $dto */
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

		$ilObjSession = $this->findILIASObject($obj->getContainerIdIlias());
		$this->handleMembership($ilObjSession, $dto);

		$obj->setUserIdIlias($dto->getUserId());
		$obj->setContainerIdIlias($ilObjSession->getRefId());
		$obj->initId();

		return $obj;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);
		$ilObjSession = $this->findILIASObject($obj->getContainerIdIlias());
		$this->removeMembership($ilObjSession, $obj->getUserIdIlias());

		return $obj;
	}


	/**
	 * @param int $ilias_id
	 *
	 * @return ilObjSession
	 * @throws HubException
	 */
	protected function findILIASObject($ilias_id) {
		if (!ilObject2::_exists($ilias_id, true)) {
			throw new HubException("Session not found with ref_id {$ilias_id}");
		}

		return new ilObjSession($ilias_id, true);
	}


	/**
	 * @param SessionMembershipDTO $dto
	 *
	 * @return int
	 * @throws HubException
	 */
	protected function buildParentRefId(SessionMembershipDTO $dto) {
		if ($dto->getSessionIdType() == SessionMembershipDTO::PARENT_ID_TYPE_REF_ID) {
			if ($this->tree()->isInTree($dto->getSessionId())) {
				return (int)$dto->getSessionId();
			}
			throw new HubException("Could not find the ref-ID of the parent session in the tree: '{$dto->getGroupId()}'");
		}
		if ($dto->getSessionIdType() == SessionMembershipDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
			// The stored parent-ID is an external-ID from a category.
			// We must search the parent ref-ID from a category object synced by a linked origin.
			// --> Get an instance of the linked origin and lookup the category by the given external ID.
			$linkedOriginId = $this->config->getLinkedOriginId();
			if (!$linkedOriginId) {
				throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
			}
			$originRepository = new OriginRepository();
			$origin = array_pop(array_filter($originRepository->sessions(), function ($origin) use ($linkedOriginId) {
				/** @var IOrigin $origin */
				return (int)$origin->getId() == $linkedOriginId;
			}));
			if ($origin === NULL) {
				$msg = "The linked origin syncing sessions was not found, please check that the correct origin is linked";
				throw new HubException($msg);
			}
			$objectFactory = new ObjectFactory($origin);
			$session = $objectFactory->session($dto->getSessionId());
			if (!$session->getILIASId()) {
				throw new HubException("The linked session does not (yet) exist in ILIAS");
			}
			if (!$this->tree()->isInTree($session->getILIASId())) {
				throw new HubException("Could not find the ref-ID of the parent session in the tree: '{$session->getILIASId()}'");
			}

			return (int)$session->getILIASId();
		}

		return 0;
	}


	/**
	 * @param ilObjSession         $ilObjSession
	 * @param SessionMembershipDTO $dto
	 *
	 * @throws HubException
	 */
	protected function handleMembership(ilObjSession $ilObjSession, SessionMembershipDTO $dto) {
		/**
		 * @var ilSessionParticipants $ilSessionParticipants
		 */
		$ilSessionParticipants = $ilObjSession->getMembersObject();

		$user_id = $dto->getUserId();
		if (!ilObjUser::_exists($user_id)) {
			throw new HubException("user with id {$user_id} does not exist");
		}

		$ilSessionParticipants->register((int)$user_id);
	}


	/**
	 * @param ilObjSession $ilObjSession
	 * @param int       $user_id
	 *
	 * @throws HubException
	 */
	protected function removeMembership(ilObjSession $ilObjSession, $user_id) {
		/**
		 * @var ilSessionParticipants $ilSessionParticipants
		 */
		$ilSessionParticipants = $ilObjSession->getMembersObject();

		if (!ilObjUser::_exists($user_id)) {
			throw new HubException("user with id {$user_id} does not exist");
		}

		$ilSessionParticipants->unregister((int)$user_id);
	}
}
