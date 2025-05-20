<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByLogin
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByLogin extends AMappingStrategy implements IMappingStrategy
{
    public function map(IDataTransferObject $dto): int
    {
        if (!$dto instanceof UserDTO) {
            throw new HubException("Mapping using Login not supported for this type of DTO");
        }

        return ilObjUser::getUserIdByLogin($dto->getLogin());
    }
}
