# autoloader
A PSR compliant autoloader for PHP.

With this package you can autoload/load the files of other packages, quickly and easily.

Usage:

```php
<?php

use Pf\Autoloader\Autoloader as Autoloader;

require_once '/path/to/Autoloader/src/index.php';

// Load Slim Framework

$al = Autoloader::factory( [ 'setVendor' => [ 'Slim' ], 'setBase' => [ '/path/to/Slim/src' ] ] );

$a1 = Autoloader::factory( [ 'setVendor' => [ 'PSR' ], 'setBase' => [ '/path/to/PSR/src' ] ] );

// Use Slim Framework

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();
```
