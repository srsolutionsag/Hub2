<?php

namespace srag\Plugins\Hub2\Taxonomy;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class Taxonomy
 *
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Taxonomy implements ITaxonomy {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var INode[]
	 */
	protected $nodes = [];
	/**
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var int
	 */
	protected $mode;
	/**
	 * @var string
	 */
	protected $description = "";


	/**
	 * Taxonomy constructor
	 *
	 * @param string $title
	 * @param int    $mode
	 */
	public function __construct(string $title, int $mode) {
		$this->title = $title;
		$this->mode = $mode;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @inheritDoc
	 */
	public function getMode(): int {
		return $this->mode;
	}


	/**
	 * @inheritDoc
	 */
	public function getNodes(): array {
		return $this->nodes;
	}


	/**
	 * @inheritDoc
	 */
	public function getNodeTitlesAsArray(): array {
		$titles = [];
		foreach ($this->nodes as $node) {
			$titles[] = $node->getTitle();
		}

		return $titles;
	}


	/**
	 * @inheritDoc
	 */
	public function attach(INode $node): ITaxonomy {
		$this->nodes[] = $node;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 *
	 * @return Taxonomy
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function __toString(): string {
		return ""; // Is this needed?
	}
}
