<?php

namespace srag\Plugins\Hub2\Object\Category;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;

/**
 * Interface ICategoryDTO
 *
 * @package srag\Plugins\Hub2\Object\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategoryDTO extends IDataTransferObject, ITaxonomyAndMetadataAwareDataTransferObject, IMappingStrategyAwareDataTransferObject, IDidacticTemplateAwareDataTransferObject
{

    const ORDER_TYPE_TITLE = 0;
    const ORDER_TYPE_MANUAL = 1;
    const ORDER_TYPE_ACTIVATION = 2;
    const ORDER_TYPE_INHERIT = 3;
    const ORDER_TYPE_CREATION = 4;
    const PARENT_ID_TYPE_REF_ID = 1;
    const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
