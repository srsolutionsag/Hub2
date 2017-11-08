<?php
namespace SRAG\Hub2\UI;

/**
 * Class AbstractGUI
 *
 * @package SRAG\Hub2\UI
 */
abstract class AbstractGUI {

	/**
	 * @var \ilTemplate
	 */
	protected $tpl;
	/**
	 * @var \ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var \ilCtrl
	 */
	protected $ctrl;


	public function __construct() {
		global $DIC;
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->tabs = $DIC->tabs();
		$this->ctrl = $DIC->ctrl();
	}
}