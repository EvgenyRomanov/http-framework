<?php

declare(strict_types=1);

namespace App;

use EvgenyRomanov\LangDetector;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class Home implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $factory,
        private LangDetector $langDetector,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        if (!is_string($name)) {
            return $this->factory->createResponse(400);
        }

        $detector = $this->langDetector;
        $lang = $detector($request, 'en');

        $response = $this->factory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');

        $response->getBody()->write('Hello, ' . $name . '! Your lang is ' . $lang);

        return $response;
    }
}
