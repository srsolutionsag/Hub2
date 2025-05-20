<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Taxonomy\Node;

/**
 * Interface INode
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface INode
{
    public function setTitle(string $title);

    public function getTitle(): string;

    /**
     * @return INode[]
     */
    public function getNodes(): array;

    /**
     * @return string[]
     */
    public function getNodeTitlesAsArray(): array;

    public function attach(INode $node): INode;
}
