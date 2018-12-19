<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;

/**
 * Class GroupDTO
 *
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupDTO extends DataTransferObject implements IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject, IMappingStrategyAwareDataTransferObject {

	use MetadataAwareDataTransferObject;
	use TaxonomyAwareDataTransferObject;
	use MappingStrategyAwareDataTransferObject;
	// View
	const VIEW_BY_TYPE = 5;
	// Registration
	const GRP_REGISTRATION_DEACTIVATED = - 1;
	const GRP_REGISTRATION_DIRECT = 0;
	const GRP_REGISTRATION_REQUEST = 1;
	const GRP_REGISTRATION_PASSWORD = 2;
	// Type
	const GRP_REGISTRATION_LIMITED = 1;
	const GRP_REGISTRATION_UNLIMITED = 2;
	const GRP_TYPE_UNKNOWN = 0;
	const GRP_TYPE_CLOSED = 1;
	const GRP_TYPE_OPEN = 2;
	const GRP_TYPE_PUBLIC = 3;
	// Other
	const MAIL_ALLOWED_ALL = 1;
	const MAIL_ALLOWED_TUTORS = 2;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
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
	protected $registerMode;
	/**
	 * @var bool
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
	protected $regMembershipLimitation;
	/**
	 * @var int
	 */
	protected $minMembers;
	/**
	 * @var int
	 */
	protected $maxMembers;
	/**
	 * @var bool
	 */
	protected $waitingList;
	/**
	 * @var bool
	 */
	protected $waitingListAutoFill;
	/**
	 * @var int timestamp
	 */
	protected $cancellationEnd;
	/**
	 * @var int timestamp
	 */
	protected $start;
	/**
	 * @var int timestamp
	 */
	protected $end;
	/**
	 * @var float
	 */
	protected $latitude;
	/**
	 * @var float
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
	/**
	 * @var bool
	 */
	protected $regAccessCodeEnabled;
	/**
	 * @var string
	 */
	protected $registrationAccessCode;
	/**
	 * @var int
	 */
	protected $viewMode;
	/**
	 * @var string
	 */
	private $parentId;
	/**
	 * @var int
	 */
	private $parentIdType = self::PARENT_ID_TYPE_REF_ID;


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 *
	 * @return GroupDTO
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
	 * @return GroupDTO
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegisterMode() {
		return $this->registerMode;
	}


	/**
	 * @param int $registerMode
	 *
	 * @return GroupDTO
	 */
	public function setRegisterMode($registerMode) {
		$this->registerMode = $registerMode;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getInformation() {
		return $this->information;
	}


	/**
	 * @param string $information
	 *
	 * @return GroupDTO
	 */
	public function setInformation($information) {
		$this->information = $information;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getGroupType() {
		return $this->groupType;
	}


	/**
	 * @param int $groupType
	 *
	 * @return GroupDTO
	 */
	public function setGroupType($groupType) {
		$this->groupType = $groupType;

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
	 * @return GroupDTO
	 */
	public function setOwner($owner) {
		$this->owner = $owner;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegUnlimited() {
		return $this->regUnlimited;
	}


	/**
	 * @param bool $regUnlimited
	 *
	 * @return GroupDTO
	 */
	public function setRegUnlimited($regUnlimited) {
		$this->regUnlimited = $regUnlimited;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegistrationStart() {
		return $this->registrationStart;
	}


	/**
	 * @param int $registrationStart
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationStart($registrationStart) {
		$this->registrationStart = $registrationStart;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegistrationEnd() {
		return $this->registrationEnd;
	}


	/**
	 * @param int $registrationEnd
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationEnd($registrationEnd) {
		$this->registrationEnd = $registrationEnd;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}


	/**
	 * @param string $password
	 *
	 * @return GroupDTO
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegMembershipLimitation() {
		return $this->regMembershipLimitation;
	}


	/**
	 * @param bool $regMembershipLimitation
	 *
	 * @return GroupDTO
	 */
	public function setRegMembershipLimitation($regMembershipLimitation) {
		$this->regMembershipLimitation = $regMembershipLimitation;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getMinMembers() {
		return $this->minMembers;
	}


	/**
	 * @param int $minMembers
	 *
	 * @return GroupDTO
	 */
	public function setMinMembers($minMembers) {
		$this->minMembers = $minMembers;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getMaxMembers() {
		return $this->maxMembers;
	}


	/**
	 * @param int $maxMembers
	 *
	 * @return GroupDTO
	 */
	public function setMaxMembers($maxMembers) {
		$this->maxMembers = $maxMembers;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getWaitingList() {
		return $this->waitingList;
	}


	/**
	 * @param bool $waitingList
	 *
	 * @return GroupDTO
	 */
	public function setWaitingList($waitingList) {
		$this->waitingList = $waitingList;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getWaitingListAutoFill() {
		return $this->waitingListAutoFill;
	}


	/**
	 * @param bool $waitingListAutoFill
	 *
	 * @return GroupDTO
	 */
	public function setWaitingListAutoFill($waitingListAutoFill) {
		$this->waitingListAutoFill = $waitingListAutoFill;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getCancellationEnd() {
		return $this->cancellationEnd;
	}


	/**
	 * @param int $cancellationEnd
	 *
	 * @return GroupDTO
	 */
	public function setCancellationEnd($cancellationEnd) {
		$this->cancellationEnd = $cancellationEnd;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getStart() {
		return $this->start;
	}


	/**
	 * @param int $start
	 *
	 * @return GroupDTO
	 */
	public function setStart($start) {
		$this->start = $start;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getEnd() {
		return $this->end;
	}


	/**
	 * @param int $end
	 *
	 * @return GroupDTO
	 */
	public function setEnd($end) {
		$this->end = $end;

		return $this;
	}


	/**
	 * @return float
	 */
	public function getLatitude() {
		return $this->latitude;
	}


	/**
	 * @param float $latitude
	 *
	 * @return GroupDTO
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;

		return $this;
	}


	/**
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}


	/**
	 * @param float $longitude
	 *
	 * @return GroupDTO
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getLocationzoom() {
		return $this->locationzoom;
	}


	/**
	 * @param int $locationzoom
	 *
	 * @return GroupDTO
	 */
	public function setLocationzoom($locationzoom) {
		$this->locationzoom = $locationzoom;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getEnableGroupMap() {
		return $this->enableGroupMap;
	}


	/**
	 * @param int $enableGroupMap
	 *
	 * @return GroupDTO
	 */
	public function setEnableGroupMap($enableGroupMap) {
		$this->enableGroupMap = $enableGroupMap;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegAccessCodeEnabled() {
		return $this->regAccessCodeEnabled;
	}


	/**
	 * @param bool $regAccessCodeEnabled
	 *
	 * @return GroupDTO
	 */
	public function setRegAccessCodeEnabled($regAccessCodeEnabled) {
		$this->regAccessCodeEnabled = $regAccessCodeEnabled;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getRegistrationAccessCode() {
		return $this->registrationAccessCode;
	}


	/**
	 * @param string $registrationAccessCode
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationAccessCode($registrationAccessCode) {
		$this->registrationAccessCode = $registrationAccessCode;

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
	 * @return GroupDTO
	 */
	public function setViewMode($viewMode) {
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
	 * @param string $parentId
	 *
	 * @return GroupDTO
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
	 * @return GroupDTO
	 */
	public function setParentIdType($parentIdType) {
		$this->parentIdType = $parentIdType;

		return $this;
	}
}
