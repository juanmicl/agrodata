<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'main/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['register'] = 'auth/register';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['el-tiempo'] = 'main/weather';
$route['contacto'] = 'main/contact';

$route['subasta/(:any)/(:any)']['get'] = 'auctions/get/$1/$2';
$route['subasta/(:any)/(:any)']['post'] = 'auctions/post/$1/$2';
$route['producto/(:any)']['get'] = 'products/get/$1';
$route['producto/(:any)']['post'] = 'products/post/$1';

$route['dashboard/ajustes'] = 'dashboard/settings';
$route['dashboard/api'] = 'dashboard/api';
$route['dashboard/api/renew'] = 'api/renew_api_key';

$route['api/auctioneers']['get'] = 'api/get_auctioneers';
$route['api/products']['get'] = 'api/get_products';

$route['api/auction/(:num)/(:any)']['get'] = 'api/get_auction/$1/$2'; // auctioneer_id, created_at
$route['api/product/(:num)/(:any)']['get'] = 'api/get_product/$1/$2'; // product_id, created_at

$route['api/search/(:any)'] = 'api/search/$1';