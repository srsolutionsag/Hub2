<?php

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\NewsSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface INewsSettingsAwareDataTransferObject
{
    public function getNewsSettings(): ?NewsSettings;

    public function setNewsSettings(?NewsSettings $newsSettings): INewsSettingsAwareDataTransferObject;
}
