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

		$this->setName( $name );
		$this->setDir( $dir );

		if ( ! is_null( $parent ) ) {
			$this->setParent( $parent );
		} else {
			if ( realpath( $dir ) ) {
				$this->isBasespace = true;
			}
		}
	}

	public function add( $name, $dir ) {

		if ( $this->hasName( $name ) ) {
			throw new Exception( 'Space already exists within parent' );
		}

		$this->sub[] = new AutoloaderSpace( $name, $dir, $this );

	}

	public function get()
	{

		return current( $this->sub );

	}

	public function getNext()
	{

		$sub = $this->sub;
		$next = next( $this->sub );

		if ( $sub === $next ) {
			return false;
		}

		return $next;

	}

	public function getPrev()
	{

		$sub = $this->sub;
		$prev = prev( $this->sub );

		if ( $sub === $prev ) {
			return false;
		}

		return $prev;

	}

	public function hasName()
	{

		foreach ( $this->sub as $space ) {
			
		}

	}

	public function getSub()
	{

		return $this->sub;

	}

}
