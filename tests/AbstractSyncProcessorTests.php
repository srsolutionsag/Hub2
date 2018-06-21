<?php

// Autoload Hub2
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Origin\Properties\IOriginProperties;
use SRAG\Plugins\Hub2\Sync\ObjectStatusTransition;

require_once('AbstractHub2Tests.php');
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Base class for all unit tests of Hub2
 *
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
abstract class AbstractSyncProcessorTests extends AbstractHub2Tests {

	use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
	/**
	 * @var SRAG\Plugins\Hub2\Origin\IOrigin
	 */
	protected $origin;
	/**
	 * @var \SRAG\Plugins\Hub2\Notification\OriginNotifications
	 */
	protected $originNotifications;
	/**
	 * @var \SRAG\Plugins\Hub2\Sync\ObjectStatusTransition
	 */
	protected $statusTransition;
	/**
	 * @var Mockery\MockInterface|\SRAG\Plugins\Hub2\Log\ILog
	 */
	protected $originLog;
	/**
	 * @var \SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject
	 */
	protected $dto;
	/**
	 * @var Mockery\MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObject;
	/**
	 * @var Mockery\MockInterface|\ilTree
	 */
	protected $tree;
	/**
	 * @var IOriginConfig
	 */
	protected $originConfig;
	/**
	 * @var IOriginProperties
	 */
	protected $originProperties;
	/**
	 * @var Mockery\MockInterface|\SRAG\Plugins\Hub2\Origin\IOriginImplementation
	 */
	protected $originImplementation;


	protected function initLog() {
		$this->originLog = \Mockery::mock("SRAG\Plugins\Hub2\Log\OriginLog");
	}


	protected function initNotifications() {
		$this->originNotifications = new OriginNotifications();
	}


	protected function initStatusTransitions() {
		$this->statusTransition = new ObjectStatusTransition(\Mockery::mock("SRAG\Plugins\Hub2\Origin\Config\IOriginConfig"));
	}


	protected function setupGeneralDependencies() {
		$this->initStatusTransitions();
		$this->initNotifications();
		$this->initLog();
		$this->initDIC();
	}


	/**
	 * @param \SRAG\Plugins\Hub2\Origin\Properties\IOriginProperties $properties
	 * @param \SRAG\Plugins\Hub2\Origin\Config\IOriginConfig         $config
	 */
	protected function initOrigin(IOriginProperties $properties, IOriginConfig $config) {
		$this->originProperties = $properties;
		$this->originConfig = $config;
		$this->origin = \Mockery::mock("SRAG\Plugins\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($properties);
		$this->origin->shouldReceive('getId');
		$this->origin->shouldReceive('config')->andReturn($config);
		$this->originImplementation = \Mockery::mock('\SRAG\Plugins\Hub2\Origin\IOriginImplementation');
	}


	protected function initDIC() {
		global $DIC;

		$DIC = \Mockery::mock('overload:\ILIAS\DI\Container', "Pimple\Container");
		$tree_mock = \Mockery::mock('overload:\ilTree');
		$tree_mock->shouldReceive('isInTree')->with(1)->once()->andReturn(true);
		$this->tree = $tree_mock;
		$DIC->shouldReceive('repositoryTree')->once()->andReturn($tree_mock);

		$language_mock = \Mockery::mock('overload:\ilLanguage', "ilObject");
		$language_mock->shouldReceive('getDefaultLanguage')->andReturn('en');
		$DIC->shouldReceive('language')->once()->andReturn($language_mock);
	}


	abstract protected function initDTO();


	abstract protected function initHubObject();


	abstract protected function initILIASObject();


	abstract protected function initDataExpectations();
}