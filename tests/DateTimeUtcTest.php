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

final class DateTimeUtcTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $result = new DateTimeUtc(new Date(1997, 6, 26), new Time(9, 5, 30, 5));

        $this->assertInstance($result);
    }

    /** @dataProvider dataCreateFromDateTime */
    public function testCreateFromDateTime($expected, $dt)
    {
        $result = DateTimeUtc::createFromDateTime($dt);

        $this->assertInstance($result);

        $this->assertSame($expected, (string) $result);
    }

    public function dataCreateFromDateTime()
    {
        return [
            ['2012-01-01T00:00:00.000000Z', new \DateTime('2012-01-01 00:00:00', new \DateTimeZone('UTC'))],
            ['2012-06-01T00:00:00.000000Z', new \DateTime('2012-06-01 01:00:00', new \DateTimeZone('Europe/London'))],
        ];
    }

    private function assertInstance($result)
    {
        $this->assertInstanceOf('Cs278\DateTimeObjects\DateTimeUtc', $result);
        $this->assertInstanceOf('Cs278\DateTimeObjects\TimeInterface', $result);
        $this->assertInstanceOf('Cs278\DateTimeObjects\DateInterface', $result);
    }
}
