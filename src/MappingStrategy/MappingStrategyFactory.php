<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class MappingStrategyFactory
 *
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MappingStrategyFactory implements IMappingStrategyFactory {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


	/**
	 * @inheritdoc
	 */
	public function byEmail(): IMappingStrategy {
		return new ByEmail();
	}


	/**
	 * @inheritdoc
	 */
	public function byLogin(): IMappingStrategy {
		return new ByLogin();
	}

	/**
	 * @inheritdoc
	 */
	public function byExternalAccount(): IMappingStrategy {
		return new ByExternalAccount();
	}

	/**
	 * @inheritdoc
	 */
	public function byTitle(): IMappingStrategy {
		return new ByTitle();
	}


	/**
	 * @inheritdoc
	 */
	public function none(): IMappingStrategy {
		return new None();
	}
}
