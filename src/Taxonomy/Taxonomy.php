<?php

namespace SRAG\Plugins\Hub2\Taxonomy;

/**
 * Class Taxonomy
 *
 * @package SRAG\Plugins\Hub2\Taxonomy
 */
class Taxonomy implements ITaxonomy {

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
	 * @param $identifier
	 */
	public function __construct($identifier) {
		$this->identifier = $identifier;
	}


	/**
	 * @inheritDoc
	 */
	public function setValue($value): ITaxonomy {
		$this->value = $value;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function setIdentifier(int $identifier): ITaxonomy {
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
