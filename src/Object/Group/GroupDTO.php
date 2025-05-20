<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    protected $regUnlimited;
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
    protected $minMembers;
    /**
     * @var null|int
     */
    protected $maxMembers;
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
     */
    public function setTitle($title): self
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
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRegisterMode(): ?int
    {
        return $this->registrationType;
    }

    public function setRegisterMode(int $registrationType): self
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
     */
    public function setInformation($information): self
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
     */
    public function setGroupType($groupType): self
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
     */
    public function setOwner($owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRegUnlimited(): ?bool
    {
        return $this->regUnlimited;
    }

    public function setRegUnlimited(bool $regUnlimited): self
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
     */
    public function setRegistrationStart($registrationStart): self
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
     */
    public function setRegistrationEnd($registrationEnd): self
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
     */
    public function setPassword($password): self
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
     */
    public function setRegMembershipLimitation($regMembershipLimitation): self
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
     */
    public function setMinMembers($minMembers): self
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
     */
    public function setMaxMembers($maxMembers): self
    {
        $this->maxMembers = $maxMembers;

        return $this;
    }

    public function getWaitingList(): bool
    {
        return $this->waitingList;
    }

    public function setWaitingList(bool $waitingList): self
    {
        $this->waitingList = $waitingList;

        return $this;
    }

    public function getWaitingListAutoFill(): bool
    {
        return $this->waitingListAutoFill;
    }

    /**
     * @param bool $waitingListAutoFill
     */
    public function setWaitingListAutoFill($waitingListAutoFill): self
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
     */
    public function setCancellationEnd($cancellationEnd): self
    {
        $this->cancellationEnd = $cancellationEnd;

        return $this;
    }

    public function getStart(): ?\ilDateTime
    {
        return $this->start;
    }

    public function setStart(\ilDateTime $start): GroupDTO
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\ilDateTime
    {
        return $this->end;
    }

    public function setEnd(\ilDateTime $end): GroupDTO
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
     */
    public function setLatitude($latitude): self
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
     */
    public function setLongitude($longitude): self
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
     */
    public function setLocationzoom($locationzoom): self
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
     */
    public function setEnableGroupMap($enableGroupMap): self
    {
        $this->enableGroupMap = $enableGroupMap;

        return $this;
    }

    public function getRegAccessCodeEnabled(): bool
    {
        return $this->regAccessCodeEnabled;
    }

    public function setRegAccessCodeEnabled(bool $regAccessCodeEnabled): self
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
     */
    public function setRegistrationAccessCode($registrationAccessCode): self
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
     */
    public function setViewMode($viewMode): self
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
     */
    public function setParentId($parentId): self
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
     */
    public function setParentIdType($parentIdType): self
    {
        $this->parentIdType = $parentIdType;

        return $this;
    }

    public function getAppointementsColor(): string
    {
        return $this->appointementsColor;
    }

    public function setAppointementsColor(string $appointementsColor): self
    {
        $this->appointementsColor = $appointementsColor;

        return $this;
    }
}
