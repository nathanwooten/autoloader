<?php

namespace Pf\Autoloader;

function matchSpace( $alias, $directories = [] )
{

  $matches = [];
  $dirs = [];
  $separator = DIRECTORY_SEPARATOR;

  $alias = normalize( 'trim', $alias );
  $alias = normalize( 'replace', $alias, $separator );

  $aliasArray = explode( $separator, $alias );
  foreach ( $aliasArray as $key => $name ) {
    foreach ( $directories as $directory ) {

      $directory = normalize( 'trim', $directory );
      $directory = normalize( 'replace', $directory, $separator );
      $dirArray = explode( $separator, $directory );

      if ( in_array( $name, $dirArray ) ) {

        if ( ! isset( $dirs[ $directory ] ) ) {
            $dirs[ $directory ] = [];
            $dirs[ $directory ][ $key ] = $name;
        } else {
          end( $dirs[$directory] );
          if ( key( $dirs[ $directory ] ) === $key -1 ) {
            $dirs[ $directory ][ $key ] = $name;
          }
        }
      }
    }
  }
  $count = 0;
  foreach ( $dirs as $directory => $dArray ) {
 
   $matchCount = count( $dArray );

   if ( $count < $matchCount ) {
      $count = $matchCount;
      $matches = [ $directory ];
    } elseif ( $count === $matchCount && 0 < $count ) {
      $matches[] = $directory;
    }
    ++$count;
  }
  return $matches;

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
      $item = $trim( $item, '/\\' );
      break;

    case 'replace':

      $item = str_replace( [ '\\', '/' ], isset( $args[0] ) ? $args[0] : DIRECTORY_SEPARATOR, $item );
      break;

  }

  return  $item;

}
