<?php

declare(strict_types=1);

namespace Test\DetectLang;

use EvgenyRomanov\LangDetector;
use Framework\Http\Message\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DetectLangTest extends TestCase
{
    public function testDefault(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/');
        $detector = new LangDetector();

        $lang = $detector($request, 'en');

        self::assertEquals('en', $lang);
    }

    public function testQueryParam(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/')
            ->withQueryParams(['lang' => 'de'])
            ->withCookieParams(['lang' => 'pt'])
            ->withHeader('Accept-Language', ['ru-ru', 'ru;q=0.8', 'en;q=0.4']);
        $detector = new LangDetector();

        $lang = $detector($request, 'en');

        self::assertEquals('de', $lang);
    }

    public function testCookie(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/')
            ->withCookieParams(['lang' => 'pt'])
            ->withHeader('Accept-Language', ['ru-ru', 'ru;q=0.8', 'en;q=0.4']);
        $detector = new LangDetector();

        $lang = $detector($request, 'en');

        self::assertEquals('pt', $lang);
    }

    public function testHeader(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/')
            ->withHeader('Accept-Language', ['ru-ru', 'ru;q=0.8', 'en;q=0.4']);
        $detector = new LangDetector();

        $lang = $detector($request, 'en');

        self::assertEquals('ru', $lang);
    }
}
