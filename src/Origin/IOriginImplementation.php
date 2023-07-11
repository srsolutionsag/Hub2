<?php

namespace srag\Plugins\Hub2\Origin;

use InvalidArgumentException;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Origin\Hook\Config;

/**
 * Interface IOriginImplementation
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginImplementation
{
    /**
     * Connect to the service providing the sync data.
     * Throw a ConnectionFailedException to abort the sync if a connection is not possible.
     * @return bool
     * @throws ConnectionFailedException
     */
    public function connect();

    /**
     * Parse and prepare (sanitize/validate) the data to fill the DTO objects.
     * Return the number of data. Note that this number is used to check if the amount of delivered
     * data is sufficent to continue the sync, depending on the configuration of the origin.
     * Throw a ParseDataFailedException to abort the sync if your data cannot be parsed.
     * @return int
     * @throws ParseDataFailedException
     */
    public function parseData();

    public function canDroppedFileContentBestored(string $content) : bool;


    // HOOKS
    // ------------------------------------------------------------------------------------------------------------

    public function hookConfig() : Config;

    /**
     * Called if any exception occurs during processing the ILIAS objects. This hook can be used to
     * influence the further processing of the current origin sync or the global sync:
     * - Throw an AbortOriginSyncException to stop the current sync of this origin.
     *   Any other following origins in the processing chain are still getting executed normally.
     * - Throw an AbortOriginSyncOfCurrentTypeException to abort the current sync of the origin AND
     *   all also skip following syncs from origins of the same object type, e.g. User, Course etc.
     * - Throw an AbortSyncException to stop the global sync. The sync of any other following
     * origins in the processing chain is NOT getting executed.
     * Note that if you do not throw any of the exceptions above, the sync will continue.
     */
    public function handleLog(ILog $log);

    public function beforeCreateILIASObject(HookObject $hook);

    public function afterCreateILIASObject(HookObject $hook);

    public function beforeUpdateILIASObject(HookObject $hook);

    public function afterUpdateILIASObject(HookObject $hook);

    public function beforeDeleteILIASObject(HookObject $hook);

    public function afterDeleteILIASObject(HookObject $hook);

    /**
     * Executed before the synchronization of the origin is executed.
     */
    public function beforeSync();

    /**
     * Executed after the synchronization of the origin has been executed.
     */
    public function afterSync();

    /**
     * This Hook can be used to override the given status of an object before ObjectSyncProcessor does it's work.
     * Valid Status are:
     * - IObject::STATUS_TO_CREATE
     * - IObject::STATUS_TO_UPDATE
     * - IObject::STATUS_TO_OUTDATED
     * - IObject::STATUS_TO_RESTORE
     * - IObject::STATUS_IGNORED
     * E.G. $object->overrideStatus(IObject::STATUS_TO_UPDATE);
     * @return void
     * @throws HubException if overriding Status for NullDTOs (deleted objects)
     * @throws InvalidArgumentException if passing not supported Status
     */
    public function overrideStatus(HookObject $hook);

    /**
     * Returns an array of ext ids of parent containers to
     * be used if adhoc sync with parent scope is used.
     * This can be useful if e.g. courses with members are delivered
     * by the foreign systems API and if e.g. a course member is removed
     * the complete course with the new member list would be delivered (and
     * not the message, that this exact member is removed from the course).
     * Children (e.g. memberships) of such containers will be considered when
     * generating the list of items to be deleted.
     * @return array ext_ids of parent containers, who's children will be considered
     * when determining dto's to be deleted.
     */
    public function getAdHocParentScopesAsExtIds() : array;

    /**
     * @return mixed
     */
    public function handleNoLongerDeliveredObject(HookObject $hook);

    public function handleAllObjects(HookObject $hook);
}
