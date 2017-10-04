<?php

require_once(dirname(dirname(__DIR__)) . '/AbstractSyncProcessorTests.php');

use SRAG\Hub2\Object\Group\GroupDTO;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Origin\Config\GroupOriginConfig;
use SRAG\Hub2\Origin\Properties\GroupOriginProperties;
use SRAG\Hub2\Sync\Processor\Group\GroupSyncProcessor;

/**
 * Class GroupSyncProcessorTest
 *
 * Tests on the processor creating/updating/deleting groups
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessorTest extends AbstractSyncProcessorTests {

	const ILIAS_USER_ID = 123;
	const GROUP_REF_ID = 57;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Sync\Processor\Group\IGroupActivities
	 */
	protected $activities;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Object\Group\IGroup
	 */
	protected $iobject;
	/**
	 * @var GroupDTO
	 */
	protected $dto;
	/**
	 * @var Mockery\MockInterface|ilObjGroup
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObject;


	protected function initDTO() {
		$this->dto = new GroupDTO('extIdOfGroup');
		$this->dto->setParentIdType(GroupDTO::PARENT_ID_TYPE_REF_ID)
		          ->setParentId(1)
		          ->setDescription("Description")
		          ->setTitle("Title")
		          ->setInformation("Information")
		          ->setRegType(GroupDTO::GRP_REGISTRATION_LIMITED)
		          ->setGroupType(GroupDTO::GRP_TYPE_CLOSED)
		          ->setRegEnabled(true)
		          ->setRegUnlimited(false)
		          ->setRegStart(time())
		          ->setRegEnd(time() + 30)
		          ->setRegPassword("Password")
		          ->setRegMembershipLimitation(true)
		          ->setRegMinMembers(1)
		          ->setRegMaxMembers(10)
		          ->setWaitingList(true)
		          ->setAutoFillFromWaiting(true)
		          ->setStart(time())
		          ->setEnd(time() + 30)
		          ->setLatitude(7.1234)
		          ->setLongitude(45.1234)
		          ->setLocationzoom(5)
		          ->setEnablemap(true)
		          ->setRegAccessCodeEnabled(true)
		          ->setRegAccessCode("AccessCode");
	}


	protected function initHubObject() {
		$this->iobject = \Mockery::mock('\SRAG\Hub2\Object\Group\IGroup');
		$this->iobject->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->iobject->shouldReceive('setStatus')->once();
		$this->iobject->shouldReceive('save')->once();
	}


	protected function initILIASObject() {
		$this->ilObject = \Mockery::mock('overload:\ilObjGroup', 'ilObject');
		$this->ilObject->shouldReceive('getId')->andReturn(self::ILIAS_USER_ID);
	}


	/**
	 * Setup default mocks
	 */
	protected function setUp() {
		$this->activities = \Mockery::mock('\SRAG\Hub2\Sync\Processor\Group\IGroupActivities');

		$this->initOrigin(new GroupOriginProperties(), new GroupOriginConfig([]));
		$this->setupGeneralDependencies();
		$this->initHubObject();
		$this->initILIASObject();
		$this->initDTO();
	}


	public function tearDown() {
		\Mockery::close();
	}


	/**
	 * Create Group
	 */
	public function test_create_group_with_default_properties() {
		$processor = new GroupSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications, $this->activities);

		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

		$this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfGroup');
		$this->ilObject->shouldReceive('create')->once();
		$this->ilObject->shouldReceive('createReference')->once();
		$this->ilObject->shouldReceive('putInTree')->once();
		$this->ilObject->shouldReceive('setPermissions')->once();

		$this->initDataExpectations();

		$this->ilObject->shouldReceive('update')->once();
		$this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::GROUP_REF_ID);

		$this->iobject->shouldReceive('setILIASId')->once()->with(self::GROUP_REF_ID);

		$processor->process($this->iobject, $this->dto);
	}


	public function test_update_group_with_default_properties() {
		$processor = new GroupSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications, $this->activities);

		$this->iobject->shouldReceive('updateStatus')
		              ->once()
		              ->with(IObject::STATUS_NOTHING_TO_UPDATE);

		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
		$this->iobject->shouldReceive('computeHashCode')
		              ->once()
		              ->andReturn(serialize($this->dto->getData()));
		$this->iobject->shouldReceive('getHashCode')
		              ->once()
		              ->andReturn(serialize($this->dto->getData()));

		$this->originImplementation->shouldNotReceive('beforeUpdateILIASObject'); // Since Data did no change
		$this->originImplementation->shouldNotReceive('afterUpdateILIASObject');

		$this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfGroup');
		$this->ilObject->shouldNotReceive('createReference');
		$this->ilObject->shouldNotReceive('create');
		$this->ilObject->shouldNotReceive('putInTree');
		$this->ilObject->shouldReceive('setPermissions')->once();

		$this->initDataExpectations();

		$this->ilObject->shouldReceive('update')->once();
		$this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::GROUP_REF_ID);

		$this->iobject->shouldNotReceive('setILIASId'); // Since no new ref_id has to be set

		$processor->process($this->iobject, $this->dto);
	}


	protected function initDataExpectations() {
		$this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
		$this->ilObject->shouldReceive('setDescription')
		               ->once()
		               ->with($this->dto->getDescription());
		$this->ilObject->shouldReceive('setInformation')
		               ->once()
		               ->with($this->dto->getInformation());
		$this->ilObject->shouldReceive('setGroupType')->once()->with($this->dto->getGroupType());
		$this->ilObject->shouldReceive('setRegType')->once()->with($this->dto->getRegType());
		$this->ilObject->shouldReceive('setRegEnabled')->once()->with($this->dto->getRegEnabled());
		$this->ilObject->shouldReceive('setOwner')->once()->with($this->dto->getOwner());
		$this->ilObject->shouldReceive('setRegUnlimited')
		               ->once()
		               ->with($this->dto->getRegUnlimited());
		$this->ilObject->shouldReceive('setViewMode')->once()->with($this->dto->getViewMode());
		$this->ilObject->shouldReceive('setRegStart')
		               ->once()
		               ->with($this->dto->getRegStart());
		$this->ilObject->shouldReceive('setRegEnd')->once()->with($this->dto->getRegEnd());
		$this->ilObject->shouldReceive('setRegPassword')
		               ->once()
		               ->with($this->dto->getRegPassword());
		$this->ilObject->shouldReceive('setRegMembershipLimitation')
		               ->once()
		               ->with($this->dto->getRegMembershipLimitation());
		$this->ilObject->shouldReceive('setRegMinMembers')
		               ->once()
		               ->with($this->dto->getRegMinMembers());
		$this->ilObject->shouldReceive('setRegMaxMembers')
		               ->once()
		               ->with($this->dto->getRegMaxMembers());
		$this->ilObject->shouldReceive('setWaitingList')
		               ->once()
		               ->with($this->dto->getWaitingList());
		$this->ilObject->shouldReceive('setAutoFillFromWaiting')
		               ->once()
		               ->with($this->dto->getAutoFillFromWaiting());
		$this->ilObject->shouldReceive('setLeaveEnd')
		               ->once()
		               ->with($this->dto->getLeaveEnd());
		$this->ilObject->shouldReceive('setStart')
		               ->once()
		               ->with($this->dto->getStart());
		$this->ilObject->shouldReceive('setEnd')
		               ->once()
		               ->with($this->dto->getEnd());
		$this->ilObject->shouldReceive('setLatitude')
		               ->once()
		               ->with($this->dto->getLatitude());
		$this->ilObject->shouldReceive('setLongitude')
		               ->once()
		               ->with($this->dto->getLongitude());
		$this->ilObject->shouldReceive('setLocationzoom')
		               ->once()
		               ->with($this->dto->getLocationzoom());
		$this->ilObject->shouldReceive('setEnablemap')
		               ->once()
		               ->with($this->dto->getEnablemap());
		$this->ilObject->shouldReceive('setRegAccessCodeEnabled')
		               ->once()
		               ->with($this->dto->getRegAccessCodeEnabled());
		$this->ilObject->shouldReceive('setRegAccessCode')
		               ->once()
		               ->with($this->dto->getRegAccessCode());
		$this->ilObject->shouldReceive('setViewMode')
		               ->once()
		               ->with($this->dto->getViewMode());
	}
}