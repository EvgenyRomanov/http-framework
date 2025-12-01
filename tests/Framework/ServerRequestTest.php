<?php

declare(strict_types=1);

namespace Framework;

use Framework\Http\Message\ServerRequest;
use Framework\Http\Message\Stream;
use Framework\Http\Message\Uri;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServerRequestTest extends TestCase
{
    public function testCreate(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r');

        $request = new ServerRequest(
            serverParams: $serverParams = ['HOST' => 'app.test'],
            uri: $uri = new Uri('/home'),
            method: $method = 'GET',
            queryParams: $queryParams = ['name' => 'John'],
            headers: $headers = [
                'X-Header-1' => ['value-11', 'value-12'],
                'X-Header-2' => ['value-2'],
            ],
            cookieParams: $cookieParams = ['Cookie' => 'Val'],
            body: $body = new Stream($resource),
            parsedBody: $parsedBody = ['title' => 'Title']
        );

        self::assertEquals($serverParams, $request->getServerParams());
        self::assertEquals($uri, $request->getUri());
        self::assertEquals($method, $request->getMethod());
        self::assertEquals($queryParams, $request->getQueryParams());

        self::assertEquals($headers, $request->getHeaders());
        self::assertTrue($request->hasHeader('X-Header-1'));
        self::assertFalse($request->hasHeader('X-Header-3'));
        self::assertEquals('value-11, value-12', $request->getHeaderLine('X-Header-1'));
        self::assertEquals('value-2', $request->getHeaderLine('X-Header-2'));

        self::assertEquals($cookieParams, $request->getCookieParams());
        self::assertEquals($body, $request->getBody());
        self::assertEquals($parsedBody, $request->getParsedBody());
    }

    public function testModify(): void
    {
        /** @var resource $resource  */
        $resource = fopen('php://memory', 'r');

        $request = new ServerRequest(
            serverParams: ['HOST' => 'app.test'],
            uri: new Uri('/home'),
            method: 'GET',
            queryParams: ['name' => 'John'],
            headers: ['X-Header' => ['Value']],
            cookieParams: ['Cookie' => 'Val'],
            body: new Stream($resource),
            parsedBody: ['title' => 'Title']
        );

        $request = $request->withParsedBody(
            $parsedBody = ['body' => 'Value']
        );

        self::assertEquals($parsedBody, $request->getParsedBody());
    }
}
