<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadata
 *
 * @package SRAG\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata {

	/**
	 * @param string $value
	 *
	 * @return IMetadata
	 */
	public function setValue($value): IMetadata;


	/**
	 * @param int $identifier
	 *
	 * @return IMetadata
	 */
	public function setIdentifier(int $identifier): IMetadata;


	/**
	 * @return mixed
	 */
	public function getValue();


	/**
	 * @return mixed
	 */
	public function getIdentifier();


	/**
	 * @return string
	 */
	public function __toString(): string;
}
