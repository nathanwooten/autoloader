<?php

namespace Nw\Autoloader;

abstract class AutoloaderContext implements AutoloaderContextInterface {

	protected $vendor;
	protected $dir;

	protected $queue = [];

	protected $register = [];

	public function getVendor() {

		return $this->vendor;

	}

	public function getDir() {

		return $this->dir;

	}

	public function getConfig( $package ) {

		$package = AutoloaderCompanion::normalizeName( $package );

		$config = sprintf( '%s\%s\src\config.php', $this->getDir(), $package );

		return $config;

	}

	public function getQueue()
	{

		return $this->queue;

	}

	public function addToQueue( $package )
	{

		if ( ! in_array( $package, $this->register ) ) {

			$this->queue[] = $package;
		}

	}

	protected function unsetFromQueue( $package ) {

		unset( $this->queue[ array_search( $package, $this->queue ) ] );

	}

	protected function addToRegister( $package )
	{

		if ( ! in_array( $package, $this->register ) ) {

			$this->register[] = $package;
		}

		throw new Exception( 'Trying to re-register package' );

	}

}
