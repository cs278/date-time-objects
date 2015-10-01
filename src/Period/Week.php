<?php

/*
 * This file is part of the Date Time Objects package.
 *
 * (c) Chris Smith <chris@cs278.org>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Cs278\DateTimeObjects\Period;

use Cs278\DateTimeObjects\Period;

final class Week implements Period
{
    public function __construct(Date $date)
    {
        $this->start = null/* back to monday */;
        $this->end = null/* start + 7 days */;
    }

    public function getStartPoint()
    {
        return $this->start;
    }

    public function getEndPoint()
    {
        return $this->end;
    }
}
