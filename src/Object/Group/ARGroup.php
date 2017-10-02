<?php
namespace SRAG\Hub2\Object\Group;

use SRAG\Hub2\Object\ARObject;

/**
 * Class ARGroup
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARGroup extends ARObject implements IGroup {

	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_group';
	}
}
