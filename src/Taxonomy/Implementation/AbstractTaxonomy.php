<?php

namespace srag\Plugins\Hub2\Taxonomy\Implementation;

use ilHub2Plugin;
use ilObjTaxonomy;
use ilTaxonomyTree;
use srag\Plugins\Hub2\Taxonomy\ITaxonomy;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class AbstractTaxonomy
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractTaxonomy implements ITaxonomyImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    protected $tree_root_id;
    /**
     * @var ilTaxonomyTree
     */
    protected $tree;
    /**
     * @var array
     */
    protected $childs = [];
    /**
     * @var ilObjTaxonomy
     */
    protected $ilObjTaxonomy;
    /**
     * @var ITaxonomy
     */
    protected $taxonomy;
    /**
     * @var int
     */
    protected $ilias_parent_id;

    /**
     * Taxonomy constructor
     */
    public function __construct(ITaxonomy $taxonomy, int $ilias_parent_id)
    {
        global $DIC;
        $this->tree = $DIC['tree'];
        $this->taxonomy = $taxonomy;
        $this->ilias_parent_id = $ilias_parent_id;
    }

    protected function taxonomyExists() : bool
    {
        $childsByType = $this->tree->getChildsByType($this->getILIASParentId(), 'tax');
        if ($childsByType === []) {
            return false;
        }
        foreach ($childsByType as $value) {
            if ($value["title"] === $this->getTaxonomy()->getTitle()) {
                $this->ilObjTaxonomy = new ilObjTaxonomy($value["obj_id"]);

                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    protected function initTaxTree()
    {
        $this->tree = $this->ilObjTaxonomy->getTree();
        $this->tree_root_id = $this->tree->readRootId();
        $this->setChildrenByParentId($this->tree_root_id);
    }

    /**
     * @param int $parent_id
     */
    protected function setChildrenByParentId($parent_id)
    {
        foreach ($this->tree->getChildsByTypeFilter($parent_id, ["taxn"]) as $item) {
            $this->childs[$item['obj_id']] = $item['title'];
            $this->setChildrenByParentId($item['obj_id']);
        }
    }

    protected function nodeExists(INode $node) : bool
    {
        return in_array($node->getTitle(), $this->childs);
    }

    /**
     * @inheritdoc
     */
    abstract public function write();

    /**
     * @inheritdoc
     */
    public function getTaxonomy() : ITaxonomy
    {
        return $this->taxonomy;
    }

    /**
     * @inheritdoc
     */
    public function getILIASParentId() : int
    {
        return $this->ilias_parent_id;
    }
}
