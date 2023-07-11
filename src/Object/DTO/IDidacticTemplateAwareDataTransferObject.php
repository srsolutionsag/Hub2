<?php

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
    public function getDidacticTemplateId() : ?int;
}
