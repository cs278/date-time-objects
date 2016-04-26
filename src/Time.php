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
 * Represents the time of day using 24 hour clock.
 */
final class Time implements TimeInterface
{
    // Properties are used in comparisons, preserve the order of these.

    /** @var int */
    private $hour;

    /** @var int */
    private $minute;

    /** @var int */
    private $second;

    /** @var int */
    private $fraction;

    /**
     * Constructor.
     *
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     * @param integer $fraction
     */
    public function __construct($hour = 0, $minute = 0, $second = 0, $fraction = 0)
    {
        Assert::integer($hour, 'Hour must be an integer, got: `%1$s`');
        Assert::integer($minute, 'Minute must be an integer, got: `%1$s`');
        Assert::integer($second, 'Second must be an integer, got: `%1$s`');
        Assert::integer($fraction, 'Fraction must be an integer, got: `%1$s`');

        Assert::range($hour, 0, 24, 'Hour must be between %2$u and %3$u, got: %1$d');

        if ($hour === 24) {
            Assert::eq($minute, 0, 'Minute must equal %2$u, got: %1$d');
            Assert::eq($second, 0, 'Second must equal %2$u, got: %1$d');
            Assert::eq($fraction, 0, 'Fraction must equal %2$u, got: %1$d');
        } else {
            Assert::range($minute, 0, 59, 'Minute must be between %2$u and %3$u, got: %1$d');
            Assert::range($second, 0, 60, 'Second must be between %2$u and %3$u, got: %1$d');
            Assert::greaterThanEq($fraction, 0, 'Fraction must be greater than or equal to %2$u, got: %1$d');
        }

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->fraction = $fraction;
    }

    /**
     * Create an instance representing midnight.
     *
     * @return Time
     */
    public static function createMidnight()
    {
        return new self;
    }

    /**
     * Create an instance representing the end of a day.
     *
     * @return Time
     */
    public static function createEndOfDay()
    {
        return new self(24);
    }

    /**
     * Construct a new instance from a PHP native DateTime object.
     *
     * @param \DateTimeInterface|\DateTime $dt
     *
     * @return Time
     */
    public static function createFromDateTime($dt)
    {
        if (interface_exists('DateTimeInterface')) {
            Assert::isInstanceOf($dt, 'DateTimeInterface');
        } else {
            Assert::isInstanceOf($dt, 'DateTime');
        }

        return new self(
            (int) $dt->format('H'),
            (int) $dt->format('i'),
            (int) $dt->format('s'),
            (int) $dt->format('u')
        );
    }

    /**
     * Create a new instance from an ISO8601 time string.
     *
     * @param string $iso8601str
     *
     * @return Time
     */
    public static function createFromString($iso8601str)
    {
        Assert::regex($iso8601str,
            // Start
            '{^'
            // Hour (0-24, required)
            .'(?:0[0-9]|1[0-9]|2[0-4])'
            // Minute (0-59, optional, maybe prefixed by colon)
            .'(?:(:?)(?:0[0-9]|[1-5][0-9]))?'
            // Second (0-60, optional, prefix must match minute)
            .'(?:\1(?:0[0-9]|[1-5][0-9]|60))?'
            // Fraction (0-INF, optional, prefixed by comma or period)
            .'(?:[,.][0-9]+)?'
            // End
            .'$}'
        );

        preg_match('{^(?<hour>[0-9]{2})(?::?(?<minute>[0-9]{2}))?(?::?(?<second>[0-9]{2}))?(?:[,.](?<fraction>[0-9]+))?$}', $iso8601str, $m);

        if ([] === $m) {
            throw new \LogicException('Failed to parse valid ISO8601 time string');
        }

        $m += ['minute' => '', 'second' => '', 'fraction' => ''];

        $hour = (int) $m['hour'];
        $minute = '' === $m['minute'] ? null : (int) $m['minute'];
        $second = '' === $m['second'] ? null : (int) $m['second'];
        $fraction = '' === $m['fraction'] ? null : (int) $m['fraction'];

        unset($m);

        // If minute is unset, seconds should also be unset.
        assert(null === $minute ? null === $second : true);

        if ($fraction > 0 && null === $second) {
            $fractionPrecision = strlen($fraction);
            $fractionFloat = $fraction / pow(10, $fractionPrecision);

            if (null === $minute) {
                // Apply fraction to the hour and then carry the remainder over
                // to the minutes.
                $fractionFloat *= 60;

                $minute = (int) round($fractionFloat, $fractionPrecision);

                $fractionFloat -= $minute;
            }

            $fractionFloat *= 60;

            $second = (int) round($fractionFloat, $fractionPrecision);

            $fractionFloat -= $second;

            $fraction = (int) (round($fractionFloat, $fractionPrecision) * pow(10, $fractionPrecision));
        }

        return new self($hour, $minute ?: 0, $second ?: 0, $fraction ?: 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * {@inheritdoc}
     */
    public function getFraction()
    {
        return $this->fraction;
    }

    /**
     * Convert to an ISO8601 time string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%02u:%02u:%02u.%0-6s',
            $this->hour,
            $this->minute,
            $this->second,
            $this->fraction
        );
    }
}
