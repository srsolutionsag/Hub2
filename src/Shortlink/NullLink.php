<?php namespace SRAG\Plugins\Hub2\Shortlink;

/**
 * Class NullLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class NullLink implements IObjectLink {

	/**
	 * @inheritDoc
	 */
	public function doesObjectExist(): bool {
		return false;
	}


	/**
	 * @inheritDoc
	 */
	public function isAccessGranted(): bool {
		return false;
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedExternalLink(): string {
		return "index.php";
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessDeniedLink(): string {
		return "index.php";
	}


	/**
	 * @inheritDoc
	 */
	public function getNonExistingLink(): string {
		return "index.php";
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedInternalLink(): string {
		return "index.php";
	}
}
