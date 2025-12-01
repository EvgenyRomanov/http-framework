<?php

declare(strict_types=1);

namespace Framework;

use Framework\Http\Message\Stream;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StreamTest extends TestCase
{
    public function testEmpty(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r');

        $stream = new Stream($resource);

        self::assertEquals('', $stream->getContents());
    }

    public function testOver(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Body');

        $stream = new Stream($resource);

        self::assertEquals('', $stream->getContents());
    }

    public function testRewind(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Body');

        $stream = new Stream($resource);
        $stream->rewind();

        self::assertEquals('Body', $stream->getContents());
    }

    public function testSeekRead(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Long Body');

        $stream = new Stream($resource);
        $stream->seek(2);

        self::assertEquals('ng Bo', $stream->read(5));
    }

    public function testToString(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Body');

        $stream = new Stream($resource);

        self::assertEquals('Body', (string) $stream);
    }
}
