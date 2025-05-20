<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Exception;

use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyNodeNotFoundException
 * @package srag\Plugins\Hub2\Exception
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyNodeNotFoundException extends HubException
{
    protected INode $node;

    /**
     * TaxonomyNodeNotFoundException constructor
     */
    public function __construct(INode $node)
    {
        parent::__construct("ILIAS Taxonomy Node not found for: {$node->getTitle()}");
        $this->node = $node;
    }

    public function getNode(): INode
    {
        return $this->node;
    }
}
