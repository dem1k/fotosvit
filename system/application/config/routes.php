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
| 	example.com/class/method/id/
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
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
define('BO_URL', 'admin');


$route['default_controller'] = 'client/main';
$route['scaffolding_trigger'] = '';

/*
* Правила преобразования УРЛов для ListIt.
*/
//$route['changelink/change'] = 'client/changelink/change';
$route['about'] = 'client/articles';
$route['sales'] = 'client/articles';
$route['how_to_order'] = 'client/articles';
$route['payments'] = 'client/articles';
$route['shiping'] = 'client/articles';
$route['contacts'] = 'client/articles';
$route['(category/.+)']     = 'client/category/show/$1';
$route['category']     = 'client/category/show/';
//$route['product'] = 'client/articles';

$route['startpage'] = 'client/';
$route['upload'] = 'admin/media/uploadFile';
$route['admin/media/upload.php'] = 'admin/media/upload';
$route['(?!' . preg_quote(BO_URL) . ')(.+)'] = 'client/$1';
$route['(' . preg_quote(BO_URL) . '/.+)']     = '$1';
$route[preg_quote(BO_URL)]          = 'admin/auth/login';
//var_dump($route);exit;