<?php

namespace SRAG\Plugins\Hub2\Object\Course;

use InvalidArgumentException;
use Serializable;
use SRAG\Plugins\Hub2\Exception\LanguageCodeException;
use SRAG\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use SRAG\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\DataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\TaxonomyAndMetadataAwareDataTransferObject;

/**
 * Class CourseDTO
 *
 * @package SRAG\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseDTO extends DataTransferObject implements ITaxonomyAndMetadataAwareDataTransferObject, IMappingStrategyAwareDataTransferObject {

	use TaxonomyAndMetadataAwareDataTransferObject;
	use MappingStrategyAwareDataTransferObject;
	// @see ilCourseConstants
	const SUBSCRIPTION_TYPE_DEACTIVATED = 1;
	const SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP = 2;
	const SUBSCRIPTION_TYPE_DIRECTLY = 3;
	const SUBSCRIPTION_TYPE_PASSWORD = 4;
	const VIEW_MODE_SESSIONS = 0;
	const VIEW_MODE_OBJECTIVES = 1;
	const VIEW_MODE_TIMING = 2;
	const VIEW_MODE_SIMPLE = 4;
	const VIEW_MODE_BY_TYPE = 5;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	const ACTIVATION_OFFLINE = 0;
	const ACTIVATION_UNLIMITED = 1;
	const ACTIVATION_LIMITED = 2;
	/**
	 * @var array
	 */
	private static $subscriptionTypes = [
		self::SUBSCRIPTION_TYPE_DEACTIVATED,
		self::SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP,
		self::SUBSCRIPTION_TYPE_DIRECTLY,
		self::SUBSCRIPTION_TYPE_PASSWORD,
	];
	/**
	 * @var array
	 */
	private static $viewModes = [
		self::VIEW_MODE_SESSIONS,
		self::VIEW_MODE_OBJECTIVES,
		self::VIEW_MODE_TIMING,
		self::VIEW_MODE_SIMPLE,
		self::VIEW_MODE_BY_TYPE,
	];
	/**
	 * Copied from ilMDLanguageItem::_getPossibleLanguageCodes
	 *
	 * @var string[]
	 */
	private static $available_languages = [
		"aa",
		"ab",
		"af",
		"am",
		"ar",
		"as",
		"ay",
		"az",
		"ba",
		"be",
		"bg",
		"bh",
		"bi",
		"bn",
		"bo",
		"br",
		"ca",
		"co",
		"cs",
		"cy",
		"da",
		"de",
		"dz",
		"el",
		"en",
		"eo",
		"es",
		"et",
		"eu",
		"fa",
		"fi",
		"fj",
		"fo",
		"fr",
		"fy",
		"ga",
		"gd",
		"gl",
		"gn",
		"gu",
		"ha",
		"he",
		"hi",
		"hr",
		"hu",
		"hy",
		"ia",
		"ie",
		"ik",
		"id",
		"is",
		"it",
		"iu",
		"ja",
		"jv",
		"ka",
		"kk",
		"kl",
		"km",
		"kn",
		"ko",
		"ks",
		"ku",
		"ky",
		"la",
		"ln",
		"lo",
		"lt",
		"lv",
		"mg",
		"mi",
		"mk",
		"ml",
		"mn",
		"mo",
		"mr",
		"ms",
		"mt",
		"my",
		"na",
		"ne",
		"nl",
		"no",
		"oc",
		"om",
		"or",
		"pa",
		"pl",
		"ps",
		"pt",
		"qu",
		"rm",
		"rn",
		"ro",
		"ru",
		"rw",
		"sa",
		"sd",
		"sg",
		"sh",
		"si",
		"sk",
		"sl",
		"sm",
		"sn",
		"so",
		"sq",
		"sr",
		"ss",
		"st",
		"su",
		"sv",
		"sw",
		"ta",
		"te",
		"tg",
		"th",
		"ti",
		"tk",
		"tl",
		"tn",
		"to",
		"tr",
		"ts",
		"tt",
		"tw",
		"ug",
		"uk",
		"ur",
		"uz",
		"vi",
		"vo",
		"wo",
		"xh",
		"yi",
		"yo",
		"za",
		"zh",
		"zu"
	];
	/**
	 * @var array
	 */
	private static $parentIdTypes = [
		self::PARENT_ID_TYPE_REF_ID,
		self::PARENT_ID_TYPE_EXTERNAL_EXT_ID,
	];
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var string
	 */
	protected $importantInformation;
	/**
	 * @var string
	 */
	protected $contactResponsibility;
	/**
	 * @var string
	 */
	protected $contactEmail;
	/**
	 * @var string
	 */
	protected $parentId;
	/**
	 * @var int
	 */
	protected $parentIdType = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var string
	 */
	private $firstDependenceCategory;
	/**
	 * @var string
	 */
	private $secondDependenceCategory;
	/**
	 * @var string
	 */
	private $thirdDependenceCategory;
	/**
	 * @var array
	 */
	private $notificationEmails = [];
	/**
	 * @var int
	 */
	protected $owner = 6;
	/**
	 * @var int
	 */
	protected $subscriptionLimitationType = 0;
	/**
	 * @var int
	 */
	protected $viewMode = self::VIEW_MODE_SESSIONS;
	/**
	 * @var string
	 */
	protected $syllabus = '';
	/**
	 * @var string
	 */
	protected $contactName;
	/**
	 * @var string
	 */
	protected $contactConsultation;
	/**
	 * @var string
	 */
	protected $contactPhone;
	/**
	 * @var int
	 */
	protected $activationType = self::ACTIVATION_OFFLINE;
	/**
	 * @var string
	 */
	protected $languageCode = 'en';
	/**
	 * @var int
	 */
	protected $didacticTemplate;
	/**
	 * @var string
	 */
	protected $icon;
	/**
	 * @var Serializable
	 */
	protected $additionalData;


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 *
	 * @return CourseDTO
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 *
	 * @return CourseDTO
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getImportantInformation() {
		return $this->importantInformation;
	}


	/**
	 * @param string $importantInformation
	 *
	 * @return CourseDTO
	 */
	public function setImportantInformation($importantInformation) {
		$this->importantInformation = $importantInformation;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getContactResponsibility() {
		return $this->contactResponsibility;
	}


	/**
	 * @param string $contactResponsibility
	 *
	 * @return CourseDTO
	 */
	public function setContactResponsibility($contactResponsibility) {
		$this->contactResponsibility = $contactResponsibility;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getContactEmail() {
		return $this->contactEmail;
	}


	/**
	 * @param string $contactEmail
	 *
	 * @return CourseDTO
	 */
	public function setContactEmail($contactEmail) {
		$this->contactEmail = $contactEmail;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getFirstDependenceCategory() {
		return $this->firstDependenceCategory;
	}


	/**
	 * @param string $firstDependenceCategory
	 *
	 * @return CourseDTO
	 */
	public function setFirstDependenceCategory($firstDependenceCategory) {
		$this->firstDependenceCategory = $firstDependenceCategory;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getSecondDependenceCategory() {
		return $this->secondDependenceCategory;
	}


	/**
	 * @param string $secondDependenceCategory
	 *
	 * @return CourseDTO
	 */
	public function setSecondDependenceCategory($secondDependenceCategory) {
		$this->secondDependenceCategory = $secondDependenceCategory;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getThirdDependenceCategory() {
		return $this->thirdDependenceCategory;
	}


	/**
	 * @param string $thirdDependenceCategory
	 *
	 * @return CourseDTO
	 */
	public function setThirdDependenceCategory($thirdDependenceCategory) {
		$this->thirdDependenceCategory = $thirdDependenceCategory;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getNotificationEmails() {
		return $this->notificationEmails;
	}


	/**
	 * @param array $notificationEmails
	 *
	 * @return CourseDTO
	 */
	public function setNotificationEmails($notificationEmails) {
		$this->notificationEmails = $notificationEmails;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getOwner() {
		return $this->owner;
	}


	/**
	 * @param int $owner
	 *
	 * @return CourseDTO
	 */
	public function setOwner($owner) {
		$this->owner = (int)$owner;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getSubscriptionLimitationType() {
		return $this->subscriptionLimitationType;
	}


	/**
	 * @param int $subscriptionLimitationType
	 *
	 * @return CourseDTO
	 */
	public function setSubscriptionLimitationType($subscriptionLimitationType) {
		if (!in_array($subscriptionLimitationType, self::$subscriptionTypes)) {
			throw new InvalidArgumentException("Given $subscriptionLimitationType does not exist");
		}
		$this->subscriptionLimitationType = $subscriptionLimitationType;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getViewMode() {
		return $this->viewMode;
	}


	/**
	 * @param int $viewMode
	 *
	 * @return CourseDTO
	 */
	public function setViewMode($viewMode) {
		if (!in_array($viewMode, self::$viewModes)) {
			throw new InvalidArgumentException("Given $viewMode does not exist");
		}
		$this->viewMode = $viewMode;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getParentId() {
		return $this->parentId;
	}


	/**
	 * @param int $parentId
	 *
	 * @return $this
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getParentIdType() {
		return $this->parentIdType;
	}


	/**
	 * @param int $parentIdType
	 *
	 * @return CourseDTO
	 */
	public function setParentIdType($parentIdType) {
		if (!in_array($parentIdType, self::$parentIdTypes)) {
			throw new InvalidArgumentException("Invalid parentIdType given '$parentIdType'");
		}
		$this->parentIdType = $parentIdType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getSyllabus() {
		return $this->syllabus;
	}


	/**
	 * @param string $syllabus
	 *
	 * @return CourseDTO
	 */
	public function setSyllabus($syllabus) {
		$this->syllabus = $syllabus;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getContactName() {
		return $this->contactName;
	}


	/**
	 * @param string $contactName
	 *
	 * @return CourseDTO
	 */
	public function setContactName($contactName) {
		$this->contactName = $contactName;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getContactConsultation() {
		return $this->contactConsultation;
	}


	/**
	 * @param string $contactConsultation
	 *
	 * @return CourseDTO
	 */
	public function setContactConsultation($contactConsultation) {
		$this->contactConsultation = $contactConsultation;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getContactPhone() {
		return $this->contactPhone;
	}


	/**
	 * @param string $contactPhone
	 *
	 * @return CourseDTO
	 */
	public function setContactPhone($contactPhone) {
		$this->contactPhone = $contactPhone;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getActivationType() {
		return $this->activationType;
	}


	/**
	 * @param int $activationType
	 *
	 * @return CourseDTO
	 */
	public function setActivationType($activationType) {
		$this->activationType = $activationType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getLanguageCode() {
		return $this->languageCode;
	}


	/**
	 * @param string $languageCode
	 *
	 * @return CourseDTO
	 * @throws LanguageCodeException
	 */
	public function setLanguageCode($languageCode): CourseDTO {
		if (!self::isLanguageCode($languageCode)) {
			throw new LanguageCodeException($languageCode);
		}

		$this->languageCode = $languageCode;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getDidacticTemplate(): int {
		return (int)$this->didacticTemplate;
	}


	/**
	 * @param int $didacticTemplate
	 *
	 * @return CourseDTO
	 */
	public function setDidacticTemplate(int $didacticTemplate): CourseDTO {
		$this->didacticTemplate = $didacticTemplate;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getIcon(): string {
		return is_string($this->icon) ? $this->icon : '';
	}


	/**
	 * @param string $icon
	 *
	 * @return CourseDTO
	 */
	public function setIcon(string $icon): CourseDTO {
		$this->icon = $icon;

		return $this;
	}


	/**
	 * @return Serializable
	 */
	public function getAdditionalData(): Serializable {
		return unserialize($this->additionalData);
	}


	/**
	 * @param Serializable $additionalData
	 */
	public function setAdditionalData(Serializable $additionalData) {
		$this->additionalData = serialize($additionalData);
	}


	/**
	 * @param string $languageCode
	 *
	 * @return bool
	 */
	public static function isLanguageCode($languageCode): bool {
		return in_array($languageCode, self::$available_languages);
	}
}
