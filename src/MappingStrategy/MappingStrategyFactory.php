<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\DIC\Hub2\Version\Version;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class MappingStrategyFactory
 *
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MappingStrategyFactory implements IMappingStrategyFactory
{

    use DICTrait;
    use Hub2Trait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var Version
     */
    protected $version;

    /**
     * MappingStrategyFactory constructor.
     */
    public function __construct()
    {
        $this->version = new Version();
    }

    /**
     * @inheritdoc
     */
    public function byEmail() : IMappingStrategy
    {
        return new ByEmail($this->version);
    }


    /**
     * @inheritdoc
     */
    public function byLogin() : IMappingStrategy
    {
        return new ByLogin();
    }


    /**
     * @inheritdoc
     */
    public function byExternalAccount() : IMappingStrategy
    {
        return new ByExternalAccount();
    }


    /**
     * @inheritdoc
     */
    public function byTitle() : IMappingStrategy
    {
        return new ByTitle();
    }


    /**
     * @inheritdoc
     */
    public function byImportId() : IMappingStrategy
    {
        return new ByImportId();
    }


    /**
     * @inheritDoc
     */
    public function byExtId() : IMappingStrategy
    {
        return new ByExtId();
    }


    /**
     * @inheritdoc
     */
    public function none() : IMappingStrategy
    {
        return new None();
    }
}
