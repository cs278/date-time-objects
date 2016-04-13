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

/**
 * Represents a date on the gregorian calendar.
 */
interface DateInterface
{
    /**
     * Fetch the year.
     *
     * @return int
     */
    public function getYear();

    /**
     * Fetch the month.
     *
     * @return int
     */
    public function getMonth();

    /**
     * Fetch the day.
     *
     * @return int
     */
    public function getDay();

    /**
     * Fetch the day of the week.
     *
     * @return int
     */
    public function getWeekDay();

    /**
     * Fetch the ordinal position of the day in the year.
     *
     * @return int
     */
    public function getDayOfYear();
}
