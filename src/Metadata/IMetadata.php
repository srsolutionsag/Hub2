<?php

namespace SRAG\Plugins\Hub2\Metadata;

use SRAG\Plugins\Hub2\Object\IDataTransferObject;

/**
 * Interface IMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata {

	/**
	 * @param $value
	 *
	 * @return \SRAG\Plugins\Hub2\Object\IDataTransferObject
	 */
	public function setValue($value): IDataTransferObject;


	/**
	 * @param int $identifier
	 *
	 * @return \SRAG\Plugins\Hub2\Object\IDataTransferObject
	 */
	public function setIdentifier(int $identifier): IDataTransferObject;


	/**
	 * @return mixed
	 */
	public function getValue();


	/**
	 * @return mixed
	 */
	public function getIdentifier();
}
