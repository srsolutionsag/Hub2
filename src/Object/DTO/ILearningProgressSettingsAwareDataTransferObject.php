<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\LearningProgressSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface ILearningProgressSettingsAwareDataTransferObject
{
    public function getLPSettings(): ?LearningProgressSettings;

    public function setLPSettings(
        ?LearningProgressSettings $learningProgressSettings
    ): ILearningProgressSettingsAwareDataTransferObject;
}
