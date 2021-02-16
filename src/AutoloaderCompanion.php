<?php

namespace Nw\Autoloader;

use Exception;

use Nw\Autoloader\{
	Autoloader,
	AutoloaderContextInterface
};

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

class AutoloaderCompanion {

	protected $context;

	protected $defaults = [
		'setVendor' => '%s\\%s',
		'setDir' => '%s\\%s'
	];

	protected $autoloader = [];

	public function __construct( AutoloaderContextInterface $context )
	{

		$this->context = $context;

		$this->autoload();

	}

	public function autoload()
	{

		foreach ( $this->getContext()->getQueue() as $alias ) {

			$config = $this->context->getConfig( $alias );
			$config = require $config;
			extract( $config );

			$this->register( $alias );
			$this->load( $classes, $alias );

		}

	}

	public function getContext() {

		return $this->context;

	}

	protected function register( $package ) {

		$configure = [];
		$configure[0] = ['setVendor', [ $this->setVendor( $package ) ] ];
		$configure[1] = ['setDir', [ $this->setDir( $package ) ] ];

/*
		$configure = [ [ 'setVendor', [] ], [ 'setDir', [] ] ];
		$configure = array_map(
			function ( $callableArray ) use ( $package ) {

				$methodName = $callableArray[0];
				if ( is_callable( [ $this, $methodName ] ) ) {

						$value = $this->$methodName( $package );
var_dump( $value );
					return [ $methodName, [ $value ] ];
				}
			},
			$configure
		);
var_dump( $configure );
*/
		$al = Autoloader::factory( $configure );
		$al->register();

		$this->autoloader[ $package ] = $al;

		$this->register[] = $package;

	}

	protected function load( array $classes, $package ) {

		$al = $this->getAutoloader( $package );

		foreach ( $classes as $class ) {



		}



		$configure = array_map(
			function ( $class ) {
				$configureCallableArray = [ 'locate', [ $class ] ];
				return $configureCallableArray;
			},
			$classes
		);
//var_dump( $configure );
//var_dump( $package );
		$al = $this->getAutoloader( $package );
//var_dump( $al );
		$require_once = $al->configure( $configure );

		foreach ( $require_once as $key => $require ) {
			$file = $require['locate'];
			if ( $file ) {
				require_once $file;
			}
			else {
				throw new Exception( 'Unable to locate class in file: ' . $file );
			}
		}

	}

	protected function setVendor( $package ) {

		$vendor = $this->context->getVendor();
		$package = static::normalizeName( $package );

		$vendor = sprintf( $this->defaults['setVendor'], [ $vendor, $package ] );
var_dump( $vendor );

		return $vendor;

	}

	protected function setDir( $package ) {

		$dir = $this->context->getDir();
		$package = static::normalizeName( $package );

		$dir = sprintf( $this->defaults['setDir'], [ $dir, $package ] );

//		$dir = static::sprint( $this->defaults['setDir'], ...array_values( get_defined_vars() ) );
		return $dir;

	}

	protected function getAutoloader( $package )
	{

		return array_key_exists( $package, $this->autoloader ) ? $this->autoloader[$package] : null;

	}
/*
	public static function sprintf_( $message, array $params = [], $normalize = false, string $fill = '' ) {

		if ( $normalize ) {

			$count = 0;
			while ( false !== strpos( $string, '%s' ) ) {

				$string = substr( $string, strpos( $string, '%s' ) +2 );
				$count++;
			}

			$paramCount = count( $params );
			if ( count( $params ) > $count ) {

				$params = array_slice( $params, 0, $count );

			} elseif ( count( $params ) < $count ) {

				$params = array_merge( $params, array_fill( $fill, $count - count( $params ) ) );

			}

		}

		return vsprintf( $message, $params );

	}
*/
	public static function normalizeName( $name ) {

		$nameArray = explode( '\\', trim( $name, '\\' ) );
		foreach ( $nameArray as $key => $nameString ) {

			$nameArray[$key] = ucwords( $nameString );
		}

		$normal = implode( '\\', $nameArray );
		return $normal;

	}

}
