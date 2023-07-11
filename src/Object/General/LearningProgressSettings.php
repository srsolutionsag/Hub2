<?php

namespace srag\Plugins\Hub2\Object\General;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class LearningProgressSettings extends BaseDependentSetting implements IDependentSettings
{
    public const ACTIVATE_LEARNING_PROGRESS = 'lp_on';
    public const LP_MODE = 'mode';

    public const LP_MODE_DEACTIVATED = \ilLPObjSettings::LP_MODE_DEACTIVATED;
    public const LP_MODE_BY_TUTOR = \ilLPObjSettings::LP_MODE_MANUAL_BY_TUTOR;

    public function __construct(
        int $learning_progress_mode = self::LP_MODE_DEACTIVATED
    ) {
        $this->setLearningProgressMode($learning_progress_mode);
    }

    public function isActivateLearningProgress() : bool
    {
        return $this->offsetGet(self::ACTIVATE_LEARNING_PROGRESS);
    }

    public function activateLearningProgress(
        bool $activate_learning_progress,
        int $mode
    ) : LearningProgressSettings {
        $this->setLearningProgressMode($mode);

        return $this->set(self::ACTIVATE_LEARNING_PROGRESS, $activate_learning_progress);
    }

    public function setLearningProgressMode(int $mode) : LearningProgressSettings
    {
        // check if mode is implemented
        if (!in_array($mode, [
            self::LP_MODE_DEACTIVATED,
            self::LP_MODE_BY_TUTOR
            // more to come later
        ], true)) {
            throw new \InvalidArgumentException("Learning Progress Mode $mode is not implemented");
        }
        if ($mode !== self::LP_MODE_DEACTIVATED) {
            $this->set(self::ACTIVATE_LEARNING_PROGRESS, true);
        }

        return $this->set(self::LP_MODE, $mode);
    }

    public function getLearningProgressMode() : int
    {
        return $this->offsetGet(self::LP_MODE) ?? self::LP_MODE_DEACTIVATED;
    }
}
