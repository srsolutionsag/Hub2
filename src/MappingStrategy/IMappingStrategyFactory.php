<?php

namespace SRAG\Plugins\Hub2\MappingStrategy;

/**
 * Interface IMappingStrategyFactory
 *
 * @package SRAG\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategyFactory {

	/**
	 * @return IMappingStrategy
	 */
	public function byEmail(): IMappingStrategy;


	/**
	 * @return IMappingStrategy
	 */
	public function byLogin(): IMappingStrategy;

	/**
	 * @return IMappingStrategy
	 */
	public function byExternalAccount(): IMappingStrategy;

	/**
	 * @return IMappingStrategy
	 */
	public function byTitle(): IMappingStrategy;


	/**
	 * @return IMappingStrategy
	 */
	public function none(): IMappingStrategy;
}
