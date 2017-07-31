<#1>
<?php
require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php');
SRAG\Hub2\Origin\ARUserOrigin::installDB();
SRAG\Hub2\Object\ARUser::installDB();
SRAG\Hub2\Object\ARCourse::installDB();
SRAG\Hub2\Object\ARCategory::installDB();
SRAG\Hub2\Config\ArConfig::installDB();
$config = new \SRAG\Hub2\Config\ArConfig();
$config->save();
?>