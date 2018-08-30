<?php

namespace SRAG\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByEmail
 *
 * @package SRAG\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByEmail extends AMappingStrategy implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		if (!$dto instanceof UserDTO) {
			throw new HubException("Mapping using Email not supported for this type of DTO");
		}
		$login = false;
		$user_ids_by_email = ilObjUser::_getUserIdsByEmail($dto->getEmail());
		if (is_array($user_ids_by_email)) {
			$login = $user_ids_by_email[0];
		}

		if (!$login) {
			return 0;
		}

		return (int)ilObjUser::_lookupId($login);
	}
}
