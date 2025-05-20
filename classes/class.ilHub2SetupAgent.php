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

use ILIAS\Setup\Metrics\Storage;
use ILIAS\Setup\Objective;
use ILIAS\Setup\Config;
use ILIAS\Setup\ObjectiveCollection;
use srag\Plugins\Hub2\Setup\DB\Update;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ilHub2SetupAgent extends \ilPluginDefaultAgent
{
    public function __construct()
    {
        parent::__construct('Hub2');
    }

    public function getInstallObjective(Config $config = null): Objective
    {
        return new ObjectiveCollection(
            'HUB2 Installation',
            true,
            parent::getInstallObjective($config),
        );
    }

    public function getUpdateObjective(Config $config = null): Objective
    {
        global $DIC;
        return new ObjectiveCollection(
            'HUB2 Installation',
            true,
            new ResetObjective(),
            parent::getUpdateObjective($config),
            new \ilDatabaseUpdateStepsExecutedObjective(new Update())
        );
    }

    public function getBuildArtifactObjective(): Objective
    {
        return parent::getBuildArtifactObjective();
    }

    public function getStatusObjective(Storage $storage): Objective
    {
        return parent::getStatusObjective($storage);
    }

    public function getMigrations(): array
    {
        return [];
    }

    public function getNamedObjectives(?Config $config = null): array
    {
        return parent::getNamedObjectives($config);
    }

}
