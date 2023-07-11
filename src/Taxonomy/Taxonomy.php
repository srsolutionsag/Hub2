<?php

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
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
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
     */
    public function __construct(string $title, int $mode)
    {
        $this->title = $title;
        $this->mode = $mode;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getMode() : int
    {
        return $this->mode;
    }

    /**
     * @inheritdoc
     */
    public function getNodes() : array
    {
        return $this->nodes;
    }

    /**
     * @inheritdoc
     */
    public function getNodeTitlesAsArray() : array
    {
        $titles = [];
        foreach ($this->nodes as $node) {
            $titles[] = $node->getTitle();
        }

        return $titles;
    }

    /**
     * @inheritdoc
     */
    public function attach(INode $node) : ITaxonomy
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
     * @return Taxonomy
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        return ""; // Is this needed?
    }
}
