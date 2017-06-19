<?php namespace SRAG\Hub2\Origin;

/**
 * Class OriginRepository
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
class OriginRepository implements IOriginRepository {

	/**
	 * @inheritdoc
	 */
	public function users() {
		return ARUserOrigin::where(['object_type' => IOrigin::OBJECT_TYPE_USER])->get();
	}

	/**
	 * @inheritdoc
	 */
	public function all() {
		return array_merge(
			$this->users(),
			$this->categories(),
			$this->courses(),
			$this->courseMemberships(),
			$this->groups(),
			$this->groupMemberships(),
			$this->sessions()
		);
	}

	/**
	 * @inheritdoc
	 */
	public function allActive() {
		return array_filter($this->all(), function($origin) {
			/** @var $origin IOrigin */
			return $origin->isActive();
		});
	}

	/**
	 * @inheritdoc
	 */
	public function courses() {
		// TODO: Implement courses() method.
	}

	/**
	 * @inheritdoc
	 */
	public function categories() {
		// TODO: Implement categories() method.
	}

	/**
	 * @inheritdoc
	 */
	public function courseMemberships() {
		// TODO: Implement courseMemberships() method.
	}

	/**
	 * @inheritdoc
	 */
	public function groups() {
		// TODO: Implement groups() method.
	}

	/**
	 * @inheritdoc
	 */
	public function groupMemberships() {
		// TODO: Implement groupMemberships() method.
	}

	/**
	 * @inheritdoc
	 */
	public function sessions() {
		// TODO: Implement sessions() method.
	}
}