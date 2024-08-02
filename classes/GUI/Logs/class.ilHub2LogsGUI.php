<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use ILIAS\UI\Factory;
use ILIAS\UI\Renderer;
use srag\Plugins\Hub2\Log\LogsTable;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\UI\Log\LogsTableGUI;
use srag\Plugins\Hub2\Log\LogDBRepository;
use ILIAS\UI\Component\Table\PresentationRow;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Jobs\Log\DeleteOldLogsJob;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Log\LogRepository;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ilHub2LogsGUI extends ilHub2DispatchableBaseGUI
{
    public const SUBTAB_LOGS = 'subtab_logs';
    private const CMD_PURGE_LOGS = 'purgeLogs';
    public const CMD_SHOW_LOGS_OF_EXT_ID = 'showLogsOfExtId';
    /**
     * @readonly
     */
    private LogRepository $repo;

    /**
     * @readonly
     */
    private Renderer $ui_renderer;
    /**
     * @readonly
     */
    private Factory $ui_factory;

    public function __construct()
    {
        parent::__construct();
        global $DIC;
        $this->ui_renderer = $DIC->ui()->renderer();
        $this->ui_factory = $DIC->ui()->factory();
        $this->repo = new LogDBRepository();
    }

    public function getActiveSubTab(): ?string
    {
        return self::SUBTAB_LOGS;
    }

    public function checkAccess(): void
    {
        // TODO: Implement checkAccess() method.
    }

    public function index(): void
    {
        $this->toolbar->addComponent(
            $this->ui_factory->button()->standard(
                $this->translator->txt('purge_logs'),
                $this->ctrl->getLinkTarget($this, self::CMD_PURGE_LOGS)
            )
        );

        $requested_ext_id = $this->http->request()->getQueryParams()['ext_id'] ?? '';
        $initial_filter_values = [
            'date' => $requested_ext_id ? '' : date('Y-m-d') . ' **:**',
            'object_ext_id' => $requested_ext_id,
        ];
        $table = new LogsTable(
            $this->repo,
            ilHub2Plugin::getInstance(),
            $initial_filter_values
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
