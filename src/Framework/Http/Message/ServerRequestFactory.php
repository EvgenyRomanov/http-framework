<?php

declare(strict_types=1);

namespace Framework\Http\Message;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * @param array<string, array|string>|null $query
     * @param array<string, array|string>|null $body
     * @param array<string, string>|null $server
     * @param resource|null $input
     */
    public static function fromGlobals(
        ?array $server = null,
        ?array $query = null,
        ?array $cookie = null,
        ?array $body = null,
        mixed $input = null
    ): ServerRequestInterface {
        $server ??= $_SERVER;
        $headers = [
            'Content-Type' => [$server['CONTENT_TYPE']],
            'Content-Length' => [$server['CONTENT_LENGTH']],
        ];

        foreach ($server as $serverName => $serverValue) {
            if (str_starts_with($serverName, 'HTTP_')) {
                $name = ucwords(strtolower(str_replace('_', '-', substr($serverName, 5))), '-');
                /** @var string[] $values */
                /** @psalm-suppress InvalidArgument */
                /** @psalm-suppress PossiblyInvalidCast */
                $values = preg_split('#\s*,\s*#', $serverValue);
                $headers[$name] = $values;
            }
        }

        /** @psalm-suppress RiskyTruthyFalsyComparison */
        /** @psalm-suppress InvalidOperand */
        /** @psalm-suppress PossiblyInvalidCast */
        return new ServerRequest(
            serverParams: $server,
            uri: (new UriFactory())->createUri(
                (!empty($server['HTTPS']) ? 'https' : 'http') . '://' . $server['HTTP_HOST'] . $server['REQUEST_URI']
            ),
            method: $server['REQUEST_METHOD'],
            queryParams: $query ?? $_GET,
            headers: $headers,
            cookieParams: $cookie ?? $_COOKIE,
            body: (new StreamFactory())->createStreamFromResource($input ?: fopen('php://input', 'r')),
            parsedBody: $body ?? ($_POST ?: null)
        );
    }

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest(
            serverParams: $serverParams,
            uri: is_string($uri) ? (new UriFactory())->createUri($uri) : $uri,
            method: $method,
            queryParams: [],
            headers: [],
            cookieParams: [],
            body: (new StreamFactory())->createStream(),
            parsedBody: null
        );
    }
}
