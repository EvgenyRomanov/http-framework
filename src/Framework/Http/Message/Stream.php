<?php

declare(strict_types=1);

namespace Framework\Http\Message;

use LogicException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class Stream implements StreamInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @param resource $resource
     */
    public function __construct(mixed $resource)
    {
        $this->resource = $resource;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        fseek($this->resource, $offset, $whence);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function read($length): string
    {
        $string = fread($this->resource, $length);
        if ($string === false) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    public function write($string): int
    {
        $numberBytes = fwrite($this->resource, $string);
        if ($numberBytes === false) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $numberBytes;
    }

    public function getContents(): string
    {
        $contents = stream_get_contents($this->resource);
        if ($contents === false) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $contents;
    }

    public function __toString(): string
    {
        $this->rewind();
        return $this->getContents();
    }

    public function close(): void
    {
        throw new LogicException('Not implemented.');
    }

    public function detach(): void
    {
        throw new LogicException('Not implemented.');
    }

    public function getSize(): ?int
    {
        throw new LogicException('Not implemented.');
    }

    public function tell(): int
    {
        throw new LogicException('Not implemented.');
    }

    public function eof(): bool
    {
        throw new LogicException('Not implemented.');
    }

    public function isSeekable(): bool
    {
        throw new LogicException('Not implemented.');
    }

    public function isWritable(): bool
    {
        throw new LogicException('Not implemented.');
    }

    public function isReadable(): bool
    {
        throw new LogicException('Not implemented.');
    }

    public function getMetadata($key = null): mixed
    {
        throw new LogicException('Not implemented.');
    }
}
