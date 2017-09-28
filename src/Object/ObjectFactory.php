<?php namespace SRAG\Hub2\Object;

use SRAG\Hub2\Object\Category\ARCategory;
use SRAG\Hub2\Object\Course\ARCourse;
use SRAG\Hub2\Object\CourseMembership\ARCourseMembership;
use SRAG\Hub2\Object\Session\ARSession;
use SRAG\Hub2\Object\User\ARUser;
use SRAG\Hub2\Origin\IOrigin;

/**
 * Class ObjectFactory
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
 */
class ObjectFactory implements IObjectFactory {

	/**
	 * @var IOrigin
	 */
	protected $origin;


	/**
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}


	/**
	 * @inheritdoc
	 */
	public function user($ext_id) {
		$user = ARUser::find($this->getId($ext_id));
		if ($user === null) {
			$user = new ARUser();
			$user->setOriginId($this->origin->getId());
			$user->setExtId($ext_id);
		}

		return $user;
	}


	/**
	 * @inheritdoc
	 */
	public function course($ext_id) {
		$course = ARCourse::find($this->getId($ext_id));
		if ($course === null) {
			$course = new ARCourse();
			$course->setOriginId($this->origin->getId());
			$course->setExtId($ext_id);
		}

		return $course;
	}


	/**
	 * @inheritdoc
	 */
	public function category($ext_id) {
		$category = ARCategory::find($this->getId($ext_id));
		if ($category === null) {
			$category = new ARCategory();
			$category->setOriginId($this->origin->getId());
			$category->setExtId($ext_id);
		}

		return $category;
	}


	/**
	 * @inheritdoc
	 */
	public function group($ext_id) {
		// TODO: Implement group() method.
	}


	/**
	 * @inheritdoc
	 */
	public function session($ext_id) {
		$session = ARSession::find($this->getId($ext_id));
		if ($session === null) {
			$session = new ARSession();
			$session->setOriginId($this->origin->getId());
			$session->setExtId($ext_id);
		}

		return $session;
	}


	/**
	 * @inheritdoc
	 */
	public function courseMembership($ext_id) {
		$category = ARCourseMembership::find($this->getId($ext_id));
		if ($category === null) {
			$category = new ARCourseMembership();
			$category->setOriginId($this->origin->getId());
			$category->setExtId($ext_id);
		}

		return $category;
	}

	/**
	 * @inheritdoc
	 */
	public function groupMembership($ext_id) {
		// TODO: Implement groupMembership() method.
	}

	//	/**
	//	 * @inheritdoc
	//	 */
	//	public function objectFromDTO(IObjectDTO $dto) {
	//		if ($dto instanceof UserDTO) {
	//			return $this->user($dto->getExtId());
	//		}
	//		return null;
	//	}

	/**
	 * Get the primary ID of an object. In the ActiveRecord implementation, the primary key is a
	 * concatenation of the origins ID with the external-ID, see IObject::create()
	 *
	 * @param string $ext_id
	 *
	 * @return string
	 */
	protected function getId($ext_id) {
		return $this->origin->getId() . $ext_id;
	}
}