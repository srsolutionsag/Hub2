<?php namespace SRAG\Hub2\Object;

use SRAG\Hub2\Origin\IOrigin;

/**
 * Class ObjectFactory
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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

	public function course($ext_id) {
		// TODO: Implement course() method.
	}

	public function category($ext_id) {
		// TODO: Implement category() method.
	}

	public function group($ext_id) {
		// TODO: Implement group() method.
	}

	public function session($ext_id) {
		// TODO: Implement session() method.
	}

	public function courseMembership($ext_id) {
		// TODO: Implement courseMembership() method.
	}

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
	 * @return string
	 */
	protected function getId($ext_id) {
		return $this->origin->getId() . $ext_id;
	}
}