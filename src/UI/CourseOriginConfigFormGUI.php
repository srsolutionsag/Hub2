<?php

namespace SRAG\Plugins\Hub2\UI;

use ilCheckboxInputGUI;
use ilEMailInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use SRAG\Plugins\Hub2\Origin\Config\ICourseOriginConfig;
use SRAG\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use SRAG\Plugins\Hub2\Origin\Properties\CourseOriginProperties;

/**
 * Class CourseOriginConfigFormGUI
 *
 * @package SRAG\Plugins\Hub2\UI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginConfigFormGUI extends OriginConfigFormGUI {

	/**
	 * @var ARCourseOrigin
	 */
	protected $origin;


	protected function addSyncConfig() {
		// Extend shortlink
		//		$shortlink = $this->getItemByPostVar($this->prop(IOriginConfig::LINKED_ORIGIN_ID));
		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_check_online'), hubCourseFields::F_SL_CHECK_ONLINE);
		//		$msg = new ilTextAreaInputGUI(self::plugin()->translate('crs_prop_' . hubCourseFields::F_MSG_NOT_ONLINE), hubCourseFields::F_MSG_NOT_ONLINE);
		//		$msg->setRows(2);
		//		$msg->setCols(100);
		//		$cb->addSubItem($msg);
		//		$shortlink->addSubItem($cb);
		//
		parent::addSyncConfig();
		$te = new ilTextInputGUI(self::plugin()->translate('crs_prop_node_noparent'), $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$te->setInfo(self::plugin()->translate('crs_prop_node_noparent_info'));
		$te->setValue($this->origin->properties()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
		$this->addItem($te);
	}


	protected function addPropertiesNew() {
		parent::addPropertiesNew();
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_activate'), $this->prop(CourseOriginProperties::SET_ONLINE));
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_activate'), $this->prop(CourseOriginProperties::SET_ONLINE));
		$cb->setChecked($this->origin->properties()->get(CourseOriginProperties::SET_ONLINE));
		$this->addItem($cb);

		//		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_create_icon'), $this->prop(CourseOriginProperties::CREATE_ICON));
		//		$this->addItem($cb);

		$send_mail = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_send_notification'), $this->prop(CourseOriginProperties::SEND_CREATE_NOTIFICATION));
		$send_mail->setInfo(self::plugin()->translate('crs_prop_send_notification_info'));
		$send_mail->setChecked($this->origin->properties()->get(CourseOriginProperties::SEND_CREATE_NOTIFICATION));
		$notification_subject = new ilTextInputGUI(self::plugin()->translate('crs_prop_notification_subject'), $this->prop(CourseOriginProperties::CREATE_NOTIFICATION_SUBJECT));
		$notification_subject->setValue($this->origin->properties()->get(CourseOriginProperties::CREATE_NOTIFICATION_SUBJECT));

		$send_mail->addSubItem($notification_subject);
		$notification_body = new ilTextAreaInputGUI(self::plugin()->translate('crs_prop_notification_body'), $this->prop(CourseOriginProperties::CREATE_NOTIFICATION_BODY));
		$notification_body->setInfo(CourseOriginProperties::getPlaceHolderStrings());
		$notification_body->setRows(6);
		$notification_body->setCols(100);
		$notification_body->setValue($this->origin->properties()->get(CourseOriginProperties::CREATE_NOTIFICATION_BODY));
		$send_mail->addSubItem($notification_body);
		$notification_from = new ilEMailInputGUI(self::plugin()->translate('crs_prop_notification_from'), $this->prop(CourseOriginProperties::CREATE_NOTIFICATION_FROM));
		$notification_from->setValue($this->origin->properties()->get(CourseOriginProperties::CREATE_NOTIFICATION_FROM));
		$send_mail->addSubItem($notification_from);
		$this->addItem($send_mail);
	}


	protected function addPropertiesUpdate() {
		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_move'), $this->prop(CourseOriginProperties::MOVE_COURSE));
		$cb->setInfo(self::plugin()->translate('crs_prop_move_info'));
		$this->addItem($cb);

		$cb = new ilCheckboxInputGUI(self::plugin()->translate('crs_prop_reactivate'), $this->prop(CourseOriginProperties::SET_ONLINE_AGAIN));
		$cb->setChecked($this->origin->properties()->get(CourseOriginProperties::SET_ONLINE_AGAIN));
		$this->addItem($cb);

		parent::addPropertiesUpdate();
	}


	protected function addPropertiesDelete() {
		$delete = new ilRadioGroupInputGUI(self::plugin()->translate('crs_prop_delete_mode'), $this->prop(CourseOriginProperties::DELETE_MODE));
		$delete->setValue($this->origin->properties()->get(CourseOriginProperties::DELETE_MODE));

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_none'), CourseOriginProperties::DELETE_MODE_NONE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_inactive'), CourseOriginProperties::DELETE_MODE_OFFLINE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_delete'), CourseOriginProperties::DELETE_MODE_DELETE);
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_delete_or_inactive'), CourseOriginProperties::DELETE_MODE_DELETE_OR_OFFLINE);
		$opt->setInfo(self::plugin()->translate('crs_prop_delete_mode_delete_or_inactive_info'));
		$delete->addOption($opt);

		$opt = new ilRadioOption(self::plugin()->translate('crs_prop_delete_mode_trash'), CourseOriginProperties::DELETE_MODE_MOVE_TO_TRASH);
		$delete->addOption($opt);

		$this->addItem($delete);
		parent::addPropertiesDelete();
	}
}
