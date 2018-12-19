<?php

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;
use srag\DIC\DICTrait;

/**
 * Class Metadata
 *
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Metadata implements IMetadata {

	use DICTrait;
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
	 * Metadata constructor.
	 *
	 * @param int $identifier
	 */
	public function __construct($identifier) {
		$this->identifier = $identifier;
	}


	/**
	 * @inheritDoc
	 */
	public function setValue($value): IMetadata {
		$this->value = $value;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function setIdentifier(int $identifier): IMetadata {
		$this->identifier = $identifier;

		return $this;
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


	/**
	 * @inheritDoc
	 */
	public function __toString(): string {
		$json_encode = json_encode([ $this->getIdentifier() => $this->getValue() ]);

		return $json_encode;
	}
}
