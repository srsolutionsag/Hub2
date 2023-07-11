<?php

namespace srag\Plugins\Hub2\Object;

use ilHub2Plugin;
use LogicException;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\CompetenceManagement\ARCompetenceManagement;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagement;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\IOrigin;

/**
 * Class ObjectFactory
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectFactory implements IObjectFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOrigin
     */
    protected $origin;
    /**
     * @var \ilDBInterface
     */
    private $db;

    public function __construct(IOrigin $origin)
    {
        global $DIC;
        $this->origin = $origin;
        $this->db = $DIC->database();
    }

    /**
     * @inheritdoc
     */
    public function undefined($ext_id)
    {
        switch ($this->origin->getObjectType()) {
            case IOrigin::OBJECT_TYPE_USER:
                return $this->user($ext_id);
            case IOrigin::OBJECT_TYPE_COURSE:
                return $this->course($ext_id);
            case IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP:
                return $this->courseMembership($ext_id);
            case IOrigin::OBJECT_TYPE_CATEGORY:
                return $this->category($ext_id);
            case IOrigin::OBJECT_TYPE_GROUP:
                return $this->group($ext_id);
            case IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP:
                return $this->groupMembership($ext_id);
            case IOrigin::OBJECT_TYPE_SESSION:
                return $this->session($ext_id);
            case IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP:
                return $this->sessionMembership($ext_id);
            case IOrigin::OBJECT_TYPE_ORGNUNIT:
                return $this->orgUnit($ext_id);
            case IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP:
                return $this->orgUnitMembership($ext_id);
            case IOrigin::OBJECT_TYPE_COMPETENCE_MANAGEMENT:
                return $this->competenceManagement($ext_id);
            default:
                throw new LogicException('no object-type for this origin found');
        }
    }

    private function buildARfromDB($ext_id, \ActiveRecord $ar) : \ActiveRecord
    {
        $r = $this->db->queryF(
            "SELECT * FROM {$ar->getConnectorContainerName()} WHERE ext_id = %s AND origin_id = %s",
            ['text', 'integer'],
            [$ext_id, $this->origin->getId()]
        );

        if ($r->numRows() === 0) {
            $ar->setOriginId($this->origin->getId());
            $ar->setExtId($ext_id);
        } else {
            $data = $r->fetchAssoc();
            $ar = $ar->buildFromArray($data);
        }

        return $ar;
    }

    /**
     * @inheritdoc
     */
    public function user($ext_id) : \ActiveRecord
    {
        return $this->buildARfromDB($ext_id, new ARUser());
    }

    /**
     * @inheritdoc
     */
    public function course($ext_id)
    {
        $course = ARCourse::find($this->getId($ext_id));
        if ($course === null) {
            $course = new ARCourse();
            $course->setOriginId($this->origin->getId());
            $course->setExtId($ext_id);
        }

        return $course;
    }

    /**
     * @inheritdoc
     */
    public function category($ext_id)
    {
        $category = ARCategory::find($this->getId($ext_id));
        if ($category === null) {
            $category = new ARCategory();
            $category->setOriginId($this->origin->getId());
            $category->setExtId($ext_id);
        }

        return $category;
    }

    /**
     * @inheritdoc
     */
    public function group($ext_id)
    {
        $group = ARGroup::find($this->getId($ext_id));
        if ($group === null) {
            $group = new ARGroup();
            $group->setOriginId($this->origin->getId());
            $group->setExtId($ext_id);
        }

        return $group;
    }

    /**
     * @inheritdoc
     */
    public function session($ext_id)
    {
        $session = ARSession::find($this->getId($ext_id));
        if ($session === null) {
            $session = new ARSession();
            $session->setOriginId($this->origin->getId());
            $session->setExtId($ext_id);
        }

        return $session;
    }

    /**
     * @inheritdoc
     */
    public function courseMembership($ext_id) : \ActiveRecord
    {
        return $this->buildARfromDB($ext_id, new ARCourseMembership());
    }

    /**
     * @inheritdoc
     */
    public function groupMembership($ext_id)
    {
        $group_membership = ARGroupMembership::find($this->getId($ext_id));
        if ($group_membership === null) {
            $group_membership = new ARGroupMembership();
            $group_membership->setOriginId($this->origin->getId());
            $group_membership->setExtId($ext_id);
        }

        return $group_membership;
    }

    /**
     * @inheritdoc
     */
    public function sessionMembership($ext_id)
    {
        $session_membership = ARSessionMembership::find($this->getId($ext_id));
        if ($session_membership === null) {
            $session_membership = new ARSessionMembership();
            $session_membership->setOriginId($this->origin->getId());
            $session_membership->setExtId($ext_id);
        }

        return $session_membership;
    }

    /**
     * @inheritdoc
     */
    public function orgUnit(string $ext_id) : IOrgUnit
    {
        $org_unit = AROrgUnit::find($this->getId($ext_id));
        if ($org_unit === null) {
            $org_unit = new AROrgUnit();
            $org_unit->setOriginId($this->origin->getId());
            $org_unit->setExtId($ext_id);
        }

        return $org_unit;
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMembership(string $ext_id) : IOrgUnitMembership
    {
        $org_unit_membership = AROrgUnitMembership::find($this->getId($ext_id));
        if ($org_unit_membership === null) {
            $org_unit_membership = new AROrgUnitMembership();
            $org_unit_membership->setOriginId($this->origin->getId());
            $org_unit_membership->setExtId($ext_id);
        }

        return $org_unit_membership;
    }

    /**
     * @inheritdoc
     */
    public function competenceManagement(string $ext_id) : ICompetenceManagement
    {
        $competence_management = ARCompetenceManagement::find($this->getId($ext_id));

        if ($competence_management === null) {
            $competence_management = new ARCompetenceManagement();
            $competence_management->setOriginId($this->origin->getId());
            $competence_management->setExtId($ext_id);
        }

        return $competence_management;
    }

    /**
     * @inheritdoc
     */
    public function getId($ext_id) : string
    {
        return $this->origin->getId() . $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function users() : array
    {
        return ARUser::get();
    }

    /**
     * @inheritdoc
     */
    public function courses() : array
    {
        return ARCourse::get();
    }

    /**
     * @inheritdoc
     */
    public function categories() : array
    {
        return ARCategory::get();
    }

    /**
     * @inheritdoc
     */
    public function categorys() : array
    {
        return $this->categories();
    }

    /**
     * @inheritdoc
     */
    public function groups() : array
    {
        return ARGroup::get();
    }

    /**
     * @inheritdoc
     */
    public function sessions() : array
    {
        return ARSession::get();
    }

    /**
     * @inheritdoc
     */
    public function courseMemberships() : array
    {
        return ARCourseMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function groupMemberships() : array
    {
        return ARGroupMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function sessionMemberships() : array
    {
        return ARSessionMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function orgUnits() : array
    {
        return AROrgUnit::get();
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMemberships() : array
    {
        return AROrgUnitMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function competenceManagements() : array
    {
        return ARCompetenceManagement::get();
    }

    // ExtIds only
    private function fetchAllExtIds(\ActiveRecord $ar) : array
    {
        $r = $this->db->queryF(
            "SELECT ext_id FROM {$ar->getConnectorContainerName()} WHERE origin_id = %s",
            ['integer'],
            [$this->origin->getId()]
        );
        $ext_ids = [];
        while ($d = $this->db->fetchObject($r)) {
            $ext_ids[] = $d->ext_id;
        }

        return $ext_ids;
    }

    public function usersExtIds() : array
    {
        return $this->fetchAllExtIds(new ARUser());
    }

    public function coursesExtIds() : array
    {
        return $this->fetchAllExtIds(new ARCourse());
    }

    public function categoriesExtIds() : array
    {
        return $this->fetchAllExtIds(new ARCategory());
    }

    public function categorysExtIds() : array
    {
        return $this->categoriesExtIds();
    }

    public function groupsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARGroup());
    }

    public function sessionsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARSession());
    }

    public function courseMembershipsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARCourseMembership());
    }

    public function groupMembershipsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARGroupMembership());
    }

    public function sessionMembershipsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARSessionMembership());
    }

    public function orgUnitsExtIds() : array
    {
        return $this->fetchAllExtIds(new AROrgUnit());
    }

    public function orgUnitMembershipsExtIds() : array
    {
        return $this->fetchAllExtIds(new AROrgUnitMembership());
    }

    public function competenceManagementsExtIds() : array
    {
        return $this->fetchAllExtIds(new ARCompetenceManagement());
    }
}
