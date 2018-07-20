<?php
require_once __DIR__ . "/../vendor/autoload.php";

use SRAG\Plugins\Hub2\Config\HubConfig;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Log\OriginLog;
use SRAG\Plugins\Hub2\Origin\AROrigin;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginRepository;
use SRAG\Plugins\Hub2\Origin\OriginFactory;
use SRAG\Plugins\Hub2\Origin\OriginImplementationTemplateGenerator;
use SRAG\Plugins\Hub2\Origin\OriginRepository;
use SRAG\Plugins\Hub2\Origin\User\ARUserOrigin;
use SRAG\Plugins\Hub2\Sync\OriginSyncFactory;
use SRAG\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;
use SRAG\Plugins\Hub2\UI\OriginConfigFormGUI;
use SRAG\Plugins\Hub2\UI\OriginFormFactory;
use SRAG\Plugins\Hub2\UI\OriginsTableGUI;

/**
 * Class hub2ConfigOriginsGUI
 *
 * @package
 * @author       Stefan Wanzenried <sw@studer-raimann.ch>
 * @author       Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_calls hub2ConfigOriginsGUI: hub2DataGUI
 */
class hub2ConfigOriginsGUI extends hub2MainGUI {

	const CMD_DELETE = 'delete';
	const ORIGIN_ID = 'origin_id';
	const SUBTAB_DATA = 'subtab_data';
	const SUBTAB_ORIGINS = 'subtab_origins';
	const CMD_RUN = 'run';
	const CMD_ADD_ORIGIN = 'addOrigin';
	const Q_FORCE_UPDATE = 'force_update';
	const CMD_EDIT_ORGIN = 'editOrigin';
	const CMD_RUN_ORIGIN_SYNC = 'runOriginSync';
	const CMD_CONFIRM_DELETE = 'confirmDelete';
	const CMD_CREATE_ORIGIN = 'createOrigin';
	const CMD_SAVE_ORIGIN = 'saveOrigin';
	const CMD_CANCEL = 'cancel';
	const CMD_DEACTIVATE_ALL = 'deactivateAll';
	const CMD_ACTIVATE_ALL = 'activateAll';
	/**
	 * @var OriginSyncSummaryFactory
	 */
	protected $summaryFactory;
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;
	/**
	 * @var OriginFactory
	 */
	protected $originFactory;
	/**
	 * @var HubConfig
	 */
	protected $hubConfig;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->originFactory = new OriginFactory($this->db());
		$this->hubConfig = new HubConfig();
		$this->originRepository = new OriginRepository();
		$this->summaryFactory = new OriginSyncSummaryFactory();
	}


	/**
	 *
	 */
	public function executeCommand() {
		$this->checkAccess();
		parent::executeCommand();
		// require_once "./Customizing/global/plugins/Services/Cron/CronHook/Hub2/sql/dbupdate.php";
		switch ($this->ctrl()->getNextClass()) {
			case strtolower(hub2DataGUI::class):
				$this->ctrl()->forwardCommand(new hub2DataGUI());
				break;
		}
	}


	/**
	 *
	 */
	protected function initTabs() {
		$this->tabs()->addSubTab(self::SUBTAB_ORIGINS, $this->pl->txt(self::SUBTAB_ORIGINS), $this->ctrl()->getLinkTarget($this, self::CMD_INDEX));
		$this->tabs()->addSubTab(self::SUBTAB_DATA, $this->pl->txt(self::SUBTAB_DATA), $this->ctrl()
			->getLinkTargetByClass(hub2DataGUI::class, hub2DataGUI::CMD_INDEX));

		$this->tabs()->activateTab(self::TAB_ORIGINS);
		$this->tabs()->activateSubTab(self::SUBTAB_ORIGINS);
	}


	/**
	 *
	 */
	protected function index() {
		$this->toolbar()->setFormAction($this->ctrl()->getFormAction($this));

		$button = ilSubmitButton::getInstance();
		$button->setCaption($this->pl->txt('origin_table_button_add'), false);
		$button->setPrimary(true);
		$button->setCommand(self::CMD_ADD_ORIGIN);
		$this->toolbar()->addButtonInstance($button);

		$this->toolbar()->addSeparator();

		$this->toolbar()->addInputItem(new ilCheckboxInputGUI($this->pl->txt('origin_table_button_force'), self::Q_FORCE_UPDATE));

		$button = ilSubmitButton::getInstance();
		$button->setCaption($this->pl->txt('origin_table_button_run'), false);
		$button->setCommand(self::CMD_RUN);
		$this->toolbar()->addButtonInstance($button);

		$table = new OriginsTableGUI($this, self::CMD_INDEX, new OriginRepository());
		$this->tpl()->setContent($table->getHTML());
	}


	/**
	 *
	 */
	protected function cancel() {
		$this->index();
	}


	/**
	 *
	 */
	protected function addOrigin() {
		$form = new OriginConfigFormGUI($this, $this->hubConfig, new OriginRepository(), new ARUserOrigin());
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function createOrigin() {
		$form = new OriginConfigFormGUI($this, $this->hubConfig, new OriginRepository(), new ARUserOrigin());
		if ($form->checkInput()) {
			$origin = $this->originFactory->createByType($form->getInput('object_type'));
			$origin->setTitle($form->getInput('title'));
			$origin->setDescription($form->getInput('description'));
			$origin->save();
			ilUtil::sendSuccess($this->pl->txt('msg_success_create_origin'), true);
			$this->ctrl()->setParameter($this, self::ORIGIN_ID, $origin->getId());
			$this->ctrl()->redirect($this, self::CMD_EDIT_ORGIN);
		}
		$form->setValuesByPost();
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function saveOrigin() {
		/** @var AROrigin $origin */
		$origin = $this->getOrigin((int)$_POST[self::ORIGIN_ID]);
		$this->tpl()->setTitle($origin->getTitle());
		$form = $this->getForm($origin);
		if ($form->checkInput()) {
			$origin->setTitle($form->getInput('title'));
			$origin->setDescription($form->getInput('description'));
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
			$origin->config()->setData($configData);
			$origin->properties()->setData($propertyData);
			$origin->save();
			ilUtil::sendSuccess($this->pl->txt('msg_origin_saved'), true);
			// Try to create the implementation class file automatically
			$generator = new OriginImplementationTemplateGenerator($this->hubConfig);
			try {
				$result = $generator->create($origin);
				if ($result) {
					ilUtil::sendInfo(sprintf($this->pl->txt("msg_created_class_implementation_file"), $generator->getClassFilePath($origin)), true);
				}
			} catch (HubException $e) {
				ilUtil::sendInfo(sprintf($this->pl->txt("msg_created_class_implementation_file_failed"), $generator->getClassFilePath($origin)), true);
			}
			$this->ctrl()->saveParameter($this, self::ORIGIN_ID);
			$this->ctrl()->redirect($this, self::CMD_EDIT_ORGIN);
		}
		$form->setValuesByPost();
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function editOrigin() {
		$origin = $this->getOrigin((int)$_GET[self::ORIGIN_ID]);
		$this->tpl()->setTitle($origin->getTitle());
		$form = $this->getForm($origin);
		$this->tpl()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function activateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(true);
			$repository->save();
		}
		ilUtil::sendSuccess($this->pl->txt('msg_origin_activated'), true);
		$this->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function deactivateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(false);
			$repository->save();
		}
		ilUtil::sendSuccess($this->pl->txt('msg_origin_deactivated'), true);
		$this->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function run() {
		$force = (bool)$this->http()->request()->getParsedBody()[self::Q_FORCE_UPDATE];
		$summary = $this->summaryFactory->web();
		foreach ($this->originFactory->getAllActive() as $origin) {
			/**
			 * @var IOrigin $origin
			 */
			if ($force) {
				$origin->forceUpdate();
			}
			$originSyncFactory = new OriginSyncFactory($origin);
			$originSync = $originSyncFactory->instance();
			try {
				$originSync->execute();
			} catch (Throwable $e) {
				// Any exception being forwarded to here means that we failed to execute the sync at some point
				ilUtil::sendFailure("{$e->getMessage()} in file: {$e->getFile()} line: {$e->getLine()}<pre>{$e->getTraceAsString()}</pre>", true);
			}
			$OriginLog = new OriginLog($originSync->getOrigin());
			$OriginLog->write($summary->getSummaryOfOrigin($originSync));

			$summary->addOriginSync($originSync);
		}
		$summary->sendNotifications();
		ilUtil::sendInfo(nl2br($summary->getOutputAsString()), true);
		$this->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function runOriginSync() {
		$force = (bool)$this->http()->request()->getQueryParams()[self::Q_FORCE_UPDATE];
		/**
		 * @var IOrigin $origin
		 */

		$origin = $this->getOrigin((int)$_GET[self::ORIGIN_ID]);
		if ($force) {
			$origin->forceUpdate();
		}
		$summary = $this->summaryFactory->web();
		$originSyncFactory = new OriginSyncFactory($origin);
		$originSync = $originSyncFactory->instance();
		try {
			$originSync->execute();
		} catch (Throwable $e) {
			// Any exception being forwarded to here means that we failed to execute the sync at some point
			ilUtil::sendFailure("{$e->getMessage()} <pre>{$e->getTraceAsString()}</pre>", true);
		}
		$summary->addOriginSync($originSync);
		$summary->sendNotifications();
		ilUtil::sendInfo(nl2br($summary->getOutputAsString()), true);
		$this->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function confirmDelete() {
		$f = new OriginFactory($this->db());
		$o = $f->getById($this->http()->request()->getQueryParams()[self::ORIGIN_ID]);

		$c = new ilConfirmationGUI();
		$c->setFormAction($this->ctrl()->getFormAction($this));
		$c->addItem(self::ORIGIN_ID, $o->getId(), $o->getTitle());
		$c->setConfirm($this->pl->txt('confirm_delete_button'), self::CMD_DELETE);
		$c->setCancel($this->pl->txt('cancel_delete_button'), self::CMD_INDEX);

		$this->tpl()->setContent($c->getHTML());
	}


	/**
	 *
	 */
	protected function delete() {
		$f = new OriginFactory($this->db());
		$o = $f->getById($this->http()->request()->getParsedBody()[self::ORIGIN_ID]);
		$o->delete();
		$this->ctrl()->redirect($this, self::CMD_INDEX);
	}


	/**
	 * Check access based on plugin configuration.
	 * Returns to personal desktop if a user does not have permission to administrate hub.
	 */
	protected function checkAccess() {
		$roles = array_unique(array_merge($this->hubConfig->getAdministrationRoleIds(), [ 2 ]));
		if (!$this->rbac()->review()->isAssignedToAtLeastOneGivenRole($this->user()->getId(), $roles)) {
			ilUtil::sendFailure($this->language()->txt('permission_denied'), true);
			$this->ctrl()->redirectByClass(ilPersonalDesktopGUI::class);
		}
	}


	/**
	 * @param AROrigin $origin
	 *
	 * @return OriginConfigFormGUI
	 */
	protected function getForm(AROrigin $origin) {
		$formFactory = new OriginFormFactory();
		$formClass = $formFactory->getFormClassNameByOrigin($origin);
		$form = new $formClass($this, $this->hubConfig, new OriginRepository(), $origin);

		return $form;
	}


	/**
	 * @param int $id
	 *
	 * @return AROrigin
	 * @throws ilException
	 */
	protected function getOrigin($id) {
		/** @var AROrigin $origin */
		$origin = $this->originFactory->getById((int)$id);
		if ($origin === NULL) {
			throw new ilException(sprintf("Origin with ID '%s' not found.", $id));
		}

		return $origin;
	}
}
