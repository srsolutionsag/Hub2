<?php

require_once __DIR__ . "/AbstractHub2Tests.php";

use ILIAS\DI\Container;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Pimple\Container as PimpleContainer;
use srag\Plugins\Hub2\Log\Factory as LogFactory;
use srag\Plugins\Hub2\Log\IFactory as ILogFactory;
use srag\Plugins\Hub2\Log\IRepository as ILogRepository;
use srag\Plugins\Hub2\Log\Repository as LogRepository;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Sync\ObjectStatusTransition;

/**
 * Base class for all unit tests of Hub2
 * @author                 Stefan Wanzenried <sw@studer-raimann.ch>
 * @runTestsInSeparateProcesses
 * @preserveGlobalState    disabled
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
abstract class AbstractSyncProcessorTests extends AbstractHub2Tests
{
    use MockeryPHPUnitIntegration;

    /**
     * @var IOrigin
     */
    protected $origin;
    /**
     * @var ObjectStatusTransition
     * @deprecated
     */
    protected $statusTransition;
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

    protected function initStatusTransitions()
    {
        $this->statusTransition = new ObjectStatusTransition(Mockery::mock(IOriginConfig::class));
    }

    protected function setupGeneralDependencies()
    {
        $this->initStatusTransitions();
        $this->initDIC();
        $this->initLog();
    }

    /**
     * @param IOriginProperties $properties
     * @param IOriginConfig     $config
     */
    protected function initOrigin(IOriginProperties $properties, IOriginConfig $config)
    {
        $this->originProperties = $properties;
        $this->originConfig = $config;
        $this->origin = Mockery::mock(IOrigin::class);
        $this->origin->shouldReceive('properties')->andReturn($properties);
        $this->origin->shouldReceive('getId');
        $this->origin->shouldReceive('config')->andReturn($config);
        $this->originImplementation = Mockery::mock(IOriginImplementation::class);
    }

    protected function initDIC()
    {
        global $DIC;

        $DIC = Mockery::mock('overload:' . Container::class, PimpleContainer::class);
        $tree_mock = Mockery::mock('overload:' . ilTree::class);
        $tree_mock->shouldReceive('isInTree')->with(1)->once()->andReturn(true);
        $this->tree = $tree_mock;
        $DIC->shouldReceive('repositoryTree')->once()->andReturn($tree_mock);

        $language_mock = Mockery::mock('overload:' . ilLanguage::class, ilObject::class);
        $language_mock->shouldReceive('getDefaultLanguage')->andReturn('en');
        $DIC->shouldReceive('language')->once()->andReturn($language_mock);
    }

    /**
     *
     */
    protected function initLog()/*: void*/
    {
        LogRepository::setInstance(Mockery::mock(ILogRepository::class));
        LogFactory::setInstance(Mockery::mock(ILogFactory::class));
    }

    abstract protected function initDTO();

    abstract protected function initHubObject();

    abstract protected function initILIASObject();

    abstract protected function initDataExpectations();
}
