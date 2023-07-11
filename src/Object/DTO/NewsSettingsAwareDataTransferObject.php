<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\NewsSettings;

/**
 * Class TaxonomyAwareDataTransferObject
 *
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait NewsSettingsAwareDataTransferObject
{
    /**
     * @var NewsSettings|null
     */
    protected $newsSettings;

    public function getNewsSettings() : ?NewsSettings
    {
        return $this->newsSettings;
    }

    public function setNewsSettings(?NewsSettings $newsSettings) : INewsSettingsAwareDataTransferObject
    {
        $this->newsSettings = $newsSettings;
        return $this;
    }
}
