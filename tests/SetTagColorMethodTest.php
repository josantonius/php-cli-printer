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

class SetTagColorMethodTest extends TestCase
{
    protected CliPrinter $printer;

    protected function setUp(): void
    {
        $this->printer = new CliPrinter();

        parent::setup();
    }

    public function test_should_set_tag_color()
    {
        $this->printer->setTagColor('foo', Color::RED);

        $tags = $this->getTagColors($this->printer);

        $this->assertArrayHasKey('foo', $tags);
        $this->assertSame(Color::RED, $tags['foo']);
    }
}
