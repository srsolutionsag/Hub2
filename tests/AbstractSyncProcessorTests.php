<?php

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Origin\Config\IOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\Properties\IOriginProperties;
use SRAG\Plugins\Hub2\Sync\ObjectStatusTransition;

require_once __DIR__ . "/AbstractHub2Tests.php";

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

	use MockeryPHPUnitIntegration;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var OriginNotifications
	 */
	protected $originNotifications;
	/**
	 * @var ObjectStatusTransition
	 */
	protected $statusTransition;
	/**
	 * @var MockInterface|ILog
	 */
	protected $originLog;
	/**
	 * @var IDataTransferObject
	 */
	protected $dto;
	/**
	 * @var MockInterface
	 * @see http://docs.mockery.io/en/latest/cookbook/mocking_hard_dependencies.html
	 */
	protected $ilObject;
	/**
	 * @var MockInterface|ilTree
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
	 * @var MockInterface|IOriginImplementation
	 */
	protected $originImplementation;


	protected function initLog() {
		$this->originLog = Mockery::mock("SRAG\Plugins\Hub2\Log\OriginLog");
	}


	protected function initNotifications() {
		$this->originNotifications = new OriginNotifications();
	}


	protected function initStatusTransitions() {
		$this->statusTransition = new ObjectStatusTransition(Mockery::mock("SRAG\Plugins\Hub2\Origin\Config\IOriginConfig"));
	}


	protected function setupGeneralDependencies() {
		$this->initStatusTransitions();
		$this->initNotifications();
		$this->initLog();
		$this->initDIC();
	}


	/**
	 * @param IOriginProperties $properties
	 * @param IOriginConfig     $config
	 */
	protected function initOrigin(IOriginProperties $properties, IOriginConfig $config) {
		$this->originProperties = $properties;
		$this->originConfig = $config;
		$this->origin = Mockery::mock("SRAG\Plugins\Hub2\Origin\IOrigin");
		$this->origin->shouldReceive('properties')->andReturn($properties);
		$this->origin->shouldReceive('getId');
		$this->origin->shouldReceive('config')->andReturn($config);
		$this->originImplementation = Mockery::mock('\SRAG\Plugins\Hub2\Origin\IOriginImplementation');
	}


	protected function initDIC() {
		global $DIC;

		$DIC = Mockery::mock('overload:\ILIAS\DI\Container', "Pimple\Container");
		$tree_mock = Mockery::mock('overload:\ilTree');
		$tree_mock->shouldReceive('isInTree')->with(1)->once()->andReturn(true);
		$this->tree = $tree_mock;
		$DIC->shouldReceive('repositoryTree')->once()->andReturn($tree_mock);

		$language_mock = Mockery::mock('overload:\ilLanguage', "ilObject");
		$language_mock->shouldReceive('getDefaultLanguage')->andReturn('en');
		$DIC->shouldReceive('language')->once()->andReturn($language_mock);
	}


	abstract protected function initDTO();


	abstract protected function initHubObject();


	abstract protected function initILIASObject();


	abstract protected function initDataExpectations();
}
