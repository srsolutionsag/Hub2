<?php

namespace SRAG\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByLogin
 *
 * @package SRAG\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByLogin extends AMappingStrategy implements IMappingStrategy {

	/**
	 * @inheritDoc
	 */
	public function map(IDataTransferObject $dto): int {
		if (!$dto instanceof UserDTO) {
			throw new HubException("Mapping using Login not supported for this type of DTO");
		}

		return (int)ilObjUser::getUserIdByLogin($dto->getLogin());
	}
}
