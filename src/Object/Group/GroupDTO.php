<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DidacticTemplateAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;

/**
 * Class GroupDTO
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupDTO extends DataTransferObject implements IGroupDTO
{
    use MetadataAwareDataTransferObject;
    use TaxonomyAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;
    use DidacticTemplateAwareDataTransferObject;

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
    protected $information;
    /**
     * @var int
     */
    protected $groupType;
    /**
     * @var int
     */
    protected $registrationType;
    /**
     * @var null|bool
     */
    protected $regUnlimited = null;
    /**
     * @var int timestamp
     */
    protected $registrationStart;
    /**
     * @var int timestamp
     */
    protected $registrationEnd;
    /**
     * @var int
     */
    protected $owner;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var bool
     */
    protected $regMembershipLimitation = false;
    /**
     * @var null|int
     */
    protected $minMembers = null;
    /**
     * @var null|int
     */
    protected $maxMembers = null;
    /**
     * @var bool
     */
    protected $waitingList = false;
    /**
     * @var bool
     */
    protected $waitingListAutoFill = false;
    /**
     * @var int timestamp
     */
    protected $cancellationEnd;
    /**
     * @var \ilDateTime
     */
    protected $start;
    /**
     * @var \ilDateTime
     */
    protected $end;
    /**
     * @var float
     */
    protected $latitude;
    /**
     * @var  float
     */
    protected $longitude;
    /**
     * @var int
     */
    protected $locationzoom;
    /**
     * @var int
     */
    protected $enableGroupMap;

    protected $regAccessCodeEnabled = false;
    /**
     * @var string
     */
    protected $registrationAccessCode = '';
    /**
     * @var int
     */
    protected $viewMode;
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
    protected $appointementsColor = '';

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return GroupDTO
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
     * @return GroupDTO
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getRegisterMode() : ?int
    {
        return $this->registrationType;
    }

    public function setRegisterMode(int $registrationType) : self
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param string $information
     * @return GroupDTO
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * @param int $groupType
     * @return GroupDTO
     */
    public function setGroupType($groupType)
    {
        $this->groupType = $groupType;

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
     * @return GroupDTO
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }


    public function getRegUnlimited() : ?bool
    {
        return $this->regUnlimited;
    }


    public function setRegUnlimited(bool $regUnlimited) : self
    {
        $this->regUnlimited = $regUnlimited;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationStart()
    {
        return $this->registrationStart;
    }

    /**
     * @param int $registrationStart
     * @return GroupDTO
     */
    public function setRegistrationStart($registrationStart)
    {
        $this->registrationStart = $registrationStart;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationEnd()
    {
        return $this->registrationEnd;
    }

    /**
     * @param int $registrationEnd
     * @return GroupDTO
     */
    public function setRegistrationEnd($registrationEnd)
    {
        $this->registrationEnd = $registrationEnd;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return GroupDTO
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegMembershipLimitation()
    {
        return $this->regMembershipLimitation ?? false;
    }

    /**
     * @param bool $regMembershipLimitation
     * @return GroupDTO
     */
    public function setRegMembershipLimitation($regMembershipLimitation)
    {
        $this->regMembershipLimitation = $regMembershipLimitation;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinMembers()
    {
        return $this->minMembers;
    }

    /**
     * @param int $minMembers
     * @return GroupDTO
     */
    public function setMinMembers($minMembers)
    {
        $this->minMembers = $minMembers;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxMembers()
    {
        return $this->maxMembers;
    }

    /**
     * @param int $maxMembers
     * @return GroupDTO
     */
    public function setMaxMembers($maxMembers)
    {
        $this->maxMembers = $maxMembers;

        return $this;
    }


    public function getWaitingList() : bool
    {
        return $this->waitingList;
    }


    public function setWaitingList(bool $waitingList) : self
    {
        $this->waitingList = $waitingList;

        return $this;
    }


    public function getWaitingListAutoFill() : bool
    {
        return $this->waitingListAutoFill;
    }

    /**
     * @param bool $waitingListAutoFill
     * @return GroupDTO
     */
    public function setWaitingListAutoFill($waitingListAutoFill)
    {
        $this->waitingListAutoFill = $waitingListAutoFill;

        return $this;
    }

    /**
     * @return int
     */
    public function getCancellationEnd()
    {
        return $this->cancellationEnd;
    }

    /**
     * @param int $cancellationEnd
     * @return GroupDTO
     */
    public function setCancellationEnd($cancellationEnd)
    {
        $this->cancellationEnd = $cancellationEnd;

        return $this;
    }


    public function getStart() : ?\ilDateTime
    {
        return $this->start;
    }


    public function setStart(\ilDateTime $start) : GroupDTO
    {
        $this->start = $start;

        return $this;
    }


    public function getEnd() : ?\ilDateTime
    {
        return $this->end;
    }


    public function setEnd(\ilDateTime $end) : GroupDTO
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return GroupDTO
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return GroupDTO
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return int
     */
    public function getLocationzoom()
    {
        return $this->locationzoom;
    }

    /**
     * @param int $locationzoom
     * @return GroupDTO
     */
    public function setLocationzoom($locationzoom)
    {
        $this->locationzoom = $locationzoom;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnableGroupMap()
    {
        return $this->enableGroupMap;
    }

    /**
     * @param int $enableGroupMap
     * @return GroupDTO
     */
    public function setEnableGroupMap($enableGroupMap)
    {
        $this->enableGroupMap = $enableGroupMap;

        return $this;
    }


    public function getRegAccessCodeEnabled() : bool
    {
        return $this->regAccessCodeEnabled;
    }


    public function setRegAccessCodeEnabled(bool $regAccessCodeEnabled) : self
    {
        $this->regAccessCodeEnabled = $regAccessCodeEnabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrationAccessCode()
    {
        return $this->registrationAccessCode;
    }

    /**
     * @param string $registrationAccessCode
     * @return GroupDTO
     */
    public function setRegistrationAccessCode($registrationAccessCode)
    {
        $this->registrationAccessCode = $registrationAccessCode;

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
     * @return GroupDTO
     */
    public function setViewMode($viewMode)
    {
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
     * @param string $parentId
     * @return GroupDTO
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
     * @return GroupDTO
     */
    public function setParentIdType($parentIdType)
    {
        $this->parentIdType = $parentIdType;

        return $this;
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
}
