<?php

namespace Config;

use CodeIgniter\Routing\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

$routes->get('/', 'Auth::login');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::processLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::processRegister');
$routes->get('/logout', 'Auth::logout');

$routes->get('/home', 'Home::index');

$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/uploadPhoto', 'Profile::uploadPhoto');


$routes->get('/transaction/topup', 'Transaction::topup');
$routes->post('/transaction/topup', 'Transaction::processTopup');
$routes->get('/transaction/history', 'Transaction::history');

$routes->get('/purchase/(:segment)', 'Purchase::index/$1');
$routes->post('/purchase/process', 'Purchase::process');

$routes->set404Override();
