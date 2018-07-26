<?php namespace SRAG\Plugins\Hub2\Shortlink;

use SRAG\Plugins\Hub2\Object\ARObject;
use SRAG\Plugins\Hub2\Object\User\ARUser;

/**
 * Class NullLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractRepositoryLink extends AbstractBaseLink implements IObjectLink {

	/**
	 * @inheritDoc
	 */
	public function doesObjectExist(): bool {
		if (!$this->object->getILIASId()) {
			return false;
		}

		return \ilObject2::_exists($this->object->getILIASId(), true);
	}


	/**
	 * @inheritDoc
	 */
	public function isAccessGranted(): bool {
		global $DIC;

		return $DIC->access()->checkAccess('read', '', $this->object->getILIASId());
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedInternalLink(): string {
		if ($this->isAccessGranted()) {
			return $this->getAccessGrantedExternalLink();
		} else {
			return $this->getAccessDeniedLink();
		}
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedExternalLink(): string {
		$link = \ilLink::_getLink($this->object->getILIASId());
		$link = str_replace(ILIAS_HTTP_PATH, "", $link);

		return $link;
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessDeniedLink(): string {
		return "";
	}
}
