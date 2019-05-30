<?php

declare(strict_types = 1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

use \Micro\Micro;

$server = new Micro();

$server->serve(function (Request $request, Response $response) {
    $path = $request->getUri()->getPath();
    $response->getBody()->write("{\"path\": \"{$path}\"}");

    return $response;
});
