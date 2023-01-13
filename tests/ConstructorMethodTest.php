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

class ConstructorMethodTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setup();
    }

    public function test_should_create_new_instance()
    {
        $printer = new CliPrinter();

        $this->assertInstanceOf(CliPrinter::class, $printer);
    }

    public function test_should_create_new_instance_and_set_messages()
    {
        $printer = new CliPrinter(['foo' => 'bar']);

        $this->assertArrayHasKey('foo', $this->getMessages($printer));

        $this->assertEquals('bar', $this->getMessages($printer)['foo']);
    }
}
