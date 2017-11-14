<?php namespace SRAG\Plugins\Hub2\Origin;

require_once('./Customizing/global/plugins/Services/Cron/CronHook/Hub2/origins/courseMembership/demoCourseMember.php');

use SRAG\Plugins\Hub2\Exception\BuildObjectsFailedException;
use SRAG\Plugins\Hub2\Exception\ConnectionFailedException;
use SRAG\Plugins\Hub2\Exception\ParseDataFailedException;
use SRAG\Plugins\Hub2\Object\HookObject;
use SRAG\Plugins\Hub2\Object\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\Session\SessionDTO;

/**
 * Class demoSession
 *
 * @package SRAG\Plugins\Hub2\Origin
 */
class demoSession extends AbstractOriginImplementation {

	/**
	 * Connect to the service providing the sync data.
	 * Throw a ConnectionFailedException to abort the sync if a connection is not possible.
	 *
	 * @throws ConnectionFailedException
	 * @return bool
	 */
	public function connect() { return true; }


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
	public function parseData() {
		$this->log()->write("HELLO");
		for ($x = 1; $x <= 14; $x ++) {
			$rand = rand();
			$sessionDTO = $this->factory()
			                   ->session($x)
			                   ->setParentId("0001")// see demoCourse
			                   ->setParentIdType(SessionDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID)
			                   ->setTitle("Title {$rand}")
			                   ->setDescription("Description {$rand}")
			                   ->setLocation("Location {$rand}")
			                   ->setDetails("Details {$rand}")
			                   ->setName("Name {$rand}")
			                   ->setEmail("Email {$rand}")
			                   ->setPhone("Phone {$rand}")
			                   ->setFullDay(false)
			                   ->setMembers([ 6, 294 ])
			                   ->setStart(time() + ($x * 600))
			                   ->setEnd(time() + ($x * 600) + 3600);
			$this->data[] = $sessionDTO;
			$this->log()->write("Start:" . date(DATE_ATOM, $sessionDTO->getStart()));
		}

		return count($this->data);
	}


	/**
	 * Build the hub DTO objects from the parsed data.
	 * An instance of such objects MUST be obtained over the DTOObjectFactory. The factory
	 * is available via $this->factory().
	 *
	 * Example for an origin syncing users:
	 *
	 * $user = $this->factory()->user($data->extId) {   }
	 * $user->setFirstname($data->firstname)
	 *  ->setLastname($data->lastname)
	 *  ->setGender(UserDTO::GENDER_FEMALE) {   }
	 *
	 * Throw a BuildObjectsFailedException to abort the sync at this stage.
	 *
	 * @throws BuildObjectsFailedException
	 * @return IDataTransferObject[]
	 */
	public function buildObjects() {
		return $this->data;
	}


	// HOOKS
	// ------------------------------------------------------------------------------------------------------------

	/**
	 * Called if any exception occurs during processing the ILIAS objects. This hook can be used to
	 * influence the further processing of the current origin sync or the global sync:
	 *
	 * - Throw an AbortOriginSyncException to stop the current sync of this origin.
	 *   Any other following origins in the processing chain are still getting executed normally.
	 * - Throw an AbortOriginSyncOfCurrentTypeException to abort the current sync of the origin AND
	 *   all also skip following syncs from origins of the same object type, e.g. User, Course etc.
	 * - Throw an AbortSyncException to stop the global sync. The sync of any other following
	 * origins in the processing chain is NOT getting executed.
	 *
	 * Note that if you do not throw any of the exceptions above, the sync will continue.
	 *
	 * @param \Exception $e
	 */
	public function handleException(\Exception $e) { }


	/**
	 * @param HookObject $object
	 */
	public function beforeCreateILIASObject(HookObject $object) { }


	/**
	 * @param HookObject $object
	 */
	public function afterCreateILIASObject(HookObject $object) { }


	/**
	 * @param HookObject $object
	 */
	public function beforeUpdateILIASObject(HookObject $object) { }


	/**
	 * @param HookObject $object
	 */
	public function afterUpdateILIASObject(HookObject $object) { }


	/**
	 * @param HookObject $object
	 */
	public function beforeDeleteILIASObject(HookObject $object) { }


	/**
	 * @param HookObject $object
	 */
	public function afterDeleteILIASObject(HookObject $object) { }


	/**
	 * Executed before the synchronization of the origin is executed.
	 */
	public function beforeSync() { }


	/**
	 * Executed after the synchronization of the origin has been executed.
	 */
	public function afterSync() { }
}