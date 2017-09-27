<?php

require_once(dirname(dirname(__DIR__)) . '/AbstractSyncProcessorTests.php');

use SRAG\Hub2\Object\Category\CategoryDTO;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\Session\SessionDTO;
use SRAG\Hub2\Origin\Config\CategoryOriginConfig;
use SRAG\Hub2\Origin\Config\SessionOriginConfig;
use SRAG\Hub2\Origin\Properties\CategoryOriginProperties;
use SRAG\Hub2\Origin\Properties\SessionOriginProperties;
use SRAG\Hub2\Sync\Processor\Category\CategorySyncProcessor;
use SRAG\Hub2\Sync\Processor\Session\SessionSyncProcessor;

/**
 * Class SessionSyncProcessorTest
 *
 * Tests on the processor creating/updating/deleting sessions
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionSyncProcessorTest extends AbstractSyncProcessorTests {

	const REF_ID = 57;
	/**
	 * @var Mockery\MockInterface|\ilSessionAppointment
	 */
	protected $appointments;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Object\Session\ISession
	 */
	protected $iobject;
	/**
	 * @var \SRAG\Hub2\Object\Session\SessionDTO
	 */
	protected $dto;
	/**
	 * @var Mockery\MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObject;


	/**
	 * Setup default mocks
	 */
	protected function setUp() {
		$arr = [
			'update_dto_title'       => true,
			'update_dto_description' => true,
			'update_dto_location' => true,
		];
		$this->initOrigin(new SessionOriginProperties($arr), new SessionOriginConfig([]));
		$this->setupGeneralDependencies();
		$this->initHubObject();
		$this->initILIASObject();
		$this->initDTO();
	}


	public function tearDown() {
		\Mockery::close();
	}


	protected function initDataExpectations() {
		require_once('./Services/Calendar/classes/class.ilDateTime.php');
		$session_appointment_mock = \Mockery::mock('overload:\ilSessionAppointment', '\ilDatePeriod');
		$session_appointment_mock->shouldReceive("setStart");
		$session_appointment_mock->shouldReceive("setEnd");
		$session_appointment_mock->shouldReceive("toggleFullTime");
		$session_appointment_mock->shouldReceive("setSessionId")->with(self::REF_ID);
		$session_appointment_mock->shouldReceive("create");
		$session_appointment_mock->shouldReceive("update");
		$this->appointments = [ $session_appointment_mock ];

		$this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
		$this->ilObject->shouldReceive('setDescription')
		               ->once()
		               ->with($this->dto->getDescription());
		$this->ilObject->shouldReceive("setLocation")->once()->with($this->dto->getLocation());
		$this->ilObject->shouldReceive("getAppointments")->andReturn($this->appointments);
		$this->ilObject->shouldReceive("getFirstAppointment")->andReturn($this->appointments[0]);
		$this->ilObject->shouldReceive("setAppointments")->with($this->appointments);
	}


	protected function initHubObject() {
		$this->iobject = \Mockery::mock('\SRAG\Hub2\Object\Session\ISession');
		$this->iobject->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->iobject->shouldReceive('setStatus')->once();
		$this->iobject->shouldReceive('save')->once();
	}


	protected function initILIASObject() {
		\Mockery::mock('alias:\ilObject2')->shouldReceive("_exists")->withArgs([
			self::REF_ID,
			true,
		])->andReturn(true);

		$this->ilObject = \Mockery::mock('overload:\ilObjSession', '\ilObject');
		$this->ilObject->shouldReceive('getId')->andReturn(self::REF_ID);
		$this->ilObject->shouldReceive('addTranslation');
	}


	protected function initDTO() {
		$this->dto = new SessionDTO('extIdOfSession');
		$this->dto->setParentIdType(SessionDTO::PARENT_ID_TYPE_REF_ID);
		$this->dto->setParentId(1);
		$this->dto->setTitle('Title');
		$this->dto->setDescription('Description');
		$this->dto->setLocation('Location');
	}


	/**
	 * Create Category
	 */
	public function test_create_session_with_default_properties() {
		$processor = new SessionSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);

		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

		$this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfSession');
		$this->ilObject->shouldReceive('create')->once();
		$this->ilObject->shouldReceive('createReference')->once();
		$this->ilObject->shouldReceive('putInTree')->once();
		$this->ilObject->shouldReceive('setPermissions')->once();

		$this->initDataExpectations();

		$this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::REF_ID);
		$this->iobject->shouldReceive('setILIASId')->once()->with(self::REF_ID);

		$processor->process($this->iobject, $this->dto);
	}


	/**
	 * Create Category
	 */
	public function test_update_session_with_default_properties() {
		$processor = new SessionSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);

		$this->dto->setTitle("Changed Title");

		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
		$this->iobject->shouldReceive('computeHashCode')->once()->andReturn("myHashChanged");
		$this->iobject->shouldReceive('getHashCode')->once()->andReturn("myHash");
		$this->iobject->shouldReceive('updateStatus')->with(IObject::STATUS_NOTHING_TO_UPDATE);
		$this->iobject->shouldReceive('getILIASId')->andReturn(self::REF_ID);

		$this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();

		$this->ilObject->shouldReceive('update')->once();
		$this->ilObject->shouldNotReceive('createReference');
		$this->ilObject->shouldNotReceive('putInTree');
		$this->ilObject->shouldNotReceive('setPermissions');

		$this->initDataExpectations();

		$processor->process($this->iobject, $this->dto);
	}
}