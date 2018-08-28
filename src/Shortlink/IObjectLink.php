<?php

namespace SRAG\Plugins\Hub2\Shortlink;

/**
 * Interface IObjectLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectLink {

	/**
	 * @return bool
	 */
	public function doesObjectExist(): bool;


	/**
	 * @return bool
	 */
	public function isAccessGranted(): bool;


	/**
	 * @return string
	 */
	public function getAccessGrantedExternalLink(): string;


	/**
	 * @return string
	 */
	public function getAccessDeniedLink(): string;


	/**
	 * @return string
	 */
	public function getNonExistingLink(): string;


	/**
	 * @return string
	 */
	public function getAccessGrantedInternalLink(): string;
}
