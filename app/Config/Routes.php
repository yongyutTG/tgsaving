<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/', 'Portal::index');
$routes->get('section/(:segment)', 'Portal::section/$1');
$routes->get('section/(:segment)/(:num)', 'Portal::sectionDetail/$1/$2');
$routes->get('news/(:num)', 'Portal::article/$1');
$routes->get('news/(:num)/pdf', 'Portal::pdf/$1');
$routes->get('media/(:segment)', 'Portal::media/$1');
$routes->match(['get','post'], 'admin/login', 'Admin::login');
$routes->group('admin', ['filter' => 'staff'], static function ($routes) {
 $routes->get('/', 'Admin::index');
 $routes->get('news/new', 'Admin::edit');
 $routes->get('news/(:num)', 'Admin::edit/$1');
 $routes->post('news/save', 'Admin::save');
 $routes->post('news/(:num)/delete', 'Admin::delete/$1');
 $routes->post('logout', 'Admin::logout');
});
