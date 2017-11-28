<?php

namespace SRAG\Plugins\Hub2\Object\Session;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class ARSession
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSession extends ARObject implements ISession {

	use ARMetadataAwareObject;


	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_session';
	}
}