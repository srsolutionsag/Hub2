<?php

namespace srag\Plugins\Hub2\Object\Course;

use ilDate;
use InvalidArgumentException;
use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DidacticTemplateAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAndMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\LanguageCheck;
use srag\Plugins\Hub2\Object\DTO\NewsSettingsAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\LearningProgressSettingsAwareDataTransferObject;

/**
 * Class CourseDTO
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseDTO extends DataTransferObject implements ICourseDTO
{
    use TaxonomyAndMetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;
    use DidacticTemplateAwareDataTransferObject;
    use NewsSettingsAwareDataTransferObject;
    use LearningProgressSettingsAwareDataTransferObject;
    use LanguageCheck;

    /**
     * @var array
     */
    private static $subscriptionTypes
        = [
            self::SUBSCRIPTION_TYPE_DEACTIVATED,
            self::SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP,
            self::SUBSCRIPTION_TYPE_DIRECTLY,
            self::SUBSCRIPTION_TYPE_PASSWORD,
        ];
    /**
     * @var array
     */
    private static $viewModes
        = [
            self::VIEW_MODE_SESSIONS,
            self::VIEW_MODE_OBJECTIVES,
            self::VIEW_MODE_TIMING,
            self::VIEW_MODE_SIMPLE,
            self::VIEW_MODE_BY_TYPE,
            self::VIEW_MODE_INHERIT,
        ];
    /**
     * @var array
     */
    private static $parentIdTypes
        = [
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
    protected $firstDependenceCategory;
    /**
     * @var string
     */
    protected $secondDependenceCategory;
    /**
     * @var string
     */
    protected $thirdDependenceCategory;
    /**
     * @var string
     */
    protected $fourthDependenceCategory;
    /**
     * @var int
     */
    protected $template_id = 0;
    /**
     * @var array
     */
    protected $notificationEmails = [];
    /**
     * @var int
     */
    protected $owner = 6;
    /**
     * @var int
     */
    protected $subscriptionLimitationType = self::SUBSCRIPTION_TYPE_DEACTIVATED;
    /**
     * @var int
     */
    protected $viewMode = self::VIEW_MODE_SESSIONS;
    /**
     * @var string
     */
    protected $syllabus = '';
    /**
     * @var string|null
     */
    protected $targetGroup;
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
     * @var bool
     */
    protected $sessionLimitEnabled = false;
    /**
     * @var int
     */
    protected $numberOfPreviousSessions = -1;
    /**
     * @var int
     */
    protected $numberOfNextSessions = -1;
    /**
     * @var int
     */
    protected $orderType = self::SORT_TITLE;
    /**
     * @var int
     */
    protected $orderDirection = self::SORT_DIRECTION_ASC;
    /**
     * @var string
     */
    protected $appointementsColor = '';
    /**
     * @var \ilDateTime|null
     */
    protected $courseStart;
    /**
     * @var \ilDateTime|null
     */
    protected $courseEnd;
    /**
     * @var ilDate|null
     */
    protected $activationStart;
    /**
     * @var ilDate|null
     */
    protected $activationEnd;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CourseDTO
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CourseDTO
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getImportantInformation()
    {
        return $this->importantInformation;
    }

    /**
     * @param string $importantInformation
     * @return CourseDTO
     */
    public function setImportantInformation($importantInformation)
    {
        $this->importantInformation = $importantInformation;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactResponsibility()
    {
        return $this->contactResponsibility;
    }

    /**
     * @param string $contactResponsibility
     * @return CourseDTO
     */
    public function setContactResponsibility($contactResponsibility)
    {
        $this->contactResponsibility = $contactResponsibility;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     * @return CourseDTO
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getFirstDependenceCategory() : ?string
    {
        return $this->firstDependenceCategory;
    }

    /**
     * @param string $firstDependenceCategory
     * @return CourseDTO
     */
    public function setFirstDependenceCategory($firstDependenceCategory)
    {
        $this->firstDependenceCategory = $firstDependenceCategory;

        return $this;
    }

    public function getSecondDependenceCategory() : ?string
    {
        return $this->secondDependenceCategory;
    }

    /**
     * @param string $secondDependenceCategory
     * @return CourseDTO
     */
    public function setSecondDependenceCategory($secondDependenceCategory)
    {
        $this->secondDependenceCategory = $secondDependenceCategory;

        return $this;
    }

    public function getThirdDependenceCategory() : ?string
    {
        return $this->thirdDependenceCategory;
    }

    /**
     * @param string $thirdDependenceCategory
     * @return CourseDTO
     */
    public function setThirdDependenceCategory($thirdDependenceCategory)
    {
        $this->thirdDependenceCategory = $thirdDependenceCategory;

        return $this;
    }

    public function getFourthDependenceCategory() : ?string
    {
        return $this->fourthDependenceCategory;
    }

    /**
     * @return CourseDTO
     */
    public function setFourthDependenceCategory(string $fourthDependenceCategory)
    {
        $this->fourthDependenceCategory = $fourthDependenceCategory;

        return $this;
    }

    public function getTemplateId() : int
    {
        return $this->template_id;
    }

    /**
     * @return $this
     */
    public function setTemplateId(int $template_id)
    {
        $this->template_id = $template_id;

        return $this;
    }

    /**
     * @return array
     */
    public function getNotificationEmails()
    {
        return $this->notificationEmails;
    }

    /**
     * @param array $notificationEmails
     * @return CourseDTO
     */
    public function setNotificationEmails($notificationEmails)
    {
        $this->notificationEmails = $notificationEmails;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return CourseDTO
     */
    public function setOwner($owner)
    {
        $this->owner = (int) $owner;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionLimitationType()
    {
        return $this->subscriptionLimitationType;
    }

    /**
     * @param int $subscriptionLimitationType
     * @return CourseDTO
     */
    public function setSubscriptionLimitationType($subscriptionLimitationType)
    {
        if (!in_array($subscriptionLimitationType, self::$subscriptionTypes)) {
            throw new InvalidArgumentException("Given $subscriptionLimitationType does not exist");
        }
        $this->subscriptionLimitationType = $subscriptionLimitationType;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewMode()
    {
        return $this->viewMode;
    }

    /**
     * @param int $viewMode
     * @return CourseDTO
     */
    public function setViewMode($viewMode)
    {
        if (!in_array($viewMode, self::$viewModes)) {
            throw new InvalidArgumentException("Given $viewMode does not exist");
        }
        $this->viewMode = $viewMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     * @return $this
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentIdType()
    {
        return $this->parentIdType;
    }

    /**
     * @param int $parentIdType
     * @return CourseDTO
     */
    public function setParentIdType($parentIdType)
    {
        if (!in_array($parentIdType, self::$parentIdTypes)) {
            throw new InvalidArgumentException("Invalid parentIdType given '$parentIdType'");
        }
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSyllabus()
    {
        return $this->syllabus;
    }

    /**
     * @param string $syllabus
     * @return CourseDTO
     */
    public function setSyllabus($syllabus)
    {
        $this->syllabus = $syllabus;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     * @return CourseDTO
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactConsultation()
    {
        return $this->contactConsultation;
    }

    /**
     * @param string $contactConsultation
     * @return CourseDTO
     */
    public function setContactConsultation($contactConsultation)
    {
        $this->contactConsultation = $contactConsultation;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     * @return CourseDTO
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * @return int
     */
    public function getActivationType()
    {
        return $this->activationType;
    }

    /**
     * @param int $activationType
     * @return CourseDTO
     */
    public function setActivationType($activationType)
    {
        $this->activationType = $activationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param $languageCode
     * @throws LanguageCodeException if the passed $language is not a valid
     * ILIAS language code
     */
    public function setLanguageCode(string $languageCode) : CourseDTO
    {
        self::checkLanguageCode($languageCode);

        $this->languageCode = $languageCode;

        return $this;
    }

    public function isSessionLimitEnabled() : bool
    {
        return $this->sessionLimitEnabled;
    }

    public function enableSessionLimit(bool $sessionLimitEnabled) : void
    {
        $this->sessionLimitEnabled = $sessionLimitEnabled;
    }

    public function getNumberOfPreviousSessions() : int
    {
        return $this->numberOfPreviousSessions;
    }

    public function setNumberOfPreviousSessions(int $numberOfPreviousSessions) : void
    {
        $this->numberOfPreviousSessions = $numberOfPreviousSessions;
    }

    public function getNumberOfNextSessions() : int
    {
        return $this->numberOfNextSessions;
    }

    public function setNumberOfNextSessions(int $numberOfNextSessions) : void
    {
        $this->numberOfNextSessions = $numberOfNextSessions;
    }

    public function getOrderType() : int
    {
        return $this->orderType;
    }

    public function setOrderType(int $orderType) : void
    {
        $this->orderType = $orderType;
    }

    public function getOrderDirection() : int
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(int $orderDirection) : void
    {
        $this->orderDirection = $orderDirection;
    }

    public function getAppointementsColor() : string
    {
        return $this->appointementsColor;
    }

    public function setAppointementsColor(string $appointementsColor)
    {
        $this->appointementsColor = $appointementsColor;

        return $this;
    }


    public function getCourseStart() : ?\ilDateTime
    {
        return $this->courseStart;
    }


    public function setCourseStart(\ilDateTime $courseStart = null) : self
    {
        $this->courseStart = $courseStart;

        return $this;
    }


    public function getCourseEnd() : ?\ilDateTime
    {
        return $this->courseEnd;
    }


    public function setCourseEnd(\ilDateTime $courseEnd = null) : self
    {
        $this->courseEnd = $courseEnd;

        return $this;
    }

    /**
     * @return ilDate|null
     */
    public function getActivationStart()/*? : ilDate*/
    {
        return $this->activationStart;
    }

    /**
     * @param ilDate|null $activationStart
     */
    public function setActivationStart(/*?*/ ilDate $activationStart = null) : self
    {
        $this->activationStart = $activationStart;

        return $this;
    }

    /**
     * @return ilDate|null
     */
    public function getActivationEnd()/*? : ilDate*/
    {
        return $this->activationEnd;
    }

    /**
     * @param ilDate|null $activationEnd
     */
    public function setActivationEnd(/*?*/ ilDate $activationEnd = null) : self
    {
        $this->activationEnd = $activationEnd;

        return $this;
    }

    public function getTargetGroup() : ?string
    {
        return $this->targetGroup;
    }

    public function setTargetGroup(string $targetGroup) : self
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }
}
