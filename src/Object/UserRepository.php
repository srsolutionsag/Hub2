<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

/**
 * Class UserRepository
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class UserRepository extends ObjectRepository {

	/**
	 * @return IUser[]
	 */
	public function all() {
		return ARUser::get();
	}

	/**
	 * @param int $status
	 * @return IUser[]
	 */
	public function getByStatus($status) {
		return ARUser::where([
			'origin_id' => $this->origin->getId(),
			'status' => (int) $status]
		)->get();
	}

	/**
	 * @inheritdoc
	 */
	public function count() {
		return ArUser::count();
	}
}