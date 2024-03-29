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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['master/template_tunjangan/(:num)'] = 'master/template_tunjangan/detail/$1';
$route['master/template_tunjangan/(:num)/table'] = 'master/template_tunjangan/tableDetail/$1';

$route['kepegawaian/employee/(:num)'] = 'kepegawaian/employee/edit/$1';

$route['trx/absensi/(:num)'] = 'trx/absensi/detail/$1';
$route['trx/absensi/(:num)/table'] = 'trx/absensi/tableDetail/$1';

$route['review/cutoff/(:num)'] = 'review/cutoff/detail/$1';
$route['review/cutoff/(:num)/table'] = 'review/cutoff/tableDetail/$1';

$route['mitra'] = 'mitra/upload/index';
$route['mitra/table'] = 'mitra/upload/table';
$route['mitra/(:num)'] = 'mitra/upload/detail/$1';
$route['mitra/detailTable'] = 'mitra/upload/detailTable';

$route['slip/pegawai/(:num)/(:num)'] = 'slip/pegawai/detail/$1/$2';
$route['slip/pegawai/(:num)/(:num)/table'] = 'slip/pegawai/detailTable/$1/$2';
$route['slip/pegawai/(:num)/(:num)/pdf'] = 'slip/pegawai/pdf/$1/$2';

$route['slip/mitra/(:num)/(:num)'] = 'slip/mitra/detail/$1/$2';
$route['slip/mitra/(:num)/(:num)/table'] = 'slip/mitra/detailTable/$1/$2';
$route['slip/mitra/(:num)/(:num)/pdf'] = 'slip/mitra/pdf/$1/$2';