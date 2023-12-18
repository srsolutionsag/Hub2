<?php

namespace srag\Plugins\Hub2\Object\User;

use DateTime;
use InvalidArgumentException;
use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\LanguageCheck;

/**
 * Class UserDTO
 * @package srag\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserDTO extends DataTransferObject implements IUserDTO
{
    private const SQL_DATE_FORMAT = "Y-m-d H:i:s";

    use MetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;
    use LanguageCheck;

    public const AUTH_MODE_LDAP_2 = 'ldap_2';
    public const AUTH_MODE_LDAP_3 = 'ldap_3';
    public const AUTH_MODE_LDAP_4 = 'ldap_4';
    public const AUTH_MODE_LDAP_5 = 'ldap_5';
    /**
     * @var array
     */
    private static $genders
        = [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            self::GENDER_NONE,
            self::GENDER_NEUTRAL,
        ];
    /**
     * @var array
     */
    private static $auth_modes
        = [
            self::AUTH_MODE_ILIAS,
            self::AUTH_MODE_SHIB,
            self::AUTH_MODE_LDAP,
            self::AUTH_MODE_RADIUS,
            self::AUTH_MODE_LDAP_2,
            self::AUTH_MODE_LDAP_3,
            self::AUTH_MODE_LDAP_4,
            self::AUTH_MODE_LDAP_5,
            self::AUTH_MODE_OIDC
        ];
    /**
     * @var string
     */
    protected $authMode = self::AUTH_MODE_ILIAS;
    /**
     * @var string
     */
    protected $externalAccount;
    /**
     * @var string
     */
    protected $passwd;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $gender;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $secondEmail;
    /**
     * @var string
     */
    protected $emailPassword;
    /**
     * @var string
     */
    protected $institution;
    /**
     * @var string
     */
    protected $street;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var int
     */
    protected $zipcode;
    /**
     * @var string
     */
    protected $country;
    /**
     * @var string
     */
    protected $selectedCountry;
    /**
     * @var string
     */
    protected $phoneOffice;
    /**
     * @var string
     */
    protected $department;
    /**
     * @var string
     */
    protected $phoneHome;
    /**
     * @var string
     */
    protected $phoneMobile;
    /**
     * @var string
     */
    protected $fax;
    /**
     * @var int
     */
    protected $timeLimitOwner;
    /**
     * @var bool
     */
    protected $timeLimitUnlimited = true;
    /**
     * @var string
     */
    protected $timeLimitFrom;
    /**
     * @var string
     */
    protected $timeLimitUntil;
    /**
     * @var string
     */
    protected $matriculation;
    /**
     * @var string
     */
    protected $birthday;
    /**
     * @var string
     */
    protected $language = "";
    /**
     * @var array
     * @description usr_prop_ilias_roles_info
     */
    protected $iliasRoles = [self::USER_DEFAULT_ROLE];

    /**
     * @return string
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * @param string $passwd
     * @return UserDTO
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return UserDTO
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return UserDTO
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return UserDTO
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return UserDTO
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return UserDTO
     */
    public function setGender($gender)
    {
        if (!in_array($gender, self::$genders, true)) {
            throw new InvalidArgumentException("'$gender' is not a valid gender");
        }
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserDTO
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondEmail()
    {
        return $this->secondEmail;
    }

    /**
     * @param string $secondEmail
     * @return UserDTO
     */
    public function setSecondEmail($secondEmail)
    {
        $this->secondEmail = $secondEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailPassword()
    {
        return $this->emailPassword;
    }

    /**
     * @param string $emailPassword
     * @return UserDTO
     */
    public function setEmailPassword($emailPassword)
    {
        $this->emailPassword = $emailPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param string $institution
     * @return UserDTO
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return UserDTO
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return UserDTO
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param int $zipcode
     * @return UserDTO
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return UserDTO
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelectedCountry()
    {
        return $this->selectedCountry;
    }

    /**
     * @param string $selectedCountry
     * @return UserDTO
     */
    public function setSelectedCountry($selectedCountry)
    {
        $this->selectedCountry = $selectedCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneOffice()
    {
        return $this->phoneOffice;
    }

    /**
     * @param string $phoneOffice
     * @return UserDTO
     */
    public function setPhoneOffice($phoneOffice)
    {
        $this->phoneOffice = $phoneOffice;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     * @return UserDTO
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneHome()
    {
        return $this->phoneHome;
    }

    /**
     * @param string $phoneHome
     * @return UserDTO
     */
    public function setPhoneHome($phoneHome)
    {
        $this->phoneHome = $phoneHome;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneMobile()
    {
        return $this->phoneMobile;
    }

    /**
     * @param string $phoneMobile
     * @return UserDTO
     */
    public function setPhoneMobile($phoneMobile)
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return UserDTO
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeLimitOwner()
    {
        return $this->timeLimitOwner;
    }

    /**
     * @param int $timeLimitOwner
     * @return UserDTO
     */
    public function setTimeLimitOwner($timeLimitOwner)
    {
        $this->timeLimitOwner = $timeLimitOwner;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTimeLimitUnlimited()
    {
        return $this->timeLimitUnlimited;
    }

    /**
     * @param bool $timeLimitUnlimited
     * @return UserDTO
     */
    public function setTimeLimitUnlimited($timeLimitUnlimited)
    {
        $this->timeLimitUnlimited = $timeLimitUnlimited;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeLimitFrom()
    {
        return $this->timeLimitFrom;
    }

    /**
     * @return UserDTO
     */
    public function setTimeLimitFrom(DateTime $timeLimitFrom)
    {
        $this->timeLimitFrom = $timeLimitFrom->format(self::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeLimitUntil()
    {
        return $this->timeLimitUntil;
    }

    /**
     * @return UserDTO
     */
    public function setTimeLimitUntil(DateTime $timeLimitUntil)
    {
        $this->timeLimitUntil = $timeLimitUntil->format(self::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return string
     */
    public function getMatriculation()
    {
        return $this->matriculation;
    }

    /**
     * @param string $matriculation
     * @return UserDTO
     */
    public function setMatriculation($matriculation)
    {
        $this->matriculation = $matriculation;

        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return UserDTO
     */
    public function setBirthday(DateTime $birthday)
    {
        $this->birthday = $birthday->format(ActiveRecordConfig::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return array
     */
    public function getIliasRoles()
    {
        return $this->iliasRoles;
    }

    /**
     * @param array $iliasRoles
     * @return UserDTO
     */
    public function setIliasRoles($iliasRoles)
    {
        $this->iliasRoles = $iliasRoles;

        return $this;
    }

    /**
     * @return int
     */
    public function getAuthMode()
    {
        return $this->authMode;
    }

    /**
     * @param string $authMode
     */
    public function setAuthMode($authMode)
    {
        if (!in_array($authMode, self::$auth_modes)) {
            throw new InvalidArgumentException("'$authMode' is not a valid account type");
        }
        $this->authMode = $authMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalAccount()
    {
        return $this->externalAccount;
    }

    /**
     * @param string $externalAccount
     * @return UserDTO $this
     */
    public function setExternalAccount($externalAccount)
    {
        $this->externalAccount = $externalAccount;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $language (de, en, ...)
     * @return UserDTO
     * @throws LanguageCodeException if the passed $language is not a valid
     *                  ILIAS language code
     */
    public function setLanguage(string $language)
    {
        self::checkLanguageCode($language);

        $this->language = $language;

        return $this;
    }

    public function __toString()
    {
        return implode(
            ', ',
            [
                "ext_id: " . $this->getExtId(),
                "period: " . $this->getPeriod(),
                "firstname: " . $this->getFirstname(),
                "lastname: " . $this->getLastname(),
                "email: " . $this->getEmail(),
            ]
        );
    }
}
