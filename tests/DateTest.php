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

final class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromDateTime()
    {
        $dt = new \DateTime('2012-04-11 23:21:32.840028');
        $expected = new Date(2012, 04, 11);

        $this->assertEquals($expected, Date::createFromDateTime($dt));
    }

    /** @dataProvider dataCreateFromString */
    public function testCreateFromString($expected, $input)
    {
        $this->assertEquals($expected, Date::createFromString($input));
    }

    public function dataCreateFromString()
    {
        return [
            [new Date(2000, 1, 1), '2000-01-01'],
            [new Date(2000, 1, 1), '2000-1-01'],
            [new Date(2000, 1, 1), '2000-01-1'],
            [new Date(2000, 1, 1), '2000-1-1'],
            [new Date(1583, 1, 1), '1583-01-01'],
            [new Date(9999, 1, 1), '9999-01-01'],
            [new Date(2000, 1, 1), '2000-1-1'],
            [new Date(2000, 1, 2), '2000-1-2'],
            [new Date(2000, 1, 3), '2000-1-3'],
            [new Date(2000, 1, 4), '2000-1-4'],
            [new Date(2000, 1, 5), '2000-1-5'],
            [new Date(2000, 1, 6), '2000-1-6'],
            [new Date(2000, 1, 7), '2000-1-7'],
            [new Date(2000, 1, 8), '2000-1-8'],
            [new Date(2000, 1, 9), '2000-1-9'],
            [new Date(2000, 1, 10), '2000-1-10'],
            [new Date(2000, 1, 11), '2000-1-11'],
            [new Date(2000, 1, 12), '2000-1-12'],
            [new Date(2000, 1, 13), '2000-1-13'],
            [new Date(2000, 1, 14), '2000-1-14'],
            [new Date(2000, 1, 15), '2000-1-15'],
            [new Date(2000, 1, 16), '2000-1-16'],
            [new Date(2000, 1, 17), '2000-1-17'],
            [new Date(2000, 1, 18), '2000-1-18'],
            [new Date(2000, 1, 19), '2000-1-19'],
            [new Date(2000, 1, 20), '2000-1-20'],
            [new Date(2000, 1, 21), '2000-1-21'],
            [new Date(2000, 1, 22), '2000-1-22'],
            [new Date(2000, 1, 23), '2000-1-23'],
            [new Date(2000, 1, 24), '2000-1-24'],
            [new Date(2000, 1, 25), '2000-1-25'],
            [new Date(2000, 1, 26), '2000-1-26'],
            [new Date(2000, 1, 27), '2000-1-27'],
            [new Date(2000, 1, 28), '2000-1-28'],
            [new Date(2000, 1, 29), '2000-1-29'],
            [new Date(2000, 1, 30), '2000-1-30'],
            [new Date(2000, 1, 31), '2000-1-31'],
            [new Date(2000, 2, 1), '2000-2-1'],
            [new Date(2000, 3, 1), '2000-3-1'],
            [new Date(2000, 4, 1), '2000-4-1'],
            [new Date(2000, 5, 1), '2000-5-1'],
            [new Date(2000, 6, 1), '2000-6-1'],
            [new Date(2000, 7, 1), '2000-7-1'],
            [new Date(2000, 8, 1), '2000-8-1'],
            [new Date(2000, 9, 1), '2000-9-1'],
            [new Date(2000, 10, 1), '2000-10-1'],
            [new Date(2000, 11, 1), '2000-11-1'],
            [new Date(2000, 12, 1), '2000-12-1'],
        ];
    }

    public function testConstruct()
    {
        $date = new Date(1988, 12, 25);

        $this->assertInstanceOf('Cs278\DateTimeObjects\Date', $date);

        return $date;
    }

    /** @depends testConstruct */
    public function testGetYear(Date $date)
    {
        $this->assertSame(1988, $date->getYear());
    }

    /** @depends testConstruct */
    public function testGetMonth(Date $date)
    {
        $this->assertSame(12, $date->getMonth());
    }

    /** @depends testConstruct */
    public function testGetDay(Date $date)
    {
        $this->assertSame(25, $date->getDay());
    }

    /** @depends testConstruct */
    public function testGetDayOfWeek(Date $date)
    {
        $this->assertSame(WeekDay::SUN, $date->getDayOfWeek());
    }

    /** @depends testConstruct */
    public function testGetDayOfYear(Date $date)
    {
        // 1988 was a leap year.
        $this->assertSame(360, $date->getDayOfYear());
    }

    public function testGetDayOfYearLastDay()
    {
        $this->assertSame(
            365,
            (new Date(2013, 12, 31))->getDayOfYear(),
            'Non leap year should have 365 days'
        );

        $this->assertSame(
            366,
            (new Date(2012, 12, 31))->getDayOfYear(),
            'Leap year should have 366 days'
        );
    }

    /** @depends testConstruct */
    public function testToString(Date $date)
    {
        $this->assertSame('1988-12-25', (string) $date);
    }

    /** @dataProvider dataComparison */
    public function testComparison($expected, Date $a, Date $b)
    {
        // $result = $a <=> $b;
        $result = $a == $b ? 0 : ($a > $b ? 1 : -1);

        $this->assertSame($expected, $result);
    }

    public function dataComparison()
    {
        return [
            [1, new Date(1990, 4, 16), new Date(1990, 4, 15)],
            [0, new Date(1990, 4, 15), new Date(1990, 4, 15)],
            [-1, new Date(1990, 4, 14), new Date(1990, 4, 15)],
        ];
    }
}
