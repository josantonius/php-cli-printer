<?php

declare(strict_types=1);

/*
 * This file is part of https://github.com/josantonius/php-cli-printer repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Josantonius\CliPrinter;

use Monolog\Logger;
use Josantonius\CliPrinter\Color;

/**
 * Print messages on the command line interface (CLI).
 */
class CliPrinter
{
    /**
     * Maps tags to their corresponding color.
     */
    protected array $tagColors = [];

    /**
     * Logger instance.
     */
    protected ?Logger $logger = null;

    /**
     * Default color for tags without defined color.
     */
    protected Color $defaultColor = Color::BLUE;

    /**
     * Number of line breaks before and after the message.
     */
    protected array $lineBreaks = ['before' => 1, 'after' => 1];

    /**
     * Create new instance.
     *
     * @param array $messages The messages to be printed, in the format ['id' => 'message'].
     */
    public function __construct(protected array $messages = [])
    {
    }

    /**
     * Display a message on the console.
     *
     * Formatter: The $message and $params work in a similar way as the sprintf method:
     *            - display('tag', 'The %s %d', ['message', 8]).
     *
     * Messages:  If $messages was provided, the message can be replaced by the ID:
     *            - display('tag', 'user.error').
     *
     * Logger:    If used and the tag name of the message matches a level,
     *            it shall be used when adding the record. Otherwise use the debug level.
     *
     * @param string $tagName Message tag name.
     * @param string $message The message or message ID.
     * @param array  $params  Additional context for format $message or/and passing it to the log.
     */
    public function display(string $tagName, string $message, array $params = []): self
    {
        $tagName = strtolower($tagName);
        $message = sprintf($this->messages[$message] ?? $message, ...array_values($params));

        $this->recordLog($tagName, $message, $params);
        $this->printMessage($tagName, $message, $params);

        return $this;
    }

    /**
     * Dynamically display a message using the message tag as the method name.
     *
     * @param string $tagName   Message tag name.
     * @param string $arguments The first value is the message and the second the parameters.
     */
    public function __call(string $tagName, array $arguments): self
    {
        $message = $arguments[0] ?? '';
        $params  = $arguments[1] ?? [];

        return $this->display(strtolower($tagName), (string) $message, (array) $params);
    }

    /**
     * Add an instance of Monolog\Logger to add a log for each message displayed.
     *
     * @param Logger $logger Instance of the Logger class.
     */
    public function useLogger(Logger $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Print new lines on the console.
     *
     * @param int $times Number of new lines to print.
     */
    public function newLine(int $times = 1): self
    {
        print_r(str_repeat("\n", $times));

        return $this;
    }

    /**
     * Set the number of line breaks before and after a message.
     *
     * @param int $before Number of line breaks before a message.
     * @param int $after  Number of line breaks after a message.
     */
    public function setLineBreaks(int $before, int $after): self
    {
        $this->lineBreaks['before'] = $before;
        $this->lineBreaks['after']  = $after;

        return $this;
    }

    /**
     * Set the default color of the message tags.
     *
     * @param Color $color Default message tag color.
     */
    public function setDefaultTagColor(Color $color): self
    {
        $this->defaultColor = $color;

        return $this;
    }

    /**
     * Set the color for a message tag.
     *
     * @param string $tagName  Message tag name.
     * @param Color  $tagColor Message tag color.
     */
    public function setTagColor(string $tagName, Color $tagColor): self
    {
        $this->tagColors[strtolower($tagName)] = $tagColor;

        return $this;
    }

    /**
     * Print a message on the console.
     *
     * @param string $tagName Message tag name.
     * @param string $message The message to print.
     */
    protected function printMessage(string $tagName, string $message): void
    {
        if (php_sapi_name() !== 'cli' || defined('DISABLE_CONSOLE_PRINTING')) {
            return;
        }
        $color   = "\033[" . ($this->tagColors[$tagName] ?? $this->defaultColor)->value;
        $tagName = $tagName ? $color . "m " . strtoupper($tagName) .  " \033[0m " : "\033[0m";

        $this->newLine($this->lineBreaks['before']);
        print_r($tagName . $message);
        $this->newLine($this->lineBreaks['after']);
    }

    /**
     * Record a log.
     *
     * If the tag name of the message matches any level, it will be used when adding the log.
     * Otherwise it will be aggregated with the debug level.
     *
     * @param string $tagName Message tag name.
     * @param string $message The log message.
     * @param array  $params  Additional context for passing it to the log.
     */
    protected function recordLog(string $tagName, string $message, array $params): void
    {
        if ($this->logger) {
            $level = method_exists($this->logger, $tagName) ? $tagName : 'debug';
            $this->logger->{$level}($message, $params);
        }
    }
}
