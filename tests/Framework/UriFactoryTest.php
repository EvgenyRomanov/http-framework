<?php

declare(strict_types=1);

namespace Framework;

use Framework\Http\Message\UriFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UriFactoryTest extends TestCase
{
    public function testDefault(): void
    {
        $factory = new UriFactory();

        $uri = $factory->createUri($string = 'https://user:pass@test:81/home?a=2&b=3#first');

        self::assertEquals($string, (string) $uri);
    }
}
