<?php

namespace srag\Plugins\Hub2\Sync\Processor\User;

use ilMimeMail;
use ilObjUser;
use ilUserException;
use ilUtil;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;
use srag\Plugins\Hub2\Origin\Config\User\IUserOriginConfig;
use srag\Plugins\Hub2\Origin\Config\User\UserOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\User\UserProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class UserProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserSyncProcessor extends ObjectSyncProcessor implements IUserSyncProcessor
{
    use MetadataSyncProcessor;

    /**
     * @var UserProperties
     */
    private $props;
    /**
     * @var UserOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static $properties
        = [
            'authMode',
            'externalAccount',
            'firstname',
            'lastname',
            'email',
            'secondEmail',
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
            'language',
            'passwd'
        ];
    /**
     * @var \ilMailMimeSenderFactory
     */
    private $sender_factory;
    /**
     * @var \ilRbacAdmin
     */
    private $rbacadmin;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->sender_factory = $DIC['mail.mime.sender.factory'];
        $this->rbacadmin = $DIC->rbac()->admin();
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param UserDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjUser = new ilObjUser();
        $ilObjUser->setTitle($dto->getFirstname() . ' ' . $dto->getLastname());
        $ilObjUser->setDescription($dto->getEmail());
        $ilObjUser->setImportId($this->getImportId($dto));
        $ilObjUser->setLogin($this->buildLogin($dto, $ilObjUser));
        $ilObjUser->setUTitle($dto->getTitle());
        $ilObjUser->create();
        if ($this->props->get(UserProperties::ACTIVATE_ACCOUNT)) {
            $ilObjUser->setActive(true);
            $ilObjUser->setProfileIncomplete(false);
        } else {
            $ilObjUser->setActive(false);
            $ilObjUser->setProfileIncomplete(true);
        }
        if ($this->props->get(UserProperties::CREATE_PASSWORD)) {
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

        if ($this->props->get(UserProperties::CREATE_PASSWORD)) {
            $password = $this->generatePassword();
            $dto->setPasswd($password);
            $ilObjUser->setPasswd($dto->getPasswd(), IL_PASSWD_PLAIN);
            $this->sendPasswordMail($dto);
        } else {
            $ilObjUser->setPasswd($dto->getPasswd(), IL_PASSWD_PLAIN);
        }

        $ilObjUser->saveAsNew();
        $ilObjUser->writePrefs();
        $this->assignILIASRoles($dto, $ilObjUser);
    }

    /**
     * @inheritdoc
     * @param UserDTO $dto
     * @throws ilUserException
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjUser = $this->findILIASUser($ilias_id);
        if (!$ilObjUser instanceof \ilObjUser) {
            // Recreate deleted users
            $mapped_id = $dto->getMappingStrategy()->map($dto);
            if ($mapped_id === 0 || !$this->findILIASUser($mapped_id) instanceof \ilObjUser) {
                $this->handleCreate($dto);
            } else {
                $this->handleUpdate($dto, $mapped_id);
            }
            return;
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
        if ($this->props->get(UserProperties::REACTIVATE_ACCOUNT)) {
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

        // Update Password?
        if ($this->props->get(UserProperties::UPDATE_PASSWORD) && $dto->getPasswd() !== null && $dto->getPasswd(
            ) !== '') {
            $ilObjUser->resetPassword($dto->getPasswd(), $dto->getPasswd());
        }

        // Passwort zusenden
        if ($this->props->get(UserProperties::RE_SEND_PASSWORD)) {
            $this->sendPasswordMail($dto);
        }

        $ilObjUser->update();
    }

    private function sendPasswordMail(IDataTransferObject $dto) : void
    {
        /** @var UserDTO $dto */

        $mail_field = $dto->getEmail();
        if ($mail_field !== '' && $mail_field !== '0') {
            $mail = new ilMimeMail();
            $mail->From($this->sender_factory->system());
            $mail->To($dto->getEmail());
            $body = $this->props->get(UserProperties::PASSWORD_MAIL_BODY);

            $body = strtr(
                $body,
                ['[PASSWORD]' => $dto->getPasswd(), '[LOGIN]' => $dto->getLogin()]
            );
            $mail->Subject($this->props->get(UserProperties::PASSWORD_MAIL_SUBJECT)); // TODO: Also replace placeholders
            $mail->Body($body);
            $mail->Send();
        }
    }

    /**
     * @inheritdoc
     * @param UserDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjUser = $this->findILIASUser($ilias_id);
        if (!$ilObjUser instanceof \ilObjUser) {
            return;
        }
        if ($this->props->get(UserProperties::DELETE) == UserProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(UserProperties::DELETE)) {
            case UserProperties::DELETE_MODE_INACTIVE:
                $ilObjUser->setActive(false);
                $ilObjUser->update();
                break;
            case UserProperties::DELETE_MODE_DELETE:
                $ilObjUser->delete();
                break;
        }
    }

    protected function assignILIASRoles(UserDTO $user, ilObjUser $ilObjUser)
    {
        foreach ($user->getIliasRoles() as $role_id) {
            $this->rbacadmin->assignUser($role_id, $ilObjUser->getId());
        }
    }

    /**
     * Build the login name depending on the origin properties
     * @return string
     */
    protected function buildLogin(UserDTO $user, ilObjUser $ilObjUser)
    {
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
                $login = substr(
                    $this->clearString($user->getFirstname()),
                    0,
                    1
                ) . '.' . $this->clearString($user->getLastname());
                break;
            default:
                $login = substr(
                    $this->clearString($user->getFirstname()),
                    0,
                    1
                ) . '.' . $this->clearString($user->getLastname());
        }

        if (!$this->config->isKeepCase()) {
            $login = mb_strtolower($login);
        }

        // We need to make sure the login is unique, note that ILIAS does this currently only on GUI level -.-
        $appendix = 2;
        $_login = $login;
        while (ilObjUser::_loginExists($login, $ilObjUser->getId())) {
            $login = $_login . $appendix;
            $appendix++;
        }

        $user->setLogin($login);

        return $login;
    }

    /**
     * @param int $ilias_id
     * @return ilObjUser|null
     */
    protected function findILIASUser($ilias_id)
    {
        if (!ilObjUser::_exists($ilias_id, false, 'usr')) {
            return null;
        }

        return new ilObjUser($ilias_id);
    }

    /**
     * @return string
     */
    protected function generatePassword()
    {
        $generatePasswords = ilUtil::generatePasswords(1);
        return array_pop($generatePasswords);
    }
}
