<?php

namespace srag\Plugins\Hub2\Object;

/**
 * Interface IObjectRepository
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectRepository
{
    public const GLUE = "|||";

    /**
     * Return all objects
     * @return IObject[]
     */
    public function all() : array;

    /**
     * Return only the objects having the given status
     * @return IObject[]
     */
    public function getByStatus(int $status) : array;

    /**
     * Return all objects where the status TO_DELETE should be applied.
     * This method must return all hub objects where the ext-ID is not part of the given ext-IDs,
     * e.g. SELECT * FROM x WHERE ext_id NOT IN ($ext_ids).
     * @return IObject[]
     */
    public function getToDelete(array $ext_ids) : array;

    /**
     * As getToDelete this method returns all objects where the status TO_DELETE should be applied.
     * However it only checks for items in the scope of a set of parent containers. E.g. only returns
     * membership to delete for memberships of a course with an ext_id in $parent_ext_ids.
     * @return IObject[]
     */
    public function getToDeleteByParentScope(array $ext_ids, array $parent_ext_ids) : array;

    /**
     * Return the number of objects
     */
    public function count() : int;
}
