<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface Origin
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrigin
{
    public const OBJECT_TYPE_USER = 'user';
    public const OBJECT_TYPE_COURSE_MEMBERSHIP = 'courseMembership';
    public const OBJECT_TYPE_COURSE = 'course';
    public const OBJECT_TYPE_CATEGORY = 'category';
    public const OBJECT_TYPE_GROUP = 'group';
    public const OBJECT_TYPE_GROUP_MEMBERSHIP = 'groupMembership';
    public const OBJECT_TYPE_SESSION = 'session';
    public const OBJECT_TYPE_SESSION_MEMBERSHIP = 'sessionMembership';
    public const OBJECT_TYPE_ORGNUNIT = "orgUnit";
    public const OBJECT_TYPE_ORGNUNIT_MEMBERSHIP = "orgUnitMembership";
    public const OBJECT_TYPE_COMPETENCE_MANAGEMENT = "competenceManagement";
    public const ORIGIN_MAIN_NAMESPACE = "srag\\Plugins\\Hub2\\Origin";

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive($active);

    /**
     * @return string
     */
    public function getImplementationClassName();

    /**
     * @param string $name
     * @return $this
     */
    public function setImplementationClassName($name);

    /**
     * @return string
     */
    public function getImplementationNamespace();

    /**
     * @param string $implementation_namespace
     */
    public function setImplementationNamespace($implementation_namespace);

    /**
     * Get the object type that will be synced with this origin, e.g.
     * user|course|category|courseMembership
     * @return string
     */
    public function getObjectType();

    /**
     * @param string $type
     * @return $this
     */
    public function setObjectType($type);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Get access to all configuration data of this origin.
     * @return IOriginConfig
     */
    public function config();

    /**
     * Get access to all properties data of this origin.
     * @return IOriginProperties
     */
    public function properties();

    /**
     * @return string
     */
    public function getLastRun();

    /**
     * @param string $last_run
     */
    public function setLastRun($last_run);

    public function setLastRunToNow(): void;

    /**
     *
     */
    public function update();

    /**
     *
     */
    public function create();

    /**
     * Run Sync without Hash comparison
     */
    public function forceUpdate();

    public function isUpdateForced() : bool;

    public function isAdHoc() : bool;

    /**
     * @param bool $active
     */
    public function setAdHoc(bool $adhoc)/*: void*/
    ;

    public function isAdhocParentScope() : bool;

    public function setAdhocParentScope(bool $adhoc_parent_scope)/*: void*/
    ;

    public function getSort() : int;

    public function setSort(int $sort)/*: void*/
    ;

    /**
     *
     */
    public function store();
}
