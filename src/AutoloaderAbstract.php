<?php

namespace Pf\Autoloader;

use OutOfBoundsException;

$class = __NAMESPACE__ . '\\' . basename( __FILE__, '.php' );
if ( ! class_exists( $class ) ) {

abstract class AutoloaderAbstract
{

	public static function factory() {

		$class = static::class;

		if ( ! class_exists( $class ) ) {
			throw new OutOfBoundsException( 'Provided class does not exists' );
		}

		return new $class( ...func_get_args() );

	}

}
}
