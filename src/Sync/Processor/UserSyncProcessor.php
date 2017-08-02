<?php namespace SRAG\Hub2\Sync\Processor;

use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Object\UserDTO;
use SRAG\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Hub2\Sync\IObjectStatusTransition;

/**
 * Class UserProcessor
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
 */
class UserSyncProcessor extends ObjectSyncProcessor implements IUserSyncProcessor {

	/**
	 * @var UserOriginProperties
	 */
	private $props;

	/**
	 * @var UserOriginConfig
	 */
	private $config;

	/**
	 * @var array
	 */
	protected static $properties = array(
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
	 * @param IOriginImplementation $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog $originLog
	 * @param OriginNotifications $originNotifications
	 */
	public function __construct(IOrigin $origin,
	                            IOriginImplementation $implementation,
	                            IObjectStatusTransition $transition,
	                            ILog $originLog,
								OriginNotifications $originNotifications) {
		parent::__construct($origin, $implementation, $transition, $originLog, $originNotifications);
		$this->props = $origin->properties();
		$this->config = $origin->config();
	}

	/**
	 * @return array
	 */
	public static function getProperties() {
		return self::$properties;
	}


	protected function handleCreate(IDataTransferObject $object) {
		/** @var UserDTO $object */
		$ilObjUser = new \ilObjUser();
		$ilObjUser->setTitle($object->getFirstname() . ' ' . $object->getLastname());
		$ilObjUser->setDescription($object->getEmail());
		$ilObjUser->setImportId($this->getImportId($object));
		$ilObjUser->setLogin($this->buildLogin($object, $ilObjUser));
		$ilObjUser->setUTitle($object->getTitle());
		$ilObjUser->create();
		if ($this->props->get(UserOriginProperties::ACTIVATE_ACCOUNT)) {
			$ilObjUser->setActive(true);
			$ilObjUser->setProfileIncomplete(false);
		} else {
			$ilObjUser->setActive(false);
			$ilObjUser->setProfileIncomplete(true);
		}
		if ($this->props->get(UserOriginProperties::CREATE_PASSWORD)) {
			$password = $this->generatePassword();
			$ilObjUser->setPasswd($password);
		} else {
			$ilObjUser->setPasswd($object->getPasswd());
		}
		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjUser->$setter($object->$getter());
			}
		}
		$ilObjUser->saveAsNew();
		$ilObjUser->writePrefs();
		$this->assignILIASRoles($object, $ilObjUser);

//		if ($this->props->get(UserOriginProperties::SEND_PASSWORD)) {
//			$this->sendPasswordMail($object, $ilObjUser);
//		}
		return $ilObjUser;
	}

	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $object, $ilias_id) {
		/** @var UserDTO $object */
		$ilObjUser = $this->findILIASUser($ilias_id);
		if ($ilObjUser === null) {
			return null;
		}
		$ilObjUser->setImportId($this->getImportId($object));
		$ilObjUser->setTitle($object->getFirstname() . ' ' . $object->getLastname());
		$ilObjUser->setDescription($object->getEmail());
		// Update Login?
		if ($this->props->updateDTOProperty('login')) {
			$ilObjUser->setLogin($this->buildLogin($object, $ilObjUser));
		}
		// Update title?
		if ($this->props->updateDTOProperty('title')) {
			$ilObjUser->setUTitle($object->getTitle());
		}
		// Reactivate account?
		if ($this->props->get(UserOriginProperties::REACTIVATE_ACCOUNT)) {
			$ilObjUser->setActive(true);
		}
		// Set all properties if they should be updated depending on the origin config
		foreach (self::getProperties() as $property) {
			if (!$this->props->updateDTOProperty($property)) {
				continue;
			}
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($object->$getter() !== null) {
				$ilObjUser->$setter($this->$getter());
			}
		}
		// Update ILIAS roles ?
		if ($this->props->updateDTOProperty('iliasRoles')) {
			$this->assignILIASRoles($object, $ilObjUser);
		}
		$ilObjUser->update();
		return $ilObjUser;
	}

	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		$ilObjUser = $this->findILIASUser($ilias_id);
		if ($ilObjUser === null) {
			return null;
		}
		if ($this->props->get(UserOriginProperties::DELETE) == UserOriginProperties::DELETE_MODE_NONE) {
			return $ilObjUser;
		}
		switch ($this->props->get(UserOriginProperties::DELETE)) {
			case UserOriginProperties::DELETE_MODE_INACTIVE:
				$ilObjUser->setActive(false);
				$ilObjUser->update();
				break;
			case UserOriginProperties::DELETE_MODE_DELETE:
				$ilObjUser->delete();
				break;
		}
		return $ilObjUser;
	}

	/**
	 * @param UserDTO $user
	 * @param \ilObjUser $ilObjUser
	 */
	protected function assignILIASRoles(UserDTO $user, \ilObjUser $ilObjUser) {
		global $DIC;
		foreach ($user->getIliasRoles() as $role_id) {
			$DIC['rbacadmin']->assignUser($role_id, $ilObjUser->getId());
		}
	}

	/**
	 * Build the login name depending on the origin properties
	 *
	 * @param UserDTO $user
	 * @param \ilObjUser $ilObjUser
	 * @return string
	 */
	protected function buildLogin(UserDTO $user, \ilObjUser $ilObjUser) {
		switch ($this->config->getILIASLoginField()) {
			case IUserOriginConfig::LOGIN_FIELD_EMAIL:
				$login = $user->getEmail();
				break;
			case IUserOriginConfig::LOGIN_FIELD_EXT_ACCOUNT:
				$login = $user->getExternalAccount();
				break;
			case IUserOriginConfig::LOGIN_FIELD_EXT_ID:
				$login = $user->getExtId();
				break;
			case IUserOriginConfig::LOGIN_FIELD_FIRSTNAME_LASTNAME:
				$login = $this->clearString($user->getFirstname()) . '.' . $this->clearString($user->getLastname());
				break;
			case IUserOriginConfig::LOGIN_FIELD_HUB_LOGIN:
				$login = $user->getLogin();
				break;
			case IUserOriginConfig::LOGIN_FIELD_SHORTENED_FIRST_LASTNAME:
				$login = substr($this->clearString($user->getFirstname()), 0, 1) . '.' . $this->clearString($user->getLastname());
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
	 * @param int $ilias_id
	 * @return \ilObjUser|null
	 */
	protected function findILIASUser($ilias_id) {
		if (!\ilObjUser::_exists($ilias_id)) {
			return null;
		}
		return new \ilObjUser($ilias_id);
	}

	/**
	 * @return string
	 */
	protected function generatePassword() {
		return array_pop(\ilUtil::generatePasswords(1));
	}

}