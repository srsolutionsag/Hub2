<?php
require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Log\OriginLog;
use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\OriginImplementationTemplateGenerator;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\Sync\OriginSyncFactory;
use srag\Plugins\Hub2\Sync\GlobalHook;
use srag\Plugins\Hub2\Sync\Summary\OriginSyncSummaryFactory;
use srag\Plugins\Hub2\UI\OriginConfigFormGUI;
use srag\Plugins\Hub2\UI\OriginFormFactory;
use srag\Plugins\Hub2\UI\OriginsTableGUI;

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
	 * @var OriginFactory
	 */
	protected $originFactory;
	/**
	 * @var IOriginRepository
	 */
	protected $originRepository;


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->originFactory = new OriginFactory();
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
		switch (self::dic()->ctrl()->getNextClass()) {
			case strtolower(hub2DataGUI::class):
				self::dic()->ctrl()->forwardCommand(new hub2DataGUI());
				break;
		}
	}


	/**
	 *
	 */
	protected function initTabs() {
		self::dic()->tabs()->addSubTab(self::SUBTAB_ORIGINS, self::plugin()->translate(self::SUBTAB_ORIGINS), self::dic()->ctrl()
			->getLinkTarget($this, self::CMD_INDEX));
		self::dic()->tabs()->addSubTab(self::SUBTAB_DATA, self::plugin()->translate(self::SUBTAB_DATA), self::dic()->ctrl()
			->getLinkTargetByClass(hub2DataGUI::class, hub2DataGUI::CMD_INDEX));

		self::dic()->tabs()->activateTab(self::TAB_ORIGINS);
		self::dic()->tabs()->activateSubTab(self::SUBTAB_ORIGINS);
	}


	/**
	 *
	 */
	protected function index() {
		self::dic()->toolbar()->setFormAction(self::dic()->ctrl()->getFormAction($this));

		$button = ilSubmitButton::getInstance();
		$button->setCaption(self::plugin()->translate('origin_table_button_add'), false);
		$button->setPrimary(true);
		$button->setCommand(self::CMD_ADD_ORIGIN);
		self::dic()->toolbar()->addButtonInstance($button);

		self::dic()->toolbar()->addSeparator();

		self::dic()->toolbar()->addInputItem(new ilCheckboxInputGUI(self::plugin()->translate('origin_table_button_force'), self::Q_FORCE_UPDATE));

		$button = ilSubmitButton::getInstance();
		$button->setCaption(self::plugin()->translate('origin_table_button_run'), false);
		$button->setCommand(self::CMD_RUN);
		self::dic()->toolbar()->addButtonInstance($button);

		$table = new OriginsTableGUI($this, self::CMD_INDEX, new OriginRepository());
		self::dic()->mainTemplate()->setContent($table->getHTML());
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
		$form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
		self::dic()->mainTemplate()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function createOrigin() {
		$form = new OriginConfigFormGUI($this, new OriginRepository(), new ARUserOrigin());
		if ($form->checkInput()) {
			$origin = $this->originFactory->createByType($form->getInput('object_type'));
			$origin->setTitle($form->getInput('title'));
			$origin->setDescription($form->getInput('description'));
			$origin->save();
			ilUtil::sendSuccess(self::plugin()->translate('msg_success_create_origin'), true);
			self::dic()->ctrl()->setParameter($this, self::ORIGIN_ID, $origin->getId());
			self::dic()->ctrl()->redirect($this, self::CMD_EDIT_ORGIN);
		}
		$form->setValuesByPost();
		self::dic()->mainTemplate()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function saveOrigin() {
		/** @var AROrigin $origin */
		$origin = $this->getOrigin((int)$_POST[self::ORIGIN_ID]);
		self::dic()->mainTemplate()->setTitle($origin->getTitle());
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
			ilUtil::sendSuccess(self::plugin()->translate('msg_origin_saved'), true);
			// Try to create the implementation class file automatically
			$generator = new OriginImplementationTemplateGenerator();
			try {
				$result = $generator->create($origin);
				if ($result) {
					ilUtil::sendInfo(self::plugin()
						->translate("msg_created_class_implementation_file", "", [ $generator->getClassFilePath($origin) ]), true);
				}
			} catch (HubException $e) {
				ilUtil::sendInfo(self::plugin()
					->translate("msg_created_class_implementation_file_failed", "", [ $generator->getClassFilePath($origin) ]), true);
			}
			self::dic()->ctrl()->saveParameter($this, self::ORIGIN_ID);
			self::dic()->ctrl()->redirect($this, self::CMD_EDIT_ORGIN);
		}
		$form->setValuesByPost();
		self::dic()->mainTemplate()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function editOrigin() {
		$origin = $this->getOrigin((int)$_GET[self::ORIGIN_ID]);
		self::dic()->mainTemplate()->setTitle($origin->getTitle());
		$form = $this->getForm($origin);
		self::dic()->mainTemplate()->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function activateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(true);
			$repository->save();
		}
		ilUtil::sendSuccess(self::plugin()->translate('msg_origin_activated'), true);
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function deactivateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(false);
			$repository->save();
		}
		ilUtil::sendSuccess(self::plugin()->translate('msg_origin_deactivated'), true);
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function run() {
		$force = (bool)self::dic()->http()->request()->getParsedBody()[self::Q_FORCE_UPDATE];
		$summary = $this->summaryFactory->web();
        try{
            $global_hook = new GlobalHook();
            if(!$global_hook->beforeSync($this->originFactory->getAllActive())){
                return;
            }
        }catch (Exception $e) {
            $global_hook->handleExceptions($e);
        }
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
                $global_hook->handleExceptions($e);
                // Any exception being forwarded to here means that we failed to execute the sync at some point
				ilUtil::sendFailure("{$e->getMessage()} in file: {$e->getFile()} line: {$e->getLine()}<pre>{$e->getTraceAsString()}</pre>", true);
			}
			$OriginLog = new OriginLog($originSync->getOrigin());
			$OriginLog->write($summary->getSummaryOfOrigin($originSync));

			$summary->addOriginSync($originSync);
		}
		$summary->sendNotifications();
        try{
            $global_hook->afterSync($this->originFactory->getAllActive());
        }catch (Exception $e) {
            $global_hook->handleExceptions($e);
            ilUtil::sendFailure("{$e->getMessage()} in file: {$e->getFile()} line: {$e->getLine()}<pre>{$e->getTraceAsString()}</pre>", true);
        }

        ilUtil::sendInfo(nl2br($summary->getOutputAsString()), true);
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function runOriginSync() {
		$force = (bool)self::dic()->http()->request()->getQueryParams()[self::Q_FORCE_UPDATE];
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
		self::dic()->ctrl()->redirect($this);
	}


	/**
	 *
	 */
	protected function confirmDelete() {
		$f = new OriginFactory();
		$o = $f->getById(self::dic()->http()->request()->getQueryParams()[self::ORIGIN_ID]);

		$c = new ilConfirmationGUI();
		$c->setFormAction(self::dic()->ctrl()->getFormAction($this));
		$c->addItem(self::ORIGIN_ID, $o->getId(), $o->getTitle());
		$c->setConfirm(self::plugin()->translate('confirm_delete_button'), self::CMD_DELETE);
		$c->setCancel(self::plugin()->translate('cancel_delete_button'), self::CMD_INDEX);

		self::dic()->mainTemplate()->setContent($c->getHTML());
	}


	/**
	 *
	 */
	protected function delete() {
		$f = new OriginFactory();
		$o = $f->getById(self::dic()->http()->request()->getParsedBody()[self::ORIGIN_ID]);
		$o->delete();
		self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
	}


	/**
	 * Check access based on plugin configuration.
	 * Returns to personal desktop if a user does not have permission to administrate hub.
	 */
	protected function checkAccess() {
		$roles = array_unique(array_merge(ArConfig::getAdministrationRoleIds(), [ 2 ]));
		if (!self::dic()->rbacreview()->isAssignedToAtLeastOneGivenRole(self::dic()->user()->getId(), $roles)) {
			ilUtil::sendFailure(self::plugin()->translate('permission_denied', "", [], false), true);
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class);
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
		$form = new $formClass($this, new OriginRepository(), $origin);

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
