<?php

namespace srag\Plugins\Hub2\Sync\Processor\Group;

use srag\Plugins\Hub2\Sync\Processor\IDidacticTemplateSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface IGroupSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor,
                                      IDidacticTemplateSyncProcessor
{
}
