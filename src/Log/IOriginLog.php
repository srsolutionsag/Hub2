<?php

namespace srag\Plugins\Hub2\Log;

/**
 * Interface IOriginLog
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOriginLog extends ILog {

	/**
	 * @return int
	 */
	public function getOriginId(): int;


	/**
	 * @param int $origin_id
	 *
	 * @return self
	 */
	public function withOriginId(int $origin_id): self;
}
