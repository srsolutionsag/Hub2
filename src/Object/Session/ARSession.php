<?php

namespace SRAG\Hub2\Object\Session;

use SRAG\Hub2\Object\ARObject;

/**
 * Class ARSession
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSession extends ARObject implements ISession {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_session';
	}
}
