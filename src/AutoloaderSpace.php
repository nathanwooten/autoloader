<?php

namespace Pf\Autoloader;

class AutoloaderSpace
{

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

	public function next()
	{

		return next( $this->sub );

	}




}




