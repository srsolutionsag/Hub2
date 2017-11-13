<?php

namespace SRAG\Hub2\Sync\Processor\Session;

use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Object\ObjectFactory;
use SRAG\Hub2\Object\Session\SessionDTO;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Origin\OriginRepository;
use SRAG\Hub2\Sync\IObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class SessionSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionSyncProcessor extends ObjectSyncProcessor implements ISessionSyncProcessor {

	/**
	 * @var \SRAG\Hub2\Origin\Properties\SessionOriginProperties
	 */
	private $props;
	/**
	 * @var \SRAG\Hub2\Origin\Config\SessionOriginConfig
	 */
	private $config;
	/**
	 * @var array
	 */
	protected static $properties = array(
		"title",
		"description",
		"location",
		"details",
		"name",
		"phone",
		"email",
		"registrationType",
		"registrationMinUsers",
		"registrationMaxUsers",
		"registrationWaitingList",
		"waitingListAutoFill",
	);


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


	protected function handleCreate(IDataTransferObject $object) {
		/** @var \SRAG\Hub2\Object\Session\SessionDTO $object */
		$ilObjSession = new \ilObjSession();
		$ilObjSession->setImportId($this->getImportId($object));

		// Properties
		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjSession->$setter($object->$getter());
			}
		}

		$ilObjSession->create();
		$ilObjSession->createReference();
		$a_parent_ref = $this->buildParentRefId($object);
		$ilObjSession->putInTree($a_parent_ref);
		$ilObjSession->setPermissions($a_parent_ref);

		$this->handleMembers($object, $ilObjSession);

		$this->setDataForFirstAppointment($object, $ilObjSession, true);
		$ilObjSession->getFirstAppointment()->create();

		return $ilObjSession;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $object, $ilias_id) {
		/** @var \SRAG\Hub2\Object\Session\SessionDTO $object */
		$ilObjSession = $this->findILIASObject($ilias_id);
		if ($ilObjSession === null) {
			return null;
		}

		foreach (self::getProperties() as $property) {
			if (!$this->props->updateDTOProperty($property)) {
				continue;
			}
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjSession->$setter($object->$getter());
			}
		}

		$a_parent_ref = $this->buildParentRefId($object);

		$this->handleMembers($object, $ilObjSession);

		$ilObjSession->update();
		$ilObjSession = $this->setDataForFirstAppointment($object, $ilObjSession);
		$ilObjSession->getFirstAppointment()->update();

		return $ilObjSession;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$ilObjSession = $this->findILIASObject($ilias_id);
		if ($ilObjSession === null) {
			return null;
		}

		return $ilObjSession;
	}


	/**
	 * @param int $ilias_id
	 *
	 * @return \ilObjSession|null
	 */
	protected function findILIASObject($ilias_id) {
		if (!\ilObject2::_exists($ilias_id, true)) {
			return null;
		}

		return new \ilObjSession($ilias_id);
	}


	/**
	 * @param \SRAG\Hub2\Object\Session\SessionDTO $session
	 *
	 * @return int
	 * @throws \SRAG\Hub2\Exception\HubException
	 */
	protected function buildParentRefId(SessionDTO $session) {
		global $DIC;
		$tree = $DIC->repositoryTree();
		if ($session->getParentIdType() == SessionDTO::PARENT_ID_TYPE_REF_ID) {
			if ($tree->isInTree($session->getParentId())) {
				return (int)$session->getParentId();
			}
		}
		if ($session->getParentIdType() == SessionDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
			// The stored parent-ID is an external-ID from a category.
			// We must search the parent ref-ID from a category object synced by a linked origin.
			// --> Get an instance of the linked origin and lookup the category by the given external ID.
			$linkedOriginId = $this->config->getLinkedOriginId();
			if (!$linkedOriginId) {
				throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
			}
			$originRepository = new OriginRepository();
			$origin = array_pop(array_filter($originRepository->courses(), function ($origin) use ($linkedOriginId) {
				/** @var $origin IOrigin */
				return (int)$origin->getId() == $linkedOriginId;
			}));
			if ($origin === null) {
				$msg = "The linked origin syncing courses was not found, please check that the correct origin is linked";
				throw new HubException($msg);
			}
			$objectFactory = new ObjectFactory($origin);
			$course = $objectFactory->course($session->getParentId());
			if (!$course->getILIASId()) {
				throw new HubException("The linked course does not (yet) exist in ILIAS");
			}
			if (!$tree->isInTree($course->getILIASId())) {
				throw new HubException("Could not find the ref-ID of the parent course in the tree: '{$course->getILIASId()}'");
			}

			return (int)$course->getILIASId();
		}

		return 0;
	}


	/**
	 * @param \SRAG\Hub2\Object\Session\SessionDTO $object
	 * @param \ilObjSession                        $ilObjSession
	 * @param bool                                 $force
	 *
	 * @return \ilObjSession
	 */
	protected function setDataForFirstAppointment(SessionDTO $object, \ilObjSession $ilObjSession, $force = false) {
		/**
		 * @var $first \ilSessionAppointment
		 */
		$appointments = $ilObjSession->getAppointments();
		$first = $ilObjSession->getFirstAppointment();
		if ($this->props->updateDTOProperty('start') || $force) {
			$start = new \ilDateTime((int)$object->getStart(), IL_CAL_UNIX);
			$first->setStart($start->get(IL_CAL_DATETIME));
			$first->setStartingTime($start->get(IL_CAL_UNIX));
		}
		if ($this->props->updateDTOProperty('end') || $force) {
			$end = new \ilDateTime((int)$object->getEnd(), IL_CAL_UNIX);
			$first->setEnd($end->get(IL_CAL_DATETIME));
			$first->setEndingTime($end->get(IL_CAL_UNIX));
		}
		if ($this->props->updateDTOProperty('fullDay') || $force) {
			$first->toggleFullTime($object->isFullDay());
		}
		$first->setSessionId($ilObjSession->getId());
		$appointments[0] = $first;
		$ilObjSession->setAppointments($appointments);

		return $ilObjSession;
	}


	/**
	 * @param \SRAG\Hub2\Object\Session\SessionDTO $object
	 * @param \ilObjSession                        $ilObjSession
	 */
	protected function handleMembers(SessionDTO $object, \ilObjSession $ilObjSession) {
		$ilSessionParticipants = $ilObjSession->getMembersObject();
		$current_members = $ilSessionParticipants->getMembers();
		$members = $object->getMembers();
		$member_to_delete = array_diff($current_members, $members);
		if (count($members) > 0) {
			foreach ($members as $member) {
				$ilSessionParticipants->register((int)$member);
			}
		}
		if (count($member_to_delete) > 0) {
			foreach ($member_to_delete as $member) {
				$ilSessionParticipants->unregister((int)$member);
			}
		}
	}
}