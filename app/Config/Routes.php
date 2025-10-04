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
    $routes->get('bahan_baku/edit/(:num)', 'Admin\BahanBaku::edit/$1');
    $routes->post('bahan_baku/update/(:num)', 'Admin\BahanBaku::update/$1');
});

$routes->group('dapur', function($routes) {
    $routes->get('daftar_bahan_view', 'Dapur\Permintaan::index');
});
