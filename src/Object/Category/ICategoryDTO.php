<?php

namespace srag\Plugins\Hub2\Object\Category;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;

/**
 * Interface ICategoryDTO
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
    const ORDER_DIRECTION_ASC = 0;
    const ORDER_DIRECTION_DESC = 1;
    const ORDER_NEW_ITEMS_POSITION_TOP = 0;
    const ORDER_NEW_ITEMS_POSITION_BOTTOM = 1;
    const ORDER_NEW_ITEMS_BY_TITLE = 0;
    const ORDER_NEW_ITEMS_BY_CREATION = 1;
    const ORDER_NEW_ITEMS_BY_ACTIVATION = 2;
    const PARENT_ID_TYPE_REF_ID = 1;
    const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
