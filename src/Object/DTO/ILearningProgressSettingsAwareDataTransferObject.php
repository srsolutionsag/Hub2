<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\LearningProgressSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface ILearningProgressSettingsAwareDataTransferObject
{
    public function getLPSettings() : ?LearningProgressSettings;

    public function setLPSettings(
        ?LearningProgressSettings $learningProgressSettings
    ) : ILearningProgressSettingsAwareDataTransferObject;
}
