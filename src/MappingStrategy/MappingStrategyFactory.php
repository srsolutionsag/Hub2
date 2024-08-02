<?php

namespace srag\Plugins\Hub2\MappingStrategy;

/**
 * Class MappingStrategyFactory
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MappingStrategyFactory implements IMappingStrategyFactory
{
    public function byEmail(): IMappingStrategy
    {
        return new ByEmail();
    }


    public function byLogin(): IMappingStrategy
    {
        return new ByLogin();
    }


    public function byExternalAccount(): IMappingStrategy
    {
        return new ByExternalAccount();
    }


    public function byTitle(): IMappingStrategy
    {
        return new ByTitle();
    }


    public function byImportId(): IMappingStrategy
    {
        return new ByImportId();
    }


    public function byExtId(): IMappingStrategy
    {
        return new ByExtId();
    }


    public function none(): IMappingStrategy
    {
        return new None();
    }
}
