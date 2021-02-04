# autoloader
A PSR compliant autoloader for PHP.

With this package you can autoload and require ( optional ) the files of other packages, quickly and easily.

This package is for the times when Composer just isn't an option. Once in a blue moon there are conflicts, phpDocumentor warns us about this, and some packages flat out don't offer the Composer option. Composer and Fedora and this package actually share some ( not a lot ) commonality.

You can register packages to have files included at instantiation or have them inlcuded manually in case where that might be useful ( such files that you know will be used ).

# installation

Installation is simply including the version safe index.php file.
```php
<?php

require '/path/to/Autoloader/src/index.php';

//now use

...
```

# usage

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
