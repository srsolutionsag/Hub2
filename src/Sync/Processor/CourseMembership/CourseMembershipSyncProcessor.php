<?php

namespace srag\Plugins\Hub2\Sync\Processor\CourseMembership;

use ilObjCourse;
use ilObject2;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class CourseMembershipSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\CourseMembership
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipSyncProcessor extends ObjectSyncProcessor implements ICourseMembershipSyncProcessor
{
    /**
     * @var CourseProperties
     */
    protected $props;
    /**
     * @var CourseOriginConfig
     */
    protected $config;

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
     * @inheritdoc
     * @param CourseMembershipDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $ilias_course_ref_id = $this->determineCourseRefId($dto);

        $course = $this->findILIASCourse($ilias_course_ref_id);
        if (!$course instanceof \ilObjCourse) {
            return;
        }

        $user_id = $dto->getUserId();
        $membership_obj = new \ilCourseParticipants(ilObject2::_lookupObjectId($ilias_course_ref_id));
        $membership_obj->add($user_id, $this->mapRole($dto));
        $membership_obj->updateContact($user_id, $dto->isContact());
        $membership_obj->updateNotification($user_id, $dto->hasNotification());

        $this->current_ilias_object = new FakeIliasMembershipObject($ilias_course_ref_id, $user_id);
    }

    /**
     * @inheritdoc
     * @param CourseMembershipDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);
        $ilias_course_ref_id = $obj->getContainerIdIlias();
        $user_id = $dto->getUserId();
        if (!$this->props->updateDTOProperty('role')) {
            $this->current_ilias_object = new FakeIliasMembershipObject($ilias_course_ref_id, $user_id);

            return;
        }

        $course = $this->findILIASCourse($ilias_course_ref_id);
        if (!$course instanceof \ilObjCourse) {
            return;
        }

        $membership_obj = $course->getMembersObject();
        $membership_obj->add($user_id, $this->mapRole($dto));
        $membership_obj->updateRoleAssignments($user_id, [$this->getILIASRole($dto, $course)]);

        if ($this->props->updateDTOProperty("isContact")) {
            $membership_obj->updateContact($user_id, $dto->isContact());
        }

        if ($this->props->updateDTOProperty("hasNotification")) {
            $membership_obj->updateNotification($user_id, $dto->hasNotification());
        }

        $obj->setUserIdIlias($dto->getUserId());
        $obj->setContainerIdIlias($course->getRefId());
        $obj->initId();
    }

    /**
     * @inheritdoc
     * @param CourseMembershipDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

        if ((int) $this->props->get(
            CourseMembershipProperties::DELETE_MODE
        ) === CourseMembershipProperties::DELETE_MODE_NONE) {
            return;
        }

        $course = $this->findILIASCourse($obj->getContainerIdIlias());
        $course->getMembersObject()->delete($obj->getUserIdIlias());
    }

    /**
     * @param int $iliasId
     * @return ilObjCourse|null
     */
    protected function findILIASCourse($iliasId)
    {
        if (!ilObject2::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjCourse($iliasId);
    }

    /**
     * @return int
     */
    protected function mapRole(CourseMembershipDTO $object)
    {
        switch ($object->getRole()) {
            case CourseMembershipDTO::ROLE_ADMIN:
                return IL_CRS_ADMIN;
            case CourseMembershipDTO::ROLE_TUTOR:
                return IL_CRS_TUTOR;
            case CourseMembershipDTO::ROLE_MEMBER:
            default:
                return IL_CRS_MEMBER;
        }
    }

    /**
     * @return int
     */
    protected function getILIASRole(CourseMembershipDTO $object, ilObjCourse $course)
    {
        switch ($object->getRole()) {
            case CourseMembershipDTO::ROLE_ADMIN:
                return $course->getDefaultAdminRole();
            case CourseMembershipDTO::ROLE_TUTOR:
                return $course->getDefaultTutorRole();
            case CourseMembershipDTO::ROLE_MEMBER:
            default:
                return $course->getDefaultMemberRole();
        }
    }

    /**
     * @return int
     * @throws HubException
     */
    protected function determineCourseRefId(CourseMembershipDTO $course_membership)
    {
        if ($course_membership->getCourseIdType() == CourseMembershipDTO::COURSE_ID_TYPE_REF_ID) {
            return $course_membership->getCourseId();
        }
        if ($course_membership->getCourseIdType() == CourseMembershipDTO::COURSE_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored course-ID is an external-ID from a course.
            // We must search the course ref-ID from a category object synced by
            // a linked origin. --> Get an instance of the linked origin and lookup the
            // category by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if ($linkedOriginId === 0) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $arrayFilter = array_filter(
                $originRepository->courses(),
                function ($origin) use ($linkedOriginId) : bool {
                    /** @var IOrigin $origin */
                    return $origin->getId() == $linkedOriginId;
                }
            );
            $origin = array_pop(
                $arrayFilter
            );
            if (!$origin instanceof \srag\Plugins\Hub2\Origin\Course\ICourseOrigin) {
                $msg = "The linked origin syncing courses was not found, please check that the correct origin is linked";
                throw new HubException($msg);
            }
            $objectFactory = new ObjectFactory($origin);
            $course = $objectFactory->course($course_membership->getCourseId());
            if (!$course->getILIASId()) {
                throw new HubException(
                    "The linked course does not (yet) exist in ILIAS. Membership Ext-Id: " . $course_membership->getExtId(
                    )
                );
            }

            return $course->getILIASId();
        }

        return 0;
    }
}
