<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Exception\BuildObjectsFailedException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOriginGeneratorImplementation
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginGeneratorImplementation extends IOriginImplementation
{
    /**
     * Build the hub DTO objects from the parsed data.
     * An instance of such objects MUST be obtained over the DTOObjectFactory. The factory
     * is available via $this->factory().
     * Example for an origin syncing users:
     * $user = $this->factory()->user($data->extId);
     * $user->setFirstname($data->firstname)
     *  ->setLastname($data->lastname)
     *  ->setGender(UserDTO::GENDER_FEMALE);
     * Throw a BuildObjectsFailedException to abort the sync at this stage.
     * @return IDataTransferObject[]|\Generator
     * @throws BuildObjectsFailedException
     */
    public function buildObjects(): \Generator;
}
