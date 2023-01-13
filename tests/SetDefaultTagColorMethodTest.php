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

class SetDefaultTagColorMethodTest extends TestCase
{
    protected CliPrinter $printer;

    protected function setUp(): void
    {
        $this->printer = new CliPrinter();

        parent::setup();
    }

    public function test_should_set_default_tag_color()
    {
        $this->assertEquals($this->getDefaultColorForTags($this->printer), Color::BLUE);

        $this->printer->setDefaultTagColor(Color::RED);

        $this->assertEquals($this->getDefaultColorForTags($this->printer), Color::RED);
    }
}
