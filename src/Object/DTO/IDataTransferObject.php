<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

/**
 * Data Transfer Objects holding all data of objects in the hub context, e.g.
 * Users, Courses, CourseMemberships...
 *
 * @package SRAG\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDataTransferObject {

	/**
	 * Get the external ID of this object. This ID serves as primary key to identify an object of a
	 * given object type.
	 *
	 * @return string
	 */
	public function getExtId();


	/**
	 * Get the period (aka semester) where this object belongs to. The origin sync only processes
	 * this object if this period equals to the period defined by the origin.
	 *
	 * Return an empty string if this object is active for any period.
	 *
	 * @return string
	 */
	public function getPeriod();


	/**
	 * @param string $period
	 *
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
	 * Set all data as associative array
	 *
	 * @param array $data
	 *
	 * @return $this
	 */
	public function setData(array $data);
}
