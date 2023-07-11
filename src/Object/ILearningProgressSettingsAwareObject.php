<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Object\General\LearningProgressSettings;
use srag\Plugins\Hub2\Object\DTO\ILearningProgressSettingsAwareDataTransferObject;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface ILearningProgressSettingsAwareObject
{
    public function getLPSettings() : ?LearningProgressSettings;

    public function setLPSettings(
        ?LearningProgressSettings $learningProgressSettings
    ) : ILearningProgressSettingsAwareDataTransferObject;
}
