<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\CalendarSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface ICalendarSettingsAwareDataTransferObject
{
    public function getCalendarSettings(): ?CalendarSettings;

    public function setCalendarSettings(?CalendarSettings $calendar_settings): ICalendarSettingsAwareDataTransferObject;
}
