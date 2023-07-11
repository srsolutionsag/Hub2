<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Config\ArConfigOld;
use srag\Plugins\Hub2\Jobs\Log\DeleteOldLogsJob;
use srag\Plugins\Hub2\Jobs\RunSync;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Menu\Menu;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\CompetenceManagement\ARCompetenceManagement;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\Jobs\CronNotifier;

/**
 * Class ilHub2Plugin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin
{
    public const PLUGIN_ID = 'hub2';
    public const PLUGIN_NAME = 'Hub2';
    /**
     * @var ilHub2Plugin|null
     */
    protected static $instance = null;

    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }

    public static function getInstance() : self
    {
        if (!self::$instance instanceof \ilHub2Plugin) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return ilCronJob[]
     */
    public function getCronJobInstances() : array
    {
        return [new RunSync(new CronNotifier()), new DeleteOldLogsJob()];
    }

    /**
     * @param string $a_job_id
     * @return ilCronJob
     */
    public function getCronJobInstance(/*string*/
        $a_job_id
    ) {/*: ?ilCronJob*/
        switch ($a_job_id) {
            case RunSync::CRON_JOB_ID:
                return new RunSync(new CronNotifier());

            case DeleteOldLogsJob::CRON_JOB_ID:
                return new DeleteOldLogsJob();

            default:
                return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider
    {
        global $DIC;
        return new Menu($DIC, $this);
    }

    /**
     * @inheritdoc
     */
    protected function afterUninstall()/*: void*/
    {
        $this->getDBInstance()->dropTable(ARUserOrigin::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARUser::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARCourse::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARCourseMembership::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARCategory::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARSession::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARGroup::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARGroupMembership::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARSessionMembership::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ArConfig::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ArConfigOld::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(AROrgUnit::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(AROrgUnitMembership::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(Log::TABLE_NAME, false);
        $this->getDBInstance()->dropTable(ARCompetenceManagement::TABLE_NAME, false);

        ilUtil::delDir(ILIAS_DATA_DIR . "/hub/");
    }

    protected function getDBInstance() : ilDBInterface
    {
        return property_exists(
            $this,
            'db'
        ) && $this->db !== null && $this->db instanceof ilDBInterface ? $this->db : $GLOBALS['DIC']->database();
    }
}
