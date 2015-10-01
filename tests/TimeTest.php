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

final class TimeTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider dataCreateFromString */
    public function testCreateFromString(Time $expected, $input)
    {
        $this->assertEquals($expected, Time::createFromString($input));
    }

    public function dataCreateFromString()
    {
        return [
            [new Time(0, 0, 0, 0), '00'],
            [new Time(0, 0, 0, 0), '0000'],
            [new Time(0, 0, 0, 0), '000000'],
            [new Time(0, 0, 0, 0), '000000.0'],
            [new Time(1, 0, 0, 0), '01'],
            [new Time(1, 1, 0, 0), '0101'],
            [new Time(1, 1, 1, 0), '010101'],
            [new Time(1, 1, 1, 1), '010101.1'],
            [new Time(1, 1, 0, 0), '01:01'],
            [new Time(1, 1, 1, 0), '01:01:01'],
            [new Time(1, 1, 1, 1), '01:01:01.1'],

            [new Time(5, 30, 0, 0), '05.5'],
            [new Time(5, 43, 12, 0), '05.72'],
            [new Time(5, 42, 50, 400), '05.714'],
        ];
    }

    public function testCreateMidnight()
    {
        $this->assertEquals(new Time(0, 0, 0, 0), Time::createMidnight());
    }

    public function testCreateEndOfDay()
    {
        $this->assertEquals(new Time(23, 59, 59, 0), Time::createEndOfDay());
    }

    public function testConstruct()
    {
        $time = new Time(23, 10, 19, 42);

        $this->assertInstanceOf('Cs278\DateTimeObjects\Time', $time);

        return $time;
    }

    /** @depends testConstruct */
    public function testGetHour(Time $time)
    {
        $this->assertSame(23, $time->getHour());
    }

    /** @depends testConstruct */
    public function testGetMinute(Time $time)
    {
        $this->assertSame(10, $time->getMinute());
    }

    /** @depends testConstruct */
    public function testGetSecond(Time $time)
    {
        $this->assertSame(19, $time->getSecond());
    }

    /** @depends testConstruct */
    public function testGetFraction(Time $time)
    {
        $this->assertSame(42, $time->getFraction());
    }

    /** @depends testConstruct */
    public function testToString(Time $time)
    {
        $this->assertSame('23:10:19.420000', (string) $time);
    }
}
