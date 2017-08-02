<?php

use SRAG\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use SRAG\Hub2\Exception\AbortSyncException;
use SRAG\Hub2\Exception\HubException;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Config\HubConfig;
use SRAG\Hub2\Origin\OriginImplementationTemplateGenerator;
use SRAG\Hub2\Origin\OriginRepository;
use SRAG\Hub2\Sync\OriginSyncFactory;
use SRAG\Hub2\UI\OriginConfigFormGUI;

require_once(__DIR__ . '/class.ilHub2Plugin.php');

/**
 * Class hub2ConfigOriginsGUI
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy hub2ConfigOriginsGUI: ilUIPluginRouterGUI
 */
class hub2ConfigOriginsGUI {

	/**
	 * @var \ILIAS\DI\Container
	 */
	protected $DIC;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilHub2Plugin
	 */
	protected $pl;
	/**
	 * @var \SRAG\Hub2\Origin\OriginFactory
	 */
	protected $originFactory;
	/**
	 * @var HubConfig
	 */
	protected $hubConfig;
	/**
	 * @var OriginRepository
	 */
	protected $originRepository;

	public function __construct() {
		global $DIC;
		$this->DIC = $DIC;
		$this->tpl = $DIC['tpl'];
		$this->tpl->getStandardTemplate();
		$this->pl = ilHub2Plugin::getInstance();
		$this->originFactory = new \SRAG\Hub2\Origin\OriginFactory($DIC->database());
		$this->hubConfig = new HubConfig();
		$this->originRepository = new OriginRepository();
	}

	public function executeCommand() {
		$this->checkAccess();
		$this->tpl->setTitle('Hub2');
		$cmd = $this->DIC->ctrl()->getCmd('index');
		$this->$cmd();
		$this->tpl->show();
	}

	protected function index() {
		$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('origin_table_button_add'), false);
		$button->setUrl($this->DIC->ctrl()->getLinkTarget($this, 'addOrigin'));
		$this->DIC->toolbar()->addButtonInstance($button);
		$table = new \SRAG\Hub2\UI\OriginsTableGUI($this, 'index', new OriginRepository());
		$this->tpl->setContent($table->getHTML());
	}

	protected function cancel() {
		$this->index();
	}

	protected function addOrigin() {
		$form = new OriginConfigFormGUI($this, $this->hubConfig, new OriginRepository(), new \SRAG\Hub2\Origin\ARUserOrigin());
		$this->tpl->setContent($form->getHTML());
	}

	protected function createOrigin() {
		$form = new OriginConfigFormGUI($this, $this->hubConfig, new OriginRepository(), new \SRAG\Hub2\Origin\ARUserOrigin());
		if ($form->checkInput()) {
			$origin = $this->originFactory->createByType($form->getInput('object_type'));
			$origin->setTitle($form->getInput('title'));
			$origin->setDescription($form->getInput('description'));
			$origin->save();
			ilUtil::sendSuccess($this->pl->txt('msg_success_create_origin'), true);
			$this->DIC->ctrl()->setParameter($this, 'origin_id', $origin->getId());
			$this->DIC->ctrl()->redirect($this, 'editOrigin');
		}
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}

	protected function saveOrigin() {
		/** @var AROrigin $origin */
		$origin = $this->getOrigin((int)$_POST['origin_id']);
		$this->tpl->setTitle($origin->getTitle());
		$form = $this->getForm($origin);
		if ($form->checkInput()) {
			$origin->setTitle($form->getInput('title'));
			$origin->setDescription($form->getInput('description'));
			$origin->setActive($form->getInput('active'));
			$origin->setImplementationClassName($form->getInput('implementation_class_name'));
			// Get the config data as an array
			$configData = [];
			$propertyData = [];
			foreach ($form->getInputItemsRecursive() as $item) {
				if (strpos($item->getPostVar(), 'config_') === 0) {
					$key = substr($item->getPostVar(), 7);
					$configData[$key] = $form->getInput($item->getPostVar());
				} else if (strpos($item->getPostVar(), 'prop_') === 0) {
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
					ilUtil::sendInfo("Created class implementation file: " . $generator->getClassFilePath($origin), true);
				}
			} catch (HubException $e) {
				$msg = 'Unable to create class implementation file, you must create it manually at: '
					. $generator->getClassFilePath($origin);
				ilUtil::sendInfo($msg, true);
			}
			$this->DIC->ctrl()->saveParameter($this, 'origin_id');
			$this->DIC->ctrl()->redirect($this, 'editOrigin');
		}
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}

	protected function editOrigin() {
		$origin = $this->getOrigin((int)$_GET['origin_id']);
		$this->tpl->setTitle($origin->getTitle());
		$form = $this->getForm($origin);
		$this->tpl->setContent($form->getHTML());
	}

	protected function activateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(true);
			$repository->save();
		}
		ilUtil::sendSuccess($this->pl->txt('msg_origin_activated'), true);
		$this->DIC->ctrl()->redirect($this);
	}

	protected function deactivateAll() {
		foreach ($this->originRepository->all() as $repository) {
			$repository->setActive(false);
			$repository->save();
		}
		ilUtil::sendSuccess($this->pl->txt('msg_origin_deactivated'), true);
		$this->DIC->ctrl()->redirect($this);
	}

	protected function runOriginSync() {
		$origin = $this->getOrigin((int)$_GET['origin_id']);
		$originSyncFactory = new OriginSyncFactory($origin);
		$originSync = $originSyncFactory->instance();
		try {
			$originSync->execute();
			// Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
			$msg =  "Counts:\n**********\n";
			$msg .= "Delivered data sets: " . $originSync->getCountDelivered() . "\n";
			$msg .= "Created: " . $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED) . "\n";
			$msg .= "Updated: " . $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED) . "\n";
			$msg .= "Deleted: " . $originSync->getCountProcessedByStatus(IObject::STATUS_DELETED) . "\n";
			$msg .= "Ignored: " . $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED) . "\n\n";
			foreach ($originSync->getNotifications()->getMessages() as $context => $messages) {
				$msg .= "$context:\n**********\n";
				foreach ($messages as $message) {
					$msg .= "$message\n";
				}
				$msg .= "\n";
			}
			foreach ($originSync->getExceptions() as $exception) {
				$msg .= "Exceptions:\n**********\n";
				$msg .= $exception->getMessage() . "\n";
			}
			ilUtil::sendInfo(nl2br($msg), true);
		} catch (\Exception $e) {
			// Any exception being forwarded to here means that we failed to execute the sync at some point
			ilUtil::sendFailure($e->getMessage(), true);
		}
		$this->DIC->ctrl()->redirect($this);
	}

	protected function confirmDelete() {
		// TODO
		$this->tpl->setContent('TODO');
	}

	/**
	 * Check access based on plugin configuration.
	 * Returns to personal desktop if a user does not have permission to administrate hub.
	 */
	protected function checkAccess() {
		$roles = array_unique(array_merge(
			$this->hubConfig->getAdministrationRoleIds(),
			[2]
		));
		if (!$this->DIC->rbac()->review()->isAssignedToAtLeastOneGivenRole($this->DIC->user()->getId(), $roles)) {
			ilUtil::sendFailure($this->DIC->language()->txt('permission_denied'), true);
			$this->DIC->ctrl()->redirectByClass('ilpersonaldesktopgui');
		}
	}

	/**
	 * @param AROrigin $origin
	 * @return OriginConfigFormGUI
	 */
	protected function getForm(AROrigin $origin) {
		$formClass = "SRAG\\Hub2\\UI\\" . ucfirst($origin->getObjectType()) . 'OriginConfigFormGUI';
		$form = new $formClass($this, $this->hubConfig, new OriginRepository(), $origin);
		return $form;
	}

	/**
	 * @param int $id
	 * @return AROrigin
	 * @throws ilException
	 */
	protected function getOrigin($id) {
		/** @var AROrigin $origin */
		$origin = $this->originFactory->getById((int)$id);
		if ($origin === null) {
			throw new \ilException(sprintf("Origin with ID '%s' not found.", $id));
		}
		return $origin;
	}

}