# The profordable.com Autoloader
A very simple PSR-4 autoloader. Loads any PSR-4 compliant package with just a vendor name and directory.

---
With this package you can autoload and require-manually ( optional ) the files of other packages, quickly and easily.

Any package that complies with the PSR-4 standard, can be loaded by providing a vendor name ( the vendor/package part of the namespace ) and directory ( usually the src directory one level deep within the package root ).


If you are not using Composer, or are using Composer and also using packages that don't, then you can use this autoloader to easily load your PSR-4 compliant namespacing/foldering. Basically, in a PSR-4 namespaced package, the path to a file is str_replace( '<namespace>', '<directory>', '<interface>' ). The namespace and directory are provided up front. The interface is provided when a class is requested during instantiation.

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

As my usage example, I am autoloading my website.

```php
<?php

use Pf\Autoloader\Autoloader as Autoloader;

if ( ! defined( 'DS', DIRECTORY_SEPARATOR ) ) define( 'DS', DIRECTORY_SEPARATOR );
//require_once '/path/to/Autoloader/src/index.php';
require_once $dir . 'Autoloader' . DS . 'src' . DS . 'index.php';

new Autoloader( 'Website', USERDIR . DS. 'lib' . DS . 'src' );

// now my controller might do something like this:
$urlPath = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
if ( '/' === $urlPath ) {
    $page = 'Home';
    $params = [];
} else {
    $page = 'Page'
    $params = [ $urlPath ];
}
$pageController = 'Website\Page\\' . $page;
$controller = new $pageController( ...$params );

$controller->run();

```
