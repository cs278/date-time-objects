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
    private $date;
    private $time;
    private $tz;
    private $offset;

    public function __construct(Date $date, Time $time, \DateTimeZone $tz = null)
    {
        $this->date = $date;
        $this->time = $time;
        $this->tz = $tz ?: new \DateTimeZone(date_default_timezone_get());
        $this->offset = TimeOffset::createFromDateTime($this->toDateTime());
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

    public function getOffset()
    {
        return $this->offset;
    }
}
