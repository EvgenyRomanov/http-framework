<?php

declare(strict_types=1);

namespace Framework;

use Framework\Http\Message\ResponseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ResponseFactoryTest extends TestCase
{
    public function testDefault(): void
    {
        $factory = new ResponseFactory();

        $response = $factory->createResponse();

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testCode(): void
    {
        $factory = new ResponseFactory();

        $response = $factory->createResponse(302);

        self::assertEquals(302, $response->getStatusCode());
    }
}
