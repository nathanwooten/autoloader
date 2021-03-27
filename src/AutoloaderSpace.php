<?php

namespace Pf\Autoloader;

use Exception;

class AutoloaderSpace
{

	protected $name = '';
	protected $dir = '';

	protected $sub = [];

	protected $isBasespace = false;

	public function __construct( $name, $dir, $parent = null ) {

		if ( ! is_null( $parent ) ) {
			$this->setParent( $parent );
		} else {
			if ( realpath( $dir ) ) {
				$this->isBasespace = true;
			}
		}

		$this->setName( $name );
		$this->setDir( $dir );

	}

	public function setName()
	{

		return $this->dir;

	}

	public function getName( $deep = false )
	{

		if ( ! $deep ) {
			return $this->name;
		}

		$name = $this->name;
		$parent = $this->getParent();
		while ( $parent ) {
			$name = $parent->getName() . '\\' . $name;
			$parent = $parent->getParent();
		}

		return $dir;

	}

	public function setDir( $dir ) {

		if ( $this->isBasespace ) {

			$real = realpath( $dir );
			if ( ! $real ) {
				$this->isBasespace = false;
			}
		}

		$this->dir = $dir;

	}

	public function getDir( $deep = false ) {

		if ( ! $deep ) {
			return $this->dir;
		}

		$dir = $this->dir;
		$parent = $this->getParent();
		while ( $parent ) {
			$dir = $parent->getDir() . DIRECTORY_SEPARATOR . $dir;
			$parent = $parent->getParent();
		}

		$dir = realpath( $dir );

		return $dir;

	}

	public function getBy( $id, $useValue = false, array $args = [] )
	{

		$space = false;

		if ( ! $space ) {
			$space = $this->getByName( $id );
			$by = 'name';
		}

		if ( ! $space ) {
			$space = $this->getByDir( $id );
			$by = 'dir';
		}

		if ( $space ) {
			if ( $useValue ) {
				switch ( $by ) {
					case 'name':
						$getter = 'getDir';
						break;
					case 'dir':
						$getter = 'getName';
						break;
				}
				$value = $space->$getter( ...$args );
				$space = $value;
			}
		} else {
			throw new Exception( 'Unknown id' );
		}
		
		return $space;

	}

	public function getByName( $name ) {

		$this->reset();

		$current = $this->getCurrent();
		while ( $current ) {
			$curName = $current->getName();
			if ( $name === $curName ) {
				return $current;
			}
			$current = $this->getNext();
		}

		return false;

	}

	public function getByDir( $dir )
	{

		$this->reset();

		$current = $this->getCurrent();
		while ( $current && $this->key() ) {
			$curDir = $current->getDir();
			if ( $dir === $curDir ) {
				return $current;
			}
			$current = $this->getNext();
		}

		return false;

	}

	public function add( $name, $dir ) {

		if ( $this->hasName( $name ) ) {
			throw new Exception( 'Space already exists within parent' );
		}

		$this->sub[] = new AutoloaderSpace( $name, $dir, $this );

	}

	public function current() {
	{

		return current( $this->sub );

	}

	public function key() {

		return key( $this->sub );

	}
	
	public function next()
	{

		$sub = $this->sub;
		$next = next( $sub );

		return $next;

	}

	public function prev()
	{

		$sub = $this->sub;
		$prev = prev( $sub );

		return $prev;

	}

	public function reset()
	{

		reset( $this->sub );

	}

	public function end()
	{

		end( $this->sub );

	}

	public function hasName( string $name )
	{

		if ( '' === $name ) return false;

		foreach ( $this->sub as $space ) {
			$spaceName = $space->getName();

			if ( $name === $spaceName ) {
				return true;
			}
		}

		return false;

	}

	public function getSub()
	{

		return $this->sub;

	}

}
