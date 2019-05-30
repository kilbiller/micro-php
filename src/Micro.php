<?php

declare(strict_types = 1);

namespace Micro;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Closure;

use \Nyholm\Psr7\Factory\Psr17Factory;
use \Nyholm\Psr7Server\ServerRequestCreator;
use \Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class Micro {
    private $request;
    private $response;

    private function createRequest(): Request {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        $request = $creator->fromGlobals();

        return $request;
    }

    private function createResponse(): Response {
        return (new Psr17Factory())->createResponse();
    }

    public function __construct() {
        $this->request = $this->createRequest();
        $this->response = $this->createResponse();
    }

    private function send(Response $response): void {
		// Try to set json content type
		if (!$response->hasHeader('Content-Type')) {
			$body = (string)$response->getBody();
			try {
				json_decode($body, false, 512, JSON_THROW_ON_ERROR);
				$response = $response->withAddedHeader('Content-Type', 'application/json; charset=utf-8');
			} catch (\Throwable $e) {}
		}

        (new SapiEmitter())->emit($response);
    }

    public function serve(Closure $callable): void {
		try {
			$response = $callable($this->request, $this->response);

			$this->send($response);
		} catch (\Throwable $e) {
			$response = $this->createResponse();
			$response = $response->withStatus(500);
			$response->getBody()->write('Internal Server Error');

			$this->send($response);

			throw $e;
		}
    }
}
