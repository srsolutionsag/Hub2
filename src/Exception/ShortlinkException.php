<?php

namespace SRAG\Plugins\Hub2\Exception;

/**
 * Class ShortlinkException
 *
 * @package SRAG\Plugins\Hub2\Exception
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ShortlinkException extends HubException {

	/**
	 * @var string
	 */
	protected $redirect_url = '';


	/**
	 * @inheritDoc
	 */
	public function __construct(string $message, string $redirect_url) {
		$this->redirect_url = $redirect_url;
		parent::__construct($message);
	}
}
