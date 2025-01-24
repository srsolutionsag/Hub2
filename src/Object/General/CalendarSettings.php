<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\General;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class CalendarSettings extends BaseDependentSetting implements IDependentSettings
{
    public const F_ACTIVATE_CALENDAR = 'activate_calendar';
    public const F_ACTIVATE_CALENDAR_BLOCK = 'activate_calendar_block';

    protected bool $activate_calendar = true;
    protected bool $activate_calendar_block = true;

    public function __construct(
        bool $activate_calendar = true,
        bool $activate_calendar_block = true
    ) {
        $this->activateCalendar($activate_calendar);
        $this->activateCalendarBlock($activate_calendar_block);
    }

    protected function set(string $key, $value): BaseDependentSetting
    {
        $this->{$key} = $value;
        return parent::set($key, $value);
    }

    public function isCalendarActive(): bool
    {
        return $this->activate_calendar;
    }

    public function activateCalendar(bool $activate_calendar): self
    {
        return $this->set(self::F_ACTIVATE_CALENDAR, $activate_calendar);
    }

    public function isCalendarBlockActive(): bool
    {
        return $this->activate_calendar_block;
    }

    public function activateCalendarBlock(bool $activate_calendar_block): self
    {
        return $this->set(self::F_ACTIVATE_CALENDAR_BLOCK, $activate_calendar_block);
    }

    public function __toArray(): array
    {
        return $this->data;
    }

    public function __fromArray(array $data): void
    {
        $this->data = $data;
    }

    public function serialize(): string
    {
        return serialize($this->__toArray());
    }

    public function unserialize($data): void
    {
        $this->__fromArray(unserialize($data));
    }

    public function __toString(): string
    {
        return $this->serialize();
    }

    public function __fromString(string $data): void
    {
        $this->unserialize($data);
    }

    public function offsetExists($offset)
    {
        return $this->data[$offset] ?? false;
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}
