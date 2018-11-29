<?php

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\IObject;

/**
 * Interface IObjectStatus
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @deprecated
 */
interface IObjectStatusTransition {

	/**
	 * Transition from a the current intermediate status of the object to the next final status.
	 * If the current status is not an intermediate one (e.g. TO_CREATE, TO_UPDATE, TO_DELETE...),
	 * the same intermediate status is returned.
	 *
	 * Note that this method returns the new status but does NOT set it on the passed object.
	 *
	 * @param IObject $object
	 *
	 * @return int
	 *
	 * @throws HubException Invalid status!
	 *
	 * @deprecated
	 */
	public function intermediateToFinal(IObject $object): int;


	/**
	 * Transition from the current final status of the object to the next intermediate status.
	 * If the current status is not a final one (e.g. CREATED, UPDATED, DELETED, IGNORED...), the
	 * same final status is returned.
	 *
	 * Note that this method returns the new status but does NOT set it on the passed object.
	 *
	 * @param IObject $object
	 *
	 * @return mixed
	 *
	 * @throws HubException Invalid status!
	 *
	 * @deprecated
	 */
	public function finalToIntermediate(IObject $object): int;
}
