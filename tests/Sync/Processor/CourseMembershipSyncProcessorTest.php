<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\CourseMembership\ICourseMembership;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\CourseMembership\CourseMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\CourseMembershipProperties;
use srag\Plugins\Hub2\Sync\Processor\CourseMembership\CourseMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasObject;

/**
 * Class CourseMembershipSyncProcessorTest
 * Tests on the processor creating/updating/deleting course memberships
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipSyncProcessorTest extends AbstractSyncProcessorTests
{
    public const COURSE_REF_ID = 57;
    public const USER_ID = 6;
    public const IL_CRS_TUTOR_123 = 'il_crs_tutor_123';
    /**
     * @var MockInterface|ilCourseParticipants
     */
    protected $ilCourseParticipants;
    /**
     * @var MockInterface|ilObjCourse
     */
    protected $ilObjCourse;
    /**
     * @var MockInterface|ICourseMembership
     */
    protected $iobject;
    /**
     * @var CourseMembershipDTO
     */
    protected $dto;
    /**
     * @var MockInterface|FakeIliasObject
     * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
     */
    protected $ilObject;

    protected function initDTO()
    {
        $this->dto = new CourseMembershipDTO('extIdOfCourse', 'extIdOfUser');
        $this->dto->setRole(CourseMembershipDTO::ROLE_TUTOR)->setUserId(self::USER_ID)->setCourseId(
            self::COURSE_REF_ID
        );
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(ICourseMembership::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
    }

    protected function initILIASObject()
    {
        $this->ilObject = Mockery::mock(FakeIliasObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(
            self::COURSE_REF_ID . FakeIliasMembershipObject::GLUE . self::USER_ID
        );

        Mockery::mock('alias:' . ilObject2::class)->shouldReceive("_exists")->withArgs(
            [
                self::COURSE_REF_ID,
                true,
            ]
        )->andReturn(true);

        $this->ilObjCourse = Mockery::mock("overload:" . ilObjCourse::class, ilObject::class);

        $this->ilCourseParticipants = Mockery::mock("overload:" . ilCourseParticipants::class, ilParticipants::class);
        $this->ilObjCourse->shouldReceive("getMembersObject")->once()->andReturn($this->ilCourseParticipants);

        define('IL_CRS_TUTOR', 3);
    }

    /**
     * Setup default mocks
     */
    protected function setUp()
    {
        $this->initOrigin(
            new CourseMembershipProperties(['update_dto_role' => true]),
            new CourseMembershipOriginConfig([])
        );
        $this->setupGeneralDependencies();
        $this->initHubObject();
        $this->initILIASObject();
        $this->initDTO();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_create_course_membership()
    {
        $processor = new CourseMembershipSyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition
        );

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

        $this->ilCourseParticipants->shouldReceive('add')->once()->withArgs(
            [$this->dto->getUserId(), $this->dto->getRole()]
        );

        $this->iobject->shouldReceive('setILIASId')->once()->with(
            self::COURSE_REF_ID . FakeIliasMembershipObject::GLUE . self::USER_ID
        );

        $processor->process($this->iobject, $this->dto);
    }

    public function test_update_course_membership()
    {
        $processor = new CourseMembershipSyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition
        );

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->iobject->shouldReceive('computeHashCode')->once()->andReturn("newHash");
        $this->iobject->shouldReceive('getHashCode')->once()->andReturn("oldHash");
        $this->iobject->shouldReceive('setILIASId')->once()->with(
            self::COURSE_REF_ID . FakeIliasMembershipObject::GLUE . self::USER_ID
        );
        $this->iobject->shouldReceive('getILIASId')->once()->andReturn(
            self::COURSE_REF_ID . FakeIliasMembershipObject::GLUE . self::USER_ID
        );

        $this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();

        $this->ilObjCourse->shouldReceive('getDefaultTutorRole')->once()->andReturn(self::IL_CRS_TUTOR_123);

        $this->ilObjCourse->shouldReceive("getRefId")->once()->andReturn(self::COURSE_REF_ID);

        $this->ilCourseParticipants->shouldReceive('updateRoleAssignments')->once()->withArgs(
            [$this->dto->getUserId(), [self::IL_CRS_TUTOR_123]]
        );

        $processor->process($this->iobject, $this->dto);
    }

    protected function initDataExpectations()
    {
    }
}
