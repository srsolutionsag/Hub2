<?php

namespace SRAG\Plugins\Hub2\UI;

use SRAG\Plugins\Hub2\Object\Course\ARCourse;
use SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Plugins\Hub2\Object\Group\ARGroup;
use SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use SRAG\Plugins\Hub2\Object\IObjectRepository;
use SRAG\Plugins\Hub2\Object\Session\ARSession;
use SRAG\Plugins\Hub2\Object\User\ARUser;
use SRAG\Plugins\Hub2\Origin\IOriginRepository;
use SRAG\Plugins\Hub2\Origin\OriginFactory;

/**
 * class OriginsTableGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTableGUI extends \ilTable2GUI {

	const F_ORIGIN_ID = 'origin_id';
	const F_EXT_ID = 'ext_id';
	/**
	 * @var \SRAG\Plugins\Hub2\Origin\OriginFactory
	 */
	protected $originFactory;
	/**
	 * @var int
	 */
	protected $a_parent_obj;
	protected $pl;
	protected $originRepository;


	/**
	 * DataTableGUI constructor.
	 *
	 * @param object $a_parent_obj
	 * @param string $a_parent_cmd
	 */
	public function __construct($a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->pl = \ilHub2Plugin::getInstance();
		$this->a_parent_obj = $a_parent_obj;
		$this->originFactory = new OriginFactory($DIC->database());
		$this->setPrefix('hub2_');
		$this->setId('origins');
		$this->setTitle($this->pl->txt('hub_origins'));
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setFormAction($DIC->ctrl()->getFormAction($a_parent_obj));
		$this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
		$this->initFilter();
		$this->initColumns();
		$this->initTableData();
	}


	/**
	 * @inheritDoc
	 */
	public function initFilter() {
		// Status
		$status = new \ilSelectInputGUI($this->pl->txt('data_table_header_status'), 'status');

		$this->addFilterItem($status);
	}


	protected function initColumns() {
		$this->addColumn($this->pl->txt('data_table_header_view'));
		foreach ($this->getFields() as $field) {
			$this->addColumn($this->pl->txt('data_table_header_' . $field), $field);
		}
		//		$this->addColumn($this->pl->txt('data_table_header_actions'));
	}


	protected function initTableData() {
		$fields = $this->getFields();
		$users = ARUser::getArray(null, $fields);
		$courses = ARCourse::getArray(null, $fields);
		$course_members = ARCourseMembership::getArray(null, $fields);
		$groups = ARGroup::getArray(null, $fields);
		$group_members = ARGroupMembership::getArray(null, $fields);
		$sessions = ARSession::getArray(null, $fields);

		$this->setData(array_merge($users, $courses, $course_members, $groups, $group_members, $sessions));
	}


	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		global $DIC;
		$DIC->ctrl()->setParameter($this->parent_obj, self::F_EXT_ID, $a_set[self::F_EXT_ID]);
		$DIC->ctrl()->setParameter($this->parent_obj, self::F_ORIGIN_ID, $a_set[self::F_ORIGIN_ID]);

		// Adds view Glyph
		$factory = $DIC->ui()->factory();
		$renderer = $DIC->ui()->renderer();
		$modal = $factory->modal()
		                 ->roundtrip($a_set[self::F_EXT_ID], $factory->legacy(''))
		                 ->withAsyncRenderUrl($DIC->ctrl()
		                                          ->getLinkTarget($this->parent_obj, 'renderData', '', true));

		$button = $factory->button()->shy("View", "")->withOnClick($modal->getShowSignal());

		$this->tpl->setCurrentBlock('cell');
		$this->tpl->setVariable('VALUE', $renderer->render([ $button, $modal ]));
		$this->tpl->parseCurrentBlock();

		foreach ($a_set as $key => $value) {
			$this->tpl->setCurrentBlock('cell');
			switch ($key) {
				case 'status':
					$this->tpl->setVariable('VALUE', $this->pl->txt('status_' . $value));
					break;
				case self::F_ORIGIN_ID:
					$origin = $this->originFactory->getById($value);
					$this->tpl->setVariable('VALUE', $origin->getTitle());
					break;
				default:
					$this->tpl->setVariable('VALUE', $value);
					break;
			}

			$this->tpl->parseCurrentBlock();
		}

		$DIC->ctrl()->clearParameters($this->parent_obj);

		return;

		$actions = new \ilAdvancedSelectionListGUI();
		$actions->setId('actions_' . $a_set['id']);
		$actions->setListTitle($this->pl->txt('common_actions'));

		$actions->addItem($this->pl->txt('common_edit'), 'edit', $DIC->ctrl()
		                                                             ->getLinkTarget($this->parent_obj, 'editOrigin'));
		$actions->addItem($this->pl->txt('common_delete'), 'delete', $DIC->ctrl()
		                                                                 ->getLinkTarget($this->parent_obj, 'confirmDelete'));
		$actions->addItem($this->pl->txt('origin_table_button_run'), 'runOriginSync', $DIC->ctrl()
		                                                                                  ->getLinkTarget($this->parent_obj, 'runOriginSync'));

		$this->tpl->setCurrentBlock('cell');
		$this->tpl->setVariable('VALUE', $actions->getHTML());
		$this->tpl->parseCurrentBlock();
	}


	/**
	 * @return array
	 */
	protected function getFields() {
		$fields = [
			self::F_ORIGIN_ID,
			self::F_EXT_ID,
			'delivery_date',
			'processed_date',
			'ilias_id',
			'status',
			'period',
		];

		return $fields;
	}
}