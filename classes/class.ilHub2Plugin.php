<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Class ilHub2Plugin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin {

	/**
	 * @var ilHubPlugin
	 */
	protected static $instance;


	/**
	 * @return string
	 */
	function getPluginName() {
		return 'Hub2';
	}


	protected function init() {
		if (isset($_GET['ulx'])) {
			$this->updateLanguages();
		}
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


	public function getCronJobInstances() {
		return [];
	}


	public function getCronJobInstance($a_job_id) {
		// TODO: Implement getCronJobInstance() method.
	}


	public function txt($a_var) {
		require_once('./Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php');

		return sragPluginTranslator::getInstance($this)->active()->write()->txt($a_var);
	}
}