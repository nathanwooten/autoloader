<?php

namespace Pf\Autoloader;

use Exception;

class AutoloaderSpace
{

	protected $name = '';
	protected $dir = '';

	protected $sub = [];

	protected $isBasespace = false;

	public function __construct( $name, $dir, AutoloaderSpace $parent = null ) {

		$this->setName( $name );

		if ( ! is_null( $parent ) ) {
			$this->setParent( $parent );
		} else {
			if ( realpath( $dir ) ) {
				$this->isBasespace = true;
			}
		}

		$this->setDir( $dir );

	}

	public function setName( $name )
	{

		$this->name = $name;

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

	public function setParent( AutoloaderSpace $parent )
	{

		$this->parent = $parent;

		if ( ! $this->parent->hasSub( $this->getName() ) ) {
			$this->parent->setSub( $this );
		}

		$this->isBasespace = false;

	}

	public function getParent()
	{

		return $this->parent;

	}

	public function add( $name, $dir )
	{

		if ( $this->hasName( $name ) ) {
			throw new Exception( 'Space already exists within parent' );
		}

		$this->setSub( new AutoloaderSpace( $name, $dir, $this ) );

	}

	public function setSub( AutoloaderSpace $space ) {

		$this->sub[] = $space;

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

	public function hasSub( $name )
	{

		if ( '' === $name ) return false;

		$this->reset();

		$key = $this->key();
		$current = $this->current();
		while ( $current && ( 0 === $key || $key ) ) {
			$curName = $current->getName();

			if ( $name === $curName ) {
				return true;
			}

			$this->next();
			$key = $this->key();
			$current = $this->current();
		}

		return false;

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

		$key = $this->key();
		$current = $this->current();
		while ( $current && ( 0 === $key || $key ) ) {
			$curName = $current->getName();
			if ( $name === $curName ) {
				return $current;
			}
			$this->next();
			$key = $this->key();
			$current = $this->current();
		}

		return false;

	}

	public function getByDir( $dir )
	{

		$this->reset();

		$key = $this->key();
		$current = $this->current();
		while ( $current && ( 0 === $key || $key ) ) {
			$curDir = $current->getDir();
			if ( $dir === $curDir ) {
				return $current;
			}
			$this->next();
			$key = $this->key();
			$current = $this->current();
		}

		return false;

	}

	public function current()
	{

		return current( $this->sub );

	}

	public function key() {

		return key( $this->sub );

	}
	
	public function next()
	{

		return next( $this->sub );

	}

	public function prev()
	{

		return prev( $this->sub );

	}

	public function reset()
	{

		reset( $this->sub );

	}

	public function end()
	{

		end( $this->sub );

	}

	public function getSub()
	{

		return $this->sub;

	}

}
