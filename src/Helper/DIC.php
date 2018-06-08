<?php

namespace SRAG\Plugins\Hub2\Helper;

/**
 * Trait DIC
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait DIC {

	/**
	 * @return \ILIAS\DI\Container
	 */
	public function dic() {
		return $GLOBALS['DIC'];
	}


	/**
	 * @return \ilCtrl
	 */
	protected function ctrl() {
		return $this->dic()->ctrl();
	}


	/**
	 * @param $variable
	 *
	 * @return string
	 */
	public function txt($variable) {
		return $this->lng()->txt($variable);
	}


	/**
	 * @return \ilTemplate
	 */
	protected function tpl() {
		return $this->dic()->ui()->mainTemplate();
	}


	/**
	 * @return \ilLanguage
	 */
	protected function lng() {
		return $this->dic()->language();
	}


	/**
	 * @return \ilTabsGUI
	 */
	protected function tabs() {
		return $this->dic()->tabs();
	}


	/**
	 * @return \ILIAS\DI\UIServices
	 */
	protected function ui() {
		return $this->dic()->ui();
	}


	/**
	 * @return \ilObjUser
	 */
	protected function user() {
		return $this->dic()->user();
	}


	/**
	 * @return \ILIAS\DI\HTTPServices
	 */
	protected function http() {
		return $this->dic()->http();
	}


	/**
	 * @return \ilDBInterface
	 */
	protected function db() {
		return $this->dic()->database();
	}


	/**
	 * @return \ilToolbarGUI
	 */
	protected function toolbar() {
		return $this->dic()->toolbar();
	}


	/**
	 * @return \ILIAS\DI\RBACServices
	 */
	protected function rbac() {
		return $this->dic()->rbac();
	}
}
