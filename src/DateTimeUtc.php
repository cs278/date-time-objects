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

use Webmozart\Assert\Assert;

final class DateTimeUtc implements DateInterface, TimeInterface
{
    private $date;
    private $time;
    private $dt;

    public function __construct(DateInterface $date, TimeInterface $time)
    {
        $this->date = $date;
        $this->time = $time;

        // $this->dt = new \DateTime((string) $this);
    }

    public static function createFromDateTime($dt)
    {
        if (interface_exists('DateTimeInterface')) {
            Assert::isInstanceOf($dt, 'DateTimeInterface');
        } else {
            Assert::isInstanceOf($dt, 'DateTime');
        }

        // Ensure timezone of input is UTC.
        $dt = $dt->setTimezone(new \DateTimeZone('UTC'));

        return new self(
            Date::createFromDateTime($dt),
            Time::createFromDateTime($dt)
        );
    }

    public static function createFromString($iso8601str)
    {
        list($date, $time) = explode('T', $iso8601str, 2);

        return new self(
            Date::createFromString($date),
            Time::createFromString($time)
        );
    }

    public function __toString()
    {
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

    public function getWeekDay()
    {
        return $this->date->getWeekDay();
    }

    public function getDayOfYear()
    {
        return $this->date->getDayOfYear();
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
