<?php

//namespace srag\Plugins\Hub2\UI\OriginConfig;

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
 * Class ConfigOriginsGUI
 * @package      srag\Plugins\Hub2\UI\OriginConfig
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 * @ilCtrl_calls hub2ConfigOriginsGUI: hub2DataGUI
 * @ilCtrl_calls hub2ConfigOriginsGUI: hub2LogsGUI
 */
class hub2ConfigOriginsGUI extends hub2MainGUI
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
    /**
     * @var \srag\Plugins\Hub2\FileDrop\ResourceStorage\ResourceStorage
     */
    protected $file_storage;
    /**
     * @var OriginSyncSummaryFactory
     */
    protected $summaryFactory;
    /**
     * @var OriginFactory
     */
    protected $originFactory;
    /**
     * @var IOriginRepository
     */
    protected $originRepository;
    /**
     * @var \ilToolbarGUI
     */
    private $toolbar;
    /**
     * @var \ILIAS\HTTP\Services
     */
    protected $http;
    /**
     * @var \ilObjUser
     */
    protected $user;

    /**
     * ConfigOriginsGUI constructor
     */
    public function __construct()
    {
        global $DIC;
        $this->toolbar = $DIC->toolbar();
        $this->http = $DIC->http();
        $this->user = $DIC->user();
        parent::__construct();
        $this->originFactory = new OriginFactory();
        $this->originRepository = new OriginRepository();
        $this->summaryFactory = new OriginSyncSummaryFactory();
        $this->file_storage = (new Factory())->storage();
        $this->plugin = ilPluginAdmin::getPluginObject(IL_COMP_SERVICE, 'Cron', 'crnhk', 'Hub2');
    }

    /**
     *
     */
    public function executeCommand()
    {
        $this->checkAccess();
        parent::executeCommand();
        // require_once "./Customizing/global/plugins/Services/Cron/CronHook/Hub2/sql/dbupdate.php";
        switch ($this->ctrl->getNextClass()) {
            case strtolower(hub2DataGUI::class):
                $this->ctrl->forwardCommand(new hub2DataGUI());
                break;
            case strtolower(hub2LogsGUI::class):
                $this->ctrl->forwardCommand(new hub2LogsGUI());
                break;
        }
    }

    /**
     *
     */
    protected function initTabs()
    {
        $this->tabs->addSubTab(
            self::SUBTAB_ORIGINS,
            $this->plugin->txt(self::SUBTAB_ORIGINS),
            $this->ctrl->getLinkTarget(
                $this,
                self::CMD_INDEX
            )
        );

        $this->tabs->addSubTab(
            self::SUBTAB_DATA,
            $this->plugin->txt(self::SUBTAB_DATA),
            $this->ctrl->getLinkTargetByClass(
                hub2DataGUI::class,
                hub2DataGUI::CMD_INDEX
            )
        );

        $this->tabs->addSubTab(
            hub2LogsGUI::SUBTAB_LOGS,
            $this->plugin->txt("logs"),
            $this->ctrl
                ->getLinkTargetByClass(hub2LogsGUI::class, hub2LogsGUI::CMD_INDEX)
        );

        $this->tabs->activateTab(self::TAB_ORIGINS);
        $this->tabs->activateSubTab(self::SUBTAB_ORIGINS);
    }

    /**
     *
     */
    protected function index()
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
        $this->tpl->setContent($table->getHTML());
    }

    /**
     *
     */
    protected function cancel()
    {
        $this->index();
    }

    /**
     *
     */
    protected function addOrigin()
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        $this->tpl->setContent($form->getHTML());
    }

    protected function downloadFileDrop() : void
    {
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $rid = $this->http->request()->getQueryParams()['rid'] ?? null;
        if ($rid === null) {
            $this->editOrigin();
            return;
        }
        $this->file_storage->download($rid, $origin->getTitle());
    }

    /**
     *
     */
    protected function createOrigin()
    {
        $form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
        if ($form->checkInput()) {
            $origin = $this->originFactory->createByType($form->getInput('object_type'));
            $origin->setTitle($form->getInput('title'));
            $origin->setDescription($form->getInput('description'));
            $origin->store();
            ilUtil::sendSuccess($this->plugin->txt('msg_success_create_origin'), true);
            $this->ctrl->setParameter($this, self::ORIGIN_ID, $origin->getId());
            $this->ctrl->redirect($this, self::CMD_EDIT_ORGIN);
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    protected function saveOrigin()
    {
        /** @var AROrigin $origin */
        $origin = $this->getOrigin((int) $_POST[self::ORIGIN_ID]);
        $this->tpl->setTitle($origin->getTitle());
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
            $upload_service->process();

            $file_drop_filedrop = $form->getInput('manual_file_drop_filedrop');
            $file_drop_api = $form->getInput('manual_file_drop_filedrop_api');
            if (
                $upload_service->hasUploads()
                && ($rid = $origin->config()->get(IOriginConfig::FILE_DROP_RID)) !== null
            ) {
                $uploads = $upload_service->getResults();
                $upload = $uploads[$file_drop_filedrop['tmp_name']] ?? $uploads[$file_drop_api['tmp_name']] ?? null;
                if ($upload !== null) {
                    $storage = (new Factory())->storage();
                    $storage->replaceUpload($upload, $rid);
                }
            }

            $origin->config()->setData($configData);
            $origin->properties()->setData($propertyData);
            $origin->store();
            ilUtil::sendSuccess($this->plugin->txt('msg_origin_saved'), true);
            // Try to create the implementation class file automatically
            $generator = new OriginImplementationTemplateGenerator();
            try {
                $result = $generator->create($origin);
                if ($result) {
                    ilUtil::sendInfo(
                        sprintf(
                            $this->plugin->txt("msg_created_class_implementation_file"),
                            $generator->getClassFilePath($origin)
                        ),
                        true
                    );
                }
            } catch (HubException $e) {
                ilUtil::sendInfo(
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
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    protected function editOrigin()
    {
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $this->tpl->setTitle($origin->getTitle());
        $form = $this->getForm($origin);
        $this->tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    protected function activateAll()
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(true);
            $repository->store();
        }
        ilUtil::sendSuccess($this->plugin->txt('msg_origin_activated'), true);
        $this->ctrl->redirect($this);
    }

    /**
     *
     */
    protected function deactivateAll()
    {
        foreach ($this->originRepository->all() as $repository) {
            $repository->setActive(false);
            $repository->store();
        }
        ilUtil::sendSuccess($this->plugin->txt('msg_origin_deactivated'), true);
        $this->ctrl->redirect($this);
    }

    protected function toggle()
    {
        /** @var AROrigin $origin */
        $origin = $this->getOrigin((int) $_GET[self::ORIGIN_ID]);
        $origin->setActive(!$origin->isActive());
        $origin->save();
        ilUtil::sendSuccess($this->plugin->txt('msg_origin_toggled'), true);
        $this->cancel();
    }

    /**
     * @param IOrigin $origins
     */
    protected function execute(array $origins, bool $force_update = false) : void
    {
        $summary = $this->summaryFactory->web();

        (new RunSync(new CronNotifier(), $origins, $summary, $force_update))->run();

        ilUtil::sendInfo(nl2br($summary->getOutputAsString(), false), true);

        $this->ctrl->redirect($this);
    }

    protected function run(bool $force_update = false)/*: void*/
    {
        $this->execute($this->originFactory->getAllActive(), $force_update);
    }

    /**
     *
     */
    protected function runForceUpdate()/*: void*/
    {
        $this->run(true);
    }

    protected function runOriginSync(bool $force_update = false)/*: void*/
    {
        $origin = $this->getOrigin((int) filter_input(INPUT_GET, self::ORIGIN_ID));

        $this->execute([$origin], $force_update);
    }

    /**
     *
     */
    protected function runOriginSyncForceUpdate()/*: void*/
    {
        $this->runOriginSync(true);
    }

    /**
     *
     */
    protected function confirmDelete()
    {
        $f = new OriginFactory();
        $o = $f->getById($this->http->request()->getQueryParams()[self::ORIGIN_ID]);

        $c = new ilConfirmationGUI();
        $c->setFormAction($this->ctrl->getFormAction($this));
        $c->addItem(self::ORIGIN_ID, $o->getId(), $o->getTitle());
        $c->setConfirm($this->plugin->txt('confirm_delete_button'), self::CMD_DELETE);
        $c->setCancel($this->plugin->txt('cancel_delete_button'), self::CMD_INDEX);
        $this->tpl->setContent($c->getHTML());
    }

    /**
     *
     */
    protected function delete()
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
    protected function checkAccess()
    {
        $roles = array_unique(array_merge(ArConfig::getField(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS), [2]));
        if (!$this->rbac_review->isAssignedToAtLeastOneGivenRole($this->user->getId(), $roles)) {
            ilUtil::sendFailure($this->plugin->txt('permission_denied'), true);
            if (self::version()->is6()) {
                $this->ctrl->redirectByClass(ilDashboardGUI::class);
            } else {
                $this->ctrl->redirectByClass(ilPersonalDesktopGUI::class);
            }
        }
    }

    /**
     * @return OriginConfigFormGUI
     */
    protected function getForm(AROrigin $origin)
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
