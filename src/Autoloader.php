<?php

namespace Pf\Autoloader;

use function array_key_exists;
use function array_values;
use function basename;
use function class_exists;
use function count;
use function file_exists;
use function is_callable;
use function ltrim;
use function rtrim;
use function spl_autoload_register;
use function str_replace;

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

class Autoloader {


    /** @property boolean            $init Flag to determine whether or not the autoloader has been registered with spl_autoload_register */

    protected $init = false;

    /** @property string            $exists The function used to check if a file exists */

    protected $exists = 'file_exists';

    /** @property string            $vendor The namespace of the vendor/package */

    public $vendor;

    /** @property directory         $directory The directory of the vendor/package */

    public $directory;

    /** @property string            $extension The extension to be used when including files */

    public $extension = '.php';

    public static function factory()
    {

        $instance = new static;

        $params = func_get_args();
        if ( ! empty( $params ) ) {
            $instance->load( ...func_get_args() );
        }

        return $instance;

    }

    public function load( string $vendor = null, string $base = null, $classes = [], $register = true )
    {

        if ( $register ) {
            $registered = $this->register();
            if ( ! $registered ) {
                return false;
            }
        }

        if ( isset( $vendor ) ) {
            $this->setVendor( $vendor );
        }

        if ( isset( $base ) ) {
            $this->setBase( $base );
        }

        if ( ! empty( $classes ) ) {
            foreach ( $classes as $class ) {
                $this->autoload( $class );
            }
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

    public function setVendor( $vendor )
    {

        $this->vendor = $vendor;

    }

    public function getVendor()
    {

        return $this->vendor;

    }

    public function setBase( $directory )
    {

        $this->directory = $directory;

    }

    public function getBase()
    {

        return $this->directory;

    }

    public function autoload( $interface )
    {

        $file = $this->locate( $interface );
        if ( $file ) {

            require_once $file;

            return $interface;
        }

        return false;

    }

    public function locate( $interface )
    {

        $vendorName = $this->normalize( $this->getVendor() );
        $directory = $this->normalize( $this->getBase() );
        $interface = $this->normalize( $interface, false );

        $extension = $this->getExtension();

        $file = str_replace(

            $vendorName,
            $directory,

            $interface

        ) . $extension;

            $exists = $this->exists;
        if ( $exists( $file ) ) {

            return $file;
        }

    }

    public function autoloadArray( array $array, $values = true )
    {

        $interfaces = [];

        if ( ! $values ) {
            $array = array_values( $array );
        }

        for ( $i = 0; $i < count( $array ); ++$i ) {

                $interface = $array[$i];

            $status[$interface] = $this->autoload( $interface );
        }

        return $status;

    }

    public function setExtension( $extension )
    {

        $extension = '.' . ltrim( $extension, '.' );
        $this->extension = $extension;

    }

    public function getExtension()
    {

        return $this->extension;

    }

    public function normalize( $item, $append = true )
    {

        $item = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, $item );
        $item = rtrim( $item, DIRECTORY_SEPARATOR );
        if ( $append ) {
            $item .= DIRECTORY_SEPARATOR;
        }

        return $item;

    }

    public function getInstance( $vendor )
    {

        if ( array_key_exists( $vendor, static::$instance ) ) {

            $autoloader = static::$instance[$vendor];
            return $autoloader;
        }

    }

    public function hasInstance( $vendor ) {
    
        return array_key_exists( $vendor, static::$instance );
        
    }

}
