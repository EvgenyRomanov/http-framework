<?php

declare(strict_types=1);

namespace Framework;

use Framework\Http\Message\StreamFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StreamFactoryTest extends TestCase
{
    public function testDefault(): void
    {
        $factory = new StreamFactory();

        $stream = $factory->createStream();

        self::assertEquals('', $stream->getContents());
    }

    public function testContent(): void
    {
        $factory = new StreamFactory();

        $stream = $factory->createStream($content = 'Content');

        self::assertEquals($content, $stream->getContents());
    }
}
