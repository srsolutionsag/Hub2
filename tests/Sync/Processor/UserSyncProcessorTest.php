<?php

require_once __DIR__ . "/../../AbstractSyncProcessorTests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\User\IUser;
use srag\Plugins\Hub2\Object\User\UserDTO;
use srag\Plugins\Hub2\Origin\Config\User\IUserOriginConfig;
use srag\Plugins\Hub2\Origin\Config\User\UserOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\User\UserProperties;
use srag\Plugins\Hub2\Sync\Processor\User\IUserActivities;
use srag\Plugins\Hub2\Sync\Processor\User\IUserSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\User\UserSyncProcessor;

/**
 * Class UserSyncProcessorTest
 * Tests on the processor creating/updating/deleting users
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 */
class UserSyncProcessorTest extends AbstractSyncProcessorTests
{
    public const ILIAS_ID = 123;
    /**
     * @var MockInterface|IUserSyncProcessor
     */
    protected $activities;
    /**
     * @var MockInterface|IUser
     */
    protected $iobject;
    /**
     * @var UserDTO
     */
    protected $dto;
    /**
     * @var MockInterface|ilObjUser
     * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
     */
    protected $ilObject;

    /**
     * Setup default mocks
     */
    protected function setUp()
    {
        $this->activities = Mockery::mock(IUserActivities::class);
        $this->initOrigin(new UserProperties(), new UserOriginConfig([]));
        $this->setupGeneralDependencies();
        $this->initHubObject();
        $this->initILIASObject();
        $this->initDTO();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function initDTO()
    {
        $this->dto = new UserDTO('extIdOfJohnDoe');
        $this->dto->setFirstname('John');
        $this->dto->setLastname('Doe');
        $this->dto->setEmail('john.doe@example.com');
        $this->dto->setLogin('JohnDoe123');
        $this->dto->setPasswd('mySuperSecretPassword');
        $this->dto->setCountry('NoMansLand');
        $this->dto->setExternalAccount('john.doe.external');
        $this->dto->setAuthMode(UserDTO::AUTH_MODE_ILIAS);
        $this->dto->setTitle('Doctor');
        $this->dto->setGender(UserDTO::GENDER_MALE);
        $this->dto->setCity('NYC');
        $this->dto->setInstitution('FBI');
        $this->dto->setDepartment(null);
        $this->dto->setPhoneHome(123);
        $this->dto->setPhoneMobile(null);
        $this->dto->setPhoneOffice(789);
    }

    protected function initHubObject()
    {
        $this->iobject = Mockery::mock(IUser::class);
        $this->iobject->shouldReceive('setProcessedDate')->once();
        // Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
        $this->iobject->shouldReceive('setStatus')->once();
        $this->iobject->shouldReceive('save')->once();
    }

    protected function initILIASObject()
    {
        $this->ilObject = Mockery::mock('overload:' . ilObjUser::class, ilObject::class);
        $this->ilObject->shouldReceive('getId')->andReturn(self::ILIAS_ID);
    }

    /**
     * Create ILIAS User
     */
    public function test_create_user_with_default_properties()
    {
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $this->initDataExpectations();
        $this->ilObject->shouldReceive('setLogin')->once()->with('j.doe');
        $processor->process($this->iobject, $this->dto);
    }

    /**
     * Create ILIAS user: Test that the login name is built correctly for every possible mode.
     * @dataProvider getUsernameModes
     * @param string $mode
     * @param string $expectedLoginName
     */
    public function test_create_user_with_different_login_name_modes($mode, $expectedLoginName)
    {
        $this->originConfig->setData([IUserOriginConfig::LOGIN_FIELD => $mode]);
        $this->initDataExpectations();
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $this->ilObject->shouldReceive('setLogin')->once()->with($expectedLoginName);
        $processor->process($this->iobject, $this->dto);
    }

    /**
     * Create ILIAS user: Test that user is not active AND profile is set to incomplete.
     */
    public function test_create_user_with_inactive_account()
    {
        $this->originProperties->setData([UserProperties::ACTIVATE_ACCOUNT => false]);
        $this->initDataExpectations();
        $this->ilObject->shouldReceive('setActive')->once()->with(false);
        $this->ilObject->shouldReceive('setProfileIncomplete')->once()->with(true);
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    /**
     * Update ILIAS user: Using the default properties (properties of UserDTO are not forwarded to
     * ILIAS user).
     */
    public function test_update_user_with_default_properties()
    {
        $this->setDefaultExpectationsForUpdateOfILIASUser();
        $this->ilObject->shouldReceive('setImportId')->once();
        $this->ilObject->shouldReceive('setTitle')->once();
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getEmail());
        $this->ilObject->shouldReceive('update')->once();
        $this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();
        $this->iobject->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
        $this->iobject->shouldReceive('getHashCode')->once()->andReturn('previousHashCode');
        $this->iobject->shouldReceive('setMetaData')->once();
        $this->iobject->shouldReceive('setILIASId')->once()->with(self::ILIAS_ID);
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    public function test_update_user_not_required_if_no_properties_changed()
    {
        $this->setDefaultExpectationsForUpdateOfILIASUser();
        //$this->iobject->shouldReceive('updateStatus')->once()->with(IObject::STATUS_NOTHING_TO_UPDATE);
        $this->iobject->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
        $this->iobject->shouldReceive('getHashCode')->once()->andReturn('actualHashCode');
        $this->iobject->shouldReceive('setMetaData')->once();
        $this->ilObject->shouldNotReceive('update');
        $this->ilObject->shouldNotReceive('setTitle');
        $this->ilObject->shouldNotReceive('setDescription');
        $this->originImplementation->shouldNotReceive('beforeUpdateILIASObject');
        $this->originImplementation->shouldNotReceive('afterUpdateILIASObject');
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    public function test_delete_user_mode_none()
    {
        $this->setDefaultExpectationsForDeletionOfILIASUser();
        $this->originProperties->setData([UserProperties::DELETE => UserProperties::DELETE_MODE_NONE]);
        $this->originImplementation->shouldReceive('beforeDeleteILIASObject');
        $this->originImplementation->shouldReceive('afterDeleteILIASObject');
        $this->ilObject->shouldNotReceive('update');
        $this->ilObject->shouldNotReceive('delete');
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    public function test_delete_user_mode_inactive()
    {
        $this->setDefaultExpectationsForDeletionOfILIASUser();
        $this->originProperties->setData([UserProperties::DELETE => UserProperties::DELETE_MODE_INACTIVE]);
        $this->originImplementation->shouldReceive('beforeDeleteILIASObject');
        $this->originImplementation->shouldReceive('afterDeleteILIASObject');
        $this->ilObject->shouldReceive('setActive')->with(false)->once();
        $this->ilObject->shouldReceive('update')->once();
        $this->ilObject->shouldNotReceive('delete');
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    public function test_delete_user_mode_delete_user()
    {
        $this->setDefaultExpectationsForDeletionOfILIASUser();
        $this->originProperties->setData([UserProperties::DELETE => UserProperties::DELETE_MODE_DELETE]);
        $this->originImplementation->shouldReceive('beforeDeleteILIASObject');
        $this->originImplementation->shouldReceive('afterDeleteILIASObject');
        $this->ilObject->shouldReceive('delete');
        $processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition);
        $processor->process($this->iobject, $this->dto);
    }

    /**
     * @return array
     */
    public function getUsernameModes()
    {
        return [
            [UserOriginConfig::LOGIN_FIELD_SHORTENED_FIRST_LASTNAME, 'j.doe'],
            [UserOriginConfig::LOGIN_FIELD_EMAIL, 'john.doe@example.com'],
            [UserOriginConfig::LOGIN_FIELD_EXT_ACCOUNT, 'john.doe.external'],
            [UserOriginConfig::LOGIN_FIELD_EXT_ID, 'extidofjohndoe'],
            [UserOriginConfig::LOGIN_FIELD_FIRSTNAME_LASTNAME, 'john.doe'],
            [UserOriginConfig::LOGIN_FIELD_HUB_LOGIN, 'johndoe123'],
        ];
    }

    /**
     * Set some default expectations on the mock objects when updating ILIAS users
     */
    protected function setDefaultExpectationsForUpdateOfILIASUser()
    {
        $this->ilObject->shouldReceive('_exists')->with(self::ILIAS_ID)->andReturn(true);
        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
        $this->iobject->shouldReceive('getILIASId')->andReturn(self::ILIAS_ID);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
    }

    /**
     * Set some default expectations on the mock objects when deleting ILIAS users
     */
    protected function setDefaultExpectationsForDeletionOfILIASUser()
    {
        $this->ilObject->shouldReceive('_exists')->with(self::ILIAS_ID)->andReturn(true);
        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_OUTDATED);
        $this->iobject->shouldReceive('getILIASId')->andReturn(self::ILIAS_ID);
    }

    /**
     * Set some default expectations on the mock objects when creating ILIAS users
     */
    protected function initDataExpectations()
    {
        $this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
        $this->originImplementation->shouldReceive('afterCreateILIASObject')->once();
        $this->iobject->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
        $this->iobject->shouldReceive('setILIASId')->once()->with(self::ILIAS_ID);
        $this->iobject->shouldReceive('setData')->once()->with($this->dto->getData());
        $this->iobject->shouldReceive('setMetaData')->once($this->dto->getMetaData());
        $this->ilObject->shouldReceive('setTitle')->once();
        $this->ilObject->shouldReceive('setDescription')->once()->with($this->dto->getEmail());
        $this->ilObject->shouldReceive('setImportId')->once();
        $this->ilObject->shouldReceive('setLogin')->once()->byDefault();
        $this->ilObject->shouldReceive('setUTitle')->once()->with($this->dto->getTitle());
        $this->ilObject->shouldReceive('create')->once();
        $this->ilObject->shouldReceive('setActive')->once()->with(true)->byDefault();
        $this->ilObject->shouldReceive('setProfileIncomplete')->once()->with(false)->byDefault();
        $this->ilObject->shouldReceive('setPasswd')->once()->with($this->dto->getPasswd());
        $this->ilObject->shouldReceive('saveAsNew')->once();
        $this->ilObject->shouldReceive('writePrefs')->once();
        $this->ilObject->shouldReceive('_loginExists')->zeroOrMoreTimes()->andReturn(false);
        foreach (UserSyncProcessor::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            // null values are NOT forwarded to the ilObjUser since they could overwrite existing values
            if ($this->dto->$getter() === null) {
                $this->ilObject->shouldNotReceive($setter);
            } else {
                $this->ilObject->shouldReceive($setter)->once()->with($this->dto->$getter());
            }
        }
    }
}
