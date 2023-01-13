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

use ReflectionClass;
use Josantonius\CliPrinter\Color;
use Josantonius\CliPrinter\CliPrinter;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setup();
    }

    protected function getProtectedProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionClass($object);

        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    protected function getDefaultColorForTags(CliPrinter $printer): Color
    {
        return $this->getProtectedProperty($printer, 'defaultColor');
    }

    protected function getMessages(CliPrinter $printer): array
    {
        return $this->getProtectedProperty($printer, 'messages');
    }

    protected function getTagColors(CliPrinter $printer): array
    {
        return $this->getProtectedProperty($printer, 'tagColors');
    }

    protected function getColorByTagName(CliPrinter $printer, string $tagName): int
    {
        $tagColors    = $this->getTagColors($printer);
        $defaultColor = $this->getDefaultColorForTags($printer);

        return ($tagColors[strtolower($tagName)] ?? $defaultColor)->value;
    }

    protected function getBeforeLineBreaks(CliPrinter $printer): string
    {
        return str_repeat("\n", $this->getProtectedProperty($printer, 'lineBreaks')['before']);
    }

    protected function getAfterLineBreaks(CliPrinter $printer): string
    {
        return str_repeat("\n", $this->getProtectedProperty($printer, 'lineBreaks')['after']);
    }
}
