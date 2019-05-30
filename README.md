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

use \Micro\Micro;

$server = new Micro();

$server->serve(function (Request $request, Response $response) {
    $response->getBody()->write("Hello World.");
    return $response;
});
```

## Thanks

Thanks to zeit for making micro.
