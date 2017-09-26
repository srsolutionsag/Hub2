<?php namespace SRAG\Hub2\Object;

/**
 * Interface IObjectFactory
 *
 * @package SRAG\Hub2\Object
 */
interface IObjectFactory {

	/**
	 * @param string $ext_id
	 *
	 * @return IUser
	 */
	public function user($ext_id);


	public function course($ext_id);


	/**
	 * @param $ext_id
	 *
	 * @return IObject
	 */
	public function category($ext_id);


	public function group($ext_id);


	public function session($ext_id);


	public function courseMembership($ext_id);


	public function groupMembership($ext_id);

	//	/**
	//	 * @param IObjectDTO $dto
	//	 * @return IObject
	//	 */
	//	public function objectFromDTO(IObjectDTO $dto);
}