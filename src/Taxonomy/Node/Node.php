<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Taxonomy\Node;

use ilHub2Plugin;

/**
 * Class Node
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Node implements INode
{
    protected string $title;
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var INode[]
     */
    protected $nodes = [];

    /**
     * Node constructor
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
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

    public function attach(INode $node): INode
    {
        $this->nodes[] = $node;

        return $this;
    }
}
