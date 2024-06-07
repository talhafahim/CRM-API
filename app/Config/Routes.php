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
$routes->resource('users/delete', ['controller' => 'Users', 'only' => ['delete']]);
$routes->resource('users/update', ['controller' => 'UsersUpdate', 'only' => ['create']]);
//
$routes->resource('signin', ['controller' => 'Auth', 'only' => ['index']]);
//
$routes->resource('task', ['controller' => 'Task', 'only' => ['index']]);
$routes->resource('task/delete', ['controller' => 'Task', 'only' => ['delete']]);
$routes->resource('task/create', ['controller' => 'Task', 'only' => ['create']]);
//
$routes->resource('client', ['controller' => 'Client', 'only' => ['index']]);
$routes->resource('client/delete', ['controller' => 'Client', 'only' => ['delete']]);
$routes->resource('client/create', ['controller' => 'Client', 'only' => ['create']]);
//
$routes->resource('project_comment', ['controller' => 'ProjectComment', 'only' => ['index']]);
$routes->resource('project_comment/create', ['controller' => 'ProjectComment', 'only' => ['create']]);
// $routes->resource('attendance/checkout', ['controller' => 'Attendance', 'only' => ['update']]);
$routes->resource('project_comment/delete', ['controller' => 'ProjectComment', 'only' => ['delete']]);
//
$routes->resource('task_status', ['controller' => 'TaskStatus', 'only' => ['index']]);
//
$routes->resource('task_priority', ['controller' => 'TaskPriority', 'only' => ['index']]);