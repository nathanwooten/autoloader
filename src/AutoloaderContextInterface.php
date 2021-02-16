<?php

namespace Nw\Autoloader;

interface AutoloaderContextInterface {

	public function getVendor();
	public function addToQueue( $package );

}
