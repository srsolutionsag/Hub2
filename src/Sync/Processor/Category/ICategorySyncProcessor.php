<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Category;

use SRAG\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface ICategorySyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Category
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategorySyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor {

}
