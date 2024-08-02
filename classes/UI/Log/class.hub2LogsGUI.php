<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

//namespace srag\Plugins\Hub2\UI\Log;
use ILIAS\UI\Factory;
use ILIAS\UI\Renderer;
use srag\Plugins\Hub2\Log\Table;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\UI\Log\LogsTableGUI;
use srag\Plugins\Hub2\Log\Repository as LogRepository;
use srag\Plugins\Hub2\Log\LogDBRepository;
use ILIAS\UI\Component\Table\PresentationRow;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Jobs\Log\DeleteOldLogsJob;
use srag\Plugins\Hub2\Config\ArConfig;

/**
 * Class LogsGUI
 *
 * @package srag\Plugins\Hub2\UI\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class hub2LogsGUI extends hub2MainGUI
{
    public const SUBTAB_LOGS = 'logs';
    private const CMD_PURGE_LOGS = 'purgeLogs';
    /**
     * @readonly
     */
    private LogDBRepository $repo;
    /**
     * @readonly
     */
    private ilGlobalTemplateInterface $main_tpl;
    /**
     * @readonly
     */
    private Renderer $ui_renderer;
    /**
     * @readonly
     */
    private ilToolbarGUI $toolbar;
    /**
     * @readonly
     */
    private Factory $ui_factory;
    /**
     * @readonly
     */
    private Translator $translator;

    public function __construct()
    {
        parent::__construct();
        global $DIC;
        $this->toolbar = $DIC['ilToolbar'];
        $this->main_tpl = $DIC->ui()->mainTemplate();
        $this->ui_renderer = $DIC->ui()->renderer();
        $this->ui_factory = $DIC->ui()->factory();
        $this->repo = new LogDBRepository();
        $this->translator = ilHub2Plugin::getInstance(); // TODO move to Translator
    }

    protected function index(): void
    {
        $this->toolbar->addComponent(
            $this->ui_factory->button()->standard(
                $this->translator->txt('purge_logs'),
                $this->ctrl->getLinkTarget($this, self::CMD_PURGE_LOGS)
            )
        );

        $table = new Table(
            $this->repo,
            ilHub2Plugin::getInstance()
        );

        $this->main_tpl->setContent(
            $this->ui_renderer->render(
                $table->getWithFilters(
                    $this->ctrl->getLinkTarget(
                        $this,
                        self::CMD_INDEX
                    )
                )
            )
        );
    }

    protected function purgeLogs(): void
    {
        $keep_old_logs_time = (int) ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME);
        $keep_old_logs_time = max($keep_old_logs_time, 7);

        $purged = $this->repo->purge(
            DeleteOldLogsJob::KEEP_LATEST,
            1000,
            function (array $row, int $removed_in_step): void {
                global $DIC;
                $DIC->logger()->root()->warning('Purging ' . $removed_in_step . ' HUB2 logs for ' . json_encode($row));
            }
        );

        $this->main_tpl->setOnScreenMessage(
            'success',
            sprintf($this->translator->txt('msg_logs_purged'), $purged),
            true
        );
        $this->ctrl->redirect($this, self::CMD_INDEX);
    }
}
