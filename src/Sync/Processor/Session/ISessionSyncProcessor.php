<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Session;

use SRAG\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface ISessionSyncProcessor
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor {

}