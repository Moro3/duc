<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// Маршрут для админки
$route['adminka'] = "admin/admin";
$route['adminka/(:any)'] = "admin/admin/$1";

//Маршрут для залогирования
$route['a123'] = "a123/index";
$route['a123/(:any)'] = "a123/index";

// Маршрут сокрытия админки
$route['admin'] = "welcome";
$route['admin/(:any)'] = "welcome";

// Маршрут запросов ajax
$route['ajax'] = "gate/ajax";
$route['ajax/(:any)'] = "gate/ajax/$1";

// Маршрут запросов ajax с предварительной загрузкой скриптов и стилей
$route['ajaxs'] = "gate/ajax_script";
$route['ajaxs/(:any)'] = "gate/ajax_script/$1";

// Маршрут запросов grid
$route['grid'] = "gate/grid";
$route['grid/(:any)'] = "gate/grid/$1";


// Маршрут для пользователя
$route[':any'] = "route";

// Маршрут по умолчанию
$route['default_controller'] = "route";
$route['scaffolding_trigger'] = "555";


$route['404_override'] = '';




/* End of file routes.php */
/* Location: ./application/config/routes.php */