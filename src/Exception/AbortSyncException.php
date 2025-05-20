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
 * Class AbortSyncException
 * Throw this exception to abort the global sync over all origins. This means that any following
 * origin-syncs are NOT getting executed.
 * @package srag\Plugins\Hub2\Exception
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class AbortSyncException extends HubException
{
}
