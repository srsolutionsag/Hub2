<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\Plugins\Hub2\Origin\Category\ARCategoryOrigin;
use srag\Plugins\Hub2\Origin\CompetenceManagement\ARCompetenceManagementOrigin;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use srag\Plugins\Hub2\Origin\GroupMembership\ARGroupMembershipOrigin;
use srag\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;
use srag\Plugins\Hub2\Origin\Session\ARSessionOrigin;
use srag\Plugins\Hub2\Origin\SessionMembership\ARSessionMembershipOrigin;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;

/**
 * Class OriginRepository
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginRepository implements IOriginRepository
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


    public function all(): array
    {
        return array_merge(
            $this->users(),
            $this->categories(),
            $this->courses(),
            $this->courseMemberships(),
            $this->groups(),
            $this->groupMemberships(),
            $this->sessions(),
            $this->sessionsMemberships(),
            $this->orgUnits(),
            $this->orgUnitMemberships(),
            $this->competenceManagements()
        );
    }


    public function allActive(): array
    {
        return array_filter(
            $this->all(),
            fn ($origin): bool =>
                /** @var IOrigin $origin */
                $origin->isActive()
        );
    }


    public function users(): array
    {
        return ARUserOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_USER])->get();
    }


    public function courses(): array
    {
        return ARCourseOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_COURSE])->get();
    }


    public function categories(): array
    {
        return ARCategoryOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_CATEGORY])->get();
    }


    public function courseMemberships(): array
    {
        return ARCourseMembershipOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP])->get();
    }


    public function groups(): array
    {
        return ARGroupOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_GROUP])->get();
    }


    public function groupMemberships(): array
    {
        return ARGroupMembershipOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP])->get();
    }


    public function sessions(): array
    {
        return ARSessionOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_SESSION])->get();
    }


    public function sessionsMemberships(): array
    {
        return ARSessionMembershipOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP])->get();
    }


    public function orgUnits(): array
    {
        return AROrgUnitOrigin::where(["object_type" => IOrigin::OBJECT_TYPE_ORGNUNIT])->get();
    }


    public function orgUnitMemberships(): array
    {
        return AROrgUnitOrigin::where(["object_type" => IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP])->get();
    }


    public function competenceManagements(): array
    {
        return ARCompetenceManagementOrigin::where(["object_type" => IOrigin::OBJECT_TYPE_COMPETENCE_MANAGEMENT])->get(
        );
    }
}
