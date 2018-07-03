<?php

namespace SRAG\Plugins\Hub2\Helper;

use ilCtrl;
use ilDBInterface;
use ILIAS\DI\Container;
use ILIAS\DI\HTTPServices;
use ILIAS\DI\RBACServices;
use ILIAS\DI\UIServices;
use ILIAS\Filesystem\Filesystems;
use ilLanguage;
use ilLog;
use ilMailMimeSenderFactory;
use ilObjUser;
use ilSetting;
use ilTabsGUI;
use ilTemplate;
use ilToolbarGUI;
use ilTree;

/**
 * Trait DIC
 *
 * @package SRAG\Plugins\Hub2\Helper
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait DIC {

	/**
	 * @return Container
	 */
	public function dic() {
		return $GLOBALS['DIC'];
	}


	/**
	 * @return ilCtrl
	 */
	protected function ctrl() {
		return $this->dic()->ctrl();
	}


	/*/* *
	 * @param string $variable
	 *
	 * @return string
	 * /
	public function txt($variable) {
		return ilHub2Plugin::getInstance()->txt($variable);
	}*/

	/**
	 * @return ilTemplate
	 */
	protected function tpl() {
		return $this->dic()->ui()->mainTemplate();
	}


	/**
	 * @return ilLanguage
	 */
	protected function lng() {
		return $this->dic()->language();
	}


	/**
	 * @return ilTabsGUI
	 */
	protected function tabs() {
		return $this->dic()->tabs();
	}


	/**
	 * @return UIServices
	 */
	protected function ui() {
		return $this->dic()->ui();
	}


	/**
	 * @return ilObjUser
	 */
	protected function user() {
		return $this->dic()->user();
	}


	/**
	 * @return HTTPServices
	 */
	protected function http() {
		return $this->dic()->http();
	}


	/**
	 * @return ilDBInterface
	 */
	protected function db() {
		return $this->dic()->database();
	}


	/**
	 * @return ilToolbarGUI
	 */
	protected function toolbar() {
		return $this->dic()->toolbar();
	}


	/**
	 * @return RBACServices
	 */
	protected function rbac() {
		return $this->dic()->rbac();
	}


	/**
	 * @return ilTree
	 */
	protected function tree() {
		return $this->dic()->repositoryTree();
	}


	/**
	 * @return Filesystems
	 */
	protected function filesystem() {
		return $this->dic()->filesystem();
	}


	/**
	 * @return ilMailMimeSenderFactory
	 */
	protected function mailMimeSenderFactory() {
		return $this->dic()["mail.mime.sender.factory"];
	}


	/**
	 * @return ilLog
	 */
	protected function ilLog() {
		return $this->dic()["ilLog"];
	}


	/**
	 * @return ilSetting
	 */
	protected function settings() {
		return $this->dic()->settings();
	}
}
