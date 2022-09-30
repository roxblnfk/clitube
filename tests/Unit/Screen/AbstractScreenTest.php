<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Tests\Unit\Screen;

use PHPUnit\Framework\TestCase;
use Roxblnfk\CliTube\Tests\Unit\Stub\Screen;

class AbstractScreenTest extends TestCase
{
    public function stringLengthProvider(): iterable
    {
        return [
            ['foo', 3],
            ['', 0],
            [$this->wrap('bar', 33), 3],
            [$this->wrap('', 42), 0],
        ];
    }

    /**
     * @dataProvider stringLengthProvider
     */
    public function testStrlen(string $string, int $expectedLength): void
    {
        $screen = new Screen();
        $this->assertSame($expectedLength, $screen->strlen($string));
    }

    private function wrap(string|int $str, int|string $code): string
    {
        return "\033[{$code}m$str\033[0m";
    }
}
