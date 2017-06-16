<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

/**
 * Data Transfer Objects of Hub objects.
 * These objects are exposed to the implementation of an origin.
 *
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
interface IObjectDTO {

	/**
	 * Get the external ID of this object. This ID serves as primary key to identify an object.
	 *
	 * @return string
	 */
	public function getExtId();

	/**
	 * Get the period (aka semester) where this object belongs to. The origin sync only processes
	 * this object if the current period equals the period returned here.
	 *
	 * Return an empty string if this object is active for any period.
	 *
	 * @return string
	 */
	public function getPeriod();

	/**
	 * @param string $period
	 * @return $this
	 */
	public function setPeriod($period);

	/**
	 * Get all data as associative array
	 *
	 * @return array
	 */
	public function getData();

	/**
	 * Get all properties as array
	 *
	 * @return array
	 */
	public function getProperties();
}