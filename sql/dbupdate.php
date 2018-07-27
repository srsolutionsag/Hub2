<#1>
<?php
require_once "Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php";

SRAG\Plugins\Hub2\Origin\User\ARUserOrigin::updateDB();
SRAG\Plugins\Hub2\Object\User\ARUser::updateDB();
SRAG\Plugins\Hub2\Object\Course\ARCourse::updateDB();
SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::updateDB();
SRAG\Plugins\Hub2\Object\Category\ARCategory::updateDB();
SRAG\Plugins\Hub2\Object\Session\ARSession::updateDB();
SRAG\Plugins\Hub2\Object\Group\ARGroup::updateDB();
SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::updateDB();
SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::updateDB();
SRAG\Plugins\Hub2\Config\ArConfig::updateDB();

$config = new \SRAG\Plugins\Hub2\Config\ArConfig();
$config->save();
?>
<#2>
<?php
require_once "Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php";

global $DIC;
$ilDB = $DIC->database();

$ilDB->modifyTableColumn(SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::TABLE_NAME, 'ilias_id', array(
	"type" => "text",
	"length" => 256,
));
$ilDB->modifyTableColumn(SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::TABLE_NAME, 'ilias_id', array(
	"type" => "text",
	"length" => 256,
));
$ilDB->modifyTableColumn(SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::TABLE_NAME, 'ilias_id', array(
	"type" => "text",
	"length" => 256,
));
?>
<#3>
<?php
require_once "Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php";

use SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;

AROrgUnit::updateDB();
AROrgUnitMembership::updateDB();
?>
