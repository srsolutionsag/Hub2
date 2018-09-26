<#1>
<?php
\SRAG\Plugins\Hub2\Origin\User\ARUserOrigin::updateDB();
\SRAG\Plugins\Hub2\Object\User\ARUser::updateDB();
\SRAG\Plugins\Hub2\Object\Course\ARCourse::updateDB();
\SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::updateDB();
\SRAG\Plugins\Hub2\Object\Category\ARCategory::updateDB();
\SRAG\Plugins\Hub2\Object\Session\ARSession::updateDB();
\SRAG\Plugins\Hub2\Object\Group\ARGroup::updateDB();
\SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::updateDB();
\SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::updateDB();
?>
<#2>
<?php
\srag\DIC\DICStatic::dic()->database()
	->modifyTableColumn(\SRAG\Plugins\Hub2\Object\CourseMembership\ARCourseMembership::TABLE_NAME, 'ilias_id', array(
		"type" => "text",
		"length" => 256,
	));
\srag\DIC\DICStatic::dic()->database()
	->modifyTableColumn(\SRAG\Plugins\Hub2\Object\SessionMembership\ARSessionMembership::TABLE_NAME, 'ilias_id', array(
		"type" => "text",
		"length" => 256,
	));
\srag\DIC\DICStatic::dic()->database()->modifyTableColumn(\SRAG\Plugins\Hub2\Object\GroupMembership\ARGroupMembership::TABLE_NAME, 'ilias_id', array(
	"type" => "text",
	"length" => 256,
));
?>
<#3>
<?php
\SRAG\Plugins\Hub2\Object\OrgUnit\AROrgUnit::updateDB();
\SRAG\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership::updateDB();
?>
<#4>
<?php
\SRAG\Plugins\Hub2\Config\ArConfig::updateDB();

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\SRAG\Plugins\Hub2\Config\ArConfigOld::TABLE_NAME)) {
	foreach (\SRAG\Plugins\Hub2\Config\ArConfigOld::get() as $config) {
		/**
		 * @var \SRAG\Plugins\Hub2\Config\ArConfigOld $config
		 */
		switch ($config->getKey()) {
			default:
				\SRAG\Plugins\Hub2\Config\ArConfig::setValueByKey(strval($config->getKey()), strval($config->getValue()));
				break;
		}
	}

	\srag\DIC\DICStatic::dic()->database()->dropTable(\SRAG\Plugins\Hub2\Config\ArConfigOld::TABLE_NAME);
}
?>
<#5>
<?php
$administration_role_ids = \SRAG\Plugins\Hub2\Config\ArConfig::getValueByKey(\SRAG\Plugins\Hub2\Config\IArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS, json_encode(\SRAG\Plugins\Hub2\Config\IArConfig::DEFAULT_ADMINISTRATE_HUB_ROLE_IDS));

if (strpos($administration_role_ids, "[") === false) {
	$administration_role_ids = preg_split('/, */', $administration_role_ids);
	$administration_role_ids = array_map(function (string $id): int {
		return intval($id);
	}, $administration_role_ids);

	\SRAG\Plugins\Hub2\Config\ArConfig::setAdministrationRoleIds($administration_role_ids);
}
?>
