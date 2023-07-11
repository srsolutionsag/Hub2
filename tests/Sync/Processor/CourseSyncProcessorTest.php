<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\Course\ICourse;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Sync\Processor\Course\CourseSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Course\ICourseActivities;

/**
 * Class CourseSyncProcessorTest
 * Tests on the processor creating/updating/deleting courses
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseSyncProcessorTest extends AbstractSyncProcessorTests
{
    public const ILIAS_USER_ID = 123;
    public const COURSE_REF_ID = 57;
    /**
     * @var MockInterface|ICourseActivities
     */
    protected $activities;
    /**
     * @var MockInterface|ICourse
     */
    protected $iobject;
    /**
     * @var CourseDTO
     */
    protected $dto;
    /**
     * @var MockInterface|ilObjCourse
     * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
     */
    protected $ilObject;

    protected function initDTO()
    {
        $this->dto = new CourseDTO('extIdOfCourse');
        $this->dto->setParentIdType(CourseDTO::PARENT_ID_TYPE_REF_ID)->setParentId(1)->setDescription(
            "Description"
        )->setTitle("Title")
                  ->setContactEmail("contact@email.com")->setContactResponsibility(
                      "Responsibility"
                  )->setImportantInformation("Important Information")
                  ->setNotificationEmails(["notification@email.com"])->setOwner(6)->setSubscriptionLimitationType(
                      CourseDTO::SUBSCRIPTION_TYPE_PASSWORD
                  )
                  ->setViewMode(CourseDTO::VIEW_MODE_BY_TYPE)->setContactName("Contact Name")->setSyllabus('Syllabus')
                  ->setContactConsultation('1 2 3 4 5 6')->setContactPhone('+41 123 456 789');
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(ICourse::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
    }

    protected function initILIASObject()
    {
        $this->ilObject = Mockery::mock('overload:' . ilObjCourse::class, ilObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(self::ILIAS_USER_ID);
    }

    /**
     * Setup default mocks
     */
    protected function setUp()
    {
        $this->activities = Mockery::mock(ICourseActivities::class);

        $this->initOrigin(new CourseProperties(), new CourseOriginConfig([]));
        $this->setupGeneralDependencies();
        $this->initHubObject();
        $this->initILIASObject();
        $this->initDTO();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Create Course
     */
    public function test_create_course_with_default_properties()
    {
        $processor = new CourseSyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition,
            $this->activities
        );

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

        $this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfCourse');
        $this->ilObject->shouldReceive('create')->once();
        $this->ilObject->shouldReceive('createReference')->once();
        $this->ilObject->shouldReceive('putInTree')->once();
        $this->ilObject->shouldReceive('setPermissions')->once();

        $this->initDataExpectations();

        $this->ilObject->shouldReceive('update')->once();
        $this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::COURSE_REF_ID);

        $this->iobject->shouldReceive('setILIASId')->once()->with(self::COURSE_REF_ID);

        $processor->process($this->iobject, $this->dto);
    }

    public function test_update_course_with_default_properties()
    {
        $processor = new CourseSyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition,
            $this->activities
        );

        //$this->iobject->shouldReceive('updateStatus')->once()->with(IObject::STATUS_NOTHING_TO_UPDATE);

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->iobject->shouldReceive('computeHashCode')->once()->andReturn(serialize($this->dto->getData()));
        $this->iobject->shouldReceive('getHashCode')->once()->andReturn(serialize($this->dto->getData()));

        $this->originImplementation->shouldNotReceive('beforeUpdateILIASObject'); // Since Data did no change
        $this->originImplementation->shouldNotReceive('afterUpdateILIASObject');

        $this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfCourse');
        $this->ilObject->shouldNotReceive('createReference');
        $this->ilObject->shouldNotReceive('create');
        $this->ilObject->shouldNotReceive('putInTree');
        $this->ilObject->shouldReceive('setPermissions')->once();

        $this->initDataExpectations();

        $this->ilObject->shouldReceive('update')->once();
        $this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::COURSE_REF_ID);

        $this->iobject->shouldNotReceive('setILIASId'); // Since no new ref_id has to be set

        $processor->process($this->iobject, $this->dto);
    }

    protected function initDataExpectations()
    {
        $this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getDescription());
        $this->ilObject->shouldReceive('setImportantInformation')->once()->with($this->dto->getImportantInformation());
        $this->ilObject->shouldReceive('setContactResponsibility')->once()->with(
            $this->dto->getContactResponsibility()
        );
        $this->ilObject->shouldReceive('setContactEmail')->once()->with($this->dto->getContactEmail());
        $this->ilObject->shouldReceive('setOwner')->once()->with($this->dto->getOwner());
        $this->ilObject->shouldReceive('setSubscriptionLimitationType')->once()->with(
            $this->dto->getSubscriptionLimitationType()
        );
        $this->ilObject->shouldReceive('setViewMode')->once()->with($this->dto->getViewMode());
        $this->ilObject->shouldReceive('setContactName')->once()->with($this->dto->getContactName());
        $this->ilObject->shouldReceive('setSyllabus')->once()->with($this->dto->getSyllabus());
        $this->ilObject->shouldReceive('setContactConsultation')->once()->with($this->dto->getContactConsultation());
        $this->ilObject->shouldReceive('setContactPhone')->once()->with($this->dto->getContactPhone());
        $this->ilObject->shouldReceive('setActivationType')->once()->with($this->dto->getActivationType());
    }
}
