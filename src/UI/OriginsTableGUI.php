<?php

namespace SRAG\Plugins\Hub2\UI;

use hub2ConfigOriginsGUI;
use ilAdvancedSelectionListGUI;
use ilHub2Plugin;
use ilTable2GUI;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Object\IObjectRepository;
use SRAG\Plugins\Hub2\Origin\IOriginRepository;

/**
 * Class OriginsTableGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginsTableGUI extends ilTable2GUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var int
	 */
	protected $a_parent_obj;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 * @param hub2ConfigOriginsGUI $a_parent_obj
	 * @param string               $a_parent_cmd
	 * @param IOriginRepository    $originRepository
	 *
	 * @internal param
	 */
	public function __construct($a_parent_obj, $a_parent_cmd, IOriginRepository $originRepository) {
		$this->originRepository = $originRepository;
		$this->a_parent_obj = $a_parent_obj;
		$this->setPrefix('hub2_');
		$this->setId('origins');
		$this->setTitle(self::plugin()->translate('hub_origins'));
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setFormAction(self::dic()->ctrl()->getFormAction($a_parent_obj));
		$this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
		$this->initColumns();
		$this->initTableData();
		$this->addCommandButton(hub2ConfigOriginsGUI::CMD_DEACTIVATE_ALL, self::plugin()->translate('origin_table_button_deactivate_all'));
		$this->addCommandButton(hub2ConfigOriginsGUI::CMD_ACTIVATE_ALL, self::plugin()->translate('origin_table_button_activate_all'));
	}


	/**
	 *
	 */
	protected function initColumns() {
		$this->addColumn('ID', 'id');
		$this->addColumn(self::plugin()->translate('origin_table_header_active'), 'active');
		$this->addColumn(self::plugin()->translate('origin_table_header_title'), 'title');
		$this->addColumn(self::plugin()->translate('origin_table_header_description'), 'description');
		$this->addColumn(self::plugin()->translate('origin_table_header_usage_type'), 'object_type');
		$this->addColumn(self::plugin()->translate('origin_table_header_last_update'), 'last_sync');
		$this->addColumn(self::plugin()->translate('origin_table_header_count'), 'n_objects');
		$this->addColumn(self::plugin()->translate('common_actions'));
	}


	/**
	 *
	 */
	protected function initTableData() {
		$data = [];
		foreach ($this->originRepository->all() as $origin) {
			$class = "SRAG\\Plugins\\Hub2\\Object\\" . ucfirst($origin->getObjectType()) . "\\" . ucfirst($origin->getObjectType()) . "Repository";
			/** @var IObjectRepository $objectRepository */
			$objectRepository = new $class($origin);
			$row = [];
			$row['id'] = $origin->getId();
			$row['active'] = self::plugin()->translate("common_" . ($origin->isActive() ? "yes" : "no"));
			$row['title'] = $origin->getTitle();
			$row['description'] = $origin->getDescription();
			$row['object_type'] = $origin->getObjectType(); // TODO: Translate object type
			$row['last_sync'] = $origin->getLastRun();
			$row['n_objects'] = $objectRepository->count();
			$data[] = $row;
		}
		$this->setData($data);
	}


	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		foreach ($a_set as $key => $value) {
			$this->tpl->setCurrentBlock('cell');
			$this->tpl->setVariable('VALUE', !is_null($value) ? $value : "&nbsp;");
			$this->tpl->parseCurrentBlock();
		}
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setId('actions_' . $a_set['id']);
		$actions->setListTitle(self::plugin()->translate('common_actions'));
		self::dic()->ctrl()->setParameter($this->parent_obj, 'origin_id', $a_set['id']);
		$actions->addItem(self::plugin()->translate('common_edit'), 'edit', self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_EDIT_ORGIN));
		$actions->addItem(self::plugin()->translate('common_delete'), 'delete', self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_CONFIRM_DELETE));
		$actions->addItem(self::plugin()->translate('origin_table_button_run'), 'runOriginSync', self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_RUN_ORIGIN_SYNC));
		self::dic()->ctrl()->clearParameters($this->parent_obj);
		$this->tpl->setCurrentBlock('cell');
		$this->tpl->setVariable('VALUE', $actions->getHTML());
		$this->tpl->parseCurrentBlock();
	}
}
