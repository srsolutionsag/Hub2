<?php

namespace srag\Plugins\Hub2\UI;

use hub2ConfigOriginsGUI;
use ilCheckboxInputGUI;
use ilFormSectionHeaderGUI;
use ilHiddenInputGUI;
use ilHub2Plugin;
use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\Properties\DTOPropertyParser;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginConfigFormGUI
 *
 * @package srag\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginConfigFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	const POST_VAR_ADHOC = "adhoc";
	protected $parent_gui;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 * @param hub2ConfigOriginsGUI $parent_gui
	 * @param IOriginRepository    $originRepository
	 * @param IOrigin              $origin
	 */
	public function __construct($parent_gui, IOriginRepository $originRepository, IOrigin $origin) {
		$this->parent_gui = $parent_gui;
		$this->origin = $origin;
		$this->originRepository = $originRepository;
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
		if (!$origin->getId()) {
			$this->addCommandButton(hub2ConfigOriginsGUI::CMD_CREATE_ORIGIN, self::plugin()->translate('button_save'));
			$this->setTitle(self::plugin()->translate('origin_form_title_add'));
		} else {
			$this->addCommandButton(hub2ConfigOriginsGUI::CMD_SAVE_ORIGIN, self::plugin()->translate('button_save'));
			$this->setTitle(self::plugin()->translate('origin_form_title_edit'));
		}
		$this->addCommandButton(hub2ConfigOriginsGUI::CMD_CANCEL, self::plugin()->translate('button_cancel'));
		parent::__construct();
	}


	/**
	 *
	 */
	protected function initForm() {
		$this->addGeneral();
		if ($this->origin->getId()) {
			$this->addConnectionConfig();
			$this->addSyncConfig();
			$this->addNotificationConfig();

			// Properties for object status: NEW, UPDATE, DELETE
			$header = new ilFormSectionHeaderGUI();
			$header->setTitle(self::plugin()->translate('common_on_status', "", [ self::plugin()->translate('common_on_status_new') ]));
			$this->addItem($header);
			$this->addPropertiesNew();

			$header = new ilFormSectionHeaderGUI();
			$header->setTitle(self::plugin()->translate('common_on_status', "", [ self::plugin()->translate('common_on_status_update') ]));
			$this->addItem($header);
			$this->addPropertiesUpdate();

			$header = new ilFormSectionHeaderGUI();
			$header->setTitle(self::plugin()->translate('common_on_status', "", [ self::plugin()->translate('common_on_status_delete') ]));
			$this->addItem($header);
			$this->addPropertiesDelete();
		}
	}


	/**
	 *
	 */
	protected function addPropertiesNew() {
	}


	/**
	 * By default, this method parses the DTO objects and presents a checkbox for each DTO property,
	 * meaning if this property should be updated on the user object, e.g. should the firstname of
	 * a user be updated?
	 * Subclasses using static properties should overwrite this method, add the static properties
	 * and call parent::addPropertiesUpdate() at the very end
	 */
	protected function addPropertiesUpdate() {
		$ucfirst = ucfirst($this->origin->getObjectType());
		$parser = new DTOPropertyParser("srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}DTO");
		foreach ($parser->getProperties() as $property) {
			$postVar = IOriginProperties::PREFIX_UPDATE_DTO . $property->name;
			$title = self::plugin()->translate('origin_form_field_update_dto', "", [ ucfirst($property->name) ]);
			$cb = new ilCheckboxInputGUI($title, $this->prop($postVar));
			if ($property->descriptionKey) {
				$cb->setInfo(self::plugin()->translate($property->descriptionKey));
			}
			$cb->setChecked($this->origin->properties()->updateDTOProperty($property->name));
			$this->addItem($cb);
		}
	}


	/**
	 *
	 */
	protected function addPropertiesDelete() {
	}


	/**
	 *
	 */
	protected function addNotificationConfig() {
		$h = new ilFormSectionHeaderGUI();
		$h->setTitle(self::plugin()->translate('origin_form_header_notification'));
		$this->addItem($h);
		$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_summary_email'), $this->conf(IOriginConfig::NOTIFICATION_SUMMARY));
		$te->setValue(implode(',', $this->origin->config()->getNotificationsSummary()));
		$te->setInfo(self::plugin()->translate('origin_form_comma_separated'));
		$this->addItem($te);
		$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_notification_email'), $this->conf(IOriginConfig::NOTIFICATION_ERRORS));
		$te->setValue(implode(',', $this->origin->config()->getNotificationsErrors()));
		$te->setInfo(self::plugin()->translate('origin_form_comma_separated'));
		$this->addItem($te);
	}


	/**
	 *
	 */
	protected function addConnectionConfig() {
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle(self::plugin()->translate('origin_form_header_connection'));
		$this->addItem($header);
		$ro = new ilRadioGroupInputGUI(self::plugin()->translate('origin_form_field_conf_type'), $this->conf(IOriginConfig::CONNECTION_TYPE));
		$ro->setValue($this->origin->config()->getConnectionType());
		{
			$db = new ilRadioOption(self::plugin()->translate('origin_form_field_conf_type_file'), IOriginConfig::CONNECTION_TYPE_FILE, self::plugin()
				->translate('origin_form_field_conf_type_file_info'));
			{
				$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_conf_type_file_path'), $this->conf(IOriginConfig::FILE_PATH));
				$te->setValue($this->origin->config()->getFilePath());
				$db->addSubItem($te);
			}
			$ro->addOption($db);
			$file = new ilRadioOption(self::plugin()
				->translate('origin_form_field_conf_type_db'), IOriginConfig::CONNECTION_TYPE_SERVER, self::plugin()
				->translate('origin_form_field_conf_type_db_info'));
			{
				$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_conf_type_db_host'), $this->conf(IOriginConfig::SERVER_HOST));
				$te->setValue($this->origin->config()->getServerHost());
				$file->addSubItem($te);
				$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_conf_type_db_port'), $this->conf(IOriginConfig::SERVER_PORT));
				$te->setValue($this->origin->config()->getServerPort());
				$file->addSubItem($te);
				$te = new ilTextInputGUI(self::plugin()
					->translate('origin_form_field_conf_type_db_username'), $this->conf(IOriginConfig::SERVER_USERNAME));
				$te->setValue($this->origin->config()->getServerUsername());
				$file->addSubItem($te);
				$te = new ilTextInputGUI(self::plugin()
					->translate('origin_form_field_conf_type_db_password'), $this->conf(IOriginConfig::SERVER_PASSWORD));
				$te->setValue($this->origin->config()->getServerPassword());
				$file->addSubItem($te);
				$te = new ilTextInputGUI(self::plugin()
					->translate('origin_form_field_conf_type_db_database'), $this->conf(IOriginConfig::SERVER_DATABASE));
				$te->setValue($this->origin->config()->getServerDatabase());
				$file->addSubItem($te);
				$te = new ilTextInputGUI(self::plugin()
					->translate('origin_form_field_conf_type_db_search_base'), $this->conf(IOriginConfig::SERVER_SEARCH_BASE));
				$te->setValue($this->origin->config()->getServerSearchBase());
				$file->addSubItem($te);
			}
			$ro->addOption($file);
			$external = new ilRadioOption(self::plugin()
				->translate('origin_form_field_conf_type_external'), IOriginConfig::CONNECTION_TYPE_EXTERNAL, self::plugin()
				->translate('origin_form_field_conf_type_external_info'));
			$ro->addOption($external);
		}
		$this->addItem($ro);
	}


	/**
	 *
	 */
	protected function addSyncConfig() {
		$h = new ilFormSectionHeaderGUI();
		$h->setTitle(self::plugin()->translate('origin_form_header_sync'));
		$this->addItem($h);

		$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_class_name'), 'implementation_class_name');
		$te->setInfo(nl2br(self::plugin()
			->translate('origin_form_field_class_name_info', "", [ ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH) ]), false));
		$te->setValue($this->origin->getImplementationClassName());
		$te->setRequired(true);
		$this->addItem($te);

		$te = new ilTextInputGUI(self::plugin()->translate('origin_form_field_namespace'), 'implementation_namespace');
		$te->setInfo(self::plugin()->translate('origin_form_field_namespace_info'));
		$te->setValue($this->origin->getImplementationNamespace());
		$te->setRequired(true);
		$this->addItem($te);

		$se = new ilSelectInputGUI(self::plugin()->translate('com_prop_link_to_origin'), $this->conf(IOriginConfig::LINKED_ORIGIN_ID));
		$options = [ '' => '' ];
		foreach ($this->originRepository->all() as $origin) {
			if ($origin->getId() == $this->origin->getId()) {
				continue;
			}
			$options[$origin->getId()] = $origin->getTitle();
		}
		$se->setOptions($options);
		$se->setValue($this->origin->config()->getLinkedOriginId());
		$this->addItem($se);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('com_prop_check_amount'), $this->conf(IOriginConfig::CHECK_AMOUNT));
		$cb->setInfo(self::plugin()->translate('com_prop_check_amount_info'));
		$cb->setChecked($this->origin->config()->getCheckAmountData());

		$se = new ilSelectInputGUI(self::plugin()
			->translate('com_prop_check_amount_percentage'), $this->conf(IOriginConfig::CHECK_AMOUNT_PERCENTAGE));
		$options = [];
		for ($i = 10; $i <= 100; $i += 10) {
			$options[$i] = "$i%";
		}
		$se->setOptions($options);
		$se->setValue($this->origin->config()->getCheckAmountDataPercentage());
		$cb->addSubItem($se);
		$this->addItem($cb);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('com_prop_shortlink'), $this->conf(IOriginConfig::SHORT_LINK));
		$cb->setChecked($this->origin->config()->useShortLink());
		$subcb = new ilCheckboxInputGUI(self::plugin()->translate('com_prop_force_login'), $this->conf(IOriginConfig::SHORT_LINK_FORCE_LOGIN));
		$subcb->setChecked($this->origin->config()->useShortLinkForcedLogin());
		$cb->addSubItem($subcb);
		$this->addItem($cb);

		$te = new ilTextInputGUI(self::plugin()->translate('origin_from_field_active_period'), $this->conf(IOriginConfig::ACTIVE_PERIOD));
		$te->setInfo(self::plugin()->translate('origin_from_field_active_period_info'));
		$te->setValue($this->origin->config()->getActivePeriod());
		$this->addItem($te);
	}


	/**
	 *
	 */
	protected function addGeneral() {
		if ($this->origin->getId()) {
			$item = new ilNonEditableValueGUI();
			$item->setTitle('ID');
			$item->setValue($this->origin->getId());
			$this->addItem($item);
			$item = new ilHiddenInputGUI('origin_id');
			$item->setValue($this->origin->getId());
			$this->addItem($item);
		}
		$item = new ilTextInputGUI(self::plugin()->translate('origin_title'), 'title');
		$item->setValue($this->origin->getTitle());
		$item->setRequired(true);
		$this->addItem($item);
		$item = new ilTextAreaInputGUI(self::plugin()->translate('origin_description'), 'description');
		$item->setValue($this->origin->getDescription());
		$item->setRequired(true);
		$this->addItem($item);
		if ($this->origin->getId()) {
			$item = new ilNonEditableValueGUI();
			$item->setTitle(self::plugin()->translate('origin_form_field_usage_type'));
			$item->setValue(self::plugin()->translate("origin_object_type_" . $this->origin->getObjectType()));
			$this->addItem($item);
			$item = new ilCheckboxInputGUI(self::plugin()->translate("origin_form_field_adhoc"), self::POST_VAR_ADHOC);
			$item->setChecked($this->origin->isAdHoc());
			$item->setInfo(self::plugin()->translate("origin_form_field_adhoc_info"));
            $this->addItem($item);
            $item = new ilCheckboxInputGUI(self::plugin()->translate('origin_form_field_active'), 'active');
			$item->setChecked($this->origin->isActive());
			$this->addItem($item);
		} else {
			$item = new ilSelectInputGUI(self::plugin()->translate('origin_form_field_usage_type'), 'object_type');
			$item->setRequired(true);
			$options = [];
			foreach (AROrigin::$object_types as $type) {
				$options[$type] = self::plugin()->translate('origin_object_type_' . $type);
			}
			$item->setOptions($options);
			$this->addItem($item);
		}
	}


	/**
	 * @param string $postVar
	 *
	 * @return string
	 */
	protected function prop($postVar) {
		return 'prop_' . $postVar;
	}


	/**
	 * @param string $postVar
	 *
	 * @return string
	 */
	protected function conf($postVar) {
		return 'config_' . $postVar;
	}
}
