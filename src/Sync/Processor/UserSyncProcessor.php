<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync\Processor;

use SRAG\ILIAS\Plugins\Hub2\Object\IObject;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectDTO;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectFactory;
use SRAG\ILIAS\Plugins\Hub2\Object\IUser;
use SRAG\ILIAS\Plugins\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\ILIAS\Plugins\Hub2\Origin\IOrigin;
use SRAG\ILIAS\Plugins\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\ILIAS\Plugins\Hub2\Sync\IObjectStatusTransition;

/**
 * Class UserProcessor
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Sync\Processor
 */
class UserSyncProcessor extends ObjectSyncProcessor implements IUserSyncProcessor {

	/**
	 * @var IUserOriginConfig
	 */
	private $config;

	/**
	 * @var UserOriginProperties
	 */
	private $props;

	/**
	 * @var array
	 */
	protected static $user_properties = array(
		'institution',
		'street',
		'city',
		'zipcode',
		'country',
		'selectedCountry',
		'phoneOffice',
		'phoneHome',
		'phoneMobile',
		'department',
		'fax',
		'timeLimitOwner',
		'timeLimitUnlimited',
		'timeLimitFrom',
		'timeLimitUntil',
		'matriculation',
		'gender',
		'birthday',
	);

	/**
	 * @param IOrigin $origin
	 * @param IObjectStatusTransition $transition
	 */
	public function __construct(IOrigin $origin, IObjectStatusTransition $transition) {
		parent::__construct($origin, $transition);
		$this->config = $origin->config();
		$this->props = $origin->properties();
	}


	protected function handleCreate(IObject $object) {
		/** @var IUser $object */
		$ilObjUser = new \ilObjUser();
		$ilObjUser->setTitle($object->getFirstname() . ' ' . $object->getLastname());
		$ilObjUser->setDescription($object->getEmail());
		$ilObjUser->setImportId($this->getImportId($object));
		$ilObjUser->create();
		$ilObjUser->setFirstname($object->getFirstname());
		$ilObjUser->setLastname($object->getLastname());
		$ilObjUser->setEmail($object->getEmail());
		if ($this->props->get(UserOriginProperties::ACTIVATE_ACCOUNT)) {
			$ilObjUser->setActive(true);
			$ilObjUser->setProfileIncomplete(false);
		} else {
			$ilObjUser->setActive(false);
			$ilObjUser->setProfileIncomplete(true);
		}
		if ($this->props->get(UserOriginProperties::CREATE_PASSWORD)) {
			// TODO Generate password
		}
		foreach (self::$user_properties as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if (method_exists($ilObjUser, $setter) && ($object->$getter() !== null)) {
				$ilObjUser->$setter($this->$getter());
			}
		}
		$ilObjUser->saveAsNew();
		$ilObjUser->writePrefs();
		$this->assignRoles($object, $ilObjUser);
		if ($this->props->get(UserOriginProperties::SEND_PASSWORD)) {
			$this->sendPasswordMail($object, $ilObjUser);
		}
	}


	protected function sendPasswordMail(IUser $user, \ilObjUser $ilObjUser) {
		// TODO
	}


	/**
	 * @param IUser $user
	 * @param \ilObjUser $ilObjUser
	 */
	protected function assignRoles(IUser $user, \ilObjUser $ilObjUser) {
		if (!$ilObjUser->getId()) {
			return;
		}
		global $DIC;
		foreach ($user->getRoles() as $role_id) {
			$DIC['rbacadmin']->assignUser($role_id, $ilObjUser->getId());
		}
	}

	protected function handleUpdate(IObject $object) {
		// TODO: Implement handleUpdate() method.
	}

	protected function handleDelete(IObject $object) {
		// TODO: Implement handleDelete() method.
	}
}