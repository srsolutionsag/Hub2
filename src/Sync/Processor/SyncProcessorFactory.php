<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\Category\CategorySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CompetenceManagement\CompetenceManagementSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CompetenceManagement\ICompetenceManagementSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Course\CourseActivities;
use srag\Plugins\Hub2\Sync\Processor\Course\CourseSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CourseMembership\CourseMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Group\GroupActivities;
use srag\Plugins\Hub2\Sync\Processor\Group\GroupSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\GroupMembership\GroupMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\OrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\OrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Session\SessionSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SessionMembership\SessionMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\User\UserSyncProcessor;

/**
 * Class SyncProcessorFactory
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SyncProcessorFactory implements ISyncProcessorFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOrigin
     */
    protected $origin;
    /**
     * @var IObjectStatusTransition
     * @deprecated
     */
    protected $statusTransition;
    /**
     * @var IOriginImplementation
     */
    protected $implementation;
    /**
     * @var \ilDBInterface
     */
    protected $database;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $statusTransition
    ) {
        global $DIC;
        $this->database = $DIC->database();
        $this->origin = $origin;
        $this->statusTransition = $statusTransition;
        $this->implementation = $implementation;
    }

    /**
     * @inheritdoc
     */
    public function user() : \srag\Plugins\Hub2\Sync\Processor\User\UserSyncProcessor
    {
        return new UserSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function course() : \srag\Plugins\Hub2\Sync\Processor\Course\CourseSyncProcessor
    {
        return new CourseSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition,
            new CourseActivities($this->database)
        );
    }

    /**
     * @inheritdoc
     */
    public function category() : \srag\Plugins\Hub2\Sync\Processor\Category\CategorySyncProcessor
    {
        return new CategorySyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function session() : \srag\Plugins\Hub2\Sync\Processor\Session\SessionSyncProcessor
    {
        return new SessionSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function courseMembership() : \srag\Plugins\Hub2\Sync\Processor\CourseMembership\CourseMembershipSyncProcessor
    {
        return new CourseMembershipSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function group() : \srag\Plugins\Hub2\Sync\Processor\Group\GroupSyncProcessor
    {
        return new GroupSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition,
            new GroupActivities($this->database)
        );
    }

    /**
     * @inheritdoc
     */
    public function groupMembership() : \srag\Plugins\Hub2\Sync\Processor\GroupMembership\GroupMembershipSyncProcessor
    {
        return new GroupMembershipSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function sessionMembership(
    ) : \srag\Plugins\Hub2\Sync\Processor\SessionMembership\SessionMembershipSyncProcessor {
        return new SessionMembershipSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function orgUnit() : IOrgUnitSyncProcessor
    {
        return new OrgUnitSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMembership() : IOrgUnitMembershipSyncProcessor
    {
        return new OrgUnitMembershipSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }

    /**
     * @inheritdoc
     */
    public function competenceManagement() : ICompetenceManagementSyncProcessor
    {
        return new CompetenceManagementSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition
        );
    }
}
