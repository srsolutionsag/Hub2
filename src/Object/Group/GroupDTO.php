<?php

namespace SRAG\Hub2\Object\Group;

use SRAG\Hub2\Object\DataTransferObject;

/**
 * Class GroupDTO
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupDTO extends DataTransferObject {

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
	 * @var bool
	 */
	protected $regEnabled;
	/**
	 * @var bool
	 */
	protected $regUnlimited;
	/**
	 * @var int timestamp
	 */
	protected $regStart;
	/**
	 * @var int timestamp
	 */
	protected $regEnd;
	/**
	 * @var string
	 */
	protected $regPassword;
	/**
	 * @var bool
	 */
	protected $regMembershipLimitation;
	/**
	 * @var int
	 */
	protected $regMinMembers;
	/**
	 * @var int
	 */
	protected $reg_MaxMembers;
	/**
	 * @var bool
	 */
	protected $waitingList;
	/**
	 * @var bool
	 */
	protected $autoFillFromWaiting;
	/**
	 * @var int timestamp
	 */
	protected $leaveEnd;
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
	protected $enablemap;
	/**
	 * @var bool
	 */
	protected $regAccessCodeEnabled;
	/**
	 * @var string
	 */
	protected $regAccessCode;
	/**
	 * @var int
	 */
	protected $viewMode;


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
	 * @return bool
	 */
	public function isRegEnabled() {
		return $this->regEnabled;
	}


	/**
	 * @param bool $regEnabled
	 *
	 * @return GroupDTO
	 */
	public function setRegEnabled($regEnabled) {
		$this->regEnabled = $regEnabled;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isRegUnlimited() {
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
	public function getRegStart() {
		return $this->regStart;
	}


	/**
	 * @param int $regStart
	 *
	 * @return GroupDTO
	 */
	public function setRegStart($regStart) {
		$this->regStart = $regStart;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegEnd() {
		return $this->regEnd;
	}


	/**
	 * @param int $regEnd
	 *
	 * @return GroupDTO
	 */
	public function setRegEnd($regEnd) {
		$this->regEnd = $regEnd;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getRegPassword() {
		return $this->regPassword;
	}


	/**
	 * @param string $regPassword
	 *
	 * @return GroupDTO
	 */
	public function setRegPassword($regPassword) {
		$this->regPassword = $regPassword;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isRegMembershipLimitation() {
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
	public function getRegMinMembers() {
		return $this->regMinMembers;
	}


	/**
	 * @param int $regMinMembers
	 *
	 * @return GroupDTO
	 */
	public function setRegMinMembers($regMinMembers) {
		$this->regMinMembers = $regMinMembers;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegMaxMembers() {
		return $this->reg_MaxMembers;
	}


	/**
	 * @param int $reg_MaxMembers
	 *
	 * @return GroupDTO
	 */
	public function setRegMaxMembers($reg_MaxMembers) {
		$this->reg_MaxMembers = $reg_MaxMembers;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isWaitingList() {
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
	public function isAutoFillFromWaiting() {
		return $this->autoFillFromWaiting;
	}


	/**
	 * @param bool $autoFillFromWaiting
	 *
	 * @return GroupDTO
	 */
	public function setAutoFillFromWaiting($autoFillFromWaiting) {
		$this->autoFillFromWaiting = $autoFillFromWaiting;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getLeaveEnd() {
		return $this->leaveEnd;
	}


	/**
	 * @param int $leaveEnd
	 *
	 * @return GroupDTO
	 */
	public function setLeaveEnd($leaveEnd) {
		$this->leaveEnd = $leaveEnd;

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
	public function getEnablemap() {
		return $this->enablemap;
	}


	/**
	 * @param int $enablemap
	 *
	 * @return GroupDTO
	 */
	public function setEnablemap($enablemap) {
		$this->enablemap = $enablemap;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isRegAccessCodeEnabled() {
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
	public function getRegAccessCode() {
		return $this->regAccessCode;
	}


	/**
	 * @param string $regAccessCode
	 *
	 * @return GroupDTO
	 */
	public function setRegAccessCode($regAccessCode) {
		$this->regAccessCode = $regAccessCode;

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
}