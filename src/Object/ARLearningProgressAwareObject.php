<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Object\General\LearningProgressSettings;
use srag\Plugins\Hub2\Object\DTO\ILearningProgressSettingsAwareDataTransferObject;

/**
 * Class ARTaxonomyAwareObject
 *
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARLearningProgressAwareObject
{
    /**
     * @var LearningProgressSettings|null
     */
    private $learning_progress_settings;

    public function getLPSettings() : ?LearningProgressSettings
    {
        return $this->learning_progress_settings;
    }

    public function setLPSettings(
        ?LearningProgressSettings $learningProgressSettings
    ) : ILearningProgressSettingsAwareDataTransferObject {
        $this->learning_progress_settings = $learningProgressSettings;

        return $this;
    }
}
