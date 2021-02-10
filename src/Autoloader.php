<?php

/**
 * @link      http://github.com/nathanwooten/autoloader
 * @copyright Copyright (c) 2021 Nathan Wooten (http://www.profordable.com)
 * @license   MIT License
 */

namespace Pf\Autoloader;

use function str_replace;

use function basename;
use function class_exists;
use function file_exists;
use function ltrim;
use function rtrim;
use function spl_autoload_register;

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

class Autoloader {


    /**
     * The namespace of the vendor/package
     *
     * @var string $vendor
     */

    public $vendor;

    /**
     * The base directory of the vendor/package
     *
     * @var string $base
     */

    public $dir;

    /**
     * The extension to be used when including files
     *
     * @var string $ext
     */

    public $ext = 'php';

    /**
     * Prepend the autoloader ( the vendor ) in PHP's autoloader queue
     *
     * @var string $prepend
     */

    public $prepend = false;

    /**
     * Indicates that the autoloader has been registered or not
     *
     * @var boolean $registered
     */

    protected $registered = false;

    /**
     * The factory method, a callable you can
     * use to instantiate the autoloader, also
     * calls load.
     *
     */

    public static function factory()
    {

        $instance = new static;

        $params = func_get_args();
        if ( ! empty( $params ) ) {
            $instance->load( ...func_get_args() );
        }

        return $instance;

    }

    /**
     * This is the main loading function. You can provide,
     * vendor
     * Th
    
    
    
    public function load( $configure = [], $register = true )
    {

        foreach ( $configure as $methodName => $params ) {
            $this->$methodName( ...$params );
        }

        if ( $register ) {
            $registered = $this->register();
            if ( ! $registered ) {
                return false;
            }
        }

        if ( isset( $configure['classes'] ) ) {
            $classes = $configure['classes'];
            foreach ( $classes as $class ) {
                $this->autoload( $class );
            }
        }

    }

    public function autoload( string $interface )
    {

        $file = $this->locate( $interface );
        if ( $file ) {
            require_once $file;

            return $interface;
        }

        return false;

    }

    /**
     * This is where the magic happens,
     *
     * Example
     *
     *    Interface:            Pf\Application\Router\Router
     *
     *    Preset:
     *
     *        Vendor:            Pf\Application
     *
     *            replace above with below
     *
     *        Directory:        \path\to\Application\src
     *
     *        ---
     *
     *        Extension:        .php
     *
     *    File Pathname:        \path\to\Application\src\Router\Router.php

     */

    public function locate( string $interface )
    {

        $interface = $this->normalize( $interface, false );

        $vendor = $this->getVendor();
        $directory = $this->getDir();

        $extension = $this->getExt();

        $file = str_replace(

            // replace vendor with directory
            $vendor,
            $directory,

            // in interrace
            $interface

        ) . $extension;

        if ( file_exists( $file ) ) {

            return $file;
        }

    }

    /**
     * Registers the autoloader in the autoloader queue
     *
     */

    public function register() {

        if ( ! $this->registered ) {
            $this->registered = spl_autoload_register( [ $this, 'autoload'], false, $this->prepend );
        }

    }

    /**
     * Set the vendor portion of the namespace
     *
     * @param string $vendor 
     *
     */

    public function setVendor( string $vendor )
    {

        $this->vendor = $vendor;

    }

    public function getVendor( $normalize = true )
    {

        $vendor = $this->vendor;

        if ( $normalize ) {
            $vendor = $this->normalize( $vendor );            
        }

        return $vendor;

    }

    public function setDir( $dir )
    {

        $this->dir = $dir;

    }

    public function getDir( $normalize = true )
    {

        $dir = $this->dir;
        
        if ( $normalize ) {
            $dir = $this->normalize( $dir );
        }

        return $dir;

    }

    public function setExt( $ext )
    {

        $this->ext = $ext;

    }

    public function getExt()
    {

        $ext = $this->ext;
        $ext = '.' . ltrim( $ext, '.' );

        return $ext;

    }

    public function setPreprend( $prepend ) {

        if ( $this->registered ) {
            return;
        }

        $this->prepend = (bool) $prepend;

    }

    public function getPrepend() {

        return $this->prepend;

    }

    public function normalize( $item, $append = true )
    {

        $item = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, rtrim( $item, DIRECTORY_SEPARATOR );

        if ( $append ) {
            $item .= DIRECTORY_SEPARATOR;
        }

        return $item;

    }

}
