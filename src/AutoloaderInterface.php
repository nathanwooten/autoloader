<?php

/**
 * @link      https://github.com/nathanwooten/autoloader
 * @copyright Copyright (c) 2021 Nathan Wooten
 * @license   MIT License (https://mit-license.org/)
 */

namespace nathanwooten\Autoloader;

use function basename;
use function interface_exists;

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'index.php';

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( interface_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

interface AutoloaderInterface {}
