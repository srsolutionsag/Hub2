<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Setup;

use ILIAS\Setup\Environment;
use ILIAS\Setup\Objective;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ResetObjective implements Objective
{
    public function getHash(): string
    {
        return hash('md5', self::class);
    }

    public function getLabel(): string
    {
        return 'Reset Hub2';
    }

    public function isNotable(): bool
    {
        return true;
    }

    public function isApplicable(Environment $environment): bool
    {
        return true;
    }

    public function getPreconditions(Environment $environment): array
    {
        return [new \ilDatabaseInitializedObjective()];
    }

    public function achieve(Environment $environment): Environment
    {
        // this objective currently does nothing. if enabled, it will reset the new update
        // steps to be run again on the next update.

        return $environment;
    }
}
