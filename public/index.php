<?php

declare(strict_types=1);

use App\Home;
use EvgenyRomanov\LangDetector;
use Framework\Http\Message\ResponseFactory;
use Framework\Http\Message\ServerRequestFactory;
use Framework\Http\SapiStreamEmitter;

/** @psalm-suppress MissingFile */
require __DIR__ . '/../vendor/autoload.php';

http_response_code(500);

### Grabbing

$request = ServerRequestFactory::fromGlobals();

### Preprocessing

if (str_starts_with($request->getHeaderLine('Content-Type'), 'application/x-www-form-urlencoded')) {
    parse_str((string) $request->getBody(), $data);
    $request = $request->withParsedBody($data);
}

### Running

$langDetector = new LangDetector();
$home = new Home(new ResponseFactory(), $langDetector);

$response = $home->handle($request);

### Postprocessing

$response = $response->withHeader('X-Frame-Options', 'DENY');

### Sending

$emitter = new SapiStreamEmitter();
$emitter->emit($response);
