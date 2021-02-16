<?php

$package = 'Nw\App';
$classes = [
	'Nw\App\Std\StdObject',
	'Nw\App\Std\Resolver',
	'Nw\App\Controller\ControllerAbstract',
	'Nw\App\App',
	'Nw\App\Http\RequestInterface',
	'Nw\App\Http\RequestAbstract',
	'Nw\App\Http\Request',
	'Nw\App\Http\RequestResolver',
	'Nw\App\Http\RequestContext',
	'Nw\App\Http\RequestCommand',
	'Nw\App\Http\Router',
	'Nw\App\Http\Response'
];
$status = isset( $status ) ? $status : 'HBDIR';
$status = constant( $status ) . DIRECTORY_SEPARATOR . $package . DIRECTORY_SEPARATOR . 'src';

$config = [ 'package' => $package, 'classes' => $classes, 'status' => $status ];
return $config;
