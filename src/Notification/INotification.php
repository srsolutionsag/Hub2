<?php namespace SRAG\Hub2\Notification;

/**
 * Interface INotification
 * @package SRAG\Hub2\Notification
 */
interface INotification {

	/**
	 * @return string
	 */
	public function getSubject();

	/**
	 * @return string
	 */
	public function getBody();

}