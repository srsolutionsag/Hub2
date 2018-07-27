<?php

namespace SRAG\Plugins\Hub2\Notification;

/**
 * Class OriginNotifications
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Notification
 */
class OriginNotifications {

	const CONTEXT_COMMON = 'common';
	/**
	 * Holds all messages of all contexts
	 *
	 * @var array
	 */
	protected $messages = [];


	/**
	 * Add a new message
	 *
	 * @param string $message
	 * @param string $context
	 *
	 * @return $this
	 */
	public function addMessage($message, $context = '') {
		$context = ($context) ? $context : self::CONTEXT_COMMON;
		if (!is_array($this->messages[$context])) {
			$this->messages[$context] = [];
		}
		$this->messages[$context][] = $message;

		return $this;
	}


	/**
	 * Get all messages of all contexts. If you provide a $context, only messages of the given
	 * context are returned.
	 *
	 * @param string $context
	 *
	 * @return array
	 */
	public function getMessages($context = '') {
		return ($context
			&& isset($this->messages[$context])) ? $this->messages[$context] : $this->messages;
	}
}
