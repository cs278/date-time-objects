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
    private $hour;
    private $minute;

    public function __construct($hour = 0, $minute = 0)
    {
        $this->hour = $hour;
        $this->minute = $minute;
    }

    public static function createFromDateTime(\DateTimeInterface $dt)
    {
        return self::createFromString($dt->format('Y-m-d'));
    }

    public static function createFromString($iso8601str)
    {
        preg_match('{^(?<hour>[0-9]{1,2})(?<minute>[0-9]{1,2})?(?<second>[0-9]{1,2})?(?:[,.](?<fraction>[0-9]+))?$}', $iso8601str, $m);

        $hour = (int) $m['hour'];
        $minute = '' === $m['minute'] ? null : (int) $m['minute'];
        $second = '' === $m['second'] ? null : (int) $m['second'];
        $fraction = '' === $m['fraction'] ? null : (int) $m['fraction'];

        unset($m);

        // If minute is unset, seconds should also be unset.
        assert(null === $minute ? null === $second : true);

        if ($fraction > 0) {
            $fractionPrecision = strlen($fraction);
            $fractionFloat = $fraction / pow(10, $fractionPrecision);

            if (null === $minute) {
                $fractionFloat *= 60;

                $minute = (int) round($fractionFloat, $fractionPrecision);

                $fractionFloat -= $minute;
            }

            if (null === $second) {
                $fractionFloat *= 60;

                $second = (int) round($fractionFloat, $fractionPrecision);

                $fractionFloat -= $second;

                // $fraction = $fractionFloat * pow(10, $fractionPrecision);
                $fraction = 9999;

                // $fraction = (int) (round($fractionFloat, $fractionPrecision) * 10);
            }
        }

        return new self($hour, $minute, $second, $fraction);
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
