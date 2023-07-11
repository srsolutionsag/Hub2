<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\Session\ISession;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;
use srag\Plugins\Hub2\Sync\Processor\Session\SessionSyncProcessor;

/**
 * Class SessionSyncProcessorTest
 * Tests on the processor creating/updating/deleting sessions
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionSyncProcessorTest extends AbstractSyncProcessorTests
{
    public const REF_ID = 57;
    public const USER_ID_OF_MEMBER_TO_DELETE = 22;
    /**
     * @var MockInterface|ilSessionParticipants
     */
    protected $participants;
    /**
     * @var MockInterface|ilSessionAppointment
     */
    protected $appointments;
    /**
     * @var MockInterface|ISession
     */
    protected $iobject;
    /**
     * @var SessionDTO
     */
    protected $dto;
    /**
     * @var MockInterface
     * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
     */
    protected $ilObject;

    /**
     * Setup default mocks
     */
    protected function setUp()
    {
        $arr = [
            'update_dto_title' => true,
            'update_dto_description' => true,
            'update_dto_location' => true,
        ];
        $this->initOrigin(new SessionProperties($arr), new SessionOriginConfig([]));
        $this->setupGeneralDependencies();
        $this->initHubObject();
        $this->initILIASObject();
        $this->initDTO();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function initDataExpectations()
    {
        $session_appointment_mock = Mockery::mock('overload:' . ilSessionAppointment::class, ilDatePeriod::class);
        $session_appointment_mock->shouldReceive("setStart");
        $session_appointment_mock->shouldReceive("setStartingTime");
        $session_appointment_mock->shouldReceive("setEnd");
        $session_appointment_mock->shouldReceive("setEndingTime");
        $session_appointment_mock->shouldReceive("toggleFullTime");
        $session_appointment_mock->shouldReceive("setSessionId")->with(self::REF_ID);
        $session_appointment_mock->shouldReceive("create");
        $session_appointment_mock->shouldReceive("update");
        $this->appointments = [$session_appointment_mock];

        $this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getDescription());
        $this->ilObject->shouldReceive("setLocation")->once()->with($this->dto->getLocation());
        $this->ilObject->shouldReceive("getAppointments")->andReturn($this->appointments);
        $this->ilObject->shouldReceive("getFirstAppointment")->andReturn($this->appointments[0]);
        $this->ilObject->shouldReceive("setAppointments")->with($this->appointments);

        $this->participants = Mockery::mock('overload:' . ilSessionParticipants::class, ilParticipants::class);

        $this->ilObject->shouldReceive("getMembersObject")->andReturn($this->participants);
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(ISession::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
        $this->iobject->shouldReceive('setMetaData')->once();
        $this->iobject->shouldReceive('setTaxonomies')->once();
    }

    protected function initILIASObject()
    {
        Mockery::mock('alias:' . ilObject2::class)->shouldReceive("_exists")->withArgs(
            [
                self::REF_ID,
                true,
            ]
        )->andReturn(true);

        $this->ilObject = Mockery::mock('overload:' . ilObjSession::class, ilObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(self::REF_ID);
        $this->ilObject->shouldReceive('addTranslation');
    }

    protected function initDTO()
    {
        $this->dto = new SessionDTO('extIdOfSession');
        $this->dto->setParentId(1)->setParentIdType(SessionDTO::PARENT_ID_TYPE_REF_ID)->setTitle(
            'Title'
        )->setDescription('Description')
                  ->setLocation('Location');
    }

    /**
     * Create Category
     */
    public function test_create_session_with_default_properties()
    {
        $processor = new SessionSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);

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
    public function test_update_session_with_default_properties()
    {
        $processor = new SessionSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);

        $this->dto->setTitle("Changed Title");

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->iobject->shouldReceive('computeHashCode')->once()->andReturn("myHashChanged");
        $this->iobject->shouldReceive('getHashCode')->once()->andReturn("myHash");
        //$this->iobject->shouldReceive('updateStatus')->with(IObject::STATUS_NOTHING_TO_UPDATE);
        $this->iobject->shouldReceive('getILIASId')->andReturn(self::REF_ID);
        $this->iobject->shouldReceive('setILIASId')->with(self::REF_ID);

        $this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();

        $this->ilObject->shouldReceive('update')->once();
        $this->ilObject->shouldNotReceive('createReference');
        $this->ilObject->shouldNotReceive('putInTree');
        $this->ilObject->shouldNotReceive('setPermissions');
        $this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::REF_ID);

        $this->initDataExpectations();

        $processor->process($this->iobject, $this->dto);
    }
}
