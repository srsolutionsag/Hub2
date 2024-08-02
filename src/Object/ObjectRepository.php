<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use ilHub2Plugin;
use srag\Plugins\Hub2\Object\Group\GroupRepository;
use srag\Plugins\Hub2\Object\Session\SessionRepository;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;

/**
 * Class ObjectRepository
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectRepository implements IObjectRepository
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var \ilDBInterface
     */
    protected $db;
    protected IOrigin $origin;
    /**
     * @var array
     */
    protected static $classmap = [];

    /**
     * ObjectRepository constructor
     */
    public function __construct(IOrigin $origin)
    {
        global $DIC;
        $this->origin = $origin;
        $this->db = $DIC->database();
    }


    public function all(): array
    {
        $class = $this->getClass();

        /** @var ActiveRecord $class */
        return $class::where(['origin_id' => $this->origin->getId()])->get();
    }


    public function getByStatus(int $status): array
    {
        $class = $this->getClass();

        /** @var ActiveRecord $class */
        return $class::where(
            [
                'origin_id' => $this->origin->getId(),
                'status' => $status,
            ]
        )->get();
    }


    public function getToDeleteByParentScope(array $ext_ids, array $parent_ext_ids): array
    {
        $existing_ext_id_query = null;
        $glue = self::GLUE;
        $class = $this->getClass();

        if ($parent_ext_ids !== []) {
            if ($ext_ids !== []) {
                $existing_ext_id_query = " AND ext_id NOT IN ('" . implode("','", $ext_ids) . "') ";
            }
            if ($this instanceof GroupRepository || $this instanceof SessionRepository) {
                $parent_scope_query = " AND (";
                foreach ($parent_ext_ids as $parent_ext_id) {
                    $parent_scope_query .= " data LIKE '%\"parentId\":\"$parent_ext_id\"%' OR";
                }
                $parent_scope_query = rtrim($parent_scope_query, "OR");
                $parent_scope_query .= ")";
            } else {
                $parent_scope_query = " AND SUBSTRING_INDEX(ext_id,'" . $glue . "',1) IN ('" . implode(
                    "','",
                    $parent_ext_ids
                ) . "') ";
            }

            return $class::where(
                "origin_id = " . $this->origin->getId() . " AND status IN ('" . implode(
                    "','",
                    [
                        IObject::STATUS_CREATED,
                        IObject::STATUS_UPDATED,
                        IObject::STATUS_IGNORED,
                    ]
                ) . "') " . $existing_ext_id_query . $parent_scope_query
            )->get();
        }

        return [];
    }


    public function getToDelete(array $ext_ids): array
    {
        $class = $this->getClass();

        if ($ext_ids !== []) {
            /** @var ActiveRecord $class */
            return $class::where(
                [
                    'origin_id' => $this->origin->getId(),
                    // We only can transmit from final states CREATED and UPDATED to TO_DELETE
                    // E.g. not from OUTDATED or IGNORED
                    'status' => [IObject::STATUS_CREATED, IObject::STATUS_UPDATED, IObject::STATUS_IGNORED],
                    'ext_id' => $ext_ids,
                ],
                ['origin_id' => '=', 'status' => 'IN', 'ext_id' => 'NOT IN']
            )->get();
        }
        /** @var ActiveRecord $class */
        return $class::where(
            [
                'origin_id' => $this->origin->getId(),
                // We only can transmit from final states CREATED and UPDATED to TO_DELETE
                // E.g. not from OUTDATED or IGNORED
                'status' => [IObject::STATUS_CREATED, IObject::STATUS_UPDATED, IObject::STATUS_IGNORED],
            ],
            ['origin_id' => '=', 'status' => 'IN']
        )->get();
    }


    public function count(): int
    {
        // we moved the implementation away from AR since this was a memory killer
        /** @var ARObject|ARUser|ARCategory|ARCourse|ARGroup|ARCourseMembership|ARGroupMembership|ARSession|ARSessionMembership $class */
        $class = $this->getClass();
        $table_name = $class::returnDbTableName();
        $q = "SELECT COUNT(*) as count FROM $table_name WHERE origin_id = " . $this->db->quote(
            $this->origin->getId(),
            'integer'
        );
        $result = $this->db->query($q);

        return (int) $this->db->fetchObject($result)->count;
    }

    /**
     * Returns the active record class name for the origin
     * @return string
     */
    protected function getClass()
    {
        $object_type = $this->origin->getObjectType();

        if (isset(self::$classmap[$object_type])) {
            return self::$classmap[$object_type];
        }

        $ucfirst = ucfirst($object_type);
        self::$classmap[$object_type] = "srag\\Plugins\\Hub2\\Object\\" . $ucfirst . "\\AR" . $ucfirst;

        return self::$classmap[$object_type];
    }
}
