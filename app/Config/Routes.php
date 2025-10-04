<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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
    $routes->get('bahan_baku/edit/(:num)', 'Admin\BahanBaku::edit/$1');
    $routes->post('bahan_baku/update/(:num)', 'Admin\BahanBaku::update/$1');
    $routes->post('bahan_baku/delete/(:num)', 'Admin\BahanBaku::delete/$1');
    $routes->get('showPermintaan', 'Admin\Permintaan::index');
    $routes->get('showPermintaan/detail/(:num)', 'Admin\Permintaan::show/$1');
    $routes->post('showPermintaan/process/(:num)', 'Admin\Permintaan::process/$1');
    $routes->get('permintaan/detail/(:num)', 'Admin\Permintaan::detail/$1');

});

$routes->group('dapur', ['namespace' => 'App\Controllers\Dapur'], function ($routes) {
    $routes->get('permintaan', 'Permintaan::index');
    $routes->post('permintaan/send', 'Permintaan::send');
    $routes->get('status_permintaan', 'StatusPermintaan::index'); 
    $routes->get('status_permintaan', 'StatusPermintaan::index'); 
    $routes->get('status_permintaan/detail/(:num)', 'StatusPermintaan::detail/$1');
});

