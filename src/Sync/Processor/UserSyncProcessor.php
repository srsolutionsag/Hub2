<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Exception\ILIASObjectNotFoundException;
use SRAG\Hub2\Object\ARUser;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\IUser;
use SRAG\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Hub2\Sync\IObjectStatusTransition;

/**
 * Class UserProcessor
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
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
		'authMode',
		'externalAccount',
		'firstname',
		'lastname',
		'email',
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
		/** @var ARUser $object */
		$ilObjUser = new \ilObjUser();
		$ilObjUser->setTitle($object->getFirstname() . ' ' . $object->getLastname());
		$ilObjUser->setDescription($object->getEmail());
		$ilObjUser->setImportId($this->getImportId($object));
		$ilObjUser->setLogin($this->buildLogin($object, $ilObjUser));
		$ilObjUser->create();
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
//		if ($this->props->get(UserOriginProperties::SEND_PASSWORD)) {
//			$this->sendPasswordMail($object, $ilObjUser);
//		}
		return $ilObjUser->getId();
	}


	protected function handleUpdate(IObject $object) {
		$ilObjUser = $this->findILIASUser($object);
		$ilObjUser->setImportId($this->getImportId($object));
		$ilObjUser->setTitle($object->getFirstname() . ' ' . $object->getLastname());
		$ilObjUser->setDescription($object->getEmail());
		// Update Login?
		if ($this->props->updateDTOProperty('login')) {
			$ilObjUser->setLogin($this->buildLogin($object, $ilObjUser));
		}
		// Reactivate account?
		if ($this->props->get(UserOriginProperties::REACTIVATE_ACCOUNT)) {
			$ilObjUser->setActive(true);
		}
		// Set all properties if they should be updated depending on the origin config
		foreach (self::$user_properties as $property) {
			if (!$this->props->updateDTOProperty($property)) {
				continue;
			}
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if (method_exists($ilObjUser, $setter) && ($object->$getter() !== null)) {
				$ilObjUser->$setter($this->$getter());
			}
		}
		// Update roles ?
		if ($this->props->updateDTOProperty('roles')) {
			$this->assignRoles($object, $ilObjUser);
		}
		$ilObjUser->update();
	}

	protected function handleDelete(IObject $object) {
		if (!$this->props->get(UserOriginProperties::DELETE)) {
			return;
		}
		$ilObjUser = $this->findILIASUser($object);
		switch ($this->props->get(UserOriginProperties::DELETE)) {
			case UserOriginProperties::DELETE_MODE_INACTIVE:
				$ilObjUser->setActive(false);
				$ilObjUser->update();
				break;
			case UserOriginProperties::DELETE_MODE_DELETE:
				$ilObjUser->delete();
				break;
		}
	}

	/**
	 * @param IUser $user
	 * @param \ilObjUser $ilObjUser
	 */
	protected function assignRoles(IUser $user, \ilObjUser $ilObjUser) {
		global $DIC;
		foreach ($user->getRoles() as $role_id) {
			$DIC['rbacadmin']->assignUser($role_id, $ilObjUser->getId());
		}
	}

	/**
	 * Build the login name depending on the origin properties
	 *
	 * @param IUser $user
	 * @param \ilObjUser $ilObjUser
	 * @return string
	 */
	protected function buildLogin(IUser $user, \ilObjUser $ilObjUser) {
		switch ($this->props->get(UserOriginProperties::LOGIN_FIELD)) {
			case 'email':
				$login = $user->getEmail();
				break;
			case 'external_account':
				$login = $user->getExternalAccount();
				break;
			case 'ext_id':
				$login = $user->getExtId();
				break;
			case 'first_and_lastname':
				$login = $this->clearString($user->getFirstname()) . '.' . $this->clearString($this->getLastname());
				break;
			case 'own':
				$login = $this->getLogin();
				break;
			default:
				$login = substr($this->clearString($user->getFirstname()), 0, 1) . '.' . $this->clearString($user->getLastname());
		}
		$login = mb_strtolower($login);

		// We need to make sure the login is unique, note that ILIAS does this currently only on GUI level -.-
		$appendix = 2;
		$_login = $login;
		while (\ilObjUser::_loginExists($login, $ilObjUser->getId())) {
			$login = $_login . $appendix;
			$appendix++;
		}
		return $login;
	}

	/**
	 * @param IUser $user
	 * @return \ilObjUser
	 * @throws ILIASObjectNotFoundException
	 */
	protected function findILIASUser(IUser $user) {
		if (!\ilObjUser::_exists($user->getILIASId())) {
			throw new ILIASObjectNotFoundException("User does not exist in ILIAS: {$user}");
		}
		return new \ilObjUser($user->getIliasId());
	}

}