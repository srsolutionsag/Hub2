<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\Group\IGroup;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\Sync\Processor\Group\GroupSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Group\IGroupActivities;

/**
 * Class GroupSyncProcessorTest
 * Tests on the processor creating/updating/deleting groups
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @author                 Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessorTest extends AbstractSyncProcessorTests
{
    public const ILIAS_USER_ID = 123;
    public const GROUP_REF_ID = 57;
    /**
     * @var MockInterface|IGroupActivities
     */
    protected $activities;
    /**
     * @var MockInterface|IGroup
     */
    protected $iobject;
    /**
     * @var GroupDTO
     */
    protected $dto;
    /**
     * @var MockInterface|ilObjGroup
     * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
     */
    protected $ilObject;

    protected function initDTO()
    {
        $this->dto = new GroupDTO('extIdOfGroup');
        $this->dto->setParentIdType(GroupDTO::PARENT_ID_TYPE_REF_ID)->setParentId(1)->setDescription(
            "Description"
        )->setTitle("Title")
                  ->setInformation("Information")->setRegisterMode(GroupDTO::GRP_REGISTRATION_LIMITED)->setGroupType(
                      GroupDTO::GRP_TYPE_CLOSED
                  )
                  ->setRegUnlimited(false)->setRegistrationStart(1507202887)->setRegistrationEnd(
                      1507202887 + 30
                  )->setPassword("Password")
                  ->setRegMembershipLimitation(true)->setMinMembers(1)->setMaxMembers(10)->setWaitingList(
                      true
                  )->setWaitingListAutoFill(true)
                  ->setStart(1507202887)->setEnd(1507202887 + 30)->setLatitude(7.1234)->setLongitude(
                      45.1234
                  )->setLocationzoom(5)->setEnableGroupMap(true)
                  ->setRegAccessCodeEnabled(true)->setRegistrationAccessCode("AccessCode")->setOwner(6)->setViewMode(
                      GroupDTO::VIEW_BY_TYPE
                  )
                  ->setCancellationEnd(1507202887);
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(IGroup::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
    }

    protected function initILIASObject()
    {
        $this->ilObject = Mockery::mock('overload:' . ilObjGroup::class, ilObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(self::ILIAS_USER_ID);
    }

    /**
     * Setup default mocks
     */
    protected function setUp()
    {
        $this->activities = Mockery::mock(IGroupActivities::class);

        $this->initOrigin(new GroupProperties(), new GroupOriginConfig([]));
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
     * Create Group
     */
    public function test_create_group_with_default_properties()
    {
        $processor = new GroupSyncProcessor(
            $this->origin,
            $this->originImplementation,
            $this->statusTransition,
            $this->activities
        );

        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->iobject->shouldReceive('setILIASId')->once()->with(self::GROUP_REF_ID);

        $this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterCreateILIASObject')->once();

        $this->ilObject->shouldReceive('setImportId')->once()->with('srhub__extIdOfGroup');
        $this->ilObject->shouldReceive('create')->once();
        $this->ilObject->shouldReceive('createReference')->once();
        $this->ilObject->shouldReceive('putInTree')->once();
        $this->ilObject->shouldReceive('setPermissions')->once();
        $this->ilObject->shouldReceive('getRefId')->once()->andReturn(self::GROUP_REF_ID);

        $this->initDataExpectations();

        $processor->process($this->iobject, $this->dto);
    }

    public function test_update_group_with_default_properties()
    {
        $processor = new GroupSyncProcessor(
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

    protected function initDataExpectations()
    {
        $this->ilObject->shouldReceive('setTitle')->once()->with($this->dto->getTitle());
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getDescription());
        $this->ilObject->shouldReceive('setInformation')->once()->with($this->dto->getInformation());
        $this->ilObject->shouldReceive('setGroupType')->once()->with($this->dto->getGroupType());
        $this->ilObject->shouldReceive('setRegisterMode')->once()->with($this->dto->getRegisterMode());
        $this->ilObject->shouldReceive('setOwner')->once()->with($this->dto->getOwner());
        $this->ilObject->shouldReceive('enableUnlimitedRegistration')->once()->with($this->dto->getRegUnlimited());
        $this->ilObject->shouldReceive('setViewMode')->once()->with($this->dto->getViewMode());
        $this->ilObject->shouldReceive('setRegistrationStart')->once()->withAnyArgs(
        ); // Currently not possible to have ilDate here
        $this->ilObject->shouldReceive('setRegistrationEnd')->once()->withAnyArgs(
        ); // Currently not possible to have ilDate here
        $this->ilObject->shouldReceive('setPassword')->once()->with($this->dto->getPassword());
        $this->ilObject->shouldReceive('enableMembershipLimitation')->once()->with(
            $this->dto->getRegMembershipLimitation()
        );
        $this->ilObject->shouldReceive('setMinMembers')->once()->with($this->dto->getMinMembers());
        $this->ilObject->shouldReceive('setMaxMembers')->once()->with($this->dto->getMaxMembers());
        $this->ilObject->shouldReceive('enableWaitingList')->once()->with($this->dto->getWaitingList());
        $this->ilObject->shouldReceive('setWaitingListAutoFill')->once()->with($this->dto->getWaitingListAutoFill());

        $this->ilObject->shouldReceive('setCancellationEnd')->once()->withAnyArgs(
        ); // Currently not possible to have ilDate here
        $this->ilObject->shouldReceive('setStart')->once()->withAnyArgs(); // Currently not possible to have ilDate here
        $this->ilObject->shouldReceive('setEnd')->once()->withAnyArgs(); // Currently not possible to have ilDate here
        $this->ilObject->shouldReceive('setLatitude')->once()->with($this->dto->getLatitude());
        $this->ilObject->shouldReceive('setLongitude')->once()->with($this->dto->getLongitude());
        $this->ilObject->shouldReceive('setLocationzoom')->once()->with($this->dto->getLocationzoom());
        $this->ilObject->shouldReceive('setEnableGroupMap')->once()->with($this->dto->getEnableGroupMap());
        $this->ilObject->shouldReceive('enableRegistrationAccessCode')->once()->with(
            $this->dto->getRegAccessCodeEnabled()
        );
        $this->ilObject->shouldReceive('setRegistrationAccessCode')->once()->with(
            $this->dto->getRegistrationAccessCode()
        );
    }
}
