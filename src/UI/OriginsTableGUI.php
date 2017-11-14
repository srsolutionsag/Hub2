<?php namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Object\IObjectRepository;
use SRAG\Plugins\Hub2\Origin\IOriginRepository;

/**
 * class OriginsTableGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\UI
 */
class OriginsTableGUI extends \ilTable2GUI {

	/**
	 * @var int
	 */
	protected $a_parent_obj;
	protected $pl;
	protected $originRepository;


	/**
	 * @param                   $a_parent_obj
	 * @param string            $a_parent_cmd
	 * @param IOriginRepository $originRepository
	 *
	 * @internal param
	 */
	public function __construct($a_parent_obj, $a_parent_cmd, IOriginRepository $originRepository) {
		global $DIC;
		$this->pl = \ilHub2Plugin::getInstance();
		$this->originRepository = $originRepository;
		$this->a_parent_obj = $a_parent_obj;
		$this->setPrefix('hub2_');
		$this->setId('origins');
		$this->setTitle($this->pl->txt('hub_origins'));
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setFormAction($DIC->ctrl()->getFormAction($a_parent_obj));
		$this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
		$this->initColumns();
		$this->initTableData();
		$this->addCommandButton('run', $this->pl->txt('origin_table_button_run'));
		//		$this->addCommandButton('dryRun', $this->pl->txt('origin_table_button_dryrun'));
		$this->addCommandButton('deactivateAll', $this->pl->txt('origin_table_button_deactivate_all'));
		$this->addCommandButton('activateAll', $this->pl->txt('origin_table_button_activate_all'));
	}


	protected function initColumns() {
		$this->addColumn('ID', 'id');
		$this->addColumn($this->pl->txt('origin_table_header_active'), 'active');
		$this->addColumn($this->pl->txt('origin_table_header_title'), 'title');
		$this->addColumn($this->pl->txt('origin_table_header_description'), 'description');
		$this->addColumn($this->pl->txt('origin_table_header_usage_type'), 'object_type');
		$this->addColumn($this->pl->txt('origin_table_header_last_update'), 'last_sync');
		$this->addColumn($this->pl->txt('origin_table_header_count'), 'n_objects');
		$this->addColumn($this->pl->txt('common_actions'));
	}


	protected function initTableData() {
		$data = [];
		foreach ($this->originRepository->all() as $origin) {
			$class = "SRAG\\Plugins\\Hub2\\Object\\" . ucfirst($origin->getObjectType()) . "\\" . ucfirst($origin->getObjectType()) . "Repository";
			/** @var IObjectRepository $objectRepository */
			$objectRepository = new $class($origin);
			$row = [];
			$row['id'] = $origin->getId();
			$row['active'] = $origin->isActive();
			$row['title'] = $origin->getTitle();
			$row['description'] = $origin->getDescription();
			$row['object_type'] = $origin->getObjectType();
			$row['last_sync'] = $origin->getLastRun();
			$row['n_objects'] = $objectRepository->count();
			$data[] = $row;
		}
		$this->setData($data);
	}


	protected function fillRow($a_set) {
		foreach ($a_set as $key => $value) {
			$this->tpl->setCurrentBlock('cell');
			$this->tpl->setVariable('VALUE', $value);
			$this->tpl->parseCurrentBlock();
		}
		global $DIC;
		$actions = new \ilAdvancedSelectionListGUI();
		$actions->setId('actions_' . $a_set['id']);
		$actions->setListTitle($this->pl->txt('common_actions'));
		$DIC->ctrl()->setParameter($this->parent_obj, 'origin_id', $a_set['id']);
		$actions->addItem($this->pl->txt('common_edit'), 'edit', $DIC->ctrl()
		                                                             ->getLinkTarget($this->parent_obj, 'editOrigin'));
		$actions->addItem($this->pl->txt('common_delete'), 'delete', $DIC->ctrl()
		                                                                 ->getLinkTarget($this->parent_obj, 'confirmDelete'));
		$actions->addItem($this->pl->txt('origin_table_button_run'), 'runOriginSync', $DIC->ctrl()
		                                                                                  ->getLinkTarget($this->parent_obj, 'runOriginSync'));
		$DIC->ctrl()->clearParameters($this->parent_obj);
		$this->tpl->setCurrentBlock('cell');
		$this->tpl->setVariable('VALUE', $actions->getHTML());
		$this->tpl->parseCurrentBlock();
	}
}