<?php

namespace SRAG\Plugins\Hub2\Origin\Config;

/**
 * Interface IOrgUnitOriginConfig
 *
 * @package SRAG\Plugins\Hub2\Origin\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrgUnitOriginConfig extends IOriginConfig {

	/**
	 * @var string
	 */
	const REF_ID_IF_NO_PARENT_ID = "ref_id_if_no_parent_id";


	/**
	 * @return int
	 */
	public function getRefIdIfNoParentId(): int;
}
