<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Config\ArConfigOld;
use srag\Plugins\Hub2\Jobs\RunSync;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\RemovePluginDataConfirm\PluginUninstallTrait;

/**
 * Class ilHub2Plugin
 *
 * @package
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin {

	use PluginUninstallTrait;
	const PLUGIN_ID = 'hub2';
	const PLUGIN_NAME = 'Hub2';
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = hub2RemoveDataConfirm::class;
	/**
	 * @var self
	 */
	protected static $instance;


	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return ilCronJob[]
	 */
	public function getCronJobInstances(): array {
		return [ new RunSync() ];
	}


	/**
	 * @param string $a_job_id
	 *
	 * @return ilCronJob
	 */
	public function getCronJobInstance($a_job_id): ilCronJob {
		return new $a_job_id();
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(ARUserOrigin::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARUser::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARCourse::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARCourseMembership::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARCategory::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARSession::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARGroup::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARGroupMembership::TABLE_NAME, false);
		self::dic()->database()->dropTable(ARSessionMembership::TABLE_NAME, false);
		self::dic()->database()->dropTable(ArConfig::TABLE_NAME, false);
		self::dic()->database()->dropTable(ArConfigOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(AROrgUnit::TABLE_NAME, false);
		self::dic()->database()->dropTable(AROrgUnitMembership::TABLE_NAME, false);

		ilUtil::delDir(ILIAS_DATA_DIR . "/hub/");
	}
}
