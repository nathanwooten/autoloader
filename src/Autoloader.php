<?php

/**
 * @link      https://github.com/nathanwooten/autoloader
 * @copyright Copyright (c) 2021 Nathan Wooten
 * @license   MIT License (https://mit-license.org/)
 */

namespace nathanwooten\Autoloader;

use nathanwooten\Autoloader\{

	AutoloaderInterface,
	AutolaoderPackage

};

use Exception;

use function basename;
use function array_key_exists;
use function file_exists;
use function rtrim;
use function spl_autoload_register;
use function str_replace;

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'index.php';

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

class Autoloader implements AutoloaderInterface {

	protected $library;
	protected $package = [];

	public $namespace = null;

	public function __construct( $libraryDirectory = null )
	{

		$this->library = $libraryDirectory;

		spl_autoload_register( [ $this, 'load' ], true, true );

	}

	public function package( $namespace, $directory ) {

		$namespace = $this->normalize( $namespace );

		if ( ! array_key_exists( $namespace, $this->package ) ) {

			$this->package[ $namespace ] = new AutoloaderPackage( $this, $namespace, $directory );
		}

		$package = $this->package[ $namespace ];
		return $package;

	}

	public function packageArray( array $packageArray )
	{

		$packages = [];

		foreach ( $packageArray as $namespace => $directory ) {
			$packages[ $namespace ] = $this->package( $namespace, $directory );
		}

		return $packages;

	}

 	public function load( string $interface )
	{

		$file = $this->locate( $interface );
		if ( $file ) {

			$package = $this->getPackage( $this->namespace );
			$package->createInterface( $interface, $file );
		}

	}

	public function locate( $interface )
	{

		$packages = $this->package;
		$interface = $this->normalize( $interface, false );

		foreach ( $packages as $namespace => $package ) {

			$namespace = $this->normalize( $namespace );
			$directory = $this->getDirectory() . $package->getDirectory();

			$this->namespace = $namespace;

			$file = str_replace( $namespace, $directory, $interface ) . '.php';
			if ( file_exists( $file ) ) {
				return $file;
			}
		}

	}

	public function getDirectory()
	{

		return $this->library;

	}

	public function getPackage( $namespace )
	{

		$namespace = $this->normalize( $namespace );
		if ( array_key_exists( $namespace, $this->package ) ) {
			return $this->package[ $namespace ];
		}

	}

	public function normalize( $item, $append = true, $separator = DIRECTORY_SEPARATOR )
	{

 		$item = str_replace( ['\\', '/'], $separator, rtrim( $item, $separator ) );

  		if ( $append ) {
			$item .= $separator;
		}

		return $item;

	}

}
