<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\OriginImplementationFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SyncProcessorFactory;

/**
 * Class OriginSyncFactory
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOrigin
     */
    protected $origin;

    public function __construct(IOrigin $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @throws HubException
     */
    public function instance() : OriginSync
    {
        $statusTransition = new ObjectStatusTransition($this->origin->config());

        return new OriginSync(
            $this->origin,
            $this->getObjectRepository(),
            new ObjectFactory($this->origin),
            $statusTransition
        );
    }

    /**
     * @throws HubException
     */
    public function initImplementation(OriginSync $originSync) : void
    {
        $implementationFactory = new OriginImplementationFactory($this->origin);

        $originImplementation = $implementationFactory->instance();

        $originSync->setProcessor(
            $this->getSyncProcessor(
                $this->origin,
                $originImplementation,
                $originSync->getStatusTransition()
            )
        );

        $originSync->setImplementation($originImplementation);
    }

    /**
     * @return IObjectRepository
     */
    protected function getObjectRepository()
    {
        $ucfirst = ucfirst($this->origin->getObjectType());
        $class = "srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}Repository";

        return new $class($this->origin);
    }

    /**
     * @return IObjectSyncProcessor
     */
    protected function getSyncProcessor(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $statusTransition
    ) {
        $processorFactory = new SyncProcessorFactory($origin, $implementation, $statusTransition);
        $processor = $origin->getObjectType();

        return $processorFactory->$processor();
    }
}
