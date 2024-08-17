<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

//namespace srag\Plugins\Hub2\UI\OriginConfig;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\ResourceStorage;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Jobs\RunSync;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\OriginImplementationTemplateGenerator;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\UI\OriginConfig\OriginsTableGUI;
use srag\Plugins\Hub2\UI\OriginFormFactory;
use srag\Plugins\Hub2\Jobs\CronNotifier;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * @package      srag\Plugins\Hub2\UI\OriginConfig
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 * @ilCtrl_calls ilHub2OriginsGUI: ilHub2DataGUI
 * @ilCtrl_calls ilHub2OriginsGUI: ilHub2LogsGUI
 */
class ilHub2OriginsGUI extends ilHub2DispatchableBaseGUI
{
    public const CMD_DELETE = 'delete';
    public const ORIGIN_ID = 'origin_id';
    public const SUBTAB_ORIGINS = 'subtab_origins';
    public const SUBTAB_DATA = 'subtab_data';
    public const CMD_RUN = 'run';
    public const CMD_RUN_FORCE_UPDATE = 'runForceUpdate';
    public const CMD_ADD_ORIGIN = 'addOrigin';
    public const CMD_EDIT_ORGIN = 'editOrigin';
    public const CMD_RUN_ORIGIN_SYNC = 'runOriginSync';
    public const CMD_RUN_ORIGIN_SYNC_FORCE_UPDATE = 'runOriginSyncForceUpdate';
    public const CMD_CONFIRM_DELETE = 'confirmDelete';
    public const CMD_CREATE_ORIGIN = 'createOrigin';
    public const CMD_SAVE_ORIGIN = 'saveOrigin';
    public const CMD_CANCEL = 'cancel';
    public const CMD_DEACTIVATE_ALL = 'deactivateAll';
    public const CMD_ACTIVATE_ALL = 'activateAll';
    public const CMD_TOGGLE = 'toggle';
    public const CMD_DOWNLOAD_RID = 'downloadFileDrop';
    protected ResourceStorage $file_storage;
    protected OriginSyncSummaryFactory $summaryFactory;
    protected OriginFactory $originFactory;
    protected IOriginRepository $originRepository;

    public function __construct()
    {
        parent::__construct();
        $this->originFactory = new OriginFactory();
        $this->originRepository = new OriginRepository();
        $this->summaryFactory = new OriginSyncSummaryFactory();
        $this->file_storage = (new Factory())->storage();
    }

    public function getDefaultClass(): ilHub2DispatchableGUI
    {
        return $this;
    }

    public function getSubtabs(): array
    {
        return [
            self::SUBTAB_ORIGINS => $this->ctrl->getLinkTarget($this, self::CMD_INDEX),
            'subtab_data' => $this->ctrl->getLinkTargetByClass([self::class, ilHub2DataGUI::class], ilHub2DataGUI::CMD_INDEX),
            'subtab_logs' => $this->ctrl->getLinkTargetByClass([self::class, ilHub2LogsGUI::class], ilHub2LogsGUI::CMD_INDEX),
        ];
    }

    public function getActiveSubTab(): ?string
    {
        return self::SUBTAB_ORIGINS;
    }

    public function index(): void
    {
        $this->toolbar->setFormAction($this->ctrl->getFormAction($this));

        $button = ilSubmitButton::getInstance();
        $button->setCaption($this->plugin->txt('origin_table_button_add'), false);
        $button->setPrimary(true);
        $button->setCommand(self::CMD_ADD_ORIGIN);
        $this->toolbar->addButtonInstance($button);

        $this->toolbar->addSeparator();

        $button = ilSubmitButton::getInstance();
        $button->setCaption($this->plugin->txt('origin_table_button_run'), false);
        $button->setCommand(self::CMD_RUN);
        $this->toolbar->addButtonInstance($button);

        $button = ilSubmitButton::getInstance();
        $button->setCaption($this->plugin->txt('origin_table_button_run_force_update'), false);
        $button->setCommand(self::CMD_RUN_FORCE_UPDATE);
        $this->toolbar->addButtonInstance($button);

        $table = new OriginsTableGUI($this, self::CMD_INDEX, new OriginRepository());
        $this->main_tpl->setContent($table->getHTML());
    }

    protected function addOrigin(): void
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        $this->main_tpl->setContent($form->getHTML());
    }

    protected function downloadFileDrop(): void
    {
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $rid = $this->http->request()->getQueryParams()['rid'] ?? null;
        if ($rid === null) {
            $this->editOrigin();
            return;
        }
        $this->file_storage->download($rid, $origin->getTitle());
    }

    protected function createOrigin(): void
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        if ($form->checkInput()) {
            $origin = $this->originFactory->createByType($form->getInput('object_type'));
            $origin->setTitle($form->getInput('title'));
            $origin->setDescription($form->getInput('description'));
            $origin->store();
            $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_success_create_origin'), true);
            $this->ctrl->setParameter($this, self::ORIGIN_ID, $origin->getId());
            $this->ctrl->redirect($this, self::CMD_EDIT_ORGIN);
        }
        $form->setValuesByPost();
        $this->main_tpl->setContent($form->getHTML());
    }

    protected function saveOrigin(): void
    {
        /** @var AROrigin $origin */
        $origin = $this->getOrigin((int) $_POST[self::ORIGIN_ID]);
        $this->main_tpl->setTitle($origin->getTitle());
        $form = $this->getForm($origin);
        if ($form->checkInput()) {
            $origin->setTitle($form->getInput('title'));
            $origin->setSort($form->getInput(OriginConfigFormGUI::POST_VAR_SORT));
            $origin->setDescription($form->getInput('description'));
            $origin->setAdHoc($form->getInput(OriginConfigFormGUI::POST_VAR_ADHOC));
            $origin->setAdhocParentScope(
                $form->getInput("adhoc_parent_scope") !== '' && $form->getInput("adhoc_parent_scope") !== '0' ? 1 : 0
            );
            $origin->setActive($form->getInput('active'));
            $origin->setImplementationClassName($form->getInput('implementation_class_name'));
            $origin->setImplementationNamespace($form->getInput('implementation_namespace'));
            // Get the config data as an array
            $configData = [];
            $propertyData = [];
            foreach ($form->getInputItemsRecursive() as $item) {
                if (strpos($item->getPostVar(), 'config_') === 0) {
                    $key = substr($item->getPostVar(), 7);
                    $configData[$key] = $form->getInput($item->getPostVar());
                } elseif (strpos($item->getPostVar(), 'prop_') === 0) {
                    $key = substr($item->getPostVar(), 5);
                    $propertyData[$key] = $form->getInput($item->getPostVar());
                }
            }

            // Manual File Drops
            global $DIC;
            $upload_service = $DIC->upload();
            if (!$upload_service->hasBeenProcessed()) {
                $upload_service->process();
            }

            $file_drop_filedrop = $form->getInput('manual_file_drop_filedrop');
            $file_drop_api = $form->getInput('manual_file_drop_filedrop_api');
            if (
                $upload_service->hasUploads()
                && ($rid = $origin->config()->get(IOriginConfig::FILE_DROP_RID)) !== null
            ) {
                $uploads = $upload_service->getResults();
                $file_drop_temp_name = $file_drop_filedrop['tmp_name'] ?? '';
                $api_temp_name = $file_drop_api['tmp_name'] ?? '';
                $upload = $uploads[$file_drop_temp_name] ?? $uploads[$api_temp_name] ?? null;
                if ($upload !== null) {
                    $storage = (new Factory())->storage();
                    $storage->replaceUpload($upload, $rid);
                }
            }

            $origin->config()->setData($configData);
            $origin->properties()->setData($propertyData);
            $origin->store();
            $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_origin_saved'), true);
            // Try to create the implementation class file automatically
            $generator = new OriginImplementationTemplateGenerator();
            try {
                $result = $generator->create($origin);
                if ($result) {
                    $this->main_tpl->setOnScreenMessage(
                        'info',
                        sprintf(
                            $this->plugin->txt("msg_created_class_implementation_file"),
                            $generator->getClassFilePath($origin)
                        ),
                        true
                    );
                }
            } catch (HubException $e) {
                $this->main_tpl->setOnScreenMessage(
                    'info',
                    sprintf(
                        $this->plugin->txt("msg_created_class_implementation_file_failed"),
                        $generator->getClassFilePath($origin)
                    ),
                    true
                );
            }
            $this->ctrl->saveParameter($this, self::ORIGIN_ID);
            $this->ctrl->redirect($this, self::CMD_EDIT_ORGIN);
        }
        $form->setValuesByPost();
        $this->main_tpl->setContent($form->getHTML());
    }

    protected function editOrigin(): void
    {
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $this->main_tpl->setTitle($origin->getTitle());
        $form = $this->getForm($origin);
        $this->main_tpl->setContent($form->getHTML());
    }

    protected function activateAll(): void
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(true);
            $repository->store();
        }
        $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_origin_activated'), true);
        $this->ctrl->redirect($this);
    }

    protected function deactivateAll(): void
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(false);
            $repository->store();
        }
        $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_origin_deactivated'), true);
        $this->ctrl->redirect($this);
    }

    protected function toggle(): void
    {
        /** @var AROrigin $origin */
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $origin->setActive(!$origin->isActive());
        $origin->save();
        $this->main_tpl->setOnScreenMessage('success', $this->plugin->txt('msg_origin_toggled'), true);
        $this->ctrl->redirect($this);
    }

    protected function execute(array $origins, bool $force_update = false): void
    {
        $summary = $this->summaryFactory->web();

        (new RunSync(new CronNotifier(), $origins, $summary, $force_update))->run();

        $this->main_tpl->setOnScreenMessage('info', nl2br($summary->getOutputAsString(), false), true);

        $this->ctrl->redirect($this);
    }

    protected function run(bool $force_update = false): void
    {
        $this->execute($this->originFactory->getAllActive(), $force_update);
    }

    protected function runForceUpdate(): void
    {
        $this->run(true);
    }

    protected function runOriginSync(bool $force_update = false): void
    {
        $origin = $this->getOrigin((int) filter_input(INPUT_GET, self::ORIGIN_ID));

        $this->execute([$origin], $force_update);
    }

    protected function runOriginSyncForceUpdate(): void
    {
        $this->runOriginSync(true);
    }

    protected function confirmDelete(): void
    {
        $f = new OriginFactory();
        $o = $f->getById($this->http->request()->getQueryParams()[self::ORIGIN_ID]);

        $c = new ilConfirmationGUI();
        $c->setFormAction($this->ctrl->getFormAction($this));
        $c->addItem(self::ORIGIN_ID, $o->getId(), $o->getTitle());
        $c->setHeaderText($this->plugin->txt('msg_confirm_delete_origin'));
        $c->setConfirm($this->plugin->txt('confirm_delete_button'), self::CMD_DELETE);
        $c->setCancel($this->plugin->txt('cancel_delete_button'), self::CMD_INDEX);
        $this->main_tpl->setContent($c->getHTML());
    }

    protected function delete(): void
    {
        $origin_id = (int) $this->http->request()->getParsedBody()[self::ORIGIN_ID];

        $f = new OriginFactory();
        $f->delete($origin_id);

        $this->ctrl->redirect($this, self::CMD_INDEX);
    }

    /**
     * Check access based on plugin configuration.
     * Returns to personal desktop if a user does not have permission to administrate hub.
     */
    public function checkAccess(): void
    {
        $roles = array_unique(array_merge(ArConfig::getField(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS), [2]));
        if (!$this->rbac_review->isAssignedToAtLeastOneGivenRole($this->user->getId(), $roles)) {
            $this->main_tpl->setOnScreenMessage('failure', $this->plugin->txt('permission_denied'), true);
            $this->ctrl->redirectByClass(ilDashboardGUI::class);
        }
    }

    protected function getForm(AROrigin $origin): OriginConfigFormGUI
    {
        $formFactory = new OriginFormFactory();
        $formClass = $formFactory->getFormClassNameByOrigin($origin);

        return new $formClass($this, new OriginRepository(), $origin);
    }

    /**
     * @param int $id
     * @return AROrigin
     * @throws ilException
     */
    protected function getOrigin($id)
    {
        /** @var AROrigin $origin */
        $origin = $this->originFactory->getById((int) $id);
        if ($origin === null) {
            throw new ilException(sprintf("Origin with ID '%s' not found.", $id));
        }

        return $origin;
    }
}
