<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\MappingStrategy;

/**
 * Interface IMappingStrategyFactory
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategyFactory
{
    public function byEmail(): IMappingStrategy;

    public function byLogin(): IMappingStrategy;

    public function byExternalAccount(): IMappingStrategy;

    public function byTitle(): IMappingStrategy;

    public function byImportId(): IMappingStrategy;

    public function byExtId(): IMappingStrategy;

    public function none(): IMappingStrategy;
}
