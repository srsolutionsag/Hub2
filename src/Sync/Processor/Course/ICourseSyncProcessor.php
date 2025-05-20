<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use srag\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IDidacticTemplateSyncProcessor;

/**
 * Interface ICourseSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor,
    IDidacticTemplateSyncProcessor
{
}
