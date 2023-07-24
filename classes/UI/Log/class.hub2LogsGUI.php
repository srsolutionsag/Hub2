<?php

//namespace srag\Plugins\Hub2\UI\Log;

use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\UI\Log\LogsTableGUI;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class LogsGUI
 * @package srag\Plugins\Hub2\UI\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class hub2LogsGUI extends hub2MainGUI
{
    public const CMD_APPLY_FILTER = "applyFilter";
    public const CMD_RESET_FILTER = "resetFilter";
    public const CMD_SHOW_LOGS_OF_EXT_ID = "showLogsOfExtID";
    public const SUBTAB_LOGS = "subtab_logs";
    public const LANG_MODULE_LOGS = "logs";
    public const CMD_CLEAR = 'clear';
    private $log_repo;
    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;

    public function __construct()
    {
        parent::__construct();
        global $DIC;
        $this->toolbar = $DIC['ilToolbar'];
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function executeCommand()/*: void*/
    {
        $this->initTabs();

        $cmd = $this->ctrl->getCmd(self::CMD_INDEX);

        switch ($cmd) {
            case self::CMD_INDEX:
            case self::CMD_APPLY_FILTER:
            case self::CMD_RESET_FILTER:
            case self::CMD_SHOW_LOGS_OF_EXT_ID:
            case self::CMD_CLEAR:
                $this->{$cmd}();
                break;

            default:
                break;
        }
    }

    /**
     * @inheritdoc
     */
    protected function initTabs()/*: void*/
    {
        $this->tabs->activateSubTab(self::SUBTAB_LOGS);
    }

    /**
     * @param string $cmd
     */
    protected function getLogsTable($cmd = self::CMD_INDEX) : LogsTableGUI
    {
        return new LogsTableGUI($this, $cmd);
    }

    /**
     * @inheritdoc
     */
    protected function index()/*: void*/
    {
        $this->toolbar->addButton(
            $this->plugin->txt('logs_clear_logs'),
            $this->ctrl->getLinkTarget($this, self::CMD_CLEAR)
        );

        $table = $this->getLogsTable();

        $this->tpl->setContent($table->getHTML());
    }

    protected function clear()
    {
        $this->log_repo->deleteOldLogs(0);
        $this->ctrl->redirect($this, self::CMD_INDEX);
    }

    /**
     *
     */
    protected function applyFilter()/*: void*/
    {
        $table = $this->getLogsTable(self::CMD_APPLY_FILTER);

        try {
            $table->writeFilterToSession();
        } catch (Throwable $t) {
            // Ignore
        }

        $table->resetOffset();

        //$this->ctrl->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function resetFilter()/*: void*/
    {
        $table = $this->getLogsTable(self::CMD_RESET_FILTER);

        $table->resetFilter();

        $table->resetOffset();

        //$this->ctrl->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function showLogsOfExtID()/*: void*/
    {
        $origin_id = (int) filter_input(INPUT_GET, DataTableGUI::F_ORIGIN_ID);
        $ext_id = filter_input(INPUT_GET, DataTableGUI::F_EXT_ID);

        $table = $this->getLogsTable(self::CMD_RESET_FILTER);
        $table->resetFilter();
        $table->resetOffset();

        $_POST["origin_id"] = $origin_id;
        $_POST["object_ext_id"] = $ext_id;
        $this->applyFilter();
    }
}
