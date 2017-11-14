<?php namespace SRAG\Plugins\Hub2\Exception;

/**
 * Class AbortSyncException
 *
 * Throw this exception to abort the global sync over all origins. This means that any following
 * origin-syncs are NOT getting executed.
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Exception
 */
class AbortSyncException extends HubException {

}