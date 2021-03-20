<?php

namespace Pf\Autoloader;

$class = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );
if ( ! class_exists( $class ) ) {

class AutoloaderSpace extends AutoloaderAbstract
{

	public $name = null;
	public $dir = '';

	public $quiet = false;

	public $parent = false;
	public $sub = [];

//	public function __construct( $package, $parent, $space = '', $dir = '' )
	public function __construct( string $dir = '', string $name = null, $quiet = false, AutoloaderSpace $parent = null, AutoloaderPackage $package = null )
	{

//		$this->package = $package;

		if ( ! empty( $dir ) ) {
			$this->setDir( $dir );
		}

		if ( ! is_null( $name ) ) {
			$this->setName( $name );
		}

		if ( $parent ) {
			$this->setParent( $parent );
		}

		if ( $package ) {
			$this->setPackage( $package );
		}

		$this->setQuiet( $quiet );

	}

	public function setName( $name )
	{

		$this->name = $name;

	}

	public function getName()
	{

		$quiet = $this->quiet;

		if ( ! $quiet ) {
			return $this->name;
		} else {
			return '';
		}

	}

	public function getDomain()
	{

		return 'space';

	}

	public function setDir( $dir )
	{

		$fullPath = $this->getDir( true, $dir );





		$this->dir = $dir;

	}

	public function getDir( $deep = false, $dir = null )
	{

		$dir = $dir ?: $this->dir;

		if ( ! $deep ) {
			$dir = rtrim( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

			return $dir;
		}

		$parent = $this->getParent();

		$i = 0;
		while( $parent ) {
			$dir = $parent->getDir() . $dir;
			$parent = is_integer( $deep ) ? ( $i < $deep ? $parent->getParent() : false ) : $parent->getParent();
			++$i;
		}

		$dir = rtrim( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

		return $dir;

	}

	public function setParent( AutoloaderSpace $parent )
	{

		$this->parent = $parent;

		$this->parent->setSub( $this );

	}

	public function getParent()
	{

		return $this->parent;

	}

	public function setSub( AutoloaderSpace $space )
	{

		$name = $space->getName();

		$this->sub[ $name ] = $space;

	}

	public function setQuiet( bool $quiet )
	{

		$this->quiet = $quiet;

	}

}
}
