<?php

namespace SRAG\Plugins\Hub2\UI;

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
use SRAG\Plugins\Hub2\Config\IHubConfig;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginRepository;
use SRAG\Plugins\Hub2\Origin\Properties\DTOPropertyParser;
use SRAG\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Class OriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginConfigFormGUI extends ilPropertyFormGUI {

	use DIC;
	protected $parent_gui;
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IHubConfig
	 */
	protected $hubConfig;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 * @param hub2ConfigOriginsGUI $parent_gui
	 * @param IHubConfig           $hubConfig
	 * @param IOriginRepository    $originRepository
	 * @param IOrigin              $origin
	 */
	public function __construct($parent_gui, IHubConfig $hubConfig, IOriginRepository $originRepository, IOrigin $origin) {
		$this->parent_gui = $parent_gui;
		$this->pl = ilHub2Plugin::getInstance();
		$this->hubConfig = $hubConfig;
		$this->origin = $origin;
		$this->originRepository = $originRepository;
		$this->setFormAction($this->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
		if (!$origin->getId()) {
			$this->addCommandButton(hub2ConfigOriginsGUI::CMD_CREATE_ORIGIN, $this->pl->txt('button_save'));
			$this->setTitle($this->pl->txt('origin_form_title_add'));
		} else {
			$this->addCommandButton(hub2ConfigOriginsGUI::CMD_SAVE_ORIGIN, $this->pl->txt('button_save'));
			$this->setTitle($this->pl->txt('origin_form_title_edit'));
		}
		$this->addCommandButton(hub2ConfigOriginsGUI::CMD_CANCEL, $this->pl->txt('button_cancel'));
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
			$header->setTitle(sprintf($this->pl->txt('common_on_status'), $this->pl->txt('common_on_status_new')));
			$this->addItem($header);
			$this->addPropertiesNew();

			$header = new ilFormSectionHeaderGUI();
			$header->setTitle(sprintf($this->pl->txt('common_on_status'), $this->pl->txt('common_on_status_update')));
			$this->addItem($header);
			$this->addPropertiesUpdate();

			$header = new ilFormSectionHeaderGUI();
			$header->setTitle(sprintf($this->pl->txt('common_on_status'), $this->pl->txt('common_on_status_delete')));
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
		$parser = new DTOPropertyParser("SRAG\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}DTO");
		foreach ($parser->getProperties() as $property) {
			$postVar = IOriginProperties::PREFIX_UPDATE_DTO . $property->name;
			$title = sprintf($this->pl->txt('origin_form_field_update_dto'), ucfirst($property->name));
			$cb = new ilCheckboxInputGUI($title, $this->prop($postVar));
			if ($property->descriptionKey) {
				$cb->setInfo($this->pl->txt($property->descriptionKey));
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
		$h->setTitle($this->pl->txt('origin_form_header_notification'));
		$this->addItem($h);
		$te = new ilTextInputGUI($this->pl->txt('origin_form_field_summary_email'), $this->conf(IOriginConfig::NOTIFICATION_SUMMARY));
		$te->setValue(implode(',', $this->origin->config()->getNotificationsSummary()));
		$te->setInfo($this->pl->txt('origin_form_comma_separated'));
		$this->addItem($te);
		$te = new ilTextInputGUI($this->pl->txt('origin_form_field_notification_email'), $this->conf(IOriginConfig::NOTIFICATION_ERRORS));
		$te->setValue(implode(',', $this->origin->config()->getNotificationsErrors()));
		$te->setInfo($this->pl->txt('origin_form_comma_separated'));
		$this->addItem($te);
	}


	/**
	 *
	 */
	protected function addConnectionConfig() {
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle($this->pl->txt('origin_form_header_connection'));
		$this->addItem($header);
		$ro = new ilRadioGroupInputGUI($this->pl->txt('origin_form_field_conf_type'), $this->conf(IOriginConfig::CONNECTION_TYPE));
		$ro->setValue($this->origin->config()->getConnectionType());
		{
			$db = new ilRadioOption($this->pl->txt('origin_form_field_conf_type_file'), IOriginConfig::CONNECTION_TYPE_FILE, $this->pl->txt('origin_form_field_conf_type_file_info'));
			{
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_file_path'), $this->conf(IOriginConfig::FILE_PATH));
				$te->setValue($this->origin->config()->getFilePath());
				$db->addSubItem($te);
			}
			$ro->addOption($db);
			$file = new ilRadioOption($this->pl->txt('origin_form_field_conf_type_db'), IOriginConfig::CONNECTION_TYPE_SERVER, $this->pl->txt('origin_form_field_conf_type_db_info'));
			{
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_host'), $this->conf(IOriginConfig::SERVER_HOST));
				$te->setValue($this->origin->config()->getServerHost());
				$file->addSubItem($te);
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_port'), $this->conf(IOriginConfig::SERVER_PORT));
				$te->setValue($this->origin->config()->getServerPort());
				$file->addSubItem($te);
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_username'), $this->conf(IOriginConfig::SERVER_USERNAME));
				$te->setValue($this->origin->config()->getServerUsername());
				$file->addSubItem($te);
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_password'), $this->conf(IOriginConfig::SERVER_PASSWORD));
				$te->setValue($this->origin->config()->getServerPassword());
				$file->addSubItem($te);
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_database'), $this->conf(IOriginConfig::SERVER_DATABASE));
				$te->setValue($this->origin->config()->getServerDatabase());
				$file->addSubItem($te);
				$te = new ilTextInputGUI($this->pl->txt('origin_form_field_conf_type_db_search_base'), $this->conf(IOriginConfig::SERVER_SEARCH_BASE));
				$te->setValue($this->origin->config()->getServerSearchBase());
				$file->addSubItem($te);
			}
			$ro->addOption($file);
			$external = new ilRadioOption($this->pl->txt('origin_form_field_conf_type_external'), IOriginConfig::CONNECTION_TYPE_EXTERNAL, $this->pl->txt('origin_form_field_conf_type_external_info'));
			$ro->addOption($external);
		}
		$this->addItem($ro);
	}


	/**
	 *
	 */
	protected function addSyncConfig() {
		$h = new ilFormSectionHeaderGUI();
		$h->setTitle($this->pl->txt('origin_form_header_sync'));
		$this->addItem($h);

		$te = new ilTextInputGUI($this->pl->txt('origin_form_field_class_name'), 'implementation_class_name');
		$te->setInfo(sprintf($this->pl->txt('origin_form_field_class_name_info'), $this->hubConfig->getOriginImplementationsPath()));
		$te->setValue($this->origin->getImplementationClassName());
		$te->setRequired(true);
		$this->addItem($te);

		$te = new ilTextInputGUI($this->pl->txt('origin_form_field_namespace'), 'implementation_namespace');
		$te->setInfo($this->pl->txt('origin_form_field_namespace_info'));
		$te->setValue($this->origin->getImplementationNamespace());
		$te->setRequired(true);
		$this->addItem($te);

		$se = new ilSelectInputGUI($this->pl->txt('com_prop_link_to_origin'), $this->conf(IOriginConfig::LINKED_ORIGIN_ID));
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

		$cb = new ilCheckboxInputGUI($this->pl->txt('com_prop_check_amount'), $this->conf(IOriginConfig::CHECK_AMOUNT));
		$cb->setInfo($this->pl->txt('com_prop_check_amount_info'));
		$cb->setChecked($this->origin->config()->getCheckAmountData());

		$se = new ilSelectInputGUI($this->pl->txt('com_prop_check_amount_percentage'), $this->conf(IOriginConfig::CHECK_AMOUNT_PERCENTAGE));
		$options = [];
		for ($i = 10; $i <= 100; $i += 10) {
			$options[$i] = "$i%";
		}
		$se->setOptions($options);
		$se->setValue($this->origin->config()->getCheckAmountDataPercentage());
		$cb->addSubItem($se);
		$this->addItem($cb);

		$cb = new ilCheckboxInputGUI($this->pl->txt('com_prop_shortlink'), $this->conf(IOriginConfig::SHORT_LINK));
		$cb->setChecked($this->origin->config()->useShortLink());
		$subcb = new ilCheckboxInputGUI($this->pl->txt('com_prop_force_login'), $this->conf(IOriginConfig::SHORT_LINK_FORCE_LOGIN));
		$subcb->setChecked($this->origin->config()->useShortLinkForcedLogin());
		$cb->addSubItem($subcb);
		$this->addItem($cb);

		$te = new ilTextInputGUI($this->pl->txt('origin_from_field_active_period'), $this->conf(IOriginConfig::ACTIVE_PERIOD));
		$te->setInfo($this->pl->txt('origin_from_field_active_period_info'));
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
		$item = new ilTextInputGUI($this->pl->txt('origin_title'), 'title');
		$item->setValue($this->origin->getTitle());
		$item->setRequired(true);
		$this->addItem($item);
		$item = new ilTextAreaInputGUI($this->pl->txt('origin_description'), 'description');
		$item->setValue($this->origin->getDescription());
		$item->setRequired(true);
		$this->addItem($item);
		if ($this->origin->getId()) {
			$item = new ilNonEditableValueGUI();
			$item->setTitle($this->pl->txt('origin_form_field_usage_type'));
			$item->setValue($this->origin->getObjectType()); // TODO: Translate object type
			$this->addItem($item);
			$item = new ilCheckboxInputGUI($this->pl->txt('origin_form_field_active'), 'active');
			$item->setChecked($this->origin->isActive());
			$this->addItem($item);
		} else {
			$item = new ilSelectInputGUI($this->pl->txt('origin_form_field_usage_type'), 'object_type');
			$item->setRequired(true);
			$options = [];
			foreach (AROrigin::$object_types as $type) {
				$options[$type] = $this->pl->txt('origin_object_type_' . $type);
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
