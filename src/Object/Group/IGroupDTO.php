<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;

/**
 * Interface IGroupDTO
 *
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject, IMappingStrategyAwareDataTransferObject, IDidacticTemplateAwareDataTransferObject
{

    // View
    const VIEW_BY_TYPE = 5;
    // Registration
    const GRP_REGISTRATION_DEACTIVATED = -1;
    const GRP_REGISTRATION_DIRECT = 0;
    const GRP_REGISTRATION_REQUEST = 1;
    const GRP_REGISTRATION_PASSWORD = 2;
    // Type
    const GRP_REGISTRATION_LIMITED = 1;
    const GRP_REGISTRATION_UNLIMITED = 2;
    const GRP_TYPE_UNKNOWN = 0;
    const GRP_TYPE_CLOSED = 1;
    const GRP_TYPE_OPEN = 2;
    const GRP_TYPE_PUBLIC = 3;
    // Other
    const MAIL_ALLOWED_ALL = 1;
    const MAIL_ALLOWED_TUTORS = 2;
    const PARENT_ID_TYPE_REF_ID = 1;
    const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
