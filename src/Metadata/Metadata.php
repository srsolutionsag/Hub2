<?php

namespace SRAG\Plugins\Hub2\Metadata;

use SRAG\Plugins\Hub2\Object\DataTransferObject;
use SRAG\Plugins\Hub2\Object\IDataTransferObject;

/**
 * Class Metadata
 *
 * @package SRAG\Plugins\Hub2\Metadata
 */
class Metadata implements IMetadata {

	/**
	 * @var int
	 */
	protected $identifier = 0;
	/**
	 * @var mixed
	 */
	protected $value;
	/**
	 * @var \SRAG\Plugins\Hub2\Object\DataTransferObject
	 */
	protected $dto;


	/**
	 * Metadata constructor.
	 *
	 * @param int                                          $identifier
	 * @param \SRAG\Plugins\Hub2\Object\DataTransferObject $dto
	 */
	public function __construct($identifier, DataTransferObject $dto) {
		$this->identifier = $identifier;
		$this->dto = $dto;
	}


	/**
	 * @inheritDoc
	 */
	public function setValue($value): IDataTransferObject {
		$this->value = $value;

		return $this->dto;
	}


	/**
	 * @inheritDoc
	 */
	public function setIdentifier(int $identifier): IDataTransferObject {
		$this->identifier = $identifier;

		return $this->dto;
	}


	/**
	 * @inheritDoc
	 */
	public function getValue() {
		return $this->value;
	}


	/**
	 * @inheritDoc
	 */
	public function getIdentifier() {
		return $this->identifier;
	}
}
