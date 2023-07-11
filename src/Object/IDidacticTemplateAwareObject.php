<?php

namespace srag\Plugins\Hub2\Object;

/**
 * Interface IDidacticTemplateAwareObject
 * @package srag\Plugins\Hub2\Object;
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDidacticTemplateAwareObject
{
    /**
     * @var string key for associative arrays
     */
    public const F_NAME_TEMPLATE_ID = 'id';

    public function setDidacticTemplateId(int $id);

    /**
     * @return int
     */
    public function getDidacticTemplateId() : ?int;
}
