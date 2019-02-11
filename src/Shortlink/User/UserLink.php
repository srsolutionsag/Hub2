<?php

namespace srag\Plugins\Hub2\Shortlink\User;

use ilAdministrationGUI;
use ilLink;
use ilObjUser;
use ilObjUserGUI;
use srag\Plugins\Hub2\Shortlink\AbstractBaseLink;
use srag\Plugins\Hub2\Shortlink\IObjectLink;

/**
 * Class UserLink
 *
 *
 * @package srag\Plugins\Hub2\Shortlink\User
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserLink extends AbstractBaseLink implements IObjectLink {

	/**
	 * @inheritdoc
	 */
	public function doesObjectExist(): bool {
		if (!$this->object->getILIASId()) {
			return false;
		}

		return ilObjUser::_exists($this->object->getILIASId(), false);
	}


	/**
	 * @inheritdoc
	 */
	public function isAccessGranted(): bool {
		$userObj = new ilObjUser($this->object->getILIASId());
		if ($userObj->hasPublicProfile()) {
			return true;
		}

		return self::dic()->access()->checkAccess('read', '', 7); // Read access to user administration
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessGrantedExternalLink(): string {
		return ilLink::_getLink($this->object->getILIASId(), 'usr');
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessDeniedLink(): string {
		return "ilias.php";
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessGrantedInternalLink(): string {
		self::dic()->ctrl()->setParameterByClass(ilObjUserGUI::class, "ref_id", 7);
		self::dic()->ctrl()->setParameterByClass(ilObjUserGUI::class, "obj_id", $this->object->getILIASId());

		return self::dic()->ctrl()->getLinkTargetByClass([ ilAdministrationGUI::class, ilObjUserGUI::class ], "view");
	}
}
