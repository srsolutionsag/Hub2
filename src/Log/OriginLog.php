<?php

namespace srag\Plugins\Hub2\Log;

/**
 * Class OriginLog
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OriginLog extends Log implements IOriginLog {

	const TABLE_NAME = "sr_hub2_origin_log";
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   false
	 */
	protected $origin_id = NULL;


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "origin_id":
				return intval($field_value);

			default:
				return parent::wakeUp($field_name, $field_value);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getOriginId(): int {
		return $this->origin_id;
	}


	/**
	 * @inheritdoc
	 */
	public function withOriginId(int $origin_id): IOriginLog {
		$this->origin_id = $origin_id;

		return $this;
	}
}
