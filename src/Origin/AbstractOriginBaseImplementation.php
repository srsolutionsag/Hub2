<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyFactory;
use srag\Plugins\Hub2\Metadata\IMetadataFactory;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Taxonomy\ITaxonomyFactory;
use srag\Plugins\Hub2\Origin\Hook\Config;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class AbstractOriginBaseImplementation
 * Any implementation of a origin MUST extend this class.
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractOriginBaseImplementation implements IOriginImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IMappingStrategyFactory
     */
    private $mapping_strategy_factory;
    /**
     * @var ITaxonomyFactory
     */
    private $taxonomyFactory;
    /**
     * @var IMetadataFactory
     */
    private $metadataFactory;
    /**
     * @var IOriginConfig
     */
    private $originConfig;
    /**
     * @var IDataTransferObjectFactory
     */
    private $factory;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var IOrigin
     */
    protected $origin;

    /**
     * AbstractOriginImplementation constructor
     */
    public function __construct(
        IOriginConfig $config,
        IDataTransferObjectFactory $factory,
        IMetadataFactory $metadataFactory,
        ITaxonomyFactory $taxonomyFactory,
        IMappingStrategyFactory $mapping_strategy,
        IOrigin $origin
    ) {
        /** @noRector  include once for Origins */
        include_once "./Customizing/global/plugins/Services/Cron/CronHook/Hub2/vendor/autoload.php";
        $this->originConfig = $config;
        $this->factory = $factory;
        $this->metadataFactory = $metadataFactory;
        $this->taxonomyFactory = $taxonomyFactory;
        $this->mapping_strategy_factory = $mapping_strategy;
        $this->origin = $origin;
    }

    /**
     * @return IOriginConfig
     */
    final protected function config()
    {
        return $this->originConfig;
    }

    /**
     * @return IDataTransferObjectFactory
     */
    final protected function factory()
    {
        return $this->factory;
    }

    public function hookConfig() : Config
    {
        return new Config(
            true
        );
    }

    final protected function log(IDataTransferObject $dto = null) : ILog
    {
        return LogRepository::getInstance()->factory()->originLog($this->origin, null, $dto);
    }

    final protected function mapping() : IMappingStrategyFactory
    {
        return $this->mapping_strategy_factory;
    }

    /**
     * @return IMetadataFactory
     */
    final protected function metadata()
    {
        return $this->metadataFactory;
    }

    /**
     * @return ITaxonomyFactory
     */
    final protected function taxonomy()
    {
        return $this->taxonomyFactory;
    }

    // HOOKS

    /**
     * @inheritdoc
     */
    public function overrideStatus(HookObject $hook) : void
    {
        // TODO: Implement overrideStatus() method.
    }

    /**
     * @inheritdoc
     */
    public function getAdHocParentScopesAsExtIds() : array
    {
        return [];
    }

    public function handleNoLongerDeliveredObject(HookObject $hook) : void
    {
        // TODO: Implement handleOutdated() method.
    }

    public function handleAllObjects(HookObject $hook) : void
    {
        // TODO: Implement handleAllObjects() method.
    }

    public function canDroppedFileContentBestored(string $content) : bool
    {
        return true;
    }
}
