<?php

namespace Pf\Autoloader;

use Pf\Autoloader\AutoloaderSpace;

use function spl_autoload_register;

use Exception;

$class = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );
if ( ! class_exists( $class ) ) {

class AutoloaderPackage extends AutoloaderAbstract
{

	protected $space;

	protected $registered = false;

	public function __construct( string $basespace, string $baseDir, $register = true, string $vendorDir = '' )
	{
/*
		$this->setVendorDir( $vendorDir );

		$this->setBasespace( $basespace );
		$this->setDir( $dir );
*/

		if ( Autoloader::getInstance()->hasBasespace( $basespace ) ) {
			throw new Exception( 'This package already exists' );
		}
/*
		$adir = AutoloaderDir::factory();
		$space = $adir->spaceSetup( $basespace, $baseDir, $vendorDir );

		$this->space = $space;
*/

		$realBase = realpath( $baseDir );

		if ( $realBase ) {}
		else {
			if ( ! empty( $vendorDir ) ) {

				$realVendor = realpath( $vendorDir );
				if ( $realVendor && is_readable( $realVendor . DIRECTORY_SEPARATOR . $baseDir ) ) {
					$baseDir = $realVendor . DIRECTORY_SEPARATOR . $baseDir;
				} else {
					$space = Autoloader::getInstance()->getVendorDir( $vendorDir );
					$alVendorDir = $space->getDir();

					if ( $alVendorDir === $vendorDir ) {
						throw new Exception;
					} else {
						$realVendor = realpath( $alVendorDir );
						if ( $realVendor && is_readable( $realVendor . DIRECTORY_SEPARATOR . $baseDir ) ) {
							$baseDir = $realVendor . DIRECTORY_SEPARATOR . $baseDir;
						}
					}
				}
			}
		}

		$this->space( $basespace, $baseDir );

		if ( $register ) {
			$this->register( 1 === $register ? true : false );
		}

	}

	public function register( $prepend = false ) {

		spl_autoload_register( [ $this, 'load' ], true, $prepend );

		$this->registered = true;

	}

	public function load( $interface ) {

		if ( is_object( $interface ) ) {
			if ( $interface instanceof AutoloaderInterface ) {
				$interface = $interface->getQualified();
			} else {
				throw new Exception( 'Unable to use provided value' );
			}
		}

		if ( is_array( $interface ) ) {
			foreach ( $interface as $key => $ntrface ) {
				if ( is_object( $ntrface ) ) {
					$interface[$key] = $this->load( $interface );
				}
			}
		} else {
			$interface = [ $interface ];
		}

		$interfaces = $interface;

		foreach ( $interfaces as $interface ) {

			$file = $this->locate( $interface );

			if ( $file ) {
				require_once $file;
				return true;
			}

			throw new Exception( 'Unable to locate interface file: ' . $interface . ': ' . $file );
		}

	}

	public function locate( string $interface ) {

		if ( class_exists( $interface, false ) ) {
			return true;
		}

		$space = $this->getSpace();
		$basespace = $space->getName();

		if ( 0 !== strpos( $interface, $basespace ) ) {
			throw new OutOfBoundsException( 'Interface does not match package' );
		}

		$file = '';

		$parts = '';
		$interfaceParts = explode( '\\', $interface );
		$classname = array_pop( $interfaceParts );

		$set = '';
		foreach ( $interfaceParts as $part ) {
			$parts .= ( ! $parts ? '' : '\\' ) . $part;
			$space = $this->getSpace( $parts );

			if ( $space ) {
				$file .= $space->getDir();
				$set .= $parts;
				$parts = '';
			}
		}

		$interfaceString = implode( '', $interfaceParts );
		$remainder = str_replace( $set, '', $interfaceString );

		$file .= $remainder;

		$file .= '\\' . $classname . '.php';

		if ( file_exists( $file ) ) {
			return $file;
		}

		return false;

/*
		$interfaceParts = explode( '\\', $interface );

		$nameSoFar = '';
		$hasBaseSpace = false;

		foreach ( $interfaceParts as $part ) {

			if ( ! $hasBasepace ) {
				$nameSoFar .= '\\' . $part;
				if ( $basespace === $nameSoFar ) {
					$file .= $nameSoFar;
					$nameSoFar = '';
					$hasBasespace = true;
				}
			} else {
				$nameSoFar .= '\\' . $part;
			}
		}
*/

	}

	public function space( $name, $dir, $quiet = false, $parent )
	{

		$space = Autoloader::factory( 'space', $dir, $name, $quiet, $parent );
		$this->setSpace( $space );

		return $space;

	}

	public function getSpace( $name = null )
	{

		if ( is_null( $name ) ) {

			if ( empty( $this->space ) ) {
				return [];
			}

			res
			$space = current( $this->space );
			return $space;
		}

		return array_key_exists( $name, $this->space ) ? $this->space[ $name ] : null;

	}

	public function getSpaces( $type = 'name' )
	{

		$result = '';
		$getter = 'get' . ucwords( $type );

		foreach ( $this->space as $key => $space ) {
			$prefix = $key > 0 ? DIRECTORY_SEPARATOR : '';

			$result .= $prefix . $space->$getter();
		}

		return $result;

	}

	public function getSpaceProperty()
	{

		return $this->space;

	}

	public function isRegistered()
	{

		return $this->registered;

	}

}
}
