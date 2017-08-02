<?php

require_once(dirname(dirname(__DIR__)) . '/AbstractHub2Tests.php');

use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\UserDTO;
use SRAG\Hub2\Origin\Config\IUserOriginConfig;
use SRAG\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;
use SRAG\Hub2\Sync\ObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\UserSyncProcessor;

/**
 * Class UserSyncProcessorTest
 *
 * Tests on the processor creating/updating/deleting users
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals  disabled
 * @backupStaticAttributes disabled
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class UserSyncProcessorTest extends AbstractHub2Tests {

	const ILIAS_OBJ_ID = 123;

	/**
	 * @var Mockery\MockInterface
	 */
	protected $originImplementation;
	/**
	 * @var Mockery\MockInterface
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
	 * @var Mockery\MockInterface
	 */
	protected $user;
	/**
	 * @var UserDTO
	 */
	protected $userDTO;
	/**
	 * @var Mockery\MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObjUser;
	/**
	 * @var Mockery\MockInterface
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
		$this->originImplementation = \Mockery::mock('\SRAG\Hub2\Origin\IOriginImplementation');
		$this->originProperties = new UserOriginProperties();
		$this->origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($this->originProperties);
		$this->origin->shouldReceive('getId');
		$this->originConfig = new UserOriginConfig([]);
		$this->origin->shouldReceive('config')->andReturn($this->originConfig);
		$this->statusTransition = new ObjectStatusTransition(\Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig"));
		$this->originNotifications = new \SRAG\Hub2\Notification\OriginNotifications();
		$this->originLog = \Mockery::mock("SRAG\Hub2\Log\OriginLog");
		$this->userDTO = new UserDTO('extIdOfJohnDoe');
		$this->userDTO->setFirstname('John');
		$this->userDTO->setLastname('Doe');
		$this->userDTO->setEmail('john.doe@example.com');
		$this->userDTO->setLogin('JohnDoe123');
		$this->userDTO->setPasswd('mySuperSecretPassword');
		$this->userDTO->setCountry('NoMansLand');
		$this->userDTO->setExternalAccount('john.doe.external');
		$this->userDTO->setAuthMode(UserDTO::AUTH_MODE_ILIAS);
		$this->userDTO->setTitle('Doctor');
		$this->userDTO->setGender(UserDTO::GENDER_MALE);
		$this->userDTO->setCity('NYC');
		$this->userDTO->setInstitution('FBI');
		$this->userDTO->setDepartment(null);
		$this->userDTO->setPhoneHome(123);
		$this->userDTO->setPhoneMobile(null);
		$this->userDTO->setPhoneOffice(789);
		$this->user = \Mockery::mock('\SRAG\Hub2\Object\IUser');
		$this->user->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->user->shouldReceive('setStatus')->once();
		$this->user->shouldReceive('save')->once();
		$this->ilObjUser = \Mockery::mock('overload:\ilObjUser', 'ilObject');
		$this->ilObjUser->shouldReceive('getId')->andReturn(self::ILIAS_OBJ_ID);
	}

	public function tearDown() {
		\Mockery::close();
	}


	/**
	 * Create ILIAS User
	 */
	public function test_create_user_with_default_properties() {
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$this->setDefaultExpectationsForCreationOfILIASUser();
		$this->ilObjUser->shouldReceive('setLogin')->once()->with('j.doe');
		$processor->process($this->user, $this->userDTO);
	}

	/**
	 * Create ILIAS user: Test that the login name is built correctly for every possible mode.
	 *
	 * @dataProvider getUsernameModes
	 * @param string $mode
	 * @param string $expectedLoginName
	 */
	public function test_create_user_with_different_login_name_modes($mode, $expectedLoginName) {
		$this->originConfig->setData([IUserOriginConfig::LOGIN_FIELD => $mode]);
		$this->setDefaultExpectationsForCreationOfILIASUser();
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$this->ilObjUser->shouldReceive('setLogin')->once()->with($expectedLoginName);
		$processor->process($this->user, $this->userDTO);
	}

	/**
	 * Create ILIAS user: Test that user is not active AND profile is set to incomplete.
	 */
	public function test_create_user_with_inactive_account() {
		$this->originProperties->setData([UserOriginProperties::ACTIVATE_ACCOUNT => false]);
		$this->setDefaultExpectationsForCreationOfILIASUser();
		$this->ilObjUser->shouldReceive('setActive')->once()->with(false);
		$this->ilObjUser->shouldReceive('setProfileIncomplete')->once()->with(true);
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}

	/**
	 * Update ILIAS user: Using the default properties (properties of UserDTO are not forwarded to ILIAS user).
	 */
	public function test_update_user_with_default_properties() {
		$this->setDefaultExpectationsForUpdateOfILIASUser();
		$this->ilObjUser->shouldReceive('setImportId')->once();
		$this->ilObjUser->shouldReceive('setTitle')->once();
		$this->ilObjUser->shouldReceive('setDescription')->once()->with($this->userDTO->getEmail());
		$this->ilObjUser->shouldReceive('update')->once();
		$this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();
		$this->user->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
		$this->user->shouldReceive('getHashCode')->once()->andReturn('previousHashCode');
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}


	public function test_update_user_not_required_if_no_properties_changed() {
		$this->setDefaultExpectationsForUpdateOfILIASUser();
		$this->user->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
		$this->user->shouldReceive('getHashCode')->once()->andReturn('actualHashCode');
		$this->ilObjUser->shouldNotReceive('update');
		$this->ilObjUser->shouldNotReceive('setTitle');
		$this->ilObjUser->shouldNotReceive('setDescription');
		$this->originImplementation->shouldNotReceive('beforeUpdateILIASObject');
		$this->originImplementation->shouldNotReceive('afterUpdateILIASObject');
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}

	public function test_delete_user_mode_none() {
		$this->setDefaultExpectationsForDeletionOfILIASUser();
		$this->originProperties->setData([UserOriginProperties::DELETE => UserOriginProperties::DELETE_MODE_NONE]);
		$this->originImplementation->shouldReceive('beforeDeleteILIASObject');
		$this->originImplementation->shouldReceive('afterDeleteILIASObject');
		$this->ilObjUser->shouldNotReceive('update');
		$this->ilObjUser->shouldNotReceive('delete');
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}

	public function test_delete_user_mode_inactive() {
		$this->setDefaultExpectationsForDeletionOfILIASUser();
		$this->originProperties->setData([UserOriginProperties::DELETE => UserOriginProperties::DELETE_MODE_INACTIVE]);
		$this->originImplementation->shouldReceive('beforeDeleteILIASObject');
		$this->originImplementation->shouldReceive('afterDeleteILIASObject');
		$this->ilObjUser->shouldReceive('setActive')->with(false)->once();
		$this->ilObjUser->shouldReceive('update')->once();
		$this->ilObjUser->shouldNotReceive('delete');
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}

	public function test_delete_user_mode_delete_user() {
		$this->setDefaultExpectationsForDeletionOfILIASUser();
		$this->originProperties->setData([UserOriginProperties::DELETE => UserOriginProperties::DELETE_MODE_DELETE]);
		$this->originImplementation->shouldReceive('beforeDeleteILIASObject');
		$this->originImplementation->shouldReceive('afterDeleteILIASObject');
		$this->ilObjUser->shouldReceive('delete');
		$processor = new UserSyncProcessor($this->origin, $this->originImplementation, $this->statusTransition, $this->originLog, $this->originNotifications);
		$processor->process($this->user, $this->userDTO);
	}


	/**
	 * @return array
	 */
	public function getUsernameModes() {
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
	protected function setDefaultExpectationsForUpdateOfILIASUser() {
		$this->ilObjUser->shouldReceive('_exists')->with(self::ILIAS_OBJ_ID)->andReturn(true);
		$this->user->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
		$this->user->shouldReceive('getILIASId')->andReturn(self::ILIAS_OBJ_ID);
		$this->user->shouldReceive('setData')->once()->with($this->userDTO->getData());
	}

	/**
	 * Set some default expectations on the mock objects when deleting ILIAS users
	 */
	protected function setDefaultExpectationsForDeletionOfILIASUser() {
		$this->ilObjUser->shouldReceive('_exists')->with(self::ILIAS_OBJ_ID)->andReturn(true);
		$this->user->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_DELETE);
		$this->user->shouldReceive('getILIASId')->andReturn(self::ILIAS_OBJ_ID);
	}

	/**
	 * Set some default expectations on the mock objects when creating ILIAS users
	 */
	protected function setDefaultExpectationsForCreationOfILIASUser() {
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();
		$this->user->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->user->shouldReceive('setILIASId')->once()->with(self::ILIAS_OBJ_ID);
		$this->user->shouldReceive('setData')->once()->with($this->userDTO->getData());
		$this->ilObjUser->shouldReceive('setTitle')->once();
		$this->ilObjUser->shouldReceive('setDescription')->once()->with($this->userDTO->getEmail());
		$this->ilObjUser->shouldReceive('setImportId')->once();
		$this->ilObjUser->shouldReceive('setLogin')->once()->byDefault();
		$this->ilObjUser->shouldReceive('setUTitle')->once()->with($this->userDTO->getTitle());
		$this->ilObjUser->shouldReceive('create')->once();
		$this->ilObjUser->shouldReceive('setActive')->once()->with(true)->byDefault();
		$this->ilObjUser->shouldReceive('setProfileIncomplete')->once()->with(false)->byDefault();
		$this->ilObjUser->shouldReceive('setPasswd')->once()->with($this->userDTO->getPasswd());
		$this->ilObjUser->shouldReceive('saveAsNew')->once();
		$this->ilObjUser->shouldReceive('writePrefs')->once();
		$this->ilObjUser->shouldReceive('_loginExists')->zeroOrMoreTimes()->andReturn(false);
		foreach (UserSyncProcessor::getProperties() as $property) {
			$setter = "set" . ucfirst($property);
			$getter = "get" . ucfirst($property);
			// null values are NOT forwarded to the ilObjUser since they could overwrite existing values
			if ($this->userDTO->$getter() === null) {
				$this->ilObjUser->shouldNotReceive($setter);
			} else {
				$this->ilObjUser->shouldReceive($setter)->once()->with($this->userDTO->$getter());
			}
		}
	}
}