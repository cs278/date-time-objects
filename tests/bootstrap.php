<?php

/*
 * This file is part of the Date Time Objects package.
 *
 * (c) Chris Smith <chris@cs278.org>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';

/**
 * 'Polyfill' for the PHP 7 spaceship operator.
 */
function ⟷($a, $b)
{
    return $a == $b ? 0 : ($a > $b ? 1 : -1);
}
