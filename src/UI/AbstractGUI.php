<?php

namespace SRAG\Plugins\Hub2\UI;

use ilCtrl;
use ilTabsGUI;
use ilTemplate;
use SRAG\Plugins\Hub2\Helper\DIC;

/**
 * Class AbstractGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @deprecated
 */
abstract class AbstractGUI {

	use DIC;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;


	public function __construct() {
		$this->tpl = $this->ui()->mainTemplate();
		$this->tabs = $this->tabs();
		$this->ctrl = $this->ctrl();
	}
}
