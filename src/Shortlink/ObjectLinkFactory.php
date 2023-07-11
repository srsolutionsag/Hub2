<?php

namespace srag\Plugins\Hub2\Shortlink;

use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagement;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Shortlink\Category\CategoryLink;
use srag\Plugins\Hub2\Shortlink\CompetenceManagement\CompetenceManagementLink;
use srag\Plugins\Hub2\Shortlink\Course\CourseLink;
use srag\Plugins\Hub2\Shortlink\CourseMembership\CourseMembershipLink;
use srag\Plugins\Hub2\Shortlink\Group\GroupLink;
use srag\Plugins\Hub2\Shortlink\GroupMembership\GroupMembershipLink;
use srag\Plugins\Hub2\Shortlink\OrgUnit\OrgUnitLink;
use srag\Plugins\Hub2\Shortlink\OrgUnitMembership\OrgUnitMembershipLink;
use srag\Plugins\Hub2\Shortlink\Session\SessionLink;
use srag\Plugins\Hub2\Shortlink\SessionMembership\SessionMembershipLink;
use srag\Plugins\Hub2\Shortlink\User\UserLink;

/**
 * Class ObjectLinkFactory
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectLinkFactory
{
    /**
     * @var OriginFactory
     */
    private $origin_factory;

    /**
     * ObjectLinkFactory constructor
     */
    public function __construct()
    {
        $this->origin_factory = new OriginFactory();
    }

    public function findByExtId(string $ext_id) : IObjectLink
    {
        foreach ($this->origin_factory->getAllActive() as $origin) {
            $l = $this->findByExtIdAndOrigin($ext_id, $origin);

            if (!($l instanceof NullLink)) {
                return $l;
            }
        }

        return new NullLink();
    }

    public function findByExtIdAndOrigin(string $ext_id, IOrigin $origin) : IObjectLink
    {
        $f = new ObjectFactory($origin);

        $object = $f->undefined($ext_id);

        return $this->findByObject($object);
    }

    public function findByObject(ARObject $object) : IObjectLink
    {
        $ilias_id = $object->getILIASId();

        if ($ilias_id) {
            switch (true) {
                case ($object instanceof ARCourseMembership):
                    return new CourseMembershipLink($object);
                case ($object instanceof ARGroupMembership):
                    return new GroupMembershipLink($object);
                case ($object instanceof ARSessionMembership):
                    return new SessionMembershipLink($object);
                case ($object instanceof ARSession):
                    return new SessionLink($object);
                case ($object instanceof ARCategory):
                    return new CategoryLink($object);
                case ($object instanceof ARCourse):
                    return new CourseLink($object);
                case ($object instanceof ARGroup):
                    return new GroupLink($object);
                case ($object instanceof ARUser):
                    return new UserLink($object);
                case ($object instanceof IOrgUnit):
                    return new OrgUnitLink($object);
                case ($object instanceof IOrgUnitMembership):
                    return new OrgUnitMembershipLink($object);
                case ($object instanceof ICompetenceManagement):
                    return new CompetenceManagementLink($object);
                default:
                    break;
            }
        }

        return new NullLink();
    }
}
