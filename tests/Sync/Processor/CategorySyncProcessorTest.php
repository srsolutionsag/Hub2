<?php

require_once(dirname(dirname(__DIR__)) . '/AbstractHub2Tests.php');

use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\Category\CategoryDTO;
use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Origin\Config\CategoryOriginConfig;
use SRAG\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Hub2\Origin\Properties\CategoryOriginProperties;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Hub2\Sync\ObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\CategorySyncProcessor;

/**
 * Class CategorySyncProcessorTest
 *
 * Tests on the processor creating/updating/deleting categories
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class CategorySyncProcessorTest extends AbstractHub2Tests {

	const REF_ID = 57;
	/**
	 * @var Mockery\MockInterface|\SRAG\Hub2\Sync\Processor\ICategorySyncProcessor
	 */
	protected $activities;
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
	 * @var Mockery\MockInterface|\SRAG\Hub2\Object\Category\ICategory
	 */
	protected $iobject;
	/**
	 * @var CategoryDTO
	 */
	protected $dto;
	/**
	 * @var Mockery\MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObject;
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
		$this->originProperties = new CategoryOriginProperties();
		$this->activities = \Mockery::mock('\SRAG\Hub2\Sync\Processor\Category\ICategoryActivities');
		$this->origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($this->originProperties);
		$this->origin->shouldReceive('getId');
		$this->originConfig = new CategoryOriginConfig([]);
		$this->origin->shouldReceive('config')->andReturn($this->originConfig);
		$this->statusTransition = new ObjectStatusTransition(\Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig"));
		$this->originNotifications = new OriginNotifications();
		$this->originLog = \Mockery::mock("SRAG\Hub2\Log\OriginLog");
		$this->dto = new CategoryDTO('extIdOfCategory');
		$this->dto->setParentIdType(CategoryDTO::PARENT_ID_TYPE_REF_ID);

		$this->iobject = \Mockery::mock('\SRAG\Hub2\Object\Category\ICategory');
		$this->iobject->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->iobject->shouldReceive('setStatus')->once();
		$this->iobject->shouldReceive('save')->once();
		$this->ilObject = \Mockery::mock('overload:\ilObjCategory', '\ilObject');
		$this->ilObject->shouldReceive('getId')->andReturn(self::REF_ID);
	}


	public function tearDown() {
		\Mockery::close();
	}


	/**
	 * Create Category
	 */
	public function test_create_course_with_default_properties() {
		$processor = new CategorySyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications); // , $this->activities

		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

		$this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfCategory');
		$this->ilObject->shouldReceive('create')->once();
		$this->ilObject->shouldReceive('createReference')->once();
		$this->ilObject->shouldReceive('putInTree')->once();
		$this->ilObject->shouldReceive('setPermissions')->once();

		$this->initCourseDataExpectations();

		$this->ilObject->shouldReceive('update')->once();
		$this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::REF_ID);

		$this->iobject->shouldReceive('setILIASId')->once()->with(self::REF_ID);

		$processor->process($this->iobject, $this->dto);
	}

	//
	//	public function test_update_course_with_default_properties() {
	//		$processor = new CourseSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications, $this->activities);
	//
	//		$this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
	//		$this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
	//		$this->iobject->shouldReceive('computeHashCode')
	//		              ->once()
	//		              ->andReturn(serialize($this->dto->getData()));
	//		$this->iobject->shouldReceive('getHashCode')
	//		              ->once()
	//		              ->andReturn(serialize($this->dto->getData()));
	//
	//		$this->originImplementation->shouldNotReceive('beforeUpdateILIASObject'); // Since Data did no change
	//		$this->originImplementation->shouldNotReceive('afterUpdateILIASObject');
	//
	//		$this->ilObjCourse->shouldReceive('setImportId')->once()->with('srhub__extIdOfCourse');
	//		$this->ilObjCourse->shouldNotReceive('createReference');
	//		$this->ilObjCourse->shouldNotReceive('create');
	//		$this->ilObjCourse->shouldNotReceive('putInTree');
	//		$this->ilObjCourse->shouldReceive('setPermissions')->once();
	//
	//		$this->initCourseDataExpectations();
	//
	//		$this->ilObjCourse->shouldReceive('update')->once();
	//		$this->ilObjCourse->shouldReceive('getRefId')->once()->andReturn(self::COURSE_REF_ID);
	//
	//		$this->iobject->shouldNotReceive('setILIASId'); // Since no new ref_id has to be set
	//
	//		$processor->process($this->iobject, $this->dto);
	//	}

	protected function initCourseDataExpectations() {
		$this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
		$this->ilObject->shouldReceive('setDescription')
		               ->once()
		               ->with($this->dto->getDescription());
	}
}