<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Shortlink;

use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Class AbstractRepositoryMembershipLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 */
abstract class AbstractRepositoryMembershipLink extends AbstractRepositoryLink implements IObjectLink
{
    protected function getILIASId()
    {
        [$container_id] = explode(IObjectRepository::GLUE, $this->object->getILIASId());
        return $container_id;
    }
}
