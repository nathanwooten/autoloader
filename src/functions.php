<?php

namespace Pf\Autoloader;

function matchSpace( $alias, $directories = [] )
{
  $separator = DIRECTORY_SEPARATOR;
  $alias = normalize( 'trim', $alias );
  $alias = normalize( 'replace', $alias, $separator );
  $aliasArray = explode( $separator, $alias );
  $path = '';
  foreach ( $aliasArray as $key => $name ) {
    $path .= $name . $separator
    $directory = $directories[$key];
  }
}

function normalize( $dest, $item, ...$args )
{

  switch( $dest ) {

    case 'trim':

      if ( ! empty( $args ) ) {
         $trim = '\\' . $args[ 0 ] . 'trim';
      } else {
        $trim = '\\' . 'trim'; 
      }
      $item = $trim( $item, '/', '\\' );
      break;

    case 'replace':

      $item = str_replace( [ '\\', '/' ], isset( $args[0] ) ? $args[0], DIRECTORY_SEPARATOR, $item );
      break;

  }

  return  $item;

}





