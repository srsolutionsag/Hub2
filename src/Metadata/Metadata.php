<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class Metadata
 *
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Metadata implements IMetadata {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var int
	 */
	protected $identifier = 0;
	/**
	 * @var mixed
	 */
	protected $value;


	/**
	 * Metadata constructor
	 *
	 * @param int $identifier
	 */
	public function __construct($identifier, $record_id = 1) {
		$this->identifier = $identifier;
		$this->record_id = $record_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setValue($value): IMetadata {
		$this->value = $value;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function setIdentifier(int $identifier): IMetadata {
		$this->identifier = $identifier;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getValue() {
		return $this->value;
	}


	/**
	 * @inheritdoc
	 */
	public function getIdentifier() {
		return $this->identifier;
	}


	/**
	 * @return int
	 */
	public function getRecordId() {
		return $this->record_id;
	}


	/**
	 * @inheritdoc
	 */
	public function __toString(): string {
		$json_encode = json_encode([ $this->getIdentifier() => $this->getValue() ]);

		return $json_encode;
	}
}
