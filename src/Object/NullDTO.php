<?php namespace SRAG\Hub2\Object;

/**
 * Class NullDTO
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
 */
class NullDTO extends DataTransferObject {

	public function __construct() {
		parent::__construct(0);
	}
}