<?php namespace SRAG\Plugins\Hub2\MappingStrategy;

/**
 * Class Factory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Factory {

	/**
	 * @return IMappingStrategy
	 */
	public function byEmail(): IMappingStrategy {
		return new ByEmail();
	}


	/**
	 * @return IMappingStrategy
	 */
	public function byLogin(): IMappingStrategy {
		return new ByLogin();
	}


	/**
	 * @return IMappingStrategy
	 */
	public function byTitle(): IMappingStrategy {
		return new ByTitle();
	}


	/**
	 * @return IMappingStrategy
	 */
	public function none(): IMappingStrategy {
		return new None();
	}
}
