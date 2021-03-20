<?php

namespace Pf\Autoloader;

function match( $alias, $directories = [] ) {
  $separator = DIRECTORY_SEPARATOR;
  $alias = trim( $alias, '/', '\\' );
  $alias = str_replace( '\\/', $separator, $alias );
  $aliasArray = explode( DIRECTORY_SEPARATOR );
  
}
