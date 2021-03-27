<?php

namespace Pf\Autoloader;

class AutoloaderSpace
{

	protected $sub;

	public function __construct( $name, $dir, $parent = null ) {

		$this->setName( $name );
		$this->setDir( $dir );

		if ( ! is_null( $parent ) ) {
			$this->setParent( $parent );
		}

	}

	public function add( $name, $dir ) {

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

	public function getSub()
	{

		return $this->sub;

	}

}
