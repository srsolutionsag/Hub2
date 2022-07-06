<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyFactory;
use srag\Plugins\Hub2\Metadata\IMetadataFactory;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Taxonomy\ITaxonomyFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class AbstractOriginGeneratorImplementation
 * Any implementation of a origin MUST extend this class.
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractOriginGeneratorImplementation extends AbstractOriginBaseImplementation implements IOriginGeneratorImplementation
{

}
