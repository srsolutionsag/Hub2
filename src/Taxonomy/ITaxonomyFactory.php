<?php

namespace srag\Plugins\Hub2\Taxonomy;

use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class ITaxonomyFactory
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyFactory
{
    public function select(string $title) : ITaxonomy;

    public function create(string $title) : ITaxonomy;

    public function node(string $node_title) : INode;
}
