<#1>
<?php
require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php');
SRAG\Hub2\Origin\User\ARUserOrigin::installDB();
SRAG\Hub2\Object\User\ARUser::installDB();
SRAG\Hub2\Object\Course\ARCourse::installDB();
SRAG\Hub2\Object\Category\ARCategory::installDB();
\SRAG\Hub2\Object\Session\ARSession::installDB();
SRAG\Hub2\Config\ArConfig::installDB();
$config = new \SRAG\Hub2\Config\ArConfig();
$config->save();
?>