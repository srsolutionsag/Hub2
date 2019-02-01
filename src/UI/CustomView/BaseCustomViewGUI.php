<?php

namespace srag\Plugins\Hub2\UI\CustomView;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class BaseCustomViewGUI
 *
 * @package srag\Plugins\Hub2\UI\CustomView
 *
 * @author  Timon Amstutz
 */
abstract class BaseCustomViewGUI {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var CustomViewGUI
	 */
	protected $parent_gui;


	/**
	 * BaseCustomViewGUI constructor
	 *
	 * @param CustomViewGUI $parent_gui
	 */
	public function __construct(CustomViewGUI $parent_gui) {
		$this->parent_gui = $parent_gui;
	}


	/**
	 *
	 */
	public abstract function executeCommand()/*: void*/
	;
}
