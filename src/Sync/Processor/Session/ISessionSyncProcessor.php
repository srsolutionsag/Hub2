<?php

namespace srag\Plugins\Hub2\Sync\Processor\Session;

use srag\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface ISessionSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor
{
}
