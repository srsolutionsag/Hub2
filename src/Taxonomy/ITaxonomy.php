<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

/**
 * Interface ITaxonomy
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomy {

	/**
	 * @param $value
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function setValue($value): ITaxonomy;


	/**
	 * @param int $identifier
	 *
	 * @return \SRAG\Plugins\Hub2\Taxonomy\ITaxonomy
	 */
	public function setIdentifier(int $identifier): ITaxonomy;


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
