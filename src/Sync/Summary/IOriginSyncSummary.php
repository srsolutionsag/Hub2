<?php

namespace SRAG\Plugins\Hub2\Sync\Summary;

use SRAG\Plugins\Hub2\Sync\IOriginSync;

/**
 * Interface IOriginSyncSummary
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummary {

	/**
	 * @return string The Output of all IOriginSyncs
	 */
	public function getOutputAsString();


	/**
	 * @param \SRAG\Plugins\Hub2\Sync\IOriginSync $originSync add another already ran IOriginSync
	 */
	public function addOriginSync(IOriginSync $originSync);


	/**
	 * @param \SRAG\Plugins\Hub2\Sync\IOriginSync $originSync
	 *
	 * @return string
	 */
	public function getSummaryOfOrigin(IOriginSync $originSync);
}