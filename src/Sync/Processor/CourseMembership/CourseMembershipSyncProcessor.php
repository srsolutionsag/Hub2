<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\CourseMembership;

use ilObjCourse;
use ilObject2;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\ObjectFactory;
use SRAG\Plugins\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\OriginRepository;
use SRAG\Plugins\Hub2\Origin\Properties\CourseMembershipOriginProperties;
use SRAG\Plugins\Hub2\Origin\Properties\CourseOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class CourseMembershipSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\CourseMembership
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipSyncProcessor extends ObjectSyncProcessor implements ICourseMembershipSyncProcessor {

	/**
	 * @var CourseOriginProperties
	 */
	protected $props;
	/**
	 * @var CourseOriginConfig
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
		 * @var CourseMembershipDTO $dto
		 */
		$ilias_course_ref_id = $this->determineCourseRefId($dto);
		$dto->getCourseId();
		$course = $this->findILIASCourse($ilias_course_ref_id);
		if (!$course) {
			return NULL;
		}
		$user_id = $dto->getUserId();
		$course->getMembersObject()->add($user_id, $this->mapRole($dto));

		return new FakeIliasMembershipObject($ilias_course_ref_id, $user_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/**
		 * @var CourseMembershipDTO $dto
		 */
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);
		$ilias_course_ref_id = $obj->getContainerIdIlias();
		$user_id = $dto->getUserId();
		if (!$this->props->updateDTOProperty('role')) {
			return new FakeIliasMembershipObject($ilias_course_ref_id, $user_id);
		}

		$course = $this->findILIASCourse($ilias_course_ref_id);
		if (!$course) {
			return NULL;
		}

		$course->getMembersObject()->updateRoleAssignments($user_id, [ $this->getILIASRole($dto, $course) ]);

		$obj->setUserIdIlias($dto->getUserId());
		$obj->setContainerIdIlias($course->getRefId());
		$obj->initId();

		return $obj;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

		if ($this->props->get(CourseMembershipOriginProperties::DELETE_MODE) == CourseMembershipOriginProperties::DELETE_MODE_NONE) {
			return $obj;
		}

		$course = $this->findILIASCourse($obj->getContainerIdIlias());
		$course->getMembersObject()->delete($obj->getUserIdIlias());

		return $obj;
	}


	/**
	 * @param int $iliasId
	 *
	 * @return ilObjCourse|null
	 */
	protected function findILIASCourse($iliasId) {
		if (!ilObject2::_exists($iliasId, true)) {
			return NULL;
		}

		return new ilObjCourse($iliasId);
	}


	/**
	 * @param CourseMembershipDTO $object
	 *
	 * @return int
	 */
	protected function mapRole(CourseMembershipDTO $object) {
		switch ($object->getRole()) {
			case CourseMembershipDTO::ROLE_ADMIN:
				return IL_CRS_ADMIN;
			case CourseMembershipDTO::ROLE_TUTOR:
				return IL_CRS_TUTOR;
			case CourseMembershipDTO::ROLE_MEMBER:
				return IL_CRS_MEMBER;
			default:
				return IL_CRS_MEMBER;
		}
	}


	/**
	 * @param CourseMembershipDTO $object
	 * @param ilObjCourse         $course
	 *
	 * @return int
	 */
	protected function getILIASRole(CourseMembershipDTO $object, ilObjCourse $course) {
		switch ($object->getRole()) {
			case CourseMembershipDTO::ROLE_ADMIN:
				return $course->getDefaultAdminRole();
			case CourseMembershipDTO::ROLE_TUTOR:
				return $course->getDefaultTutorRole();
			case CourseMembershipDTO::ROLE_MEMBER:
				return $course->getDefaultMemberRole();
			default:
				return $course->getDefaultMemberRole();
		}
	}


	/**
	 * @param CourseMembershipDTO $course_membership
	 *
	 * @return int
	 * @throws HubException
	 */
	protected function determineCourseRefId(CourseMembershipDTO $course_membership) {

		if ($course_membership->getCourseIdType() == CourseMembershipDTO::COURSE_ID_TYPE_REF_ID) {
			return $course_membership->getCourseId();
		}
		if ($course_membership->getCourseIdType() == CourseMembershipDTO::COURSE_ID_TYPE_EXTERNAL_EXT_ID) {
			// The stored course-ID is an external-ID from a course.
			// We must search the course ref-ID from a category object synced by
			// a linked origin. --> Get an instance of the linked origin and lookup the
			// category by the given external ID.
			$linkedOriginId = $this->config->getLinkedOriginId();
			if (!$linkedOriginId) {
				throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
			}
			$originRepository = new OriginRepository();
			$origin = array_pop(array_filter($originRepository->courses(), function ($origin) use ($linkedOriginId) {
				/** @var IOrigin $origin */
				return $origin->getId() == $linkedOriginId;
			}));
			if ($origin === NULL) {
				$msg = "The linked origin syncing courses was not found, please check that the correct origin is linked";
				throw new HubException($msg);
			}
			$objectFactory = new ObjectFactory($origin);
			$course = $objectFactory->course($course_membership->getCourseId());
			if (!$course->getILIASId()) {
				throw new HubException("The linked course does not (yet) exist in ILIAS");
			}

			return $course->getILIASId();
		}

		return 0;
	}
}
