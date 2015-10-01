<?php

/*
 * This file is part of the Date Time Objects package.
 *
 * (c) Chris Smith <chris@cs278.org>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Cs278\DateTimeObjects;

final class DateTime
{
    public function __construct(Date $date, Time $time, TimeZone $tz = null)
    {
        $this->date = $date;
        $this->time = $time;
    }

    public function createFromString($iso8601str)
    {
        list($date, $time) = explode('T', $iso8601str, 2);

        return new self(
            Date::createFromString($date),
            Time::createFromString($time),
            null
        );
    }

    public function __toString()
    {
        // @todo time zone
        return sprintf('%sT%sZ', $this->date, $this->time);
    }

    public function getYear()
    {
        return $this->date->getYear();
    }

    public function getMonth()
    {
        return $this->date->getMonth();
    }

    public function getDay()
    {
        return $this->date->getDay();
    }

    public function getHour()
    {
        return $this->time->getHour();
    }

    public function getMinute()
    {
        return $this->time->getMinute();
    }

    public function getSecond()
    {
        return $this->time->getSecond();
    }

    public function getFraction()
    {
        return $this->time->getFraction();
    }
}
