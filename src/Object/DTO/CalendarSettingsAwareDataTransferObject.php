<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Object\General\NewsSettings;
use srag\Plugins\Hub2\Object\General\CalendarSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait CalendarSettingsAwareDataTransferObject
{
    protected ?CalendarSettings $calendarSettings = null;

    public function getCalendarSettings(): ?CalendarSettings
    {
        return $this->calendarSettings;
    }

    public function setCalendarSettings(?CalendarSettings $calendarSettings): ICalendarSettingsAwareDataTransferObject
    {
        $this->calendarSettings = $calendarSettings;
        return $this;
    }
}
