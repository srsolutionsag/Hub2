<?php namespace SRAG\Plugins\Hub2\Object\DTO;

/**
 * Class NullDTO
 *
* @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Plugins\Hub2\Object
            */
class NullDTO extends DataTransferObject {

	public function __construct() {
		parent::__construct(0);
	}
}