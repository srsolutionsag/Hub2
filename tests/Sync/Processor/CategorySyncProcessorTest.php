<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Category\ICategory;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\Sync\Processor\Category\CategorySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Category\ICategoryActivities;
use srag\Plugins\Hub2\Sync\Processor\Category\ICategorySyncProcessor;

/**
 * Class CategorySyncProcessorTest
 * Tests on the processor creating/updating/deleting categories
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class CategorySyncProcessorTest extends AbstractSyncProcessorTests
{
    public const REF_ID = 57;
    /**
     * @var MockInterface|ICategorySyncProcessor
     */
    protected $activities;
    /**
     * @var MockInterface|ICategory
     */
    protected $iobject;
    /**
     * @var CategoryDTO
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
        $this->activities = Mockery::mock(ICategoryActivities::class);
        $this->initOrigin(new CategoryProperties(), new CategoryOriginConfig([]));
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
     * Create Category
     */
    public function test_create_category_with_default_properties()
    {
        $processor = new CategorySyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition
        ); // , $this->activities

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());

        $this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

        $this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfCategory');
        $this->ilObject->shouldReceive('create')->once();
        $this->ilObject->shouldReceive('createReference')->once();
        $this->ilObject->shouldReceive('putInTree')->once();
        $this->ilObject->shouldReceive('setPermissions')->once();

        $this->initDataExpectations();

        $this->ilObject->shouldReceive('update')->once();
        $this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::REF_ID);

        $this->iobject->shouldReceive('setILIASId')->once()->with(self::REF_ID);

        $processor->process($this->iobject, $this->dto);
    }

    //
    //	public function test_update_course_with_default_properties() {
    //		$processor = new CourseSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->activities);
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

    protected function initDataExpectations()
    {
        $this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getDescription());
        $this->ilObject->shouldReceive('setOwner')->once()->with($this->dto->getOwner());
        $this->ilObject->shouldReceive('setOrderType')->once()->with($this->dto->getOrderType());
        $this->ilObject->shouldReceive('removeTranslations')->once();
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(ICategory::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
        $this->iobject->shouldReceive('setMetaData')->once();
        $this->iobject->shouldReceive('setTaxonomies')->once();
    }

    protected function initILIASObject()
    {
        $this->ilObject = Mockery::mock('overload:' . ilObjCategory::class, ilObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(self::REF_ID);
        $this->ilObject->shouldReceive('addTranslation');
    }

    protected function initDTO()
    {
        $this->dto = new CategoryDTO('extIdOfCategory');
        $this->dto->setParentIdType(CategoryDTO::PARENT_ID_TYPE_REF_ID);
        $this->dto->setParentId(1);
        $this->dto->setTitle('Title');
        $this->dto->setDescription('Description');
    }
}
