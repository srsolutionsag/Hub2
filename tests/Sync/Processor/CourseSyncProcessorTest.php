<?php

require_once(dirname(dirname(__DIR__)) . '/AbstractHub2Tests.php');

use SRAG\Hub2\Object\Course\CourseDTO;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Hub2\Origin\Properties\CourseOriginProperties;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Hub2\Sync\ObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\Course\CourseSyncProcessor;

/**
 * Class CourseSyncProcessorTest
 *
 * Tests on the processor creating/updating/deleting users
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseSyncProcessorTest extends AbstractHub2Tests {

	const ILIAS_USER_ID = 123;
	const COURSE_REF_ID = 57;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Sync\Processor\ICourseActivities
	 */
	protected $courseActivities;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Origin\IOriginImplementation
	 */
	protected $originImplementation;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Origin\IOrigin
	 */
	protected $origin;
	/**
	 * @var UserOriginProperties
	 */
	protected $originProperties;
	/**
	 * @var UserOriginConfig
	 */
	protected $originConfig;
	/**
	 * @var ObjectStatusTransition
	 */
	protected $statusTransition;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Object\Course\ICourse
	 */
	protected $course;
	/**
	 * @var CourseDTO
	 */
	protected $courseDTO;
	/**
	 * @var Mockery\MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObjCourse;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Log\ILog
	 */
	protected $originLog;
	/**
	 * @var \SRAG\Hub2\Notification\OriginNotifications
	 */
	protected $originNotifications;


	/**
	 * Setup default mocks
	 */
	protected function setUp() {
		global $DIC;

		$DIC = \Mockery::mock('overload:\ILIAS\DI\Container', "Pimple\Container");
		$tree_mock = \Mockery::mock('overload:\ilTree');
		$tree_mock->shouldReceive('isInTree')->with(1)->once()->andReturn(true);
		$DIC->shouldReceive('repositoryTree')->once()->andReturn($tree_mock);

		$this->originImplementation = \Mockery::mock('\SRAG\Hub2\Origin\IOriginImplementation');
		$this->originProperties = new CourseOriginProperties();
		$this->courseActivities = \Mockery::mock('\SRAG\Hub2\Sync\Processor\Course\ICourseActivities');
		$this->origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($this->originProperties);
		$this->origin->shouldReceive('getId');
		$this->originConfig = new CourseOriginConfig([]);
		$this->origin->shouldReceive('config')->andReturn($this->originConfig);
		$this->statusTransition = new ObjectStatusTransition(\Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig"));
		$this->originNotifications = new \SRAG\Hub2\Notification\OriginNotifications();
		$this->originLog = \Mockery::mock("SRAG\Hub2\Log\OriginLog");
		$this->courseDTO = new CourseDTO('extIdOfCourse');
		$this->courseDTO->setParentIdType(CourseDTO::PARENT_ID_TYPE_REF_ID)
		                ->setParentId(1)
		                ->setDescription("Description")
		                ->setTitle("Title")
		                ->setContactEmail("contact@email.com")
		                ->setContactResponsibility("Responsibility")
		                ->setImportantInformation("Important Information")
		                ->setNotificationEmails([ "notification@email.com" ])
		                ->setOwner(6)
		                ->setSubscriptionLimitationType(CourseDTO::SUBSCRIPTION_TYPE_PASSWORD)
		                ->setViewMode(CourseDTO::VIEW_MODE_OBJECTIVES)
		                ->setContactName("Contact Name")
		                ->setSyllabus('Syllabus')
		                ->setContactConsultation('1 2 3 4 5 6')
		                ->setContactPhone('+41 123 456 789');

		$this->course = \Mockery::mock('\SRAG\Hub2\Object\Course\ICourse');
		$this->course->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->course->shouldReceive('setStatus')->once();
		$this->course->shouldReceive('save')->once();
		$this->ilObjCourse = \Mockery::mock('overload:\ilObjCourse', 'ilObject');
		$this->ilObjCourse->shouldReceive('getId')->andReturn(self::ILIAS_USER_ID);
	}


	public function tearDown() {
		\Mockery::close();
	}


	/**
	 * Create Course
	 */
	public function test_create_course_with_default_properties() {
		$processor = new CourseSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications, $this->courseActivities);

		$this->course->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->course->shouldReceive('setData')->once()->with($this->courseDTO->getData());
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

		$this->ilObjCourse->shouldReceive('setImportId')->once()->with('srhub__extIdOfCourse');
		$this->ilObjCourse->shouldReceive('create')->once();
		$this->ilObjCourse->shouldReceive('createReference')->once();
		$this->ilObjCourse->shouldReceive('putInTree')->once();
		$this->ilObjCourse->shouldReceive('setPermissions')->once();

		$this->initCourseDataExpectations();

		$this->ilObjCourse->shouldReceive('update')->once();
		$this->ilObjCourse->shouldReceive('getRefId')->once()->andReturn(self::COURSE_REF_ID);

		$this->course->shouldReceive('setILIASId')->once()->with(self::COURSE_REF_ID);

		$processor->process($this->course, $this->courseDTO);
	}


	public function test_update_course_with_default_properties() {
		$processor = new CourseSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications, $this->courseActivities);

		$this->course->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
		$this->course->shouldReceive('setData')->once()->with($this->courseDTO->getData());
		$this->course->shouldReceive('computeHashCode')
		             ->once()
		             ->andReturn(serialize($this->courseDTO->getData()));
		$this->course->shouldReceive('getHashCode')
		             ->once()
		             ->andReturn(serialize($this->courseDTO->getData()));

		$this->originImplementation->shouldNotReceive('beforeUpdateILIASObject'); // Since Data did no change
		$this->originImplementation->shouldNotReceive('afterUpdateILIASObject');

		$this->ilObjCourse->shouldReceive('setImportId')->once()->with('srhub__extIdOfCourse');
		$this->ilObjCourse->shouldNotReceive('createReference');
		$this->ilObjCourse->shouldNotReceive('create');
		$this->ilObjCourse->shouldNotReceive('putInTree');
		$this->ilObjCourse->shouldReceive('setPermissions')->once();

		$this->initCourseDataExpectations();

		$this->ilObjCourse->shouldReceive('update')->once();
		$this->ilObjCourse->shouldReceive('getRefId')->once()->andReturn(self::COURSE_REF_ID);

		$this->course->shouldNotReceive('setILIASId'); // Since no new ref_id has to be set

		$processor->process($this->course, $this->courseDTO);
	}


	protected function initCourseDataExpectations() {
		$this->ilObjCourse->shouldReceive('setTitle')->once()->with($this->courseDTO->getTitle());
		$this->ilObjCourse->shouldReceive('setDescription')
		                  ->once()
		                  ->with($this->courseDTO->getDescription());
		$this->ilObjCourse->shouldReceive('setImportantInformation')
		                  ->once()
		                  ->with($this->courseDTO->getImportantInformation());
		$this->ilObjCourse->shouldReceive('setContactResponsibility')
		                  ->once()
		                  ->with($this->courseDTO->getContactResponsibility());
		$this->ilObjCourse->shouldReceive('setContactEmail')
		                  ->once()
		                  ->with($this->courseDTO->getContactEmail());
		$this->ilObjCourse->shouldReceive('setOwner')->once()->with($this->courseDTO->getOwner());
		$this->ilObjCourse->shouldReceive('setSubscriptionLimitationType')
		                  ->once()
		                  ->with($this->courseDTO->getSubscriptionLimitationType());
		$this->ilObjCourse->shouldReceive('setViewMode')
		                  ->once()
		                  ->with($this->courseDTO->getViewMode());
		$this->ilObjCourse->shouldReceive('setContactName')
		                  ->once()
		                  ->with($this->courseDTO->getContactName());
		$this->ilObjCourse->shouldReceive('setSyllabus')
		                  ->once()
		                  ->with($this->courseDTO->getSyllabus());
		$this->ilObjCourse->shouldReceive('setContactConsultation')
		                  ->once()
		                  ->with($this->courseDTO->getContactConsultation());
		$this->ilObjCourse->shouldReceive('setContactPhone')
		                  ->once()
		                  ->with($this->courseDTO->getContactPhone());
		$this->ilObjCourse->shouldReceive('setActivationType')
		                  ->once()
		                  ->with($this->courseDTO->getActivationType());
	}
}