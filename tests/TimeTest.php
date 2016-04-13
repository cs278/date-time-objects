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
            [new Time(0, 0, 0, 0), '00.0'],
            [new Time(0, 0, 0, 0), '0000'],
            [new Time(0, 0, 0, 0), '0000.0'],
            [new Time(0, 0, 0, 0), '000000'],
            [new Time(0, 0, 0, 0), '000000.0'],
            [new Time(1, 0, 0, 0), '01'],
            [new Time(1, 6, 0, 0), '01.1'],
            [new Time(1, 1, 0, 0), '0101'],
            [new Time(1, 1, 6, 0), '0101.1'],
            [new Time(1, 1, 1, 0), '010101'],
            [new Time(1, 1, 1, 1), '010101.1'],
            [new Time(1, 1, 0, 0), '01:01'],
            [new Time(1, 1, 6, 0), '01:01.1'],
            [new Time(1, 1, 1, 0), '01:01:01'],
            [new Time(1, 1, 1, 1), '01:01:01.1'],

            [new Time(5, 30, 0, 0), '05.5'],
            [new Time(5, 43, 12, 0), '05.72'],
            [new Time(5, 42, 50, 400), '05.714'],

            [new Time(20, 10, 55, 680), '20:10.928'],

            [new Time(0, 0, 0, 1), '000000.1'],
            [new Time(0, 0, 0, 10), '000000.10'],
            [new Time(0, 0, 0, 100), '000000.100'],
        ];
    }

    public function testCreateMidnight()
    {
        $this->assertEquals(new Time(0, 0, 0, 0), Time::createMidnight());
    }

    public function testCreateEndOfDay()
    {
        $this->assertEquals(new Time(24, 0, 0, 0), Time::createEndOfDay());
    }

    public function testConstruct()
    {
        $time = new Time(23, 10, 19, 42);

        $this->assertInstanceOf('Cs278\DateTimeObjects\Time', $time);

        return $time;
    }

    /** @dataProvider dataConstructInvalidArgumentException */
    public function testConstructInvalidArgumentException($expectedMessage, $h, $m, $s, $f)
    {
        $this->setExpectedException('InvalidArgumentException', $expectedMessage);

        new Time($h, $m, $s, $f);
    }

    public function dataConstructInvalidArgumentException()
    {
        return [
            ['Hour must be an integer, got: `string`', '12', 0, 0, 0],
            ['Hour must be an integer, got: `double`', 0.0, 0, 0, 0],
            ['Minute must be an integer, got: `string`', 0, '12', 0, 0],
            ['Minute must be an integer, got: `double`', 0, 0.0, 0, 0],
            ['Second must be an integer, got: `string`', 0, 0, '12', 0],
            ['Second must be an integer, got: `double`', 0, 0, 0.0, 0],
            ['Fraction must be an integer, got: `string`', 0, 0, 0, '12'],
            ['Fraction must be an integer, got: `double`', 0, 0, 0, 0.0],

            ['Minute must equal 0, got: 1', 24, 1, 0, 0],
            ['Second must equal 0, got: 1', 24, 0, 1, 0],
            ['Fraction must equal 0, got: 1', 24, 0, 0, 1],

            ['Hour must be between 0 and 24, got: -1', -1, 0, 0, 0],
            ['Hour must be between 0 and 24, got: 25', 25, 0, 0, 0],
            ['Hour must be between 0 and 24, got: 45', 45, 0, 0, 0],
            ['Minute must be between 0 and 59, got: -1', 20, -1, 0, 0],
            ['Minute must be between 0 and 59, got: 60', 20, 60, 0, 0],
            ['Minute must be between 0 and 59, got: 65', 20, 65, 0, 0],
            ['Second must be between 0 and 60, got: -1', 20, 0, -1, 0],
            ['Second must be between 0 and 60, got: 61', 20, 0, 61, 0],
            ['Second must be between 0 and 60, got: 65', 20, 0, 65, 0],
            ['Fraction must be greater than or equal to 0, got: -1', 20, 0, 0, -1],
        ];
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

    /** @dataProvider dataComparison */
    public function testComparison($expected, Time $a, Time $b)
    {
        $result = ⟷($a, $b);

        switch ($expected) {
            case 1:
                $message = '`%s` should be greater than `%s`';
                break;
            case -1:
                $message = '`%s` should be less than `%s`';
                break;
            case 0:
                $message = '`%s` should be equal to `%s`';
                break;

            default:
                $message = '';
        }

        $this->assertSame($expected, $result, sprintf($message, $a, $b));
    }

    public function dataComparison()
    {
        return [
            [0, new Time, new Time],

            // Hours
            [1, new Time(1), new Time(0)],
            [-1, new Time(0), new Time(1)],

            // Minutes
            [1, new Time(0, 1), new Time(0, 0)],
            [-1, new Time(0, 0), new Time(0, 1)],

            // Seconds
            [1, new Time(0, 0, 1), new Time(0, 0, 0)],
            [-1, new Time(0, 0, 0), new Time(0, 0, 1)],

            // Fractional
            [1, new Time(0, 0, 0, 1), new Time(0, 0, 0, 0)],
            [-1, new Time(0, 0, 0, 0), new Time(0, 0, 0, 1)],

            // 0000-2400
            [1, Time::createEndOfDay(), Time::createMidnight()],
            [-1, Time::createMidnight(), Time::createEndOfDay()],

            // Property order
            [1, new Time(10, 0, 0), new Time(0, 59)],
            [1, new Time(10, 0, 0), new Time(0, 0, 59)],
            [1, new Time(10, 0, 0), new Time(0, 0, 0, 59)],
            [1, new Time(0, 10, 0), new Time(0, 0, 59)],
            [1, new Time(0, 10, 0), new Time(0, 0, 0, 59)],
            [1, new Time(0, 0, 10), new Time(0, 0, 0, 59)],
        ];
    }
}
