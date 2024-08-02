<?php

namespace srag\Plugins\Hub2\Object\Session;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;

/**
 * Class SessionDTO
 * @package srag\Plugins\Hub2\Object\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionDTO extends DataTransferObject implements ISessionDTO
{
    use MetadataAwareDataTransferObject;
    use TaxonomyAwareDataTransferObject;

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
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $location;
    /**
     * @var string
     */
    protected $details;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var int
     */
    protected $registrationType;
    /**
     * @var bool
     */
    protected $registrationLimited = false;
    /**
     * @var int
     */
    protected $registrationMinUsers;
    /**
     * @var int
     */
    protected $registrationMaxUsers;
    /**
     * @var bool
     */
    protected $registrationWaitingList;
    /**
     * @var bool
     */
    protected $waitingListAutoFill;
    /**
     * @var bool
     */
    protected $fullDay = false;
    /**
     * @var int
     */
    protected $start;
    /**
     * @var int
     */
    protected $end;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return SessionDTO
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
     * @return SessionDTO
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return SessionDTO
     */
    public function setLocation($location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     * @return SessionDTO
     */
    public function setDetails($details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SessionDTO
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return SessionDTO
     */
    public function setPhone($phone): self
    {
        $this->phone = $phone;

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
     * @return SessionDTO
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationType()
    {
        return $this->registrationType;
    }

    /**
     * @param int $registrationType
     * @return SessionDTO
     */
    public function setRegistrationType($registrationType): self
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegistrationLimited()
    {
        return $this->registrationLimited;
    }

    /**
     * @param bool $registrationLimited
     * @return SessionDTO
     */
    public function setRegistrationLimited($registrationLimited): self
    {
        $this->registrationLimited = $registrationLimited;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationMinUsers()
    {
        return $this->registrationMinUsers;
    }

    /**
     * @param int $registrationMinUsers
     * @return SessionDTO
     */
    public function setRegistrationMinUsers($registrationMinUsers): self
    {
        $this->registrationMinUsers = $registrationMinUsers;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationMaxUsers()
    {
        return $this->registrationMaxUsers;
    }

    /**
     * @param int $registrationMaxUsers
     * @return SessionDTO
     */
    public function setRegistrationMaxUsers($registrationMaxUsers): self
    {
        $this->registrationMaxUsers = $registrationMaxUsers;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegistrationWaitingList()
    {
        return $this->registrationWaitingList;
    }

    /**
     * @param bool $registrationWaitingList
     * @return SessionDTO
     */
    public function setRegistrationWaitingList($registrationWaitingList): self
    {
        $this->registrationWaitingList = $registrationWaitingList;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWaitingListAutoFill()
    {
        return $this->waitingListAutoFill;
    }

    /**
     * @param bool $waitingListAutoFill
     * @return SessionDTO
     */
    public function setWaitingListAutoFill($waitingListAutoFill): self
    {
        $this->waitingListAutoFill = $waitingListAutoFill;

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
     * @return SessionDTO
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
     * @return SessionDTO
     */
    public function setParentIdType($parentIdType): self
    {
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullDay()
    {
        return $this->fullDay;
    }

    /**
     * @param bool $fullDay
     * @return SessionDTO
     */
    public function setFullDay($fullDay): self
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return SessionDTO
     */
    public function setStart($start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param int $end Unix Timestamp
     * @return SessionDTO
     */
    public function setEnd($end): self
    {
        $this->end = $end;

        return $this;
    }
}
