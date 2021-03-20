<?php

namespace Pf\Autoloader;

use Exception;
use OutOfBoundsException;

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

$class = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );
if ( ! class_exists( $class ) ) {

class Autoloader
{

	protected $package = [];

	protected $vendorDir = [];

	protected static $instance;

	protected static $classes = [
		'Pf\Autoloader\AutoloaderPackage' => [
			'package'
		],
		'Pf\Autoloader\AutoloaderSpace' => [
			'space'
		]
	];

	protected function __construct() {}

	public static function getInstance( $vendorDir = '' )
	{

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $vendorDir );
		}

		$instance = self::$instance;

		if ( ! empty( $vendorDir ) ) {
			$instance->setVendorDir( $vendorDir );
		}

		return $instance;

	}

	public static function factory() {

		$factoryInput = func_get_args();
		if ( empty( $factoryInput ) ) {
			throw new OutofBoundsException( 'Please provide a class to instantiate' );
		}
		$class = array_shift( $factoryInput );
		if ( array_key_exists( $class, self::$classes ) ) {
		} else {
			foreach ( self::$classes as $qualified => $aliases ) {
				if ( in_array( $class, $aliases ) ) {
					$class = $qualified;
					break;
				}
			}
		}

		$args = empty( $factoryInput ) ? [] : $factoryInput;

		if ( ! array_key_exists( $class, self::$classes ) ) {
			throw new OutOfBoundsException( 'Provided class is not a Pf\Autoloader class' );
		}

		$obj = $class::factory( ...$args );

		if ( $obj ) {
			return $obj;
		}

		throw new Exception( 'Unable to create from provided class and args' );

	}

	public function package( string $basespace, string $basedir, $register = true, $vendorDir = '' ) {

		$package = self::factory( 'package', $basespace, $basedir, $register, $vendorDir );
		$this->setPackage( $package );

		if ( ! $this->hasBasespace( $basespace ) ) {
			throw new Exception( 'Unable to set package' );
		}

		return $package;

	}

	public function setPackage( AutoloaderPackage $package ) {

		$space = $package->getSpace();
		$basespace = $space->getName();

		$this->package[ $basespace ] = $package;

//var_dump( $package->getSpaceProperty() );

//		$name = $package->getSpaces();
/*
		if ( array_key_exists( $name, $this->package ) ) {
			throw new OutOfBoundsException( sprintf( 'Name already exists in the registry: ', $name ) );
		}

		$this->package[ $name ] = $package;
*/
	}

	public function getPackage( $basespace )
	{

		if ( $this->hasBasespace( $basespace ) ) {
			return $this->package[ $basespace ];
		}

	}

	public function register( $basespace, $prepend = false ) {

		$package = $this->getPackage( $basespace );
		$package->register( $prepend );

		return true;

	}

	public function setVendorDir( $vendorDir )
	{

		$vendorDir = realpath( $vendorDir );

		if ( $vendorDir ) {
			$space = AutoloaderSpace::factory( $vendorDir );
			$this->vendorDir[] = $space;
		}

	}

	public function getVendorDir( $name ) {

		$real = realpath( $name );
		if ( $real ) {
			$real = $real . DIRECTORY_SEPARATOR;

			return $real;
		}

		$nameType = false !== strpos( trim( $name, '\\/' ), DIRECTORY_SEPARATOR ) ? 'complex' : 'simple';

		if ( 'simple' === $nameType ) {
			foreach ( $this->vendorDir as $space ) {

				$dir = rtrim( $space->getDir(), '\\' );
				$parts = explode( '\\', $dir );
				$spaceName = array_pop( $parts );

				if ( 0 === strcasecmp( $name, $spaceName ) ) {
					return $space;
				}
			}

		} else {
			foreach ( $this->vendorDir as $space ) {

				$dir = trim( $space->getDir(), '\\' );
				$parts = array_reverse( explode( '\\', $dir ) );

				$spaceName = '';
				foreach ( $parts as $key => $part ) {

					$postfix = 0 === $key ? '' : DIRECTORY_SEPARATOR . $spaceName;

					$spaceName = $part . $postfix;
					if ( 0 === strcasecmp( $name, $spaceName ) ) {
						return $space;
					}
				}
			}
		}

		return '';

	}

	public function hasBasespace( string $basespace )
	{

		return array_key_exists( $basespace, $this->package );

	}

}
}
