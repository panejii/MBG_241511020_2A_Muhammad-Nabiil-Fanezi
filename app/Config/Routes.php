<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute dasar untuk login dan register
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/login/logout', 'Login::logout');
$routes->get('/dashboard', 'Dashboard::index');

$routes->get('/admin/dashboard', 'Admin\DashboardGudang::index');
$routes->get('/dapur/dashboard', 'Dapur\DashboardDapur::index');

$routes->group('admin', function($routes) {
    $routes->get('bahan_baku', 'Admin\BahanBaku::index');
    $routes->get('bahan_baku/add', 'Admin\BahanBaku::add');
    $routes->post('bahan_baku/store', 'Admin\BahanBaku::store');
});

$routes->group('dapur', function($routes) {
    $routes->get('daftar_bahan_view', 'Dapur\Permintaan::index');
});

// Grup rute untuk Admin
// $routes->group('admin', function($routes) {
//     // Rute untuk Students
//     $routes->get('students', 'Admin\Students::index');
//     $routes->get('students/add', 'Admin\Students::add');
//     $routes->post('students/save', 'Admin\Students::save');
//     $routes->get('students/edit/(:num)', 'Admin\Students::edit/$1');
//     $routes->post('students/update/(:num)', 'Admin\Students::update/$1');
//     $routes->delete('students/delete/(:num)', 'Admin\\Students::delete/$1');
    
//     // Rute untuk Courses
//     $routes->get('courses', 'Admin\Courses::index');
//     $routes->get('courses/add', 'Admin\Courses::add');
//     $routes->post('courses/save', 'Admin\Courses::save');
//     $routes->get('courses/edit/(:num)', 'Admin\Courses::edit/$1');
//     $routes->post('courses/update/(:num)', 'Admin\Courses::update/$1');
//     $routes->delete('courses/delete/(:num)', 'Admin\\Courses::delete/$1');
// });

// Grup rute untuk Student
$routes->group('student', function($routes) {
    $routes->get('courses', 'Student\Courses::index');
    
    // Rute ini menangani permintaan POST dari JavaScript
    $routes->post('courses/enroll', 'Student\Courses::enrollMultiple');
    $routes->delete('courses/unenroll/(:num)', 'Student\Courses::unenroll/$1');
    
    $routes->get('enrolled', 'Student\Courses::enrolled');
});