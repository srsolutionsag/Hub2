<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilADTDate;
use ilADTDateTime;
use ilADTExternalLink;
use ilADTInternalLink;
use ilADTText;
use ilAdvancedMDValues;
use ilDateTime;

/**
 * Class CustomMetadata
 *
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CustomMetadata extends AbstractImplementation implements IMetadataImplementation
{

    /**
     * @inheritdoc
     */
    public function write()
    {
        $id = $this->getMetadata()->getIdentifier();

        $ilAdvancedMDValues = new ilAdvancedMDValues($this->getMetadata()->getRecordId(), $this->getIliasId(), null, "-");

        $ilAdvancedMDValues->read();
        $ilADTGroup = $ilAdvancedMDValues->getADTGroup();
        $value = $this->getMetadata()->getValue();
        $ilADT = $ilADTGroup->getElement($id);

        switch (true) {
            case ($ilADT instanceof ilADTText):
                $ilADT->setText($value);
                break;
            case ($ilADT instanceof ilADTDate):
            case ($ilADT instanceof ilADTDateTime):
                $ilADT->setDate(new ilDateTime(strtotime($value), IL_CAL_UNIX));
                break;
            case ($ilADT instanceof ilADTExternalLink):
                $ilADT->setUrl($value['url']);
                $ilADT->setTitle($value['title']);
                break;
            case ($ilADT instanceof ilADTInternalLink):
                $ilADT->setTargetRefId($value);
                break;
        }

        $ilAdvancedMDValues->write();
    }
}
