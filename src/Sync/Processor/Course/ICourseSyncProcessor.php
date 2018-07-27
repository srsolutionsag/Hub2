<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\Course;

use SRAG\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use SRAG\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface ICourseSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\Course
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor {

}
