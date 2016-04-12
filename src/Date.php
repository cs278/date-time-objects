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

/**
 * Represents a date on the gregorian calendar.
 */
final class Date
{
    // Properties are used in comparisons, preserve the order of these.

    /** @var int */
    private $year;

    /** @var int */
    private $month;

    /** @var int */
    private $day;

    /** @var int */
    private $weekDay;

    /** @var int */
    private $dayOfYear;

    /**
     * Constructor.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     */
    public function __construct($year, $month, $day)
    {
        Assert::integer($year);
        Assert::integer($month);
        Assert::integer($day);

        Assert::range($year, 0, 9999);
        Assert::range($month, 1, 12);
        Assert::range($day, 1, 31);

        if (!checkdate($month, $day, $year)) {
            throw new \InvalidArgumentException(sprintf(
                'Supplied date, `%04d-%02d-%02d`, is invalid',
                $year,
                $month,
                $day
            ));
        }

        if ($year >= 0 && $year <= 1582) {
            trigger_error('Date outside gregorian calendar range.', E_USER_NOTICE);
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->weekDay = (int) $this->toDateTime()->format('N');
        $this->dayOfYear = ((int) $this->toDateTime()->format('z')) + 1;
    }

    /**
     * Create new instance from a native DateTime object.
     *
     * @param \DateTime|\DateTimeInterface $dt
     *
     * @return Date
     */
    public static function createFromDateTime($dt)
    {
        if (interface_exists('DateTimeInterface')) {
            Assert::isInstanceOf($dt, 'DateTimeInterface');
        } else {
            Assert::isInstanceOf($dt, 'DateTime');
        }

        return self::createFromString($dt->format('Y-m-d'));
    }

    /**
     * Create new instance from an ISO8601 date string.
     *
     * @param string $iso8601str ISO8601 like 2012-02-28
     *
     * @return Date
     */
    public static function createFromString($iso8601str)
    {
        Assert::regex($iso8601str, '{^[0-9]{1,4}-(?:0?[1-9]|1[0-2])-(?:0?[1-9]|[12][0-9]|3[01])$}');

        // @todo Handle garbage
        list($year, $month, $day) = explode('-', $iso8601str, 3);

        return new self((int) $year, (int) $month, (int) $day);
    }

    /**
     * Fetch the year.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Fetch the month.
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Fetch the day.
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Fetch the day of the week.
     *
     * @return int
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Fetch the ordinal position of the day in the year.
     *
     * @return int
     */
    public function getDayOfYear()
    {
        return $this->dayOfYear;
    }

    /**
     * Convert to ISO8601 calendar date string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%04u-%02u-%02u', $this->year, $this->month, $this->day);
    }

    private function toDateTime()
    {
        // @todo Probably a bad idea, a day is actually a date period.
        return new \DateTime((string) $this, new \DateTimeZone('UTC'));
    }
}
