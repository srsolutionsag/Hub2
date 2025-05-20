<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;

/**
 * Interface IDidacticTemplateSyncProcessor
 * @package srag\Plugins\Hub2\Sync
 * @author  Thibeau Fuhrer <thf@studer-raimann.ch>
 */
interface IDidacticTemplateSyncProcessor
{
    public function handleDidacticTemplate(IDidacticTemplateAwareDataTransferObject $dto, ilObject $ilias_object);
}
