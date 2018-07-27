<?php

namespace SRAG\Plugins\Hub2\Origin\Config;

/**
 * Interface ICourseOriginConfig
 *
 * @package SRAG\Plugins\Hub2\Origin\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseOriginConfig extends IOriginConfig {

	const REF_ID_NO_PARENT_ID_FOUND = 'ref_id_no_parent_id_found';


	/**
	 * Get the ILIAS ref-ID acting as parent, only if hub was not able to find
	 * the correct parent ref-ID. By default, the course will be created directly
	 * in the repository (refId = 1).
	 *
	 * @return int
	 */
	public function getParentRefIdIfNoParentIdFound();
}
