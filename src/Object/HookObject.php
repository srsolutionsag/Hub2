<?php

namespace srag\Plugins\Hub2\Object;

use ilHub2Plugin;
use ilObject;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasObject;

/**
 * Class HookObject
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HookObject
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IDataTransferObject
     */
    protected $dto;
    /**
     * @var IObject
     */
    private $object;
    /**
     * @var ilObject
     */
    private $ilias_object;

    public function __construct(IObject $object, IDataTransferObject $dto)
    {
        $this->object = $object;
        $this->dto = $dto;
    }

    /**
     * Get the external ID of the object helps to identify the object
     * @return string
     */
    public function getExtId()
    {
        return $this->object->getExtId();
    }

    /**
     * Get the current status, see constants in IObject
     */
    public function getStatus() : int
    {
        return $this->object->getStatus();
    }

    /**
     * @throws HubException
     */
    public function overrideStatus(int $status) : void
    {
        $this->object->setStatus($status);
    }

    /**
     * @param ilObject|FakeIliasObject $object
     * @return HookObject
     */
    public function withILIASObject($object)
    {
        $clone = clone $this;
        $clone->ilias_object = $object;

        return $clone;
    }

    /**
     * Get the ILIAS object which has been processed.
     * Note that this object is only available in the
     * IOriginImplementation::after(Create|Update|Delete)Object callbacks, it is NOT set for any
     * before callbacks
     * @return ilObject|FakeIliasObject|null
     */
    public function getILIASObject()
    {
        return $this->ilias_object;
    }

    /**
     * Get the ID of the linked ILIAS object.
     * Note that this ID may be the object or ref-ID depending on the synced object.
     * Also note that this ID may be NULL if the ILIAS object has not been created yet, e.g.
     * in the case of IOriginImplementation::beforeCreateILIASObject()
     * @return int
     */
    public function getILIASId()
    {
        return $this->object->getILIASId();
    }

    public function getDTO() : IDataTransferObject
    {
        return $this->dto;
    }

    /**
     * @return IObject the internal AR Object, not the ILIAS Object
     */
    public function getObject() : IObject
    {
        return $this->object;
    }
}
