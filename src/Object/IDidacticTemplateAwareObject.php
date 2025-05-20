<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    public function getDidacticTemplateId(): ?int;
}
