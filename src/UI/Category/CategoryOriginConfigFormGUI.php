<?php

namespace srag\Plugins\Hub2\UI\Category;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Category\ARCategoryOrigin;
use srag\Plugins\Hub2\Origin\Config\Category\ICategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class CategoryOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCategoryOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $te = new ilTextInputGUI(
            $this->plugin->txt('cat_prop_base_node_ilias'),
            $this->conf(ICategoryOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo($this->plugin->txt('cat_prop_base_node_ilias_info'));
        $te->setValue($this->origin->config()->getParentRefIdIfNoParentIdFound());
        $this->addItem($te);

        $te = new ilTextInputGUI(
            $this->plugin->txt('cat_prop_base_node_external'),
            $this->conf(ICategoryOriginConfig::EXT_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo($this->plugin->txt('cat_prop_base_node_external_info'));
        $te->setValue($this->origin->config()->getExternalParentIdIfNoParentIdFound());
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('cat_prop_set_news'),
            $this->prop(CategoryProperties::SHOW_NEWS)
        );
        $cb->setChecked($this->origin->properties()->get(CategoryProperties::SHOW_NEWS));
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('cat_prop_set_infopage'),
            $this->prop(CategoryProperties::SHOW_INFO_TAB)
        );
        $cb->setChecked($this->origin->properties()->get(CategoryProperties::SHOW_INFO_TAB));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('cat_prop_move'),
            $this->prop(CategoryProperties::MOVE_CATEGORY)
        );
        $cb->setChecked($this->origin->properties()->get(CategoryProperties::MOVE_CATEGORY));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('cat_prop_delete_mode'),
            $this->prop(CategoryProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(CategoryProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            $this->plugin->txt('cat_prop_delete_mode_none'),
            CategoryProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            sprintf(
                $this->plugin->txt('cat_prop_delete_mode_inactive'),
                $this->plugin->txt('com_prop_mark_deleted_text')
            ),
            CategoryProperties::DELETE_MODE_MARK
        );
        $delete->addOption($opt);

        $te = new ilTextInputGUI(
            $this->plugin->txt('cat_prop_delete_mode_inactive_text'),
            $this->prop(CategoryProperties::DELETE_MODE_MARK_TEXT)
        );
        $te->setValue($this->origin->properties()->get(CategoryProperties::DELETE_MODE_MARK_TEXT));
        $opt->addSubItem($te);

        $opt = new ilRadioOption(
            $this->plugin->txt('cat_prop_delete_mode_delete'),
            CategoryProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
