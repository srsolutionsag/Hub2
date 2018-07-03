<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

use SRAG\Plugins\Hub2\Sync\IOriginSync;

/**
 * Interface IOriginSyncSummary
 *
 * @package SRAG\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummary {

	/**
	 * @return string The Output of all IOriginSyncs
	 */
	public function getOutputAsString();


	/**
	 * @param IOriginSync $originSync add another already ran IOriginSync
	 */
	public function addOriginSync(IOriginSync $originSync);


	/**
	 * @param IOriginSync $originSync
	 *
	 * @return string
	 */
	public function getSummaryOfOrigin(IOriginSync $originSync);


	/**
	 * @return void
	 */
	public function sendNotifications();
}
