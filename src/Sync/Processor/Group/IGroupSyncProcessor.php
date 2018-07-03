<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Group;

use SRAG\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface IGroupSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor {

}
