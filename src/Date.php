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
    private $dayOfWeek;

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
            throw new \InvalidArgumentException;
        }

        if ($year >= 0 && $year <= 1582) {
            trigger_error('Date outside gregorian calendar range.', E_USER_NOTICE);
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->dayOfWeek = (int) $this->toDateTime()->format('N');
        $this->dayOfYear = ((int) $this->toDateTime()->format('z')) + 1;
    }

    public static function createFromDateTime(\DateTimeInterface $dt)
    {
        return self::createFromString($dt->format('Y-m-d'));
    }

    public static function createFromString($iso8601str)
    {
        Assert::regex($iso8601str, '{^[0-9]{1,4}-(?:0?[1-9]|1[0-2])-(?:0?[1-9]|[12][0-9]|3[01])$}');

        // @todo Handle garbage
        list($year, $month, $day) = explode('-', $iso8601str, 3);

        return new self((int) $year, (int) $month, (int) $day);
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    public function getDayOfYear()
    {
        return $this->dayOfYear;
    }

    private function toDateTime()
    {
        // @todo Probably a bad idea, a day is actually a date period.
        return new \DateTimeImmutable((string) $this, new \DateTimeZone('UTC'));
    }

    public function __toString()
    {
        return sprintf('%04u-%02u-%02u', $this->year, $this->month, $this->day);
    }
}
