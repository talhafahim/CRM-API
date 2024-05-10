<?php namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// $routes->resource('attendance');
// $routes->resource('attendance', ['controller' => 'Attendance', 'only' => ['index', 'show', 'create', 'update', 'delete']]);
$routes->resource('attendance', ['controller' => 'Attendance', 'only' => ['index']]);
$routes->resource('attendance/checkin', ['controller' => 'Attendance', 'only' => ['create']]);
$routes->resource('attendance/checkout', ['controller' => 'Attendance', 'only' => ['update']]);
$routes->resource('attendance/delete', ['controller' => 'Attendance', 'only' => ['delete']]);
//
$routes->resource('project', ['controller' => 'Project', 'only' => ['index']]);
$routes->resource('project/create', ['controller' => 'Project', 'only' => ['create']]);
// $routes->resource('attendance/checkout', ['controller' => 'Attendance', 'only' => ['update']]);
$routes->resource('project/delete', ['controller' => 'Project', 'only' => ['delete']]);
//
//
$routes->resource('ticket', ['controller' => 'Ticket', 'only' => ['index']]);
$routes->resource('ticket/create', ['controller' => 'Ticket', 'only' => ['create']]);
// $routes->resource('attendance/checkout', ['controller' => 'Attendance', 'only' => ['update']]);
$routes->resource('ticket/delete', ['controller' => 'Ticket', 'only' => ['delete']]);
//
$routes->resource('users', ['controller' => 'Users', 'only' => ['index']]);
$routes->resource('users/create', ['controller' => 'Users', 'only' => ['create']]);
// $routes->resource('attendance/checkout', ['controller' => 'Attendance', 'only' => ['update']]);
$routes->resource('users/delete', ['controller' => 'Users', 'only' => ['delete']]);
//
$routes->resource('signin', ['controller' => 'Auth', 'only' => ['index']]);