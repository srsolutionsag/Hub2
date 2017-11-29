<?php

namespace SRAG\Plugins\Hub2\Object\Group;

use SRAG\Plugins\Hub2\Object\ARMetadataAwareObject;
use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\ARTaxonomyAwareObject;

/**
 * Class ARGroup
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroup extends ARObject implements IGroup {

	use ARMetadataAwareObject;
	use ARTaxonomyAwareObject;


	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_group';
	}
}
