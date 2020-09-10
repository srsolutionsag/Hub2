<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilUserDefinedData;

/**
 * Class CustomMetadata
 *
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UDF extends AbstractImplementation implements IMetadataImplementation
{

    const PREFIX = 'f_';


    /**
     * @inheritdoc
     */
    public function write()
    {
        $user_id = $this->getIliasId();
        $ilUserDefinedData = new ilUserDefinedData($user_id);
        $value = $this->getMetadata()->getValue();
        $field_id = $this->getMetadata()->getIdentifier();
        $field_id = self::PREFIX . str_replace(self::PREFIX, '', (string) $field_id);
        $ilUserDefinedData->set($field_id, $value);
        $ilUserDefinedData->update();
    }
}
