<?php

namespace Pf\Autoloader;

use Exception;

class AutoloaderDir extends AutoloaderAbstract {

	public function space( $dir, $name, $quiet = false, AutoloaderSpace $parent = null, AutoloaderPackage $package = null )
	{

		$space = Autoloader::factory( 'space', $dir, $name, $quiet, $parent, $package );
		return $space;

	}

	public function spaceSetup( $name, $dir, $parentDir = '' ) {

		if ( $parentDir ) {
			$parent = $this->space( 'vendor', $parentDir, true );
		} else {
			$parent = null;
		}

		$space = $this->space( $dir, $name, false, $parent );

		return $parentDir ? $parent : $space;

	}

	public function realFromSpace( AutoloaderSpace $space ) {

		$dir = realpath( $space->getDir( true ) );
		if ( ! $dir ) {
			throw new Exception( 'Unable to create realpath from space' );
		}

		$dir .= DIRECTORY_SEPARATOR;

		return $dir;

	}

	public function createReal( $dir, $vendor = '' ) {

		if ( $vendor ) {
			$vendorDir = Autoloader::getInstance()->getVendorDir( $vendor );
		}

		$real = realpath( $dir );
		if ( $real ) {
			return $real;

		} elseif ( $vendor ) {
			$path = $vendorDir . $dir;

			$real = realpath( $path );
			if ( $real ) {
				return $real;
			}
		}

		throw new Exception( 'Unable to create readable path' );

	}

}
