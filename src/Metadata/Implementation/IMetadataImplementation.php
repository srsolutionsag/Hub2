<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Interface IMetadataImplementation
 *
 * @package srag\Plugins\Hub2\Metadata\Implementation
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
