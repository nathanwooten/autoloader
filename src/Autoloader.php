<?php

namespace Pf\Autoloader;

	global $classLoaded;
	$classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded ) ) {

	unset( $classLoaded );
	return;
}

class Autoloader {

	protected $init = false;
	protected $exists = 'file_exists';

	public $vendor;
	public $directory;

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

			$configure = empty( $args ) ? [] : $args[0];
			if ( ! empty( $configure ) ) {
				$this->configure( $configure );
			}

			spl_autoload_register( [ $this, 'autoload' ] );

			$this->init = true;

		}

		return $this;

	}


	public function configure( array $config )
	{

		$result = [];

		foreach ( $config as $methodName => $args ) {

			$callback = [ $this, $methodName ];
			if ( ! is_callable( $callback ) ) {

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

		if ( ! $this->init ) {
			$this->init();
		}

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

		$file = str_replace(

			$vendorName,
			$directory,

			$interface

		) . '.php';

			$exists = $this->exists;
		if ( $exists( $file ) ) {

			return $file;
		}

	}

	public function autoloadArray( array $array )
	{

		$interfaces = [];

		while( $array ) {
			$interface = array_shift( $array );

			$interfaces[] = $this->autoload( $interface );
		}

		return $interfaces;

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

}
