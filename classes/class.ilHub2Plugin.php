<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Class ilHub2Plugin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin {

	const PLUGIN_NAME = 'Hub2';
	/**
	 * @var ilHubPlugin
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
		return [ new \SRAG\Plugins\Hub2\Jobs\RunSync() ];
	}


	/**
	 * @param $a_job_id
	 *
	 * @return \ilCronJob
	 */
	public function getCronJobInstance($a_job_id) {
		return new $a_job_id();
	}


	//	/**
	//	 * @param $a_var
	//	 *
	//	 * @return string
	//	 */
	//	public function txt($a_var) {
	//		require_once('./Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php');
	//
	//		return sragPluginTranslator::getInstance($this)->active()->write()->txt($a_var);
	//	}
}