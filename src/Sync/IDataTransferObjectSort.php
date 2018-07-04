<?php

namespace SRAG\Plugins\Hub2\Sync;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IDataTransferObjectSort
 *
 * @package SRAG\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDataTransferObjectSort {

	/**
	 * @var int
	 */
	const MAX_LEVEL = 100;


	/**
	 * @return IDataTransferObject
	 */
	public function getDtoObject(): IDataTransferObject;


	/**
	 * @return int
	 */
	public function getLevel(): int;


	/**
	 * @param int $level
	 */
	public function setLevel(int $level);
}
