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

use Josantonius\CliPrinter\Color;
use Josantonius\CliPrinter\CliPrinter;
use Josantonius\CliPrinter\Tests\TestCase;

class CallMethodTest extends TestCase
{
    protected CliPrinter $printer;

    protected string $logFile = __DIR__ . '/test.log';

    protected function setUp(): void
    {
        $this->printer = new CliPrinter();

        parent::setup();
    }

    public function test_should_display_the_message_using_the_level_as_a_method(): void
    {
        ob_start();

        $this->printer->setTagColor('notice', Color::PURPLE);

        $this->printer->notice('the message', []);

        $this->assertStringContainsString('the message', ob_get_clean());
    }
}
