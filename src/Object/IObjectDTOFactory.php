<?php namespace SRAG\Hub2\Object;

/**
 * Interface IObjectDTOFactory
 * @package SRAG\Hub2\Object
 */
interface IObjectDTOFactory {

	public function user($ext_id);

	public function course($ext_id);

	public function category($ext_id);

	public function group($ext_id);

	public function session($ext_id);

	public function courseMembership($ext_course_id, $ext_user_id);

	public function groupMembership($ext_group_id, $ext_user_id);
}