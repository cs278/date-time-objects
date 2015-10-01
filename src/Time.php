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

use Assert\Assertion;

final class Time
{
    // Properties are used in comparisons, preserve the order of these.
    private $hour;
    private $minute;
    private $second;
    private $fraction;

    public function __construct($hour = 0, $minute = 0, $second = 0, $fraction = 0)
    {
        Assertion::integer($hour);
        Assertion::integer($minute);
        Assertion::integer($second);
        Assertion::integer($fraction);

        Assertion::range($hour, 0, 24);
        Assertion::range($minute, 0, 59);
        Assertion::range($second, 0, 60);
        Assertion::min($fraction, 0);

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->fraction = $fraction;
    }

    public function createMidnight()
    {
        return new self;
    }

    public function createEndOfDay()
    {
        return new self(23, 59, 59);
    }

    public static function createFromString($iso8601str)
    {
        Assertion::regex($iso8601str,
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

    public function getHour()
    {
        return $this->hour;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function getSecond()
    {
        return $this->second;
    }

    public function getFraction()
    {
        return $this->fraction;
    }

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
