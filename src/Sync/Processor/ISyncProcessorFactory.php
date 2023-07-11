<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use srag\Plugins\Hub2\Sync\Processor\Category\ICategorySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CompetenceManagement\ICompetenceManagementSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Course\ICourseSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CourseMembership\ICourseMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Group\IGroupSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\GroupMembership\IGroupMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Session\ISessionSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SessionMembership\ISessionMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\User\IUserSyncProcessor;

/**
 * Interface ISyncProcessorFactory
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISyncProcessorFactory
{
    /**
     * @return IUserSyncProcessor
     */
    public function user();

    /**
     * @return ICourseSyncProcessor
     */
    public function course();

    /**
     * @return ICategorySyncProcessor
     */
    public function category();

    /**
     * @return ISessionSyncProcessor
     */
    public function session();

    /**
     * @return ICourseMembershipSyncProcessor
     */
    public function courseMembership();

    /**
     * @return IGroupSyncProcessor
     */
    public function group();

    /**
     * @return IGroupMembershipSyncProcessor
     */
    public function groupMembership();

    /**
     * @return ISessionMembershipSyncProcessor
     */
    public function sessionMembership();

    public function orgUnit() : IOrgUnitSyncProcessor;

    public function orgUnitMembership() : IOrgUnitMembershipSyncProcessor;

    public function competenceManagement() : ICompetenceManagementSyncProcessor;
}
