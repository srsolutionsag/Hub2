<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

/**
 * Interface IObjectRepository
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
interface IObjectRepository {

	/**
	 * Return all objects
	 *
	 * @return IObject[]
	 */
	public function all();

	/**
	 * Return only the objects having the given status
	 *
	 * @param int $status
	 * @return IObject[]
	 */
	public function getByStatus($status);


//	public function getAllWithIntermediateStatus();
//
//
//	public function getAllWithFinalStatus();


	/**
	 * Return the number of objects
	 *
	 * @return int
	 */
	public function count();

}