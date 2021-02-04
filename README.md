# The profordable.com Autoloader
A PSR compliant autoloader for PHP

---

With this package you can autoload and require-manually ( optional ) the files of other packages, quickly and easily.

This package is for the times when Composer just isn't an option. Once in a blue moon there are conflicts, phpDocumentor warns us about this, and some packages flat out don't offer the Composer option. Composer and Fedora and this package actually share some ( not a lot ) commonality.

You can register packages to have files included at instantiation or have them inlcuded manually in case where that might be useful ( such as files that you know will be used ).

---

## Installation

Installation is simply including the version safe index.php file.
```php
<?php

require '/path/to/Autoloader/src/index.php';

$al = new Pf\Autoloader\Autoloader;

...
```

---

## Usage

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

---

## Extra

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

Here are the links to the repositories on GitHub:

 - [nikic/fast-route](https://github.com/nikic/FastRoute)
 - [psr/container](https://github.com/php-fig/container)
 - [psr/http-factory](https://github.com/php-fig/http-factory)
 - [psr/http-message](https://github.com/php-fig/http-message)
 - [psr/http-server-handler](https://github.com/php-fig/http-server-handler)
 - [psr/http-server-middleware](https://github.com/php-fig/http-server-middleware)
 - [psr/log](https://github.com/php-fig/log)
 
Here are the links to the repositories on The Packagist:

 - [nikic/fast-route](https://packagist.org/packages/nikic/fast-route)
 - [psr/container](https://packagist.org/packages/psr/container)
 - [psr/http-factory](https://packagist.org/packages/psr/http-factory)
 - [psr/http-message](https://packagist.org/packages/psr/http-message)
 - [psr/http-server-handler](https://packagist.org/packages/psr/http-server-handler)
 - [psr/http-server-middleware](https://packagist.org/packages/psr/http-server-middleware)
 - [psr/log](https://packagist.org/packages/psr/log)

And here is the array for your convience:

```php
<?php

if ( ! defined( 'DS', DIRECTORY_SEPARATOR ) ) define( 'DS', DIRECTORY_SEPARATOR );

$depDirectory = USERDIR . 'lib' . DS . 'vendor' . DS;


// folders are given as downloaded from github

$dep = [
//nikic/fast-route
    'FastRoute' => $depDirectory . 'FastRoute-master\FastRoute-master\src'
//psr/container
    'Psr\Container' => $depDirectory . 'src'
//psr/http-factory
    'Psr\Http\Message' => $depDirectory . 'src'
//psr/http-message
    'Psr\Http\Message' => $depDirectory . 'src'
//psr/http-server-handler
    'Psr\Http\Server' => $depDirectory . 'src'
//psr/http-server-middleware
    'Psr\Http\Server' => $depDirectory . ''src'
//psr/log
    'Psr\Log' => $depDirectory . 'Log'
];

And here is how I load:

```php
foreach ($dep as $vendor => $directory ) {

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

Then again if you want just use Composer for Slim, then you can use this on the command line, and leave the direct autoloading
to packages that you can't find on [packagist.org](https://packagist.org), such as the classes and domains necessary to build a for a website.

```
composer require slim/slim "^3.0"
```

For example, I might load my website like so:

```php
<?php

new Autoloader( 'Website', USERDIR . DS . 'lib' . DS . 'src' );

```
