<#1>
<?php

require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php');
SRAG\Plugins\Hub2\Origin\User\ARUserOrigin::installDB(); // Installs for all
SRAG\Plugins\Hub2\Object\User\ARUser::installDB();
SRAG\Plugins\Hub2\Object\Course\ARCourse::installDB();
SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::installDB();
SRAG\Plugins\Hub2\Object\Category\ARCategory::installDB();
SRAG\Plugins\Hub2\Object\Session\ARSession::installDB();
SRAG\Plugins\Hub2\Object\Group\ARGroup::installDB();
SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::installDB();
SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::installDB();
SRAG\Plugins\Hub2\Config\ArConfig::installDB();
$config = new \SRAG\Plugins\Hub2\Config\ArConfig();
$config->save();
?>
<#2>
<?php
use SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
global $ilDB;
$ilDB->modifyTableColumn(ARCourseMembership::returnDbTableName(), 'ilias_id', array(
	"type"   => "text",
	"length" => 256,
));
$ilDB->modifyTableColumn(ARSessionMembership::returnDbTableName(), 'ilias_id', array(
	"type"   => "text",
	"length" => 256,
));
$ilDB->modifyTableColumn(ARGroupMembership::returnDbTableName(), 'ilias_id', array(
	"type"   => "text",
	"length" => 256,
));
?>