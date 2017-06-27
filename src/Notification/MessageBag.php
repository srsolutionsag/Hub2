<?php namespace SRAG\Hub2\Notification;

/**
 * Class MessageBag
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Notification
 */
class MessageBag {

	const CONTEXT_COMMON = 'common';

	/**
	 * @var array
	 */
	protected $messages = [];

	/**
	 * Add a new message
	 *
	 * @param string $message
	 * @param string $context
	 * @return $this
	 */
	public function addMessage($message, $context = '') {
		$hash = ($context) ? md5($context) : self::CONTEXT_COMMON;
		if (!is_array($this->messages[$hash])) {
			$this->messages = [];
		}
		$this->messages[$hash][] = $message;
		return $this;
	}


	/**
	 * Get all messages
	 *
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}
}