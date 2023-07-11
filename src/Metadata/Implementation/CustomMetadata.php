<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilADTDate;
use ilADTDateTime;
use ilADTExternalLink;
use ilADTFloat;
use ilADTInteger;
use ilADTInternalLink;
use ilADTText;
use ilADTLocalizedText;
use ilAdvancedMDValues;
use ilDateTime;

/**
 * Class CustomMetadata
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CustomMetadata extends AbstractImplementation implements IMetadataImplementation
{
    /**
     * @inheritdoc
     */
    public function write() : void
    {
        $field_id = $this->getMetadata()->getIdentifier();
        $object_id = $this->getIliasId();

        $md_values = new ilAdvancedMDValues(
            $this->getMetadata()->getRecordId(),
            $object_id,
            0,
            "-"
        );

        $md_values->read();

        $adt_group = $md_values->getADTGroup();

        $value = $this->getMetadata()->getValue();
        $adt = $adt_group->getElement($field_id);

        switch (true) {
            case ($adt instanceof ilADTLocalizedText):
                $adt->setTranslation($this->getMetadata()->getLanguageCode(), $value);
                $adt->setText($value);
                break;
            case ($adt instanceof ilADTText):
                $adt->setText($value);
                break;
            case ($adt instanceof ilADTDate):
            case ($adt instanceof ilADTDateTime):
                $adt->setDate(new ilDateTime(strtotime($value), IL_CAL_UNIX));
                break;
            case ($adt instanceof ilADTExternalLink):
                $adt->setUrl($value['url']);
                $adt->setTitle($value['title']);
                break;
            case ($adt instanceof ilADTInternalLink):
                $adt->setTargetRefId($value);
                break;
            case ($adt instanceof ilADTInteger):
            case ($adt instanceof ilADTFloat):
                $adt->setNumber($value);
                break;
        }
        $md_values->write();
    }
}
