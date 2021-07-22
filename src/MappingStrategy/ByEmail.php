<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use srag\DIC\Hub2\Version\Version;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByEmail
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByEmail extends AMappingStrategy implements IMappingStrategy
{

    private const GET_USER_IDS_BY_EMAIL_PRIOR_ILIAS_6 = '_getUserIdsByEmail';
    private const GET_USER_IDS_BY_EMAIL_ILIAS_6 = 'getUserIdsByEmail';
    /**
     * @var Version
     */
    protected $version;

    /**
     * ByEmail constructor.
     * @param Version $version
     */
    public function __construct(Version $version = null)
    {
        $this->version = $version ?? new Version();
    }

    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto) : int
    {
        if (!$dto instanceof UserDTO) {
            throw new HubException("Mapping using Email not supported for this type of DTO");
        }

        if ($this->version->isLower('6.0')) {
            $method = self::GET_USER_IDS_BY_EMAIL_PRIOR_ILIAS_6;
        } else {
            $method = self::GET_USER_IDS_BY_EMAIL_ILIAS_6;
        }

        $login = false;
        $user_ids_by_email = ilObjUser::{$method}($dto->getEmail());
        if (is_array($user_ids_by_email)) {
            $login = $user_ids_by_email[0];
        }

        if (!$login) {
            return 0;
        }
        if ($this->version->isLower('6.0')) {
            return (int) ilObjUser::_lookupId($login);
        } else {
            return (int) $login;
        }
    }
}
