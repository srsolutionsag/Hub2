<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor\General;

use srag\Plugins\Hub2\Object\General\LearningProgressSettings;
use srag\Plugins\Hub2\Object\DTO\ILearningProgressSettingsAwareDataTransferObject;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait LearningProgressSettingsSyncProcessor
{
    protected function handleLPSettings(ILearningProgressSettingsAwareDataTransferObject $dto, \ilObject $object): void
    {
        $lp_settings = $dto->getLPSettings();
        if (!$lp_settings instanceof LearningProgressSettings) {
            return;
        }
        $object_id = $object->getId();

        // Write Settings
        $obj_settings = new \ilLPObjSettings($object_id);
        $obj_settings->setMode($lp_settings->getLearningProgressMode());
        $obj_settings->update();
    }
}
