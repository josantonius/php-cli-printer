<?php

/*
 * This file is part of https://github.com/josantonius/php-cli-printer repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Josantonius\CliPrinter;

enum Color: int
{
    case BLACK  = 40;
    case RED    = 41;
    case GREEN  = 42;
    case YELLOW = 43;
    case BLUE   = 44;
    case PURPLE = 45;
    case CYAN   = 46;
    case WHITE  = 47;
}
