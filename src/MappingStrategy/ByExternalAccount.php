<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByExternalAccount
 * @package srag\Plugins\Hub2\MappingStrategy
 */
class ByExternalAccount extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto): int
    {
        if (!$dto instanceof UserDTO) {
            throw new HubException("Mapping using External Account not supported for this type of DTO");
        }
        $login = ilObjUser::_checkExternalAuthAccount($dto->getAuthMode(), $dto->getExternalAccount());

        if (!$login) {
            return 0;
        }

        return (int) ilObjUser::_lookupId($login);
    }
}
