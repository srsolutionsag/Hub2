<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    /**
     * @readonly
     */
    private IOriginConfig $originConfig;
    /**
     * @readonly
     */
    private IDataTransferObjectFactory $factory;
    /**
     * @readonly
     */
    private IMetadataFactory $metadataFactory;
    /**
     * @readonly
     */
    private ITaxonomyFactory $taxonomyFactory;
    /**
     * @readonly
     */
    private IMappingStrategyFactory $mapping_strategy_factory;
    protected IOrigin $origin;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var array
     */
    protected $data = [];

    /**
     * AbstractOriginImplementation constructor
     */
    public function __construct(
        IOriginConfig $originConfig,
        IDataTransferObjectFactory $factory,
        IMetadataFactory $metadataFactory,
        ITaxonomyFactory $taxonomyFactory,
        IMappingStrategyFactory $mapping_strategy_factory,
        IOrigin $origin
    ) {
        $this->originConfig = $originConfig;
        $this->factory = $factory;
        $this->metadataFactory = $metadataFactory;
        $this->taxonomyFactory = $taxonomyFactory;
        $this->mapping_strategy_factory = $mapping_strategy_factory;
        $this->origin = $origin;
        /** @noRector  include once for Origins */
        include_once __DIR__ . "/../../vendor/autoload.php";
    }

    final protected function config(): IOriginConfig
    {
        return $this->originConfig;
    }

    final protected function origin(): IOrigin
    {
        return $this->origin;
    }

    /**
     * @return IDataTransferObjectFactory
     */
    final protected function factory()
    {
        return $this->factory;
    }

    public function hookConfig(): Config
    {
        return new Config(
            false
        );
    }

    final protected function log(IDataTransferObject $dto = null): ILog
    {
        return LogRepository::getInstance()->factory()->originLog($this->origin, null, $dto);
    }

    final protected function mapping(): IMappingStrategyFactory
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

    public function overrideStatus(HookObject $hook): void
    {
        // TODO: Implement overrideStatus() method.
    }

    public function getAdHocParentScopesAsExtIds(): array
    {
        return [];
    }

    public function handleNoLongerDeliveredObject(HookObject $hook): void
    {
        // TODO: Implement handleOutdated() method.
    }

    public function handleAllObjects(HookObject $hook): void
    {
        // TODO: Implement handleAllObjects() method.
    }

    public function canDroppedFileContentBestored(string $content): bool
    {
        return true;
    }
}
