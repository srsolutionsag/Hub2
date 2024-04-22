<?php

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

}
