<?php

namespace srag\Plugins\Hub2\Taxonomy;

use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Interface ITaxonomy
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomy
{
    public const MODE_SELECT = 1;
    public const MODE_CREATE = 2;

    public function getTitle() : string;

    /**
     * @return int ITaxonomy::MODE_SELECT or ITaxonomy::MODE_CREATE
     */
    public function getMode() : int;

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return INode
     */
    public function setDescription($description);

    /**
     * @return INode[]
     */
    public function getNodes() : array;

    /**
     * @return string[]
     */
    public function getNodeTitlesAsArray() : array;

    public function attach(INode $node) : ITaxonomy;

    public function __toString() : string;
}
