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
 * Represents the time of day using 24 hour clock.
 */
interface TimeInterface
{
    /**
     * Fetch hour component.
     *
     * @return int
     */
    public function getHour();

    /**
     * Fetch minute component.
     *
     * @return int
     */
    public function getMinute();

    /**
     * Fetch second component.
     *
     * @return int
     */
    public function getSecond();

    /**
     * Fetch fraction component.
     *
     * @return int
     */
    public function getFraction();
}
