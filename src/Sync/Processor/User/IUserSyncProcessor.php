<?php

namespace srag\Plugins\Hub2\Sync\Processor\User;

use srag\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

/**
 * Interface IUserSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IUserSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor
{
}
