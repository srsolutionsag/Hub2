<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

use SRAG\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataImplementation
 *
 * @package SRAG\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementation {

	/**
	 * Reads the Value from the ILIAS representative (UDF od Custom MD)
	 *
	 * @return void
	 */
	public function read();


	/**
	 * Writes the Value in the ILIAS representative (UDF od Custom MD)
	 *
	 * @return void
	 */
	public function write();


	/**
	 * @return IMetadata
	 */
	public function getMetadata(): IMetadata;


	/**
	 * @return int
	 */
	public function getIliasId(): int;
}
