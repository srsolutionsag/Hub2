<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata {

	/**
	 * @return string Identifier of the User-Defined-Field or Advanced metadata
	 */
	public function getIdentifier(): string;


	/**
	 * @return string
	 */
	public function getValue(): string;
}
