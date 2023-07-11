<?php

namespace srag\Plugins\Hub2\MappingStrategy;

/**
 * Class MappingStrategyFactory
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MappingStrategyFactory implements IMappingStrategyFactory
{

    /**
     * @inheritdoc
     */
    public function byEmail() : IMappingStrategy
    {
        return new ByEmail();
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
