<?php

namespace srag\Plugins\Hub2\Object\DTO;

/**
 * Trait DidacticTemplateAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Thibeau Fuhrer <thf@studer-raimann.ch>
 */
trait DidacticTemplateAwareDataTransferObject
{

    /**
     * @var int
     */
    protected $didactic_template_id;

    /**
     * @param int $id
     */
    public function setDidacticTemplateId(int $id) : void
    {
        $this->didactic_template_id = $id;
    }

    /**
     * @return int $id
     */
    public function getDidacticTemplateId() : ?int
    {
        return $this->didactic_template_id;
    }
}
