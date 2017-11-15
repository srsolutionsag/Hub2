<?php

namespace SRAG\Plugins\Hub2\Metadata;

/**
 * Interface IMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata {

	/**
	 * @param $value
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function setValue($value): IMetadata;


	/**
	 * @param int $identifier
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
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
