# The profordable.com Autoloader
A PSR compliant autoloader for PHP, companion to Composer.

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














As my usage example, I am autoloading my website.

```php
<?php

use Pf\Autoloader\Autoloader as Autoloader;

if ( ! defined( 'DS', DIRECTORY_SEPARATOR ) ) define( 'DS', DIRECTORY_SEPARATOR );

//require_once '/path/to/Autoloader/src/index.php';
require_once $dir . 'Autoloader' . DS . 'src' . DS . 'index.php';

new Autoloader( 'Website', USERDIR . DS. 'lib' . DS . 'src' );

now my controller might do something like this:

$urlPath = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

if ( '/' === $urlPath ) {

    $controller = new Website\Page\Home;

} else {

    $controller = new Website\Page\Page( $urlPath );

}

```
