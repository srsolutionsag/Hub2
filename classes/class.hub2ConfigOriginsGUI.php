<?php

use SRAG\Hub2\Origin\AROrigin;
use SRAG\Hub2\Config\HubConfig;
use SRAG\Hub2\Origin\OriginRepository;
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

	public function __construct() {
		global $DIC;
		$this->DIC = $DIC;
		$this->tpl = $DIC['tpl'];
		$this->tpl->getStandardTemplate();
		$this->pl = ilHub2Plugin::getInstance();
		$this->originFactory = new \SRAG\Hub2\Origin\OriginFactory($DIC->database());
		$this->hubConfig = new HubConfig();
	}

	public function executeCommand() {
		$this->checkAccess();
		$this->tpl->setTitle($this->pl->txt('origins'));
		$cmd = $this->DIC->ctrl()->getCmd('index');
		$this->$cmd();
		$this->tpl->show();
	}

	protected function index() {
		$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('add_origin'), false);
		$button->setUrl($this->DIC->ctrl()->getLinkTarget($this, 'addOrigin'));
		$this->DIC->toolbar()->addButtonInstance($button);
		$this->tpl->setContent('index');
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
			$class = 'SRAG\Hub2\Origin\AR' . ucfirst($form->getInput('object_type')) . 'Origin';
			/** @var AROrigin $origin */
			$origin = new $class();
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
			$this->DIC->ctrl()->saveParameter($this, 'origin_id');
			$this->DIC->ctrl()->redirect($this, 'editOrigin');
		}
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHTML());
	}

	protected function editOrigin() {
		$origin = $this->getOrigin((int)$_GET['origin_id']);
		$form = $this->getForm($origin);
		$this->tpl->setContent($form->getHTML());
	}

	protected function checkAccess() {
		// TODO
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