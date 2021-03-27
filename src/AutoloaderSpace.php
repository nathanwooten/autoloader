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

	public function getName()
	{

		return $this->name;

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
			$dir = $parent->getDir() . $dir;
		}

		return $dir;

	}
	
	public function getBy( $id, $useValue = false )
	{

		$space = false;

		$space = $this->getByName( $id )
		if ( $space ) {
			$by = 'name';
		}

		$space = $this->getByDir( $dir );
		if ( $space ) {
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
				$value = $space->$getter();
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
		while ( $current ) {
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

	public function getCurrent() {
	{

		return current( $this->sub );

	}

	public function getNext()
	{

		$sub = $this->sub;
		$next = next( $sub );

		return $next;

	}

	public function getPrev()
	{

		$sub = $this->sub;
		$prev = prev( $sub );

		return $prev;

	}

	public function doReset()
	{

		reset( $this->sub );

	}

	public function doEnd()
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
