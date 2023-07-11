<?php

namespace srag\Plugins\Hub2\Shortlink;

use ilLink;
use ilObject2;
use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class AbstractRepositoryLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractRepositoryLink extends AbstractBaseLink implements IObjectLink
{
    /**
     * @var \ilAccessHandler
     */
    private $access;
    /**
     * @var \ilTree
     */
    private $tree;

    public function __construct(ARObject $object)
    {
        global $DIC;
        $this->access = $DIC->access();
        $this->tree = $DIC['tree'];
        parent::__construct($object);
    }

    /**
     * @inheritdoc
     */
    public function doesObjectExist() : bool
    {
        if ($this->getILIASId() === 0) {
            return false;
        }

        return ilObject2::_exists($this->getILIASId(), true);
    }

    /**
     * @inheritdoc
     */
    public function isAccessGranted() : bool
    {
        return (bool) $this->access->checkAccess("read", '', $this->getILIASId());
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedInternalLink() : string
    {
        if ($this->isAccessGranted()) {
            return $this->getAccessGrantedExternalLink();
        } else {
            return $this->getAccessDeniedLink();
        }
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedExternalLink() : string
    {
        $ref_id = $this->getILIASId();

        return $this->generateLink($ref_id);
    }

    /**
     * @inheritdoc
     */
    public function getAccessDeniedLink() : string
    {
        $ref_id = $this->findReadableParent();
        if ($ref_id === 0) {
            return "index.php";
        }

        return $this->generateLink($ref_id);
    }

    /**
     * @return int
     */
    protected function getILIASId()
    {
        return $this->object->getILIASId();
    }

    protected function findReadableParent() : int
    {
        $ref_id = $this->getILIASId();

        while ($ref_id && !$this->access->checkAccess('read', '', $ref_id) && $ref_id != 1) {
            $ref_id = (int) $this->tree->getParentId($ref_id);
        }

        if ((!$ref_id || $ref_id === 1) && !$this->access->checkAccess('read', '', $ref_id)) {
            return 0;
        }

        return $ref_id;
    }

    /**
     * @param int $ref_id
     * @return mixed|string
     */
    private function generateLink($ref_id)
    {
        $link = ilLink::_getLink($ref_id);

        return str_replace(ILIAS_HTTP_PATH, "", $link);
    }
}
