<?php namespace SRAG\Hub2\UI;


abstract class AbstractGUI {


	protected $tpl;
	protected $tabs;
	protected $ctrl;

	public function __construct() {
		global $DIC;
		$this->tpl = $DIC['template'];
		$this->tabs = $DIC['ilTabs'];
		$this->ctrl = $DIC['ilCtrl'];

	}


}