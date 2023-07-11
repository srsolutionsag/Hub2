<?php

namespace srag\Plugins\Hub2\Taxonomy\Node;

/**
 * Interface INode
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface INode
{
    public function setTitle(string $title);

    public function getTitle() : string;

    /**
     * @return INode[]
     */
    public function getNodes() : array;

    /**
     * @return string[]
     */
    public function getNodeTitlesAsArray() : array;

    public function attach(INode $node) : INode;
}
