<?php

namespace srag\Plugins\Hub2\UI\Course;

use ilCheckboxInputGUI;
use ilEMailInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\Course\ICourseOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class CourseOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCourseOrigin
     */
    protected $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        // Extend shortlink
        //		$shortlink = $this->getItemByPostVar($this->prop(IOriginConfig::LINKED_ORIGIN_ID));
        //		$cb = new ilCheckboxInputGUI($this->plugin->txt('crs_prop_check_online'), hubCourseFields::F_SL_CHECK_ONLINE);
        //		$msg = new ilTextAreaInputGUI($this->plugin->txt('crs_prop_' . hubCourseFields::F_MSG_NOT_ONLINE), hubCourseFields::F_MSG_NOT_ONLINE);
        //		$msg->setRows(2);
        //		$msg->setCols(100);
        //		$cb->addSubItem($msg);
        //		$shortlink->addSubItem($cb);
        //

        $te = new ilTextInputGUI(
            $this->plugin->txt('crs_prop_node_noparent'),
            $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo($this->plugin->txt('crs_prop_node_noparent_info'));
        $te->setValue($this->origin->config()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('crs_prop_activate'),
            $this->prop(CourseProperties::SET_ONLINE)
        );
        $cb->setChecked($this->origin->properties()->get(CourseProperties::SET_ONLINE));
        $this->addItem($cb);

        //		$cb = new ilCheckboxInputGUI($this->plugin->txt('crs_prop_create_icon'), $this->prop(CourseOriginProperties::CREATE_ICON));
        //		$this->addItem($cb);

        $send_mail = new ilCheckboxInputGUI(
            $this->plugin->txt('crs_prop_send_notification'),
            $this->prop(CourseProperties::SEND_CREATE_NOTIFICATION)
        );
        $send_mail->setInfo($this->plugin->txt('crs_prop_send_notification_info'));
        $send_mail->setChecked($this->origin->properties()->get(CourseProperties::SEND_CREATE_NOTIFICATION));
        $notification_subject = new ilTextInputGUI(
            $this->plugin->txt('crs_prop_notification_subject'),
            $this->prop(CourseProperties::CREATE_NOTIFICATION_SUBJECT)
        );
        $notification_subject->setValue(
            $this->origin->properties()->get(CourseProperties::CREATE_NOTIFICATION_SUBJECT)
        );

        $send_mail->addSubItem($notification_subject);
        $notification_body = new ilTextAreaInputGUI(
            $this->plugin->txt('crs_prop_notification_body'),
            $this->prop(CourseProperties::CREATE_NOTIFICATION_BODY)
        );
        $notification_body->setInfo(CourseProperties::getPlaceHolderStrings());
        $notification_body->setRows(6);
        $notification_body->setCols(100);
        $notification_body->setValue($this->origin->properties()->get(CourseProperties::CREATE_NOTIFICATION_BODY));
        $send_mail->addSubItem($notification_body);
        $notification_from = new ilEMailInputGUI(
            $this->plugin->txt('crs_prop_notification_from'),
            $this->prop(CourseProperties::CREATE_NOTIFICATION_FROM)
        );
        $notification_from->setValue($this->origin->properties()->get(CourseProperties::CREATE_NOTIFICATION_FROM));
        $send_mail->addSubItem($notification_from);
        $this->addItem($send_mail);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('crs_prop_move'),
            $this->prop(CourseProperties::MOVE_COURSE)
        );
        $cb->setChecked($this->origin->properties()->get(CourseProperties::MOVE_COURSE));

        $cb->setInfo($this->plugin->txt('crs_prop_move_info'));
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            $this->plugin->txt('crs_prop_reactivate'),
            $this->prop(CourseProperties::SET_ONLINE_AGAIN)
        );
        $cb->setChecked($this->origin->properties()->get(CourseProperties::SET_ONLINE_AGAIN));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            $this->plugin->txt('crs_prop_delete_mode'),
            $this->prop(CourseProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(CourseProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_none'),
            CourseProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_inactive'),
            CourseProperties::DELETE_MODE_OFFLINE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_delete'),
            CourseProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_delete_or_inactive'),
            CourseProperties::DELETE_MODE_DELETE_OR_OFFLINE
        );
        $opt->setInfo(
            nl2br(
                str_replace(
                    "\\n",
                    "\n",
                    $this->plugin->txt('crs_prop_delete_mode_delete_or_inactive_info')
                ),
                false
            )
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            $this->plugin->txt('crs_prop_delete_mode_trash'),
            CourseProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
