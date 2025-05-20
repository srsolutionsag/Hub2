<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Exception;

/**
 * Class AbortOriginSyncOfCurrentTypeException
 * Throw this exception to abort the current sync of the origin AND all also skip following syncs
 * from origins of the same object type, e.g. User, Course etc.
 * @package srag\Plugins\Hub2\Exception
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class AbortOriginSyncOfCurrentTypeException extends HubException
{
}
