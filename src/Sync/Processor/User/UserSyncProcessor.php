<?php namespace SRAG\Plugins\Hub2\Sync\Processor\User;

use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Metadata\Implementation\MetadataImplementationFactory;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\User\UserDTO;
use SRAG\Plugins\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\Plugins\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class UserProcessor
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Sync\Processor
 */
class UserSyncProcessor extends ObjectSyncProcessor implements IUserSyncProcessor {

	use MetadataSyncProcessor;
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
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications) {
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


	protected function handleCreate(IDataTransferObject $dto) {
		/** @var UserDTO $dto */
		$ilObjUser = new \ilObjUser();
		$ilObjUser->setTitle($dto->getFirstname() . ' ' . $dto->getLastname());
		$ilObjUser->setDescription($dto->getEmail());
		$ilObjUser->setImportId($this->getImportId($dto));
		$ilObjUser->setLogin($this->buildLogin($dto, $ilObjUser));
		$ilObjUser->setUTitle($dto->getTitle());
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
			$ilObjUser->setPasswd($dto->getPasswd());
		}
		foreach (self::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			if ($dto->$getter() !== null) {
				$ilObjUser->$setter($dto->$getter());
			}
		}
		$ilObjUser->saveAsNew();
		$ilObjUser->writePrefs();
		$this->assignILIASRoles($dto, $ilObjUser);

		//		if ($this->props->get(UserOriginProperties::SEND_PASSWORD)) {
		//			$this->sendPasswordMail($object, $ilObjUser);
		//		}
		return $ilObjUser;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id) {
		/** @var UserDTO $dto */
		$ilObjUser = $this->findILIASUser($ilias_id);
		if ($ilObjUser === null) {
			return null;
		}
		$ilObjUser->setImportId($this->getImportId($dto));
		$ilObjUser->setTitle($dto->getFirstname() . ' ' . $dto->getLastname());
		$ilObjUser->setDescription($dto->getEmail());
		// Update Login?
		if ($this->props->updateDTOProperty('login')) {
			$ilObjUser->updateLogin($this->buildLogin($dto, $ilObjUser));
		}
		// Update title?
		if ($this->props->updateDTOProperty('title')) {
			$ilObjUser->setUTitle($dto->getTitle());
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
			if ($dto->$getter() !== null) {
				$ilObjUser->$setter($dto->$getter());
			}
		}
		// Update ILIAS roles ?
		if ($this->props->updateDTOProperty('iliasRoles')) {
			$this->assignILIASRoles($dto, $ilObjUser);
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
		if ($this->props->get(UserOriginProperties::DELETE)
		    == UserOriginProperties::DELETE_MODE_NONE) {
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
	 * @param UserDTO    $user
	 * @param \ilObjUser $ilObjUser
	 */
	protected function assignILIASRoles(UserDTO $user, \ilObjUser $ilObjUser) {
		global $DIC;
		foreach ($user->getIliasRoles() as $role_id) {
			$DIC->rbac()->admin()->assignUser($role_id, $ilObjUser->getId());
		}
	}


	/**
	 * Build the login name depending on the origin properties
	 *
	 * @param UserDTO    $user
	 * @param \ilObjUser $ilObjUser
	 *
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
				$login = $this->clearString($user->getFirstname()) . '.'
				         . $this->clearString($user->getLastname());
				break;
			case IUserOriginConfig::LOGIN_FIELD_HUB_LOGIN:
				$login = $user->getLogin();
				break;
			case IUserOriginConfig::LOGIN_FIELD_SHORTENED_FIRST_LASTNAME:
				$login = substr($this->clearString($user->getFirstname()), 0, 1) . '.'
				         . $this->clearString($user->getLastname());
				break;
			default:
				$login = substr($this->clearString($user->getFirstname()), 0, 1) . '.'
				         . $this->clearString($user->getLastname());
		}
		$login = mb_strtolower($login);

		// We need to make sure the login is unique, note that ILIAS does this currently only on GUI level -.-
		$appendix = 2;
		$_login = $login;
		while (\ilObjUser::_loginExists($login, $ilObjUser->getId())) {
			$login = $_login . $appendix;
			$appendix ++;
		}

		return $login;
	}


	/**
	 * @param int $ilias_id
	 *
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