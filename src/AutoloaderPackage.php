<?php

/**
 * @link      https://github.com/nathanwooten/autoloader
 * @copyright Copyright (c) 2021 Nathan Wooten
 * @license   MIT License (https://mit-license.org/)
 */

namespace nathanwooten\Autoloader;

use function basename;
use function class_exists;
use function in_array;
use function is_readable;
use function strrev;
use function substr;

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'index.php';

    global $classLoaded;
    $classLoaded = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );

if ( class_exists( $classLoaded, false ) ) {

    unset( $classLoaded );
    return;
}

class AutoloaderPackage implements AutoloaderInterface
{

	protected $autoloader;

	public $namespace_;
	public $directory_;

	public $files = [];
	public $interfaces = [];

	public function __construct( Autoloader $autoloader, $namespace, $directory ) {

		$this->autoloader = $autoloader;
		$this->namespace_ = $this->normalize( $namespace );
		$this->directory_ = $this->normalize( $directory );

	}

	public function getNamespace()
	{

		return $this->namespace_;

	}

	public function getDirectory()
	{

		return $this->directory_;

	}

	public function getFiles()
	{

		return $this->files;

	}

	public function getInterfaces()
	{

		return $this->interfaces;

	}

	public function getInterface( $interfaceName )
	{

		if ( array_key_exists( $interfaceName, $this->interfaces ) ) {
			return $this->interfaces[ $interfaceName ];
		}

	}

	public function getFile( $filename )
	{

		if ( array_key_exists( $filename, $this->files ) ) {
			return $this->files[ $filename ];
		}

	}

	public function toFile( $file )
	{

		if ( ! is_readable( $file ) ) {
			throw new Exception( $file, 'File must be readable' );
		}

		$file = $this->normalize( $file );
		$filename = strrev( substr( strrev( $file, 0, strpos( $file, DIRECTORY_SEPARATOR ) ) ) );

		$filesystemDirectory = $this->getFilesystemDir();
		$packageDirectory = $this->getDirectory();

		$directory = str_replace( [ $filesystemDirectory, $packageDirectory ], '', $file );

		return [ $filename, $directory ];

	}

	public function createFile( $filename, $directory = '' )
	{

		if ( is_readable( $filename ) ) {

			$file = $filename;
			require_once $file;

			return $this->createFile( ...$this->toFile( $file) );

		} else {

			$files = $this->getFiles();

			$directory = $this->getFilesystemDirectory() . $this->getDirectory() . $directory;
			$directory = $this->normalize( $directory );

			$file = $directory . $filename;

			if ( ! is_readable ( $file ) ) {
				throw new Exception( 'Bad file, unreadable' );
			}
			require_once $file;

			if ( ! array_key_exists( $filename, $files ) ) {
				$files[ $filename ] = [];
			}

			$fileArray = [ $filename, $file, $directory ];

			$files[ $filename ][] = $fileArray;
		}

		$this->files = $files;

	}

	public function createInterface( $interface, $file = null )
	{

		$file = is_null( $file ) ? $this->getAutoloader()->locate( $interface ) : $file;
		if ( ! file_exists( $file ) ) {
			throw new Exception( 'Where\'s the file? Who knows.' );
		}
		require_once $file;

		$interfaces = $this->getInterfaces();

		$normal = rtrim( $this->normalize( $interface ), DIRECTORY_SEPARATOR );
		$name = str_replace( '.php', '', $this->toFilename( $file ) );

		$domain = substr( $interface, 0, strpos( $interface . '.php', $name . '.php' ) );

		if ( ! array_key_exists( $name, $interfaces ) ) {
			$interfaces[ $name ] = [];
		}

		$interfaceArray = [ $interface, $file, $domain, $name ];

		if ( ! in_array( $interfaceArray, $interfaces[ $name ] ) ) {
			$interfaces[ $name ][] = $interfaceArray;
		}

		$this->interfaces = $interfaces;

	}

	public function toFilename( $file ) {

		return strrev( substr( strrev( $file ), 0, strpos( strrev( $file ), DIRECTORY_SEPARATOR ) ) );

	}

	public function normalize( $toNormal, $append = true, $separator = DIRECTORY_SEPARATOR )
	{

		return $this->getAutoloader()->normalize( $toNormal, $append, $separator );

	}

	public function getFilesystemDirectory()
	{

		$autoloader = $this->getAutoloader();
		$filesystemDirectory = $autoloader->getDirectory();

		return $filesystemDirectory;

	}

	public function getAutoloader()
	{

		return $this->autoloader;

	}

}
