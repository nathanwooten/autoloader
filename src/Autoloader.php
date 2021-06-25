<?php

namespace nathanwooten\Autoloader;

use nathanwooten\Filter\{

	Filter

};

use Exception;

use function file_exists;
use function in_array;
use function is_array;
use function is_dir;
use function rtrim;
use function scandir;
use function spl_autoload_register;
use function str_replace;

class Autoloader {

	public $package = [];
	public $library = null;

	public $multi = [
		''
	];

	public function __construct( $libraryDirectory = null )
	{

		$this->library = $libraryDirectory;

		spl_autoload_register( [ $this, 'load' ], true, true );

	}

	public function set( $namespace, $directory ) {

		if ( ! isset( $this->package[ $namespace ] ) ) {
			$this->package[ $namespace ] = [];
		}

		$this->package[ $namespace ][] = $directory;

	}

	public function setArray( array $array )
	{

		$result = [];

		foreach ( $array as $namespace => $directories ) {

			$result[ $namespace ] = [];

			foreach ( $directories as $directory ) {

				$result[ $namespace ][] = $this->set( $namespace, $directory );
			}
		}

		return $result;

	}

	public function load( $interface )
	{

		$require_once = $this->locate( $interface );
		if ( $require_once ) {

			require_once $require_once;

			return $interface;
		}

	}

	public function locate( $interface )
	{

		$packages = $this->package;

		foreach ( $packages as $namespace => $directory ) {

			if ( ! is_array( $directory ) ) {
				$directory = (array) $directory;
			}

			$namespace = $this->normalize( $namespace );

			foreach ( $directory as $dir ) {

				$dir = $this->normalize( $dir );

				$file = str_replace( $namespace, $dir, $interface ) . '.php';

				if ( file_exists( $file ) ) {
					return $file;
				}
			}
		}

	}

	public function normalize( $item, $append = true )
    {

        $item = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, rtrim( $item, DIRECTORY_SEPARATOR ) );

        if ( $append ) {
            $item .= DIRECTORY_SEPARATOR;
        }

        return $item;

    }

	public function loadIndexes( $filters = [] )
	{

		$dir = $this->library;

		if ( ! is_array( $filters ) ) {
			$filters = [ $filters ];
		}

		$scan = scandir( $dir );
		foreach ( $scan as $item ) {

			if ( '.' === $item || '..' === $item ) continue;

			$item = $this->filter( $item, ...$filters );
			if ( ! $item ) $continue;

			$path = $dir . $item;

			if ( is_dir( $path ) ) {
				$index = rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'index.php';
			}

			if ( file_exists( $index ) ) {

				$result = require_once $index;

				if ( ! $result ) {
					throw new Exception( 'Error loading package: ' . $item );
				}
			}
		}
	}

	public function filter( $item, Filter ...$filters ) {

		foreach ( $filters as $filter ) {
			$item = $filter( $item );
		}

		return $item;

	}

}
