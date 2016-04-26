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

final class TimeOffset
{
    // @todo Allow comparisons.

    /** @var int */
    private $hour;

    /** @var int */
    private $minute;

    public function __construct($hour = 0, $minute = 0)
    {
        $this->hour = $hour;
        $this->minute = $minute;
    }

    public static function createFromDateTime($dt)
    {
        if (interface_exists('DateTimeInterface')) {
            Assert::isInstanceOf($dt, 'DateTimeInterface');
        } else {
            Assert::isInstanceOf($dt, 'DateTime');
        }

        $offset = $dt->getOffset();
        $hour = (int) floor($offset / 3600);
        $offset -= $hour * 3600;
        $minute = (int) floor($offset / 60);

        return new self($hour, $minute);
    }

    public function getHour()
    {
        return $this->hour;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function __toString()
    {
        return sprintf('%%+02d:%02u', $this->year, $this->month, $this->day);
    }
}
