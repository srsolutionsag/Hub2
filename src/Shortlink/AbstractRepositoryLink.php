<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilLink;
use ilObject2;

/**
 * Class AbstractRepositoryLink
 *
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractRepositoryLink extends AbstractBaseLink implements IObjectLink {

	/**
	 * @inheritdoc
	 */
	public function doesObjectExist(): bool {
		if (!$this->getILIASId()) {
			return false;
		}

		return ilObject2::_exists($this->getILIASId(), true);
	}


	/**
	 * @inheritdoc
	 */
	public function isAccessGranted(): bool {
		return (bool)self::dic()->access()->checkAccess("read", '', $this->getILIASId());
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessGrantedInternalLink(): string {
		if ($this->isAccessGranted()) {
			return $this->getAccessGrantedExternalLink();
		} else {
			return $this->getAccessDeniedLink();
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessGrantedExternalLink(): string {
		$ref_id = $this->getILIASId();
		$link = $this->generateLink($ref_id);

		return $link;
	}


	/**
	 * @inheritdoc
	 */
	public function getAccessDeniedLink(): string {
		$ref_id = $this->findReadableParent();
		if ($ref_id === 0) {
			return "index.php";
		}

		$link = $this->generateLink($ref_id);

		return $link;
	}


	/**
	 * @return int
	 */
	protected function getILIASId() {
		return $this->object->getILIASId();
	}


	/**
	 * @return int
	 */
	private function findReadableParent(): int {
		$ref_id = $this->getILIASId();

		while ($ref_id AND !self::dic()->access()->checkAccess('read', '', $ref_id) AND $ref_id != 1) {
			$ref_id = (int)self::dic()->tree()->getParentId($ref_id);
		}

		if (!$ref_id || $ref_id === 1) {
			if (!self::dic()->access()->checkAccess('read', '', $ref_id)) {
				return 0;
			}
		}

		return (int)$ref_id;
	}


	/**
	 * @param int $ref_id
	 *
	 * @return mixed|string
	 */
	private function generateLink($ref_id) {
		$link = ilLink::_getLink($ref_id);
		$link = str_replace(ILIAS_HTTP_PATH, "", $link);

		return $link;
	}
}
