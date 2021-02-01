<?php

namespace Pf\Autoloader;

use function file_exists;

	global $classLoaded;
	$classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

	unset( $classLoaded );
	return;
}

class Autoloader {

	protected $init = false;
	protected $exists = 'file_exists';

	public $vendor;
	public $directory;

	public $extension = '.php';

	public function load( $vendor, $directory ) {

		$this->setVendor( $vendor );
		$this->setBase ( $directory );

		$this->init();

	}

	public static function factory()
	{

		$instance = new static;
		$instance->init( ...func_get_args() );

		return $instance;

	}

	public function init() {

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

	public function autoload( $interface ) {

		$file = $this->locate( $interface );
		if ( $file ) {

			require_once $file;

			return $interface;
		}

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

	public function autoloadArray( array $array )
	{

		$interfaces = [];

		while( $array ) {

			$interfaces[] = $this->autoload( array_shift( $array ) );
		}

		return $interfaces;

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
