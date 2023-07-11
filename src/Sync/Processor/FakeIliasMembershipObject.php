<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Class FakeIliasMembershipObject
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class FakeIliasMembershipObject extends FakeIliasObject
{
    public const GLUE = IObjectRepository::GLUE;
    /**
     * @var int
     */
    protected $user_id_ilias;
    /**
     * @var int
     */
    protected $container_id_ilias;

    /**
     * FakeIliasMembershipObject constructor
     * @param int $container_id_ilias
     * @param int $user_id_ilias
     */
    public function __construct($container_id_ilias, $user_id_ilias)
    {
        parent::__construct();
        $this->container_id_ilias = (int) $container_id_ilias;
        $this->user_id_ilias = (int) $user_id_ilias;
        $this->initId();
    }

    /**
     * @return FakeIliasMembershipObject
     */
    public static function loadInstanceWithConcatenatedId(string $id)
    {
        [$container_id_ilias, $user_id_ilias] = explode(self::GLUE, $id);

        return new self((int) $container_id_ilias, (int) $user_id_ilias);
    }

    /**
     * @inheritdoc
     */
    public function getId() : string
    {
        return $this->id;
    }

    public function getUserIdIlias() : int
    {
        return $this->user_id_ilias;
    }

    public function setUserIdIlias(int $user_id_ilias) : void
    {
        $this->user_id_ilias = $user_id_ilias;
    }

    public function getContainerIdIlias() : int
    {
        return $this->container_id_ilias;
    }

    public function setContainerIdIlias(int $container_id_ilias) : void
    {
        $this->container_id_ilias = $container_id_ilias;
    }

    /**
     *
     */
    public function initId() : void
    {
        $this->setId(implode(self::GLUE, [$this->container_id_ilias, $this->user_id_ilias]));
    }
}
