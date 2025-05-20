<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\FileDrop\ResourceStorage;

use ILIAS\ResourceStorage\Stakeholder\AbstractResourceStakeholder;

/**
 * Class Stakeholder
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Stakeholder extends AbstractResourceStakeholder
{
    public function getId(): string
    {
        return "hub2";
    }

    public function getOwnerOfNewResources(): int
    {
        return 6;
    }

    /** @noinspection MagicMethodsValidityInspection */
    public function __construct()
    {
    }

}
