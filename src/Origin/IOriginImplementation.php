<?php namespace SRAG\ILIAS\Plugins\Hub2\Origin;

use SRAG\ILIAS\Plugins\Exception\BuildObjectsFailedException;
use SRAG\ILIAS\Plugins\Exception\ConnectionFailedException;
use SRAG\ILIAS\Plugins\Exception\ParseDataFailedException;
use SRAG\ILIAS\Plugins\Hub2\Object\IObject;
use SRAG\ILIAS\Plugins\Hub2\Object\IObjectDTO;

/**
 * Interface OriginImplementation
 */
interface IOriginImplementation {

	/**
	 * Connect to the service providing the sync data.
	 * Throw a ConnectionFailedException to abort the sync if a connection is not possible.
	 *
	 * @throws ConnectionFailedException
	 * @return bool
	 */
	public function connect();

	/**
	 * Parse and prepare (sanitize/validate) the data to fill the DTO objects.
	 * Return the number of data. Note that this number is used to check if the amount of delivered
	 * data is sufficent to continue the sync, depending on the configuration of the origin.
	 *
	 * Throw a ParseDataFailedException to abort the sync if your data cannot be parsed.
	 *
	 * @throws ParseDataFailedException
	 * @return int
	 */
	public function parseData();

	/**
	 * Build the hub DTO objects from the parsed data.
	 * An instance of such objects MUST be obtained over the DTOObjectFactory. The factory
	 * is available via $this->factory().
	 *
	 * Example for an origin syncing users:
	 *
	 * $user = $this->factory()->user($data->extId);
	 * $user->setFirstname($data->firstname)
	 *  ->setLastname($data->lastname)
	 *  ->setGender(UserDTO::GENDER_FEMALE);
	 *
	 * Throw a BuildObjectsFailedException to abort the sync at this stage.
	 *
	 * @throws BuildObjectsFailedException
	 * @return IObjectDTO[]
	 */
	public function buildHubDTOs();


	// HOOKS
	// ------------------------------------------------------------------------------------------------------------

	/**
	 * Called if any exception occurs during processing the ILIAS objects. This hook can be used to influence the
	 * further processing of the current origin sync or the global sync:
	 *
	 * - Throw an AbortOriginSyncException to stop the current sync of this origin.
	 *   Any other following origins in the processing chain are still getting executed normally.
	 * - Throw an AbortSyncException to stop the global sync. The sync of any other following origins in the
	 *   processing chain is NOT getting executed.
	 *
	 * Note that if you do not throw any of the exceptions above, the sync will continue.
	 *
	 * @param \Exception $e
	 */
	public function handleException(\Exception $e);

	/**
	 * @param IObject $object
	 */
	public function beforeCreateILIASObject(IObject $object);

	/**
	 * @param IObject $object
	 */
	public function afterCreateILIASObject(IObject $object);

	/**
	 * @param IObject $object
	 */
	public function beforeUpdateILIASObject(IObject $object);

	/**
	 * @param IObject $object
	 */
	public function afterUpdateILIASObject(IObject $object);

	/**
	 * @param IObject $object
	 */
	public function beforeDeleteILIASObject(IObject $object);

	/**
	 * @param IObject $object
	 */
	public function afterDeleteILIASObject(IObject $object);

	/**
	 * Executed before the synchronization of the origin is executed.
	 */
	public function beforeSync();

	/**
	 * Executed after the synchronization of the origin has been executed.
	 */
	public function afterSync();


//	/**
//	 * Get the available periods
//	 *
//	 * @return array
//	 */
//	public function getAvailablePeriods();
//	/**
//	 * Get access to the config data of the origin
//	 *
//	 * @return OriginConfigInterface
//	 */
//	public function conf();
//
//	/**
//	 * Get access to the properties of the origin
//	 *
//	 * @return OriginProperties
//	 */
//	public function props();
//
//	/**
//	 * Get access to the custom properties of the origin
//	 *
//	 * @return OriginProperties
//	 */
//	public function customProps();
//
//	/**
//	 * Get access to the hub object factory
//	 *
//	 * @return Factory
//	 */
//	public function factory();
}