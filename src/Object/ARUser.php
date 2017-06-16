<?php namespace SRAG\ILIAS\Plugins\Hub2\Object;

/**
 * Class ARUser
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\Hub2\Object
 */
class ARUser extends ARObject {

	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $passwd;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $firstname;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $lastname;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $login;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $title;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           64
	 */
	protected $gender;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $email;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $email_password;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $institution;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $street;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $city;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $zipcode;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $country;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           8
	 */
	protected $selected_country;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $phone_office;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $department;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $phone_home;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $phone_mobile;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $fax;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $time_limit_owner;
	/**
	 * @var bool
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           4
	 */
	protected $time_limit_unlimited;
	/**
	 * @var \DateTime
	 *
	 * @db_has_field        true
	 * @db_fieldtype        timestamp
	 */
	protected $time_limit_from;
	/**
	 * @var \DateTime
	 *
	 * @db_has_field        true
	 * @db_fieldtype        timestamp
	 */
	protected $time_limit_until;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $matriculation;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        clob
	 */
	protected $image;
	/**
	 * @var \DateTime
	 *
	 * @db_has_field        true
	 * @db_fieldtype        date
	 */
	protected $birthday;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected $account_type;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $external_account;
	/**
	 * @var array
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $ilias_roles = array();


	public function sleep($field_name) {
		$value = $date = $this->{$field_name};
		switch ($field_name) {
			case 'birthday':
			case 'time_limit_from':
			case 'time_limit_until':
				if ($date instanceof \DateTime) {
					return $date->format('Y-m-d H:i:s');
				}
				break;
			case 'ilias_roles':
				return json_encode((array) $value);
		}
		return parent::sleep($field_name);
	}


	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'birthday':
			case 'time_limit_from':
			case 'time_limit_until':
				return $field_value ? new \DateTime($field_value) : null;
			case 'ilias_roles':
				return json_decode($field_value, true);
		}
		return parent::wakeUp($field_name, $field_value);
	}

	/**
	 * @return string
	 */
	public function getPasswd() {
		return $this->passwd;
	}

	/**
	 * @param string $passwd
	 */
	public function setPasswd($passwd) {
		$this->passwd = $passwd;
	}

	/**
	 * @return string
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param string $firstname
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @return string
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param string $lastname
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * @param string $login
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param string $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmailPassword() {
		return $this->email_password;
	}

	/**
	 * @param string $email_password
	 */
	public function setEmailPassword($email_password) {
		$this->email_password = $email_password;
	}

	/**
	 * @return string
	 */
	public function getInstitution() {
		return $this->institution;
	}

	/**
	 * @param string $institution
	 */
	public function setInstitution($institution) {
		$this->institution = $institution;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param string $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return string
	 */
	public function getZipcode() {
		return $this->zipcode;
	}

	/**
	 * @param string $zipcode
	 */
	public function setZipcode($zipcode) {
		$this->zipcode = $zipcode;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param string $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * @return string
	 */
	public function getSelectedCountry() {
		return $this->selected_country;
	}

	/**
	 * @param string $selected_country
	 */
	public function setSelectedCountry($selected_country) {
		$this->selected_country = $selected_country;
	}

	/**
	 * @return string
	 */
	public function getPhoneOffice() {
		return $this->phone_office;
	}

	/**
	 * @param string $phone_office
	 */
	public function setPhoneOffice($phone_office) {
		$this->phone_office = $phone_office;
	}

	/**
	 * @return string
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * @param string $department
	 */
	public function setDepartment($department) {
		$this->department = $department;
	}

	/**
	 * @return string
	 */
	public function getPhoneHome() {
		return $this->phone_home;
	}

	/**
	 * @param string $phone_home
	 */
	public function setPhoneHome($phone_home) {
		$this->phone_home = $phone_home;
	}

	/**
	 * @return string
	 */
	public function getPhoneMobile() {
		return $this->phone_mobile;
	}

	/**
	 * @param string $phone_mobile
	 */
	public function setPhoneMobile($phone_mobile) {
		$this->phone_mobile = $phone_mobile;
	}

	/**
	 * @return string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * @param string $fax
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}

	/**
	 * @return string
	 */
	public function getTimeLimitOwner() {
		return $this->time_limit_owner;
	}

	/**
	 * @param string $time_limit_owner
	 */
	public function setTimeLimitOwner($time_limit_owner) {
		$this->time_limit_owner = $time_limit_owner;
	}

	/**
	 * @return bool
	 */
	public function isTimeLimitUnlimited() {
		return $this->time_limit_unlimited;
	}

	/**
	 * @param bool $time_limit_unlimited
	 */
	public function setTimeLimitUnlimited($time_limit_unlimited) {
		$this->time_limit_unlimited = $time_limit_unlimited;
	}

	/**
	 * @return \DateTime
	 */
	public function getTimeLimitFrom() {
		return $this->time_limit_from;
	}

	/**
	 * @param \DateTime $time_limit_from
	 */
	public function setTimeLimitFrom($time_limit_from) {
		$this->time_limit_from = $time_limit_from;
	}

	/**
	 * @return \DateTime
	 */
	public function getTimeLimitUntil() {
		return $this->time_limit_until;
	}

	/**
	 * @param \DateTime $time_limit_until
	 */
	public function setTimeLimitUntil($time_limit_until) {
		$this->time_limit_until = $time_limit_until;
	}

	/**
	 * @return string
	 */
	public function getMatriculation() {
		return $this->matriculation;
	}

	/**
	 * @param string $matriculation
	 */
	public function setMatriculation($matriculation) {
		$this->matriculation = $matriculation;
	}

	/**
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param string $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @param \DateTime $birthday
	 */
	public function setBirthday($birthday) {
		$this->birthday = $birthday;
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
		$this->account_type = $account_type;
	}

	/**
	 * @return string
	 */
	public function getExternalAccount() {
		return $this->external_account;
	}

	/**
	 * @param string $external_account
	 */
	public function setExternalAccount($external_account) {
		$this->external_account = $external_account;
	}

	/**
	 * @return array
	 */
	public function getIliasRoles() {
		return $this->ilias_roles;
	}

	/**
	 * @param array $ilias_roles
	 */
	public function setIliasRoles($ilias_roles) {
		$this->ilias_roles = $ilias_roles;
	}


	/**
	 * @inheritdoc
	 */
	public static function returnDbTableName() {
		return 'sr_hub2_user';
	}

}