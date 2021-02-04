# autoloader
A PSR compliant autoloader for PHP.

With this package you can autoload and require-manually ( optional ) the files of other packages, quickly and easily.

This package is for the times when Composer just isn't an option. Once in a blue moon there are conflicts, phpDocumentor warns us about this, and some packages flat out don't offer the Composer option. Composer and Fedora and this package actually share some ( not a lot ) commonality.

You can register packages to have files included at instantiation or have them inlcuded manually in case where that might be useful ( such as files that you know will be used ).

## installation

Installation is simply including the version safe index.php file.
```php
<?php

require '/path/to/Autoloader/src/index.php';

$al = new Pf\Autoloader\Autoloader;

...
```

## usage

As my usage example, I am autoloading Slim Framework, which actually does use Composer, but if you wanted to load it without Composer, you might do it this way.

```php
<?php

use Pf\Autoloader\Autoloader as Autoloader;

require_once '/path/to/Autoloader/src/index.php';

// Load Slim Framework

$al = Autoloader::factory( [ 'setVendor' => [ 'Slim' ], 'setBase' => [ '/path/to/Slim/Slim' ] ] );

$a1 = Autoloader::factory( [ 'setVendor' => [ 'Psr\Log' ], 'setBase' => [ '/path/to/Psr/Log/Log' ] ] );
$a1 = Autoloader::factory( [ 'setVendor' => [ 'Psr\Http\Message' ], 'setBase' => [ '/path/to/Psr/Http/Message/src' ] ] );
...dependencies etc

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

It appears that Slim Framework has these dependencies:

From the composer.json file

```json
{
    "require": {
        "php": "^7.2 || ^8.0",
        "ext-json": "*",
        "nikic/fast-route": "^1.3",
        "psr/container": "^1.0",
        "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0",
        "psr/http-server-handler": "^1.0",
        "psr/http-server-middleware": "^1.0",
        "psr/log": "^1.1"
    },
}
```

And here is the array for your convience:

```php
<?php

$dependency = [
//nikic/fast-route
    'FastRoute' => USERDIR . 'lib/vendor/FastRoute'
//psr/container
    'Psr\Container' => <directory> . 'src'
//psr/http-factory
    'Psr\Http\Message' => <directory> . 'src'
//psr/http-message
    'Psr\Http\Message' => 'lib/vendor/PsrHttpMessage/src'
//psr/http-server-handler
    'Psr\Http\Server' => 'lib/vendor/PsrHttpServerHandler/src'
//psr/http-server-middleware
    'Psr\Http\Server' => 'lib/vendor/PsrHttpServerMiddleware/src'
//psr/log
    'Psr\Log' => '/path/to/Psr/Log/Log'
];

And here is how I load:

```php
foreach ($dependency as $vendor => $directory ) {

    $al = new Autoloader;
    $al->setVendor( $vendor );
    $al->setBase( $directory );
}

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
