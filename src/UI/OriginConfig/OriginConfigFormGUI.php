<?php

namespace srag\Plugins\Hub2\UI\OriginConfig;

use hub2ConfigOriginsGUI;
use hub2MainGUI;
use ilCheckboxInputGUI;
use ilFormSectionHeaderGUI;
use ilHiddenInputGUI;
use ilHub2Plugin;
use ilNonEditableValueGUI;
use ilNumberInputGUI;
use ilPropertyFormGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilRepositorySelector2InputGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\CourseMembership\ICourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Group\IGroupOrigin;
use srag\Plugins\Hub2\Origin\GroupMembership\IGroupMembershipOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\Properties\DTOPropertyParser;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Origin\Session\ISessionOrigin;
use srag\Plugins\Hub2\Origin\SessionMembership\ISessionMembershipOrigin;
use srag\Plugins\Hub2\FileDrop\Handler;
use srag\Plugins\Hub2\FileDrop\Token;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;

/**
 * Class OriginConfigFormGUI
 * @package      srag\Plugins\Hub2\UI\OriginConfig
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginConfigFormGUI extends ilPropertyFormGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const POST_VAR_ADHOC = "adhoc";
    public const POST_VAR_SORT = "sort";
    public const PLUGIN_BASE = 'Customizing/global/plugins/Services/Cron/CronHook/Hub2';
    /**
     * @var Token
     */
    private $token;
    /**
     * @var ilHub2Plugin
     */
    protected $plugin;
    /**
     * @var \srag\Plugins\Hub2\FileDrop\ResourceStorage\ResourceStorage
     */
    protected $file_storage;

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
     */
    public function __construct($parent_gui, IOriginRepository $originRepository, IOrigin $origin)
    {
        global $DIC;
        $this->plugin = ilHub2Plugin::getInstance();
        parent::__construct();
        $this->parent_gui = $parent_gui;
        $this->lng =
        $this->origin = $origin;
        $this->originRepository = $originRepository;
        $this->token = new Token();
        $this->file_storage = (new Factory())->storage();
        $this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
        $this->initForm();
        if ($origin->getId() === 0) {
            $this->addCommandButton(hub2ConfigOriginsGUI::CMD_CREATE_ORIGIN, $this->translate('button_save'));
            $this->setTitle($this->translate('origin_form_title_add'));
        } else {
            $this->addCommandButton(hub2ConfigOriginsGUI::CMD_SAVE_ORIGIN, $this->translate('button_save'));
            $this->setTitle($this->translate('origin_form_title_edit'));
        }
        $this->addCommandButton(hub2ConfigOriginsGUI::CMD_CANCEL, $this->translate('button_cancel'));
    }

    /**
     * @deprecated get rid of those self::plugin things
     */
    private function translate(string $key, array $placeholders = []) : string
    {
        return sprintf($this->plugin->txt($key), ...$placeholders);
    }

    /**
     *
     */
    protected function initForm()
    {
        $this->addGeneral();
        if ($this->origin->getId() !== 0) {
            $this->addConnectionConfig();
            $this->addSyncConfig();
            $this->addNotificationConfig();

            // Properties for object status: NEW, UPDATE, DELETE
            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(
                $this->translate(
                    'common_on_status',
                    [$this->translate('common_on_status_new')]
                )
            );
            $this->addItem($header);
            $this->addPropertiesNew();

            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(
                $this->translate(
                    'common_on_status',
                    [$this->translate('common_on_status_update')]
                )
            );
            $this->addItem($header);
            $this->addPropertiesUpdate();

            $header = new ilFormSectionHeaderGUI();
            $header->setTitle(
                $this->translate(
                    'common_on_status',
                    [$this->translate('common_on_status_delete')]
                )
            );
            $this->addItem($header);
            $this->addPropertiesDelete();
        }
    }

    /**
     *
     */
    protected function addPropertiesNew()
    {
    }

    /**
     * By default, this method parses the DTO objects and presents a checkbox for each DTO property,
     * meaning if this property should be updated on the user object, e.g. should the firstname of
     * a user be updated?
     * Subclasses using static properties should overwrite this method, add the static properties
     * and call parent::addPropertiesUpdate() at the very end
     */
    protected function addPropertiesUpdate()
    {
        $ucfirst = ucfirst($this->origin->getObjectType());
        $parser = new DTOPropertyParser("srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}DTO");
        foreach ($parser->getProperties() as $property) {
            $postVar = IOriginProperties::PREFIX_UPDATE_DTO . $property->name;
            $title = $this->translate('origin_form_field_update_dto', [ucfirst($property->name)]);
            $cb = new ilCheckboxInputGUI($title, $this->prop($postVar));
            if ($property->descriptionKey !== '' && $property->descriptionKey !== '0') {
                $cb->setInfo($this->translate($property->descriptionKey));
            }
            $cb->setChecked($this->origin->properties()->updateDTOProperty($property->name));
            $this->addItem($cb);
        }
    }

    /**
     *
     */
    protected function addPropertiesDelete()
    {
    }

    /**
     *
     */
    protected function addNotificationConfig()
    {
        $h = new ilFormSectionHeaderGUI();
        $h->setTitle($this->translate('origin_form_header_notification'));
        $this->addItem($h);
        $te = new ilTextInputGUI(
            $this->translate('origin_form_field_summary_email'),
            $this->conf(IOriginConfig::NOTIFICATION_SUMMARY)
        );
        $te->setValue(implode(',', $this->origin->config()->getNotificationsSummary()));
        $te->setInfo($this->translate('origin_form_comma_separated'));
        $this->addItem($te);
        $te = new ilTextInputGUI(
            $this->translate('origin_form_field_notification_email'),
            $this->conf(IOriginConfig::NOTIFICATION_ERRORS)
        );
        $te->setValue(implode(',', $this->origin->config()->getNotificationsErrors()));
        $te->setInfo($this->translate('origin_form_comma_separated'));
        $this->addItem($te);
    }

    /**
     *
     */
    protected function addConnectionConfig()
    {
        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->translate('origin_form_header_connection'));
        $this->addItem($header);
        $ro = new ilRadioGroupInputGUI(
            $this->translate('origin_form_field_conf_type'),
            $this->conf(IOriginConfig::CONNECTION_TYPE)
        );
        $ro->setValue($this->origin->config()->getConnectionType());

        {
            // by Path
            $by_path = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_path'),
                IOriginConfig::CONNECTION_TYPE_PATH,
                $this->translate('origin_form_field_conf_type_path_info')
            );
            {
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_path_path'),
                    $this->conf(IOriginConfig::PATH)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::PATH));
                $by_path->addSubItem($te);
            }
            $ro->addOption($by_path);

            // By Database
            $by_database = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_db'),
                IOriginConfig::CONNECTION_TYPE_SERVER,
                $this->translate('origin_form_field_conf_type_db_info')
            );
            {
                // Database Config Fields
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_host'),
                    $this->conf(IOriginConfig::SERVER_HOST)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_HOST));
                $by_database->addSubItem($te);
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_port'),
                    $this->conf(IOriginConfig::SERVER_PORT)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_PORT));
                $by_database->addSubItem($te);
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_username'),
                    $this->conf(IOriginConfig::SERVER_USERNAME)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_USERNAME));
                $by_database->addSubItem($te);
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_password'),
                    $this->conf(IOriginConfig::SERVER_PASSWORD)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_PASSWORD));
                $by_database->addSubItem($te);
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_database'),
                    $this->conf(IOriginConfig::SERVER_DATABASE)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_DATABASE));
                $by_database->addSubItem($te);
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_search_base'),
                    $this->conf(IOriginConfig::SERVER_SEARCH_BASE)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_SEARCH_BASE));
                $by_database->addSubItem($te);
            }
            $ro->addOption($by_database);

            // by External Data
            $external = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_external'),
                IOriginConfig::CONNECTION_TYPE_EXTERNAL,
                $this->translate('origin_form_field_conf_type_external_info')
            );
            $ro->addOption($external);

            // by ILIAS File
            $ilias_file = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_ilias_file'),
                IOriginConfig::CONNECTION_TYPE_ILIAS_FILE,
                $this->translate('origin_form_field_conf_type_ilias_file_info')
            );
            $ilias_file->addSubItem($this->getILIASFileRepositorySelector());
            $ro->addOption($ilias_file);

            // by FileDrop
            $filedrop = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_filedrop'),
                IOriginConfig::CONNECTION_TYPE_FILE_DROP,
                $this->translate('origin_form_field_conf_type_filedrop_info')
            );
            {
                $url_info = new ilNonEditableValueGUI($this->translate('origin_form_field_conf_type_filedrop_url'));
                $url_info->setValue(Handler::getURL('o' . $this->origin->getId()));
                $filedrop->addSubItem($url_info);

                $method = new ilNonEditableValueGUI($this->translate('origin_form_field_conf_type_filedrop_method'));
                $method->setValue(Handler::METHOD);
                $filedrop->addSubItem($method);

                $auth_token = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_filedrop_auth_token'),
                    $this->conf(IOriginConfig::FILE_DROP_AUTH_TOKEN)
                );
                $auth_token->setValue(
                    $this->origin->config()->get(IOriginConfig::FILE_DROP_AUTH_TOKEN) ?? $this->token->generate()
                );
                $filedrop->addSubItem($auth_token);

                $this->addRIDSection($filedrop, 'filedrop');
            }
            $ro->addOption($filedrop);

            // By API
            $api = new ilRadioOption(
                $this->translate('origin_form_field_conf_type_api'),
                IOriginConfig::CONNECTION_TYPE_API,
                $this->translate('origin_form_field_conf_type_api_info')
            );
            {
                $te = new ilTextInputGUI(
                    $this->translate('origin_form_field_conf_type_db_host'),
                    $this->conf(IOriginConfig::SERVER_HOST)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_HOST));
                $api->addSubItem($te);

                $te = new ilTextAreaInputGUI(
                    $this->translate('origin_form_field_conf_type_api_token'),
                    $this->conf(IOriginConfig::SERVER_PASSWORD)
                );
                $te->setValue($this->origin->config()->get(IOriginConfig::SERVER_PASSWORD));
                $api->addSubItem($te);

                $this->addRIDSection($api, 'api');
            }


            $ro->addOption($api);
        }
        $this->addItem($ro);
    }

    public function getILIASFileRepositorySelector() : ilRepositorySelector2InputGUI
    {
        $this->ctrl->setParameterByClass(
            hub2MainGUI::class,
            hub2ConfigOriginsGUI::ORIGIN_ID,
            $this->origin->getId()
        );

        $ilias_file_selector = new ilRepositorySelector2InputGUI(
            $this->translate("origin_form_field_conf_type_ilias_file"),
            $this->conf(IOriginConfig::ILIAS_FILE_REF_ID)
        );

        $ilias_file_selector->getExplorerGUI()->setSelectableTypes(["file"]);

        $ilias_file_selector->setValue($this->origin->config()->get(IOriginConfig::ILIAS_FILE_REF_ID));

        return $ilias_file_selector;
    }

    /**
     *
     */
    protected function addSyncConfig()
    {
        $h = new ilFormSectionHeaderGUI();
        $h->setTitle($this->translate('origin_form_header_sync'));
        $this->addItem($h);

        $te = new ilTextInputGUI(
            $this->translate('origin_form_field_class_name'),
            'implementation_class_name'
        );
        $te->setInfo(
            nl2br(
                str_replace(
                    "\\n",
                    "\n",
                    $this->translate(
                        'origin_form_field_class_name_info',
                        [ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH)]
                    )
                ),
                false
            )
        );
        $te->setValue($this->origin->getImplementationClassName());
        $te->setRequired(true);
        $this->addItem($te);

        $te = new ilTextInputGUI($this->translate('origin_form_field_namespace'), 'implementation_namespace');
        $te->setInfo($this->translate('origin_form_field_namespace_info'));
        $te->setValue($this->origin->getImplementationNamespace());
        $te->setRequired(true);
        $this->addItem($te);

        $se = new ilSelectInputGUI(
            $this->translate('com_prop_link_to_origin'),
            $this->conf(IOriginConfig::LINKED_ORIGIN_ID)
        );
        $options = ['' => ''];
        foreach ($this->originRepository->all() as $origin) {
            if ($origin->getId() === $this->origin->getId()) {
                continue;
            }
            $options[$origin->getId()] = $origin->getTitle();
        }
        $se->setOptions($options);
        $se->setValue($this->origin->config()->getLinkedOriginId());
        $this->addItem($se);

        $cb = new ilCheckboxInputGUI(
            $this->translate('com_prop_check_amount'),
            $this->conf(IOriginConfig::CHECK_AMOUNT)
        );
        $cb->setInfo($this->translate('com_prop_check_amount_info'));
        $cb->setChecked($this->origin->config()->getCheckAmountData());

        $se = new ilSelectInputGUI(
            $this->translate('com_prop_check_amount_percentage'),
            $this->conf(IOriginConfig::CHECK_AMOUNT_PERCENTAGE)
        );
        $options = [];
        for ($i = 10; $i <= 100; $i += 10) {
            $options[$i] = "$i%";
        }
        $se->setOptions($options);
        $se->setValue($this->origin->config()->getCheckAmountDataPercentage());
        $cb->addSubItem($se);
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            $this->translate('com_prop_shortlink'),
            $this->conf(IOriginConfig::SHORT_LINK)
        );
        $cb->setChecked($this->origin->config()->useShortLink());
        $subcb = new ilCheckboxInputGUI(
            $this->translate('com_prop_force_login'),
            $this->conf(IOriginConfig::SHORT_LINK_FORCE_LOGIN)
        );
        $subcb->setChecked($this->origin->config()->useShortLinkForcedLogin());
        $cb->addSubItem($subcb);
        $this->addItem($cb);

        $te = new ilTextInputGUI(
            $this->translate('origin_from_field_active_period'),
            $this->conf(IOriginConfig::ACTIVE_PERIOD)
        );
        $te->setInfo($this->translate('origin_from_field_active_period_info'));
        $te->setValue($this->origin->config()->getActivePeriod());
        $this->addItem($te);
    }

    /**
     *
     */
    protected function addGeneral()
    {
        if ($this->origin->getId() !== 0) {
            $item = new ilNonEditableValueGUI();
            $item->setTitle($this->translate("origin_id"));
            $item->setValue($this->origin->getId());
            $this->addItem($item);
            $item = new ilHiddenInputGUI('origin_id');
            $item->setValue($this->origin->getId());
            $this->addItem($item);

            $item = new ilNumberInputGUI($this->translate("origin_sort"), self::POST_VAR_SORT);
            $item->setValue($this->origin->getSort());
            $this->addItem($item);
        }
        $item = new ilTextInputGUI($this->translate('origin_title'), 'title');
        $item->setValue($this->origin->getTitle());
        $item->setRequired(true);
        $this->addItem($item);
        $item = new ilTextAreaInputGUI($this->translate('origin_description'), 'description');
        $item->setValue($this->origin->getDescription());
        $item->setRequired(true);
        $this->addItem($item);
        if ($this->origin->getId() !== 0) {
            $item = new ilNonEditableValueGUI();
            $item->setTitle($this->translate('origin_form_field_usage_type'));
            $item->setValue($this->translate("origin_object_type_" . $this->origin->getObjectType()));
            $this->addItem($item);
            $item = new ilCheckboxInputGUI($this->translate("origin_form_field_adhoc"), self::POST_VAR_ADHOC);
            $item->setChecked($this->origin->isAdHoc());
            $item->setInfo($this->translate("origin_form_field_adhoc_info"));

            if ($this->hasOriginAdHocParentScope()) {
                $subitem = new ilCheckboxInputGUI(
                    $this->translate("origin_form_field_adhoc_parent_scope"),
                    "adhoc_parent_scope"
                );
                $subitem->setChecked($this->origin->isAdhocParentScope());
                $subitem->setInfo($this->translate("origin_form_field_adhoc_parent_scope_info"));
                $item->addSubItem($subitem);
            }

            $this->addItem($item);
            $item = new ilCheckboxInputGUI($this->translate('origin_form_field_active'), 'active');
            $item->setChecked($this->origin->isActive());
            $this->addItem($item);
        } else {
            $item = new ilSelectInputGUI($this->translate('origin_form_field_usage_type'), 'object_type');
            $item->setRequired(true);
            $options = [];
            foreach (AROrigin::$object_types as $type) {
                $options[$type] = $this->translate('origin_object_type_' . $type);
            }
            $item->setOptions($options);
            $this->addItem($item);
        }
    }

    /**
     * @return bool
     */
    protected function hasOriginAdHocParentScope()
    {
        switch (true) {
            case $this->origin instanceof ICourseMembershipOrigin:
            case $this->origin instanceof IGroupOrigin:
            case $this->origin instanceof IGroupMembershipOrigin:
            case $this->origin instanceof ISessionOrigin:
            case $this->origin instanceof ISessionMembershipOrigin:
                return true;
            default:
                return false;
        }
    }

    /**
     * @param string $postVar
     * @return string
     */
    protected function prop($postVar)
    {
        return 'prop_' . $postVar;
    }

    /**
     * @param string $postVar
     * @return string
     */
    protected function conf($postVar)
    {
        return 'config_' . $postVar;
    }

    private function getLinkToResource(string $resource_identification) : string
    {
        $this->ctrl->setParameterByClass(hub2ConfigOriginsGUI::class, 'rid', $resource_identification);
        return "<a href=\"{$this->ctrl->getLinkTarget($this->parent_gui, hub2ConfigOriginsGUI::CMD_DOWNLOAD_RID)}\">{$resource_identification}</a>";
    }

    /**
     * @param ilRadioOption $filedrop
     * @return void
     */
    protected function addRIDSection(ilRadioOption $filedrop, string $parent_section) : void
    {
        $rid = new ilNonEditableValueGUI(
            $this->translate('origin_form_field_conf_type_filedrop_rid'),
            "",
            true
        );
        $resource_identification = $this->origin->config()->get(IOriginConfig::FILE_DROP_RID);
        $rid_link = $resource_identification === null
            ? $this->translate(
                'origin_form_field_conf_type_filedrop_rid_nya'
            )
            : $this->getLinkToResource($resource_identification);
        $rid->setValue($rid_link);
        $filedrop->addSubItem($rid);

        if ($resource_identification !== null) {
            $latest_file = new ilNonEditableValueGUI(
                $this->translate('origin_form_field_conf_type_filedrop_latest'),
                "",
                true
            );
            $resource_info = $this->file_storage->getRevisionInfo($resource_identification);
            $latest_file->setValue($resource_info['creation_date'] ?? '');
            $filedrop->addSubItem($latest_file);

            // new fileupload-field for manual upload
            if ($this->origin->config()->getConnectionType() !== IOriginConfig::CONNECTION_TYPE_API) {
                $file = new \ilFileInputGUI(
                    $this->translate('origin_form_field_conf_type_filedrop_file'),
                    'manual_file_drop_' . $parent_section
                );
                $file->setRequired(false);
                $filedrop->addSubItem($file);
            }
        }
    }
}
