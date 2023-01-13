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

use Monolog\Logger;
use Josantonius\CliPrinter\Color;
use Monolog\Handler\StreamHandler;
use Josantonius\CliPrinter\CliPrinter;
use Josantonius\CliPrinter\Tests\TestCase;

class DisplayMethodTest extends TestCase
{
    protected Logger $logger;

    protected CliPrinter $printer;

    protected string $logFile = __DIR__ . '/test.log';

    protected function setUp(): void
    {
        $this->printer = new CliPrinter();
        $this->logger  = new Logger('foo');

        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/test.log'));

        parent::setup();
    }

    public function test_should_display_message(): void
    {
        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);
        $color  = $this->getColorByTagName($this->printer, 'notice');

        $this->expectOutputString("$before\033[{$color}m NOTICE \033[0m message{$after}");

        $this->printer->display('notice', 'message');
    }

    public function test_should_display_message_without_tag(): void
    {
        $message = 'foo';

        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);

        $this->expectOutputString("$before\033[0m{$message}{$after}");

        $this->printer->display('', $message);
    }

    public function test_should_display_message_without_message(): void
    {
        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);
        $color  = $this->getColorByTagName($this->printer, 'notice');

        $this->expectOutputString("$before\033[{$color}m NOTICE \033[0m {$after}");

        $this->printer->display('notice', '');
    }

    public function test_should_display_message_without_message_or_tag(): void
    {
        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);

        $this->expectOutputString("$before\033[0m{$after}");

        $this->printer->display('', '');
    }

    public function test_should_display_message_through_your_key(): void
    {
        ob_start();

        $printer = new CliPrinter(['foo.bar' => 'the message']);
        $printer->display('tag', 'foo.bar');

        $this->assertStringContainsString('the message', ob_get_clean());
    }

    public function test_should_display_message_with_default_color_tag(): void
    {
        ob_start();

        $this->printer->display('tag', 'bar');

        $color = $this->getDefaultColorForTags($this->printer)->value;

        $this->assertStringContainsString("\033[{$color}m", ob_get_clean());
    }

    public function test_should_display_message_with_custom_color_tag(): void
    {
        ob_start();

        $this->printer->setTagColor('tag', Color::RED);
        $this->printer->display('tag', 'bar');

        $color = Color::RED->value;

        $this->assertStringContainsString("\033[{$color}m", ob_get_clean());
    }

    public function test_should_display_message_with_the_right_label(): void
    {
        ob_start();

        $this->printer->display('alert', 'bar');

        $this->assertStringContainsString("ALERT", ob_get_clean());

        ob_start();

        $this->printer->display('warning', 'bar');

        $this->assertStringContainsString("WARNING", ob_get_clean());
    }

    public function test_should_format_the_message(): void
    {
        ob_start();

        $printer = new CliPrinter();
        $printer->display('tag', 'the %s', ['message']);

        $this->assertStringContainsString('the message', ob_get_clean());

        ob_start();

        $printer = new CliPrinter(['foo.bar' => 'the %s']);
        $printer->display('tag', 'foo.bar', ['message']);

        $this->assertStringContainsString('the message', ob_get_clean());
    }

    public function test_should_add_line_breaks_correctly(): void
    {
        ob_start();

        $this->printer->display('tag', 'bar');

        $output = ob_get_clean();

        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);

        $this->assertStringStartsWith($before, $output);
        $this->assertStringEndsWith($after, $output);

        ob_start();

        $this->printer->setLineBreaks(2, 3);
        $this->printer->display('tag', 'bar');

        $output = ob_get_clean();

        $before = $this->getBeforeLineBreaks($this->printer);
        $after  = $this->getAfterLineBreaks($this->printer);

        $this->assertStringStartsWith($before, $output);
        $this->assertStringEndsWith($after, $output);
    }

    public function test_should_save_log_using_his_log_level(): void
    {
        ob_start();

        $printer = new CliPrinter();
        $printer->useLogger($this->logger);
        $printer->display('info', 'message');

        ob_get_clean();

        $this->assertStringContainsString("INFO: message", fgets(fopen($this->logFile, 'r')));

        unlink($this->logFile);
    }

    public function test_should_log_using_the_log_debug_level_if_not_matches(): void
    {
        ob_start();

        $printer = new CliPrinter();
        $printer->useLogger($this->logger);
        $printer->display('custom', 'message');

        ob_get_clean();

        $this->assertStringContainsString("DEBUG: message", fgets(fopen($this->logFile, 'r')));

        unlink($this->logFile);
    }

    public function test_should_add_extra_parameters_in_the_log(): void
    {
        ob_start();

        $printer = new CliPrinter();
        $printer->useLogger($this->logger);

        $params = ['foo' => 'bar'];
        $printer->display('info', 'message', $params);

        ob_get_clean();

        $this->assertStringContainsString(json_encode($params), fgets(fopen($this->logFile, 'r')));
        unlink($this->logFile);
    }

    public function test_should_not_display_the_message_if_there_is_a_development_constant(): void
    {
        define('DISABLE_CONSOLE_PRINTING', true);

        $this->printer->display('info', 'message');

        $this->expectOutputString("");
    }
}
