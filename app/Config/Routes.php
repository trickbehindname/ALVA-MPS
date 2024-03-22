<?php

use App\Controllers\Mps;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('mps',[Mps::class, 'index']);
$routes->post('mps',[Mps::class, 'create']);
//$routes->get('simulation',[Mps::class, 'simulation']);

$routes->match(['get','post'],'simresult','Mps::simresult');
$routes->match(['get','post'],'loadsimresult','Mps::loadsimresult');

$routes->match(['get','post'],'simulation','Mps::simulation');
$routes->match(['get','post'],'loadsimulation','Mps::loadsimulation');
$routes->match(['get','post'],'processsimulation','Mps::processsimulation');


//$routes->get('(:segment)', [Mps::class, 'view']);
