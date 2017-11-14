<?php namespace SRAG\Plugins\Hub2\Exception;

/**
 * Class AbortOriginSyncOfCurrentTypeException
 *
 * Throw this exception to abort the current sync of the origin AND all also skip following syncs
 * from origins of the same object type, e.g. User, Course etc.
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Exception
 */
class AbortOriginSyncOfCurrentTypeException extends HubException {

}