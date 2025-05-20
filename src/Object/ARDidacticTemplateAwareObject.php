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
 * Class ARDidacticTemplateAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARDidacticTemplateAwareObject
{
    public function setDidacticTemplateId(int $id): void
    {
        $this->data[self::F_NAME_TEMPLATE_ID] = $id;
    }

    public function getDidacticTemplateId(): ?int
    {
        return $this->data[self::F_NAME_TEMPLATE_ID];
    }
}
