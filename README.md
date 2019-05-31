# Micro

Micro is a PHP micro-framework primarly designed to work with microservices. It is heavily inspired by zeit/micro (basically a port).

## Highlights

* < 100 lines of code
* Uses psr7 http request/response so no need to learn anything new
* Modern (Fully typed, Requires PHP >= 7.3)

## Installation

```bash
composer require kilbiller/micro
```

## Usage

```php
<?php

declare(strict_types = 1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use function \micro\{createServer, micro};

createServer(micro(function (Request $req, Response $res) {
    $res->getBody()->write("Hello World.");
    return $res;
}));
```

## Advanced

It's possible to use a custom psr7 implementation by implementing your own createServer.

$fn => $fn($req, $res)

## Thanks

Thanks to zeit for making micro.
