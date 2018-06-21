<?php

require_once __DIR__ . "/../vendor/autoload.php";

use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;

/**
 * Class ilHub2Plugin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin {

	use DIC;
	const PLUGIN_ID = 'hub2';
	const PLUGIN_NAME = 'Hub2';
	/**
	 * @var ilHub2Plugin
	 */
	protected static $instance;


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return ilHub2Plugin
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return \ilCronJob[]
	 */
	public function getCronJobInstances() {
		return [new \SRAG\Plugins\Hub2\Jobs\RunSync()];
	}


	/**
	 * @param $a_job_id
	 *
	 * @return \ilCronJob
	 */
	public function getCronJobInstance($a_job_id) {
		return new $a_job_id();
	}


	/**
	 * @return bool
	 */
	protected function beforeUninstall() {
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Origin\User\ARUserOrigin::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\User\ARUser::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\Course\ARCourse::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\Category\ARCategory::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\Session\ARSession::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\Group\ARGroup::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::TABLE_NAME, false);
		$this->db()
			->dropTable(SRAG\Plugins\Hub2\Config\ArConfig::TABLE_NAME, false);
		$this->db()
			->dropTable(AROrgUnit::TABLE_NAME, false);
		$this->db()
			->dropTable(AROrgUnitMembership::TABLE_NAME, false);

		ilUtil::delDir(ILIAS_DATA_DIR . "/hub/");

		return true;
	}


	public function txt($a_var) {
		require_once "./Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php";
		$mode = 'core'; // fs, fw, core

		switch ($mode) {
			case 'fw':
				$a = sragPluginTranslator::getInstance($this)
					->active()
					->write();

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

					$a->sragPluginTranslaterJson->addEntry(
						$category, $key, $txt, $this->lng()
						->getLangKey()
					);
					$a->sragPluginTranslaterJson->save();
				}

				return $txt;
			case 'fs':
				return sragPluginTranslator::getInstance($this)
					->active(true)
					->write()
					->txt($a_var);
			case 'core':
			default:
				return parent::txt($a_var);
		}
	}
}
