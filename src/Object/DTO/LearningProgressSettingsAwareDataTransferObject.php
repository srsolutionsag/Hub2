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
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait LearningProgressSettingsAwareDataTransferObject
{
    /**
     * @var LearningProgressSettings|null
     */
    protected $learningProgressSettings;

    public function getLPSettings(): ?LearningProgressSettings
    {
        return $this->learningProgressSettings;
    }

    public function setLPSettings(
        ?LearningProgressSettings $learningProgressSettings
    ): ILearningProgressSettingsAwareDataTransferObject {
        $this->learningProgressSettings = $learningProgressSettings;
        return $this;
    }
}
