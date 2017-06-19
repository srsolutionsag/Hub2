<?php namespace SRAG\Hub2\Object;

/**
 * Class UserDTO
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
 */
class UserDTO extends ObjectDTO {

	const GENDER_MALE = 'm';
	const GENDER_FEMALE = 'f';
	const ACCOUNT_TYPE_ILIAS = 'local';
	const ACCOUNT_TYPE_SHIB = 'shibboleth';
	const ACCOUNT_TYPE_LDAP = 'ldap';
	const ACCOUNT_TYPE_RADIUS = 'radius';

	/**
	 * @var array
	 */
	protected static $genders = [
		self::GENDER_MALE,
		self::GENDER_FEMALE
	];

	/**
	 * @var array
	 */
	protected static $account_types = [
		self::ACCOUNT_TYPE_ILIAS,
		self::ACCOUNT_TYPE_SHIB,
		self::ACCOUNT_TYPE_LDAP,
		self::ACCOUNT_TYPE_RADIUS,
	];

	/**
	 * @var int
	 */
	protected $account_type = self::ACCOUNT_TYPE_ILIAS;
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
	protected $email_password;
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
	protected $selected_country;
	/**
	 * @var string
	 */
	protected $phone_office;
	/**
	 * @var string
	 */
	protected $department;
	/**
	 * @var string
	 */
	protected $phone_home;
	/**
	 * @var string
	 */
	protected $phone_mobile;
	/**
	 * @var string
	 */
	protected $fax;
	/**
	 * @var int
	 */
	protected $time_limit_owner;
	/**
	 * @var bool
	 */
	protected $time_limit_unlimited = true;
	/**
	 * @var \DateTime
	 */
	protected $time_limit_from;
	/**
	 * @var \DateTime
	 */
	protected $time_limit_until;
	/**
	 * @var string
	 */
	protected $matriculation;
	/**
	 * @var \DateTime
	 */
	protected $birthday;
	/**
	 * @var array
	 */
	protected $ilias_roles = array();

	/**
	 * @return string
	 */
	public function getPasswd() {
		return $this->passwd;
	}

	/**
	 * @param string $passwd
	 * @return UserDTO
	 */
	public function setPasswd($passwd) {
		$this->passwd = $passwd;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param string $firstname
	 * @return UserDTO
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param string $lastname
	 * @return UserDTO
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * @param string $login
	 * @return UserDTO
	 */
	public function setLogin($login) {
		$this->login = $login;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return UserDTO
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param string $gender
	 * @return UserDTO
	 */
	public function setGender($gender) {
		if (!in_array($gender, self::$genders)) {
			throw new \InvalidArgumentException("'$gender' is not a valid gender");
		}
		$this->gender = $gender;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return UserDTO
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmailPassword() {
		return $this->email_password;
	}

	/**
	 * @param string $email_password
	 * @return UserDTO
	 */
	public function setEmailPassword($email_password) {
		$this->email_password = $email_password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getInstitution() {
		return $this->institution;
	}

	/**
	 * @param string $institution
	 * @return UserDTO
	 */
	public function setInstitution($institution) {
		$this->institution = $institution;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param string $street
	 * @return UserDTO
	 */
	public function setStreet($street) {
		$this->street = $street;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string $city
	 * @return UserDTO
	 */
	public function setCity($city) {
		$this->city = $city;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getZipcode() {
		return $this->zipcode;
	}

	/**
	 * @param int $zipcode
	 * @return UserDTO
	 */
	public function setZipcode($zipcode) {
		$this->zipcode = $zipcode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param string $country
	 * @return UserDTO
	 */
	public function setCountry($country) {
		$this->country = $country;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSelectedCountry() {
		return $this->selected_country;
	}

	/**
	 * @param string $selected_country
	 * @return UserDTO
	 */
	public function setSelectedCountry($selected_country) {
		$this->selected_country = $selected_country;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhoneOffice() {
		return $this->phone_office;
	}

	/**
	 * @param string $phone_office
	 * @return UserDTO
	 */
	public function setPhoneOffice($phone_office) {
		$this->phone_office = $phone_office;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * @param string $department
	 * @return UserDTO
	 */
	public function setDepartment($department) {
		$this->department = $department;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhoneHome() {
		return $this->phone_home;
	}

	/**
	 * @param string $phone_home
	 * @return UserDTO
	 */
	public function setPhoneHome($phone_home) {
		$this->phone_home = $phone_home;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhoneMobile() {
		return $this->phone_mobile;
	}

	/**
	 * @param string $phone_mobile
	 * @return UserDTO
	 */
	public function setPhoneMobile($phone_mobile) {
		$this->phone_mobile = $phone_mobile;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * @param string $fax
	 * @return UserDTO
	 */
	public function setFax($fax) {
		$this->fax = $fax;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTimeLimitOwner() {
		return $this->time_limit_owner;
	}

	/**
	 * @param int $time_limit_owner
	 * @return UserDTO
	 */
	public function setTimeLimitOwner($time_limit_owner) {
		$this->time_limit_owner = $time_limit_owner;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isTimeLimitUnlimited() {
		return $this->time_limit_unlimited;
	}

	/**
	 * @param bool $time_limit_unlimited
	 * @return UserDTO
	 */
	public function setTimeLimitUnlimited($time_limit_unlimited) {
		$this->time_limit_unlimited = $time_limit_unlimited;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getTimeLimitFrom() {
		return $this->time_limit_from;
	}

	/**
	 * @param \DateTime $time_limit_from
	 * @return UserDTO
	 */
	public function setTimeLimitFrom(\DateTime $time_limit_from) {
		$this->time_limit_from = $time_limit_from;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getTimeLimitUntil() {
		return $this->time_limit_until;
	}

	/**
	 * @param \DateTime $time_limit_until
	 * @return UserDTO
	 */
	public function setTimeLimitUntil(\DateTime $time_limit_until) {
		$this->time_limit_until = $time_limit_until;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMatriculation() {
		return $this->matriculation;
	}

	/**
	 * @param string $matriculation
	 * @return UserDTO
	 */
	public function setMatriculation($matriculation) {
		$this->matriculation = $matriculation;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @param \DateTime $birthday
	 * @return UserDTO
	 */
	public function setBirthday(\DateTime $birthday) {
		$this->birthday = $birthday;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getIliasRoles() {
		return $this->ilias_roles;
	}

	/**
	 * @param array $ilias_roles
	 * @return UserDTO
	 */
	public function setIliasRoles($ilias_roles) {
		$this->ilias_roles = $ilias_roles;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAccountType() {
		return $this->account_type;
	}

	/**
	 * @param int $account_type
	 */
	public function setAccountType($account_type) {
		if (!in_array($account_type, self::$account_types)) {
			throw new \InvalidArgumentException("'$account_type' is not a valid account type");
		}
		$this->account_type = $account_type;
	}

}