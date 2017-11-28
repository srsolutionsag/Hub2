<?php

namespace SRAG\Plugins\Hub2\Object\SessionMembership;

use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARSessionMembership
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembership extends ARObject implements ISessionMembership {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_session_mem';
	}
}