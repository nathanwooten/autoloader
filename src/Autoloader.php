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

    public function load( $vendor, $directory, $classes = [] )
	{

        $this->init();

        $this->setVendor( $vendor );
        $this->setBase ( $directory );

		if ( ! empty( $classes ) ) {
			$classes = $this->autoloadArray( $classes );
		}

		if ( empty( $classes ) ) $classes = true;

		return $classes;

    }

    public static function factory()
    {

        $instance = new self;
        $instance->init( ...func_get_args() );

        return $instance;

    }

    public function init()
	{

        if ( ! $this->init ) {

                $args = func_get_args();
            if ( !empty( $args ) ) {

                $this->configure( $args[0] );
            }

            spl_autoload_register( [ $this, 'autoload' ] );

            $this->init = true;

        }

        return $this;

    }

    public function configure( array $config, $callable = true )
    {

        $result = [];

        foreach ( $config as $methodName => $args ) {

            $callback = [ $this, $methodName ];
            if ( ! $callable && ! is_callable( $callback ) ) {

                 return false;
            }

            $result[] = $callback( ...array_values( $args ) );
        }

        return $result;

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

}
