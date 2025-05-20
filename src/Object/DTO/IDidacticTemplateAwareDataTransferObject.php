<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

/**
 * Interface IDidacticTemplateAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Thibeau Fuhrer <thf@studer-raimann.ch>
 */
interface IDidacticTemplateAwareDataTransferObject
{
    public function setDidacticTemplateId(int $id);

    /**
     * @return int
     */
    public function getDidacticTemplateId(): ?int;
}
