<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor\General;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\General\NewsSettings;
use srag\Plugins\Hub2\Object\DTO\ICalendarSettingsAwareDataTransferObject;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait CalendarSettingsSyncProcessor
{
    protected function handleCalendarSettings(IDataTransferObject $dto, \ilContainer $container): void
    {
        if (!$dto instanceof ICalendarSettingsAwareDataTransferObject) {
            return;
        }

        $calendar_settings = $dto->getCalendarSettings();
        if ($calendar_settings === null) {
            return;
        }
        $course_obj_id = $container->getId();

        $active = $calendar_settings->isCalendarActive() ? '1' : '0';
        $visible = $calendar_settings->isCalendarBlockActive() ? '1' : '0';

        if (\ilCalendarSettings::_getInstance()->isEnabled()) {
            \ilContainer::_writeContainerSetting(
                $course_obj_id,
                'cont_activation_calendar',
                $active
            );
            \ilContainer::_writeContainerSetting(
                $course_obj_id,
                'cont_show_calendar',
                ((bool) $active) ? $visible : ""
            );
        }
    }
}
