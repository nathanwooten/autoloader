<?php

namespace Pf\Autoloader;

function matchSpace( $alias, $directories = [] )
{
  $match = '';
  $dirs = [];
  $separator = DIRECTORY_SEPARATOR;
  $alias = normalize( 'trim', $alias );
  $alias = normalize( 'replace', $alias, $separator );
  $aliasArray = explode( $separator, $alias );
  $path = '';
  foreach ( $aliasArray as $key => $name ) {
    foreach ( $directories as $directory ) {
      $directory = normalize( 'trim', $directory );
      $directory = normalize( 'replace', $directory, $separator );
      $dirArray = explode( $separator, $directory );
      if ( in_array( $name, $dirArray ) ) {
        if ( isset( $dir[ $directory ] ) ) {
          end( $dir[$directory] );
          if ( key( $dir[ $directory ] === $key -1 ) {
            $dir[ $directory ][ $key ] = $name;
          }
        }
      }
    }
  }
  $count = 0;
  foreach ( $dir as $directory => $dArray ) {
    $matchCount = count( $dArray )
    if ( $count < $matchCount ) {
      $count = $matchCount;
      $match = $directory;
    }    
  }
  return $directory;
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





