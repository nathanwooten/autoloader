<?php

/**
 * @link      http://github.com/nathanwooten/autoloader
 * @copyright Copyright (c) 2021 Nathan Wooten (http://www.profordable.com)
 * @license   MIT License (https://mit-license.org/)
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
     * The extended name/dir array
     *
     * @var array $name
     */

    public $name = [];


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

    public $registered = false;

    /**
     * The factory method, a callable you can
     * use to instantiate the autoloader
     *
     */

    public static function factory()
    {

        $instance = new static;

        $params = func_get_args();
        if ( ! empty( $params ) ) {
            $instance->configure( ...func_get_args() );
        }

        return $instance;

    }

    /**
     * Configure this instance of the autoloader
     *
     * @param array $configure
     */

    public function configure( array $configure = [] )
    {

        foreach ( $configure as $methodName => $params ) {
            $this->$methodName( ...$params );
        }

        if ( isset( $configure['classes'] ) ) {
            $classes = $configure['classes'];
            foreach ( $classes as $class ) {
                $this->autoload( $class );
            }
        }

        $registered = $this->register();
        if ( ! $registered ) {
            return false;
        }

    }

    /**
     * Load or autoload with this method,
     * this is the method to be provided
     * to the spl_autoload_register or
     * it can be used directly
     *
     * @param string $interface The fully-qualified class/interface/trait name.
     */

    public function load( string $interface )
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
     *
     * @param string $interface The class/interface/trait name to be instantiated
     *
     */

    public function locate( string $interface )
    {

        $interface = $this->normalize( $interface, false );

        $vendor = $this->normalize( $this->getVendor() );
        $directory = $this->normalize( $this->getDir() );

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
     * Set the vendor portion of the namespace declaration
     *
     * @param string $vendor 
     */

    public function setVendor( string $vendor )
    {

        $this->vendor = $vendor;

    }

    /**
     * Get the vendor portion of the namespace declaration
     * 
     * @param boolean $normalize
     */

    public function getVendor( $normalize = true)
    {

        $vendor = $this->vendor;

        if ( $normalize ) {
            $vendor = $this->normalize( $vendor );
        }

        return $vendor;

    }

    /**
     * Set the base directory of the vendor/package
     *
     * @param string $dir
     */


    public function setDir( $dir )
    {

        $this->dir = $dir;

    }

    /**
     * Get the base directory of the vendor/package
     *
     * @param boolean $normalize
     */

    public function getDir(  $normalize = true  )
    {

        $dir = $this->dir;

        if ( $normalize ) {
            $dir = $this->normalize( $dir );
        }

        return $dir;

    }

    /**
     * Set the file extension to be used by
     * files that will be included
     *
     * @param string $ext
     */

    public function setExt( $ext )
    {

        $this->ext = $ext;

    }

    /**
     * Get the file extension to be used by
     * files that will be included
     *
     */

    public function getExt()
    {

        $ext = $this->ext;
        $ext = '.' . ltrim( $ext, '.' );

        return $ext;

    }

    /**
     * Decide whether or not this autoloader,
     * will be prepended to the autoloader
     * queue, instead of the default ( append )
     *
     * @param boolean $prepend
     */

    public function setPreprend( $prepend ) {

        if ( $this->registered ) {
            return;
        }

        $this->prepend = (bool) $prepend;

    }

    /**
     * Get the prepend flag value
     *
     */

    public function getPrepend() {

        return $this->prepend;

    }

    /**
     * Normalize a string, to match the desired format
     *
     */

    public function normalize( $item, $append = true )
    {

        $item = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, rtrim( $item, DIRECTORY_SEPARATOR ) );

        if ( $append ) {
            $item .= DIRECTORY_SEPARATOR;
        }

        return $item;

    }

}
