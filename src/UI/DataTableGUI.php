<?php

namespace srag\Plugins\Hub2\UI;

use ActiveRecordList;
use hub2DataGUI;
use ilCheckboxInputGUI;
use ilFormPropertyGUI;
use ilHub2Plugin;
use ilSelectInputGUI;
use ilTable2GUI;
use ilTextInputGUI;
use ReflectionClass;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Shortlink\ObjectLinkFactory;

/**
 * Class OriginsTableGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTableGUI extends ilTable2GUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const F_ORIGIN_ID = 'origin_id';
	const F_EXT_ID = 'ext_id';
	/**
	 * @var ObjectLinkFactory
	 */
	protected $originLinkfactory;
	/**
	 * @var array
	 */
	protected $filtered = [];
	/**
	 * @var OriginFactory
	 */
	protected $originFactory;
	/**
	 * @var int
	 */
	protected $a_parent_obj;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 * DataTableGUI constructor.
	 *
	 * @param hub2DataGUI $a_parent_obj
	 * @param string      $a_parent_cmd
	 */
	public function __construct(hub2DataGUI $a_parent_obj, $a_parent_cmd) {
		$this->a_parent_obj = $a_parent_obj;
		$this->originFactory = new OriginFactory();
		$this->originLinkfactory = new ObjectLinkFactory();
		$this->setPrefix('hub2_');
		$this->setId('origins');
		$this->setTitle(self::plugin()->translate('hub_origins'));
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setFormAction(self::dic()->ctrl()->getFormAction($a_parent_obj));
		$this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
		$this->initFilter();
		$this->initColumns();
		$this->initTableData();
	}


	/**
	 * @inheritDoc
	 */
	public function initFilter() {
		$origin = new ilSelectInputGUI(self::plugin()->translate('data_table_header_origin_id'), 'origin_id');
		$origin->setOptions($this->getAvailableOrigins());
		$this->addAndReadFilterItem($origin);

		// Status
		$status = new ilSelectInputGUI(self::plugin()->translate('data_table_header_status'), 'status');
		$status->setOptions($this->getAvailableStatus());
		$this->addAndReadFilterItem($status);

		$ext_id = new ilTextInputGUI(self::plugin()->translate('data_table_header_ext_id'), 'ext_id');
		$this->addAndReadFilterItem($ext_id);

		$data = new ilTextInputGUI(self::plugin()->translate('data_table_header_data'), 'data');
		$this->addAndReadFilterItem($data);
	}


	/**
	 * @param ilFormPropertyGUI $item
	 */
	protected function addAndReadFilterItem(ilFormPropertyGUI $item) {
		$this->addFilterItem($item);
		$item->readFromSession();
		if ($item instanceof ilCheckboxInputGUI) {
			$this->filtered[$item->getPostVar()] = $item->getChecked();
		} else {
			$this->filtered[$item->getPostVar()] = $item->getValue();
		}
	}


	/**
	 *
	 */
	protected function initColumns() {
		foreach ($this->getFields() as $field) {
			$this->addColumn(self::plugin()->translate('data_table_header_' . $field), $field);
		}
		$this->addColumn(self::plugin()->translate('data_table_header_view'));
	}


	/**
	 *
	 */
	protected function initTableData() {
		$fields = $this->getFields();
		$classes = [
			ARUser::class,
			ARCourse::class,
			ARGroup::class,
			ARSession::class,
			ARCategory::class,
			ARCourseMembership::class,
			ARGroupMembership::class,
			ARSessionMembership::class,
			AROrgUnit::class,
			AROrgUnitMembership::class,
		];
		$data = [];
		/**
		 * @var ActiveRecordList $collection
		 */
		foreach ($classes as $class) {
			$collection = $class::getCollection();
			foreach ($this->filtered as $postvar => $value) {
				if (!$postvar || !$value) {
					continue;
				}
				switch ($postvar) {
					case 'data':
					case 'ext_id':
						$str = "%{$value}%";
						$collection = $collection->where([ $postvar => $str ], 'LIKE');
						break;
					default:
						$collection = $collection->where([ $postvar => $value ]);
						break;
				}
			}
			$data = array_merge($data, $collection->getArray(NULL, $fields));
		}

		$this->setData($data);
	}


	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		self::dic()->ctrl()->setParameter($this->parent_obj, self::F_EXT_ID, $a_set[self::F_EXT_ID]);
		self::dic()->ctrl()->setParameter($this->parent_obj, self::F_ORIGIN_ID, $a_set[self::F_ORIGIN_ID]);

		$origin = $this->originFactory->getById($a_set[self::F_ORIGIN_ID]);

		foreach ($a_set as $key => $value) {
			$this->tpl->setCurrentBlock('cell');
			switch ($key) {
				case 'status':
					$this->tpl->setVariable('VALUE', $this->getAvailableStatus()[$value]);
					break;
				case self::F_EXT_ID:
					$this->tpl->setVariable('VALUE', $this->renderILIASLinkForIliasId($value, $origin));
					break;
				case self::F_ORIGIN_ID:
					if (!$origin) {
						$this->tpl->setVariable('VALUE', " Origin deleted");
					} else {
						$this->tpl->setVariable('VALUE', $origin->getTitle());
					}
					break;
				default:
					$this->tpl->setVariable('VALUE', $value ? $value : "&nbsp;");
					break;
			}

			$this->tpl->parseCurrentBlock();
		}

		// Adds view Glyph
		$factory = self::dic()->ui()->factory();
		$renderer = self::dic()->ui()->renderer();
		$modal = $factory->modal()->roundtrip($a_set[self::F_EXT_ID], $factory->legacy(''))->withAsyncRenderUrl(self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, 'renderData', '', true));

		$button = $factory->button()->shy(self::plugin()->translate("data_table_header_view"), "#")->withOnClick($modal->getShowSignal());

		$this->tpl->setCurrentBlock('cell');
		$this->tpl->setVariable('VALUE', $renderer->render([ $button, $modal ]));
		$this->tpl->parseCurrentBlock();

		self::dic()->ctrl()->clearParameters($this->parent_obj);
	}


	/**
	 * @param string  $ext_id
	 * @param IOrigin $origin
	 *
	 * @return string
	 */
	protected function renderILIASLinkForIliasId($ext_id, IOrigin $origin) {
		if (!$origin) {
			return $ext_id;
		}

		$link = $this->originLinkfactory->findByExtIdAndOrigin($ext_id, $origin);
		$button_factory = self::dic()->ui()->factory()->button();
		$renderer = self::dic()->ui()->renderer();

		return $renderer->render($button_factory->shy($ext_id, $link->getAccessGrantedInternalLink()));
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


	/**
	 * @return array
	 */
	private function getAvailableStatus() {
		static $status;
		if (is_array($status)) {
			return $status;
		}
		$r = new ReflectionClass(IObject::class);
		$status = [ 0 => self::plugin()->translate("data_table_all") ];
		foreach ($r->getConstants() as $name => $value) {
			if (strpos($name, "STATUS_") === 0) {
				$status[$value] = $name; // TODO: Translate status
			}
		}

		return $status;
	}


	/**
	 * @return array
	 */
	private function getAvailableOrigins() {
		static $origins;
		if (is_array($origins)) {
			return $origins;
		}

		$origins = [ 0 => self::plugin()->translate("data_table_all") ];
		foreach ($this->originFactory->getAll() as $origin) {
			$origins[$origin->getId()] = $origin->getTitle();
		}

		return $origins;
	}
}
