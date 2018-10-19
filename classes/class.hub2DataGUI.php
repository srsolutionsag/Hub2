<?php

use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\UI\DataTableGUI;

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class hub2DataGUI
 *
 * @package
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2DataGUI extends hub2MainGUI {

	/**
	 *
	 */
	public function executeCommand() {
		$this->initTabs();
		$cmd = self::dic()->ctrl()->getCmd(self::CMD_INDEX);
		$this->{$cmd}();
	}


	/**
	 *
	 */
	protected function index() {
		$table = new DataTableGUI($this, self::CMD_INDEX);
		self::dic()->mainTemplate()->setContent($table->getHTML());
	}


	/**
	 *
	 */
	protected function applyFilter() {
		$table = new DataTableGUI($this, self::CMD_INDEX);
		$table->writeFilterToSession();
		$table->resetOffset();
		self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
	}


	/**
	 *
	 */
	protected function resetFilter() {
		$table = new DataTableGUI($this, self::CMD_INDEX);
		$table->resetFilter();
		$table->resetOffset();
		self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
	}


	/**
	 *
	 */
	protected function initTabs() {
		self::dic()->tabs()->activateSubTab(hub2ConfigOriginsGUI::SUBTAB_DATA);
	}


	/**
	 *
	 */
	protected function renderData() {
		$ext_id = self::dic()->http()->request()->getQueryParams()[DataTableGUI::F_EXT_ID];
		$origin_id = self::dic()->http()->request()->getQueryParams()[DataTableGUI::F_ORIGIN_ID];

		$origin_factory = new OriginFactory();
		$object_factory = new ObjectFactory($origin_factory->getById($origin_id));

		$object = $object_factory->undefined($ext_id);

		$factory = self::dic()->ui()->factory();

		$properties = array_merge([
			"period" => $object->getPeriod(),
			"delivery_date" => $object->getDeliveryDate()->format(DATE_ATOM),
			"processed_date" => $object->getProcessedDate()->format(DATE_ATOM),
			"ilias_id" => $object->getILIASId(),
			"status" => $object->getStatus(),
		], $object->getData());

		if ($object instanceof IMetadataAwareObject) {
			foreach ($object->getMetaData() as $metadata) {
				$properties[self::plugin()->translate("table_md", "", [ $metadata->getIdentifier() ])] = $metadata->getValue();
			}
		}

		if ($object instanceof ITaxonomyAwareObject) {
			foreach ($object->getTaxonomies() as $taxonomy) {
				$properties[self::plugin()->translate("table_tax", "", [ $taxonomy->getTitle() ])] = implode(", ", $taxonomy->getNodeTitlesAsArray());
			}
		}

		$filtered = [];
		foreach ($properties as $key => $property) {
			if (!is_null($property)) {
				if (is_array($property)) {
					$filtered[$key] = implode(',', $property);
				} else {
					$filtered[$key] = (string)$property;
				}
			}
			if ($property === '') {
				$filtered[$key] = "&nbsp;";
			}
		}

		ksort($filtered);

		// Unfortunately the item suchs in rendering in Modals, therefore we take a descriptive listing
		$data_table = $factory->item()->standard(self::plugin()->translate("data_table_ext_id", "", [ $object->getExtId() ]))->withProperties($filtered);

		$data_table = $factory->listing()->descriptive($filtered);

		$renderer = self::dic()->ui()->renderer();

		$modal = $factory->modal()->roundtrip(self::plugin()->translate("data_table_hash", "", [ $object->getHashCode() ]), $data_table);

		echo $renderer->renderAsync($modal);
		exit;
	}
}
