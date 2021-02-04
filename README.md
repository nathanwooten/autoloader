# The profordable.com Autoloader
A very simple PSR-4 autoloader. Loads any PSR-4 compliant package ( recursive ) with just a vendor name and directory.

---
With this package you can autoload and require-manually ( optional ) the files of other packages, quickly and easily.

Any package that complies with the PSR-4 standard, can be loaded by providing a vendor name ( the vendor/package part of the namespace ) and directory ( usually the src directory one level deep within the package root ).

You do not have to use any more information than a vendor name and a single directory for the recursion. No providing directories necessary. No heavy directory searches.

## Installation

```php
<?php

//Installation is simply including the version safe index.php file
require USERDIR . DS . 'lib' . DS . 'vendor' . DS . 'ProfordableAutoloader' . DS . 'index.php';

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
require_once USERDIR . DS 'lib' . DS . 'vendor' . DS . 'Autoloader' . DS . 'src' . DS . 'index.php';

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
    // website/src/page folder
$pageController = 'Website\Page\\' . $page;
$controller = new $pageController( ...$params );

$controller->run();

```
