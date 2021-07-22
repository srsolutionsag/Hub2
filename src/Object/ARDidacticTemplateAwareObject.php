<?php

namespace srag\Plugins\Hub2\Object;

/**
 * Class ARDidacticTemplateAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARDidacticTemplateAwareObject
{

    /**
     * @param int $id
     */
    public function setDidacticTemplateId(int $id) : void
    {
        $this->data[self::F_NAME_TEMPLATE_ID] = $id;
    }

    /**
     * @return int
     */
    public function getDidacticTemplateId() : ?int
    {
        return $this->data[self::F_NAME_TEMPLATE_ID];
    }
}
