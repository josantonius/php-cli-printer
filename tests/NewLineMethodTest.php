<?php

/*
 * This file is part of https://github.com/josantonius/php-cli-printer repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */

namespace Josantonius\CliPrinter\Tests;

use Josantonius\CliPrinter\CliPrinter;
use Josantonius\CliPrinter\Tests\TestCase;

class NewLineMethodTest extends TestCase
{
    protected CliPrinter $printer;

    protected function setUp(): void
    {
        $this->printer = new CliPrinter();

        parent::setup();
    }

    public function test_newLine_with_default_times()
    {
        $this->expectOutputString("\n");
        $this->printer->newLine();
    }

    public function test_newLine_with_custom_times()
    {
        $this->expectOutputString("\n\n\n");
        $this->printer->newLine(3);
    }

    public function test_newLine_with_zero_times()
    {
        $this->expectOutputString("");
        $this->printer->newLine(0);
    }
}
