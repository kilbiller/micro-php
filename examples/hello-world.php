<?php

declare(strict_types = 1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

use function \micro\{createServer, micro};

createServer(micro(function (Request $req, Response $res) {
    $res->getBody()->write("Hello World.");
    return $res;
}));
