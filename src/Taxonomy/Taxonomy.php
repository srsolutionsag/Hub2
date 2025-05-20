<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Taxonomy;

use ilHub2Plugin;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class Taxonomy
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Taxonomy implements ITaxonomy
{
    protected string $title;
    protected int $mode;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var INode[]
     */
    protected $nodes = [];
    /**
     * @var string
     */
    protected $description = "";

    /**
     * Taxonomy constructor
     */
    public function __construct(string $title, int $mode)
    {
        $this->title = $title;
        $this->mode = $mode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMode(): int
    {
        return $this->mode;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getNodeTitlesAsArray(): array
    {
        $titles = [];
        foreach ($this->nodes as $node) {
            $titles[] = $node->getTitle();
        }

        return $titles;
    }

    public function attach(INode $node): ITaxonomy
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return ""; // Is this needed?
    }
}
