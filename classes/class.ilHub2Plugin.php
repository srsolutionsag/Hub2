<?php

require_once __DIR__ . "/../vendor/autoload.php";

use SRAG\Plugins\Hub2\Config\ArConfig;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Jobs\RunSync;
use SRAG\Plugins\Hub2\Object\Category\ARCategory;
use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use SRAG\Plugins\Hub2\Object\User\ARUser;
use SRAG\Plugins\Hub2\Origin\User\ARUserOrigin;

/**
 * Class ilHub2Plugin
 *
 * @package
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin {

	use DIC;
	const PLUGIN_ID = 'hub2';
	const PLUGIN_NAME = 'Hub2';
	const UNINSTALL_REMOVE_HUB2_DATA = "uninstall_remove_hub2_data";
	/**
	 * @var ilHub2Plugin
	 */
	protected static $instance;


	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return ilHub2Plugin
	 */
	public static function getInstance(): ilHub2Plugin {
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
	 * @return bool
	 */
	protected function beforeUninstall(): bool {
		$uninstall_remove_hub2_data = ArConfig::getValueByKey(self::UNINSTALL_REMOVE_HUB2_DATA);

		if ($uninstall_remove_hub2_data === NULL) {
			hub2RemoveDataConfirm::saveParameterByClass();

			$this->ctrl()->redirectByClass([
				ilUIPluginRouterGUI::class,
				hub2RemoveDataConfirm::class
			], hub2RemoveDataConfirm::CMD_CONFIRM_REMOVE_HUB2_DATA);

			return false;
		}

		$uninstall_remove_hub2_data = boolval($uninstall_remove_hub2_data);

		if ($uninstall_remove_hub2_data) {
			$this->removeHub2Data();
		} else {
			// Ask again if reinstalled
			ArConfig::getInstanceByKey(self::UNINSTALL_REMOVE_HUB2_DATA)->delete();
		}

		return true;
	}


	/**
	 *
	 */
	protected function removeHub2Data() {
		$this->db()->dropTable(ARUserOrigin::TABLE_NAME, false);
		$this->db()->dropTable(ARUser::TABLE_NAME, false);
		$this->db()->dropTable(ARCourse::TABLE_NAME, false);
		$this->db()->dropTable(ARCourseMembership::TABLE_NAME, false);
		$this->db()->dropTable(ARCategory::TABLE_NAME, false);
		$this->db()->dropTable(ARSession::TABLE_NAME, false);
		$this->db()->dropTable(ARGroup::TABLE_NAME, false);
		$this->db()->dropTable(ARGroupMembership::TABLE_NAME, false);
		$this->db()->dropTable(ARSessionMembership::TABLE_NAME, false);
		$this->db()->dropTable(ArConfig::TABLE_NAME, false);
		$this->db()->dropTable(AROrgUnit::TABLE_NAME, false);
		$this->db()->dropTable(AROrgUnitMembership::TABLE_NAME, false);

		ilUtil::delDir(ILIAS_DATA_DIR . "/hub/");
	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	public function txt($a_var): string {
		if (!file_exists("Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php")) {
			return parent::txt($a_var);
		}

		require_once "Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php";

		$mode = 'core'; // fs, fw, core

		switch ($mode) {
			case 'fw':
				$a = sragPluginTranslator::getInstance($this)->active()->write();
				$a->txt($a_var);

				$txt = parent::txt($a_var);

				if ($txt === sragPluginTranslatorJson::MISSING) {
					$txt .= " " . $a_var;

					if (preg_match(sragPluginTranslator::REGEX, $a_var, $index)) {
						$key = $index[2];
						$category = $index[1];
					} else {
						$key = $a_var;
						$category = 'common';
					}

					$a->sragPluginTranslaterJson->addEntry($category, $key, $txt, $this->lng()->getLangKey());
					$a->sragPluginTranslaterJson->save();
				}

				return $txt;
			case 'fs':
				return sragPluginTranslator::getInstance($this)->active(true)->write()->txt($a_var);
			case 'core':
			default:
				return parent::txt($a_var);
		}
	}
}
