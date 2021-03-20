<?php

namespace Pf\Autoloader;

function matchSpace( $alias, $directories = [] ) {
  $separator = DIRECTORY_SEPARATOR;
  $alias = trim( $alias, '/', '\\' );
  $alias = str_replace( ['\\', '/', $spearator, $alias );
  $aliasArray = explode( $separator );
  $path = '';
  foreach ( $aliasArray as $key => $name ) {
    $path .= $name . $separator
    foreach ( explode( $separator, str_replace( '\\', '
  }
}
