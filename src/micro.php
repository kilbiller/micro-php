<?php

declare(strict_types = 1);

namespace micro;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Closure;

use \Nyholm\Psr7\Factory\Psr17Factory;
use \Nyholm\Psr7Server\ServerRequestCreator;
use \Zend\HttpHandlerRunner\Emitter\SapiEmitter;

function createRequest(): Request {
    $psr17Factory = new Psr17Factory();

    $creator = new ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $req = $creator->fromGlobals();

    return $req;
}

function createResponse(): Response {
    return (new Psr17Factory())->createResponse();
}

function send(Response $res): void {
    // Try to set json content type
    if (!$res->hasHeader('Content-Type')) {
        $body = (string)$res->getBody();
        try {
            json_decode($body, false, 512, JSON_THROW_ON_ERROR);
            $res = $res->withAddedHeader('Content-Type', 'application/json; charset=utf-8');
        } catch (\Throwable $e) {}
    }

    (new SapiEmitter())->emit($res);
}

/**
 * Populate callback with request and response data from current request
 *
 * @param Closure $fn
 * @return void
 */
function createServer(Closure $fn): void {
    $req = createRequest();
    $res = createResponse();

    $fn($req, $res);
}

/**
 * Main logic
 *
 * @param Closure $fn
 * @return Closure
 */
function micro(Closure $fn): Closure {
    return function (Request $req, Response $res) use ($fn) {
        try {
            $res = $fn($req, $res);
    
            send($res);
        } catch (\Throwable $e) {
            $res = createResponse();
            $res = $res->withStatus(500);
            $res->getBody()->write('Internal Server Error');
    
            send($res);
    
            throw $e;
        }
    };
}
