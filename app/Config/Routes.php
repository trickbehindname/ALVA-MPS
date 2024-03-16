<?php

use App\Controllers\Mps;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('simulation',[Mps::class, 'simulation']);
$routes->get('mps',[Mps::class, 'index']);
$routes->post('mps',[Mps::class, 'create']);

$routes->get('(:segment)', [Mps::class, 'view']);
