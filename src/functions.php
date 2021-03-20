<?php

namespace Pf\Autoloader;

function matchSpace( $alias, $directories = [] )
{
  $dirs = [];
  $separator = DIRECTORY_SEPARATOR;
  $alias = normalize( 'trim', $alias );
  $alias = normalize( 'replace', $alias, $separator );
  $aliasArray = explode( $separator, $alias );
  $path = '';
  foreach ( $aliasArray as $key => $name ) {
    $path .= ( 0 < $key ? $separator : '' ) . $name;
    foreach ( $directories as $directory ) {
      $directory = normalize( 'trim', $directory );
      $directory = normalize( 'replace', $directory, $separator );
      $dirArray = explode( $separator, $directory );
    }
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





