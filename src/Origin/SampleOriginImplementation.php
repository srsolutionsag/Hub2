<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Exception\BuildObjectsFailedException;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;

/**
 * Class SampleOriginImplementation
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SampleOriginImplementation extends AbstractOriginImplementation
{
    /**
     * Connect to the service providing the sync data.
     * Throw a ConnectionFailedException to abort the sync if a connection is not possible.
     * @throws ConnectionFailedException
     */
    public function connect() : bool
    {
        //		$file = $this->config()->getPath();
        //		if (!is_file($file)) {
        //			throw new ConnectionFailedException("Data file does not exist");
        //		}
        // TODO: Implement connect() method.
    }

    /**
     * Parse and prepare (sanitize/validate) the data to fill the DTO objects.
     * Return the number of data. Note that this number is used to check if the amount of delivered
     * data is sufficent to continue the sync, depending on the configuration of the origin.
     * Throw a ParseDataFailedException to abort the sync if your data cannot be parsed.
     * @throws ParseDataFailedException
     */
    public function parseData() : int
    {
        //		foreach (['userData1', 'userData2', 'userData3'] as $fakeUserData) {
        //			$this->data[] = $fakeUserData;
        //		}
        //		return count($this->data);
        // TODO: Implement parseData() method.
    }

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
     * @return IDataTransferObject[]
     * @throws BuildObjectsFailedException
     */
    public function buildObjects() : array
    {
        //		$userDTOs = [];
        //		foreach ($this->data as $userData) {
        //			$userDTO = $this->factory()->user('myExternalI')
        //				->setFirstname('John')
        //				->setLastname('Doe')
        //				->setEmail('john.doe@fbi.com');
        //			$userDTOs[] = $userDTO;
        //		}
        //		return $userDTOs;
        // TODO: Implement buildObjects() method.
    }

    /**
     * Called if any exception occurs during processing the ILIAS objects. This hook can be used to
     * influence the further processing of the current origin sync or the global sync:
     * - Throw an AbortOriginSyncException to stop the current sync of this origin.
     *   Any other following origins in the processing chain are still getting executed normally.
     * - Throw an AbortSyncException to stop the global sync. The sync of any other following
     * origins in the processing chain is NOT getting executed.
     * Note that if you do not throw any of the exceptions above, the sync will continue.
     */
    public function handleLog(ILog $log) : void
    {
    }

    public function beforeCreateILIASObject(HookObject $hook) : void
    {
    }

    public function afterCreateILIASObject(HookObject $hook) : void
    {
    }

    public function beforeUpdateILIASObject(HookObject $hook) : void
    {
    }

    public function afterUpdateILIASObject(HookObject $hook) : void
    {
    }

    public function beforeDeleteILIASObject(HookObject $hook) : void
    {
    }

    public function afterDeleteILIASObject(HookObject $hook) : void
    {
    }

    /**
     * Executed before the synchronization of the origin is executed.
     */
    public function beforeSync() : void
    {
    }

    /**
     * Executed after the synchronization of the origin has been executed.
     */
    public function afterSync() : void
    {
    }

    /**
     * @inheritdoc
     */
    public function overrideStatus(HookObject $hook) : void
    {
    }
}
