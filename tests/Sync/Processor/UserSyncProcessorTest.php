<?php

require_once(dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php');

use SRAG\Hub2\Object\IObject;
use SRAG\Hub2\Object\UserDTO;
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
class UserSyncProcessorTest extends \PHPUnit\Framework\TestCase {

	use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

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
	protected $properties;
	/**
	 * @var Mockery\MockInterface
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
	 * Setup default mocks
	 */
	protected function setUp() {
		$this->originImplementation = \Mockery::mock('\SRAG\Hub2\Origin\IOriginImplementation');
		$this->properties = new UserOriginProperties();
		$this->origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($this->properties);
		$this->origin->shouldReceive('implementation')->andReturn($this->originImplementation);
		$this->origin->shouldReceive('getId');
		$this->originConfig = \Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig");
		$this->statusTransition = new ObjectStatusTransition($this->originConfig);
		$this->userDTO = new UserDTO('extIdOfJohnDoe');
		$this->userDTO->setFirstname('John');
		$this->userDTO->setLastname('Doe');
		$this->userDTO->setEmail('john.doe@example.com');
		$this->userDTO->setLogin('JohnDoe123');
		$this->userDTO->setPasswd('mySuperSecretPassword');
		$this->userDTO->setCountry('NoMansLand');
//		$this->userDTO->setBirthday(new \DateTime('1986-06-19'));
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
		$this->user->shouldReceive('setData')->once()->with($this->userDTO->getData());
		$this->user->shouldReceive('setProcessedDate')->once();
		// Note: We don't care about the correct status here since this is tested in ObjectStatusTransitionTest
		$this->user->shouldReceive('setStatus')->once();
		$this->user->shouldReceive('save')->once();
		$this->ilObjUser = \Mockery::mock('overload:\ilObjUser', 'ilObject');
		$this->ilObjUser->shouldReceive('getId')->andReturn(self::ILIAS_OBJ_ID);
	}

//	protected function tearDown() {
//		$this->userDTO = null;
//		$this->user = null;
//		$this->ilObjUser = null;
//		$this->origin = null;
//		$this->originConfig = null;
//		$this->originImplementation = null;
//		$this->statusTransition = null;
//		$this->properties = null;
//	}

	/**
	 * Create ILIAS User
	 */
	public function test_create_user_with_default_properties() {
		$processor = new UserSyncProcessor($this->origin, $this->statusTransition);
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
		$this->properties->setData([UserOriginProperties::USERNAME_MODE => $mode]);
		$this->setDefaultExpectationsForCreationOfILIASUser();
		$processor = new UserSyncProcessor($this->origin, $this->statusTransition);
		$this->ilObjUser->shouldReceive('setLogin')->once()->with($expectedLoginName);
		$processor->process($this->user, $this->userDTO);
	}

	/**
	 * Create ILIAS user: Test that user is not active AND profile is set to incomplete.
	 */
	public function test_create_user_with_inactive_account() {
		$this->properties->setData([UserOriginProperties::ACTIVATE_ACCOUNT => false]);
		$this->setDefaultExpectationsForCreationOfILIASUser();
		$this->ilObjUser->shouldReceive('setActive')->once()->with(false);
		$this->ilObjUser->shouldReceive('setProfileIncomplete')->once()->with(true);
		$processor = new UserSyncProcessor($this->origin, $this->statusTransition);
		$processor->process($this->user, $this->userDTO);
	}

	/**
	 * Update ILIAS user: Using the default properties (properties of UserDTO are not forwarded to ILIAS user).
	 */
	public function test_update_user_with_default_properties() {
		$this->setDefaultExpectationsForUpdateOfILIASUser();
		$this->ilObjUser->shouldReceive('_exists')->zeroOrMoreTimes()->andReturn(true);
		$this->ilObjUser->shouldReceive('setImportId')->once();
		$this->ilObjUser->shouldReceive('setTitle')->once();
		$this->ilObjUser->shouldReceive('setDescription')->once()->with($this->userDTO->getEmail());
		$this->ilObjUser->shouldReceive('update')->once();
		$this->originImplementation->shouldReceive('beforeUpdateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterUpdateILIASObject')->once();
		$this->user->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
		$this->user->shouldReceive('getHashCode')->once()->andReturn('previousHashCode');
		$processor = new UserSyncProcessor($this->origin, $this->statusTransition);
		$processor->process($this->user, $this->userDTO);
	}


	public function test_update_user_not_required_if_no_properties_changed() {
		$this->setDefaultExpectationsForUpdateOfILIASUser();
		$this->user->shouldReceive('computeHashCode')->once()->andReturn('actualHashCode');
		$this->user->shouldReceive('getHashCode')->once()->andReturn('actualHashCode');
		$processor = new UserSyncProcessor($this->origin, $this->statusTransition);
		$processor->process($this->user, $this->userDTO);
		$this->originImplementation->shouldNotHaveReceived('beforeUpdateILIASObject');
		$this->originImplementation->shouldNotHaveReceived('afterUpdateILIASObject');
		$this->ilObjUser->shouldNotHaveReceived('update');
		$this->ilObjUser->shouldNotHaveReceived('setTitle');
		$this->ilObjUser->shouldNotHaveReceived('setDescription');
	}

	/**
	 * @return array
	 */
	public function getUsernameModes() {
		return [
			[UserOriginProperties::USERNAME_MODE_SHORTENED_FIRST_LASTNAME, 'j.doe'],
			[UserOriginProperties::USERNAME_MODE_EMAIL, 'john.doe@example.com'],
			[UserOriginProperties::USERNAME_MODE_EXT_ACCOUNT, 'john.doe.external'],
			[UserOriginProperties::USERNAME_MODE_EXT_ID, 'extidofjohndoe'],
			[UserOriginProperties::USERNAME_MODE_FIRST_LASTNAME, 'john.doe'],
			[UserOriginProperties::USERNAME_MODE_HUB, 'johndoe123'],
		];
	}

	/**
	 * Set some default expectations on the mock objects when updating ILIAS users
	 */
	protected function setDefaultExpectationsForUpdateOfILIASUser() {
		$this->ilObjUser->shouldReceive('_exists')->zeroOrMoreTimes()->byDefault();
		$this->user->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_UPDATE);
		$this->user->shouldReceive('getILIASId')->andReturn(self::ILIAS_OBJ_ID);
//		$this->ilObjUser->shouldReceive('setTitle')->once()->byDefault();
//		$this->ilObjUser->shouldReceive('setDescription')->once()->with($this->userDTO->getEmail());
//		$this->ilObjUser->shouldReceive('setImportId')->once()->byDefault();
//		$this->ilObjUser->shouldReceive('update')->once();
	}

	/**
	 * Set some default expectations on the mock objects when creating ILIAS users
	 */
	protected function setDefaultExpectationsForCreationOfILIASUser() {
		$this->originImplementation->shouldReceive('beforeCreateILIASObject')->once();
		$this->originImplementation->shouldReceive('afterCreateILIASObject')->once();
		$this->user->shouldReceive('getStatus')->andReturn(IObject::STATUS_TO_CREATE);
		$this->user->shouldReceive('setILIASId')->once()->with(self::ILIAS_OBJ_ID);
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