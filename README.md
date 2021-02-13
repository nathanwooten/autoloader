# The profordable.com Autoloader
A very simple PSR-4 autoloader. Loads any PSR-4 compliant package ( recursive ) with just a vendor name and directory.

---
With this package you can autoload and require-manually ( optional ) the files of other packages, quickly and easily.

Any package that complies with the PSR-4 standard, can be loaded by providing a vendor name ( the vendor/package part of the namespace declaration ) and directory ( usually the src directory one level deep within the package root ).

You do not have to use any more information than a vendor name and a single directory for the recursion. No providing directories necessary. No heavy directory searches.

## Installation

As far as installation goes, you can use Composer:

### Composer

On the command line (this or some version of this):

```
composer require nathanwooten/autoloader
```

In your code:

```
require '\composer\vendor\autoload.php';
```

### Manual Installation

or you can just install manually

```php
<?php

require USERDIR . '\lib\vendor\Autoloader\index.php';

$al = new Pf\Autoloader\Autoloader;
...
```

---

## Usage

As my usage example, I am autoloading my website.

```php
<?php

use Pf\Autoloader\Autoloader as Autoloader;

require_once USERDIR . DS 'lib' . DS . 'vendor' . DS . 'Autoloader' . DS . 'src' . DS . 'index.php';

$al = new Autoloader
$al->setVendor( 'Website' );
$al->setDir( USERDIR . '\lib\src );

$ctrl = new Website\Controller\ControllerResolver( $_SERVER['REQUEST_URI'] );
$ctrl->run();

```
