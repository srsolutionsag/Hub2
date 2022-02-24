<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;

/**
 * Trait DidacticTemplateSyncProcessor
 * @package srag\Plugins\Hub2\Sync
 * @author  Thibeau Fuhrer <thf@studer-raimann.ch>
 */
trait DidacticTemplateSyncProcessor
{

    /**
     * @param IDidacticTemplateAwareDataTransferObject $dto
     * @param ilObject                                 $ilias_object
     */
    public function handleDidacticTemplate(IDidacticTemplateAwareDataTransferObject $dto, ilObject $ilias_object) : void
    {
        if (null !== ($tpl_id = $dto->getDidacticTemplateId())) {
            $ilias_object->applyDidacticTemplate($tpl_id);
        }
    }
}
