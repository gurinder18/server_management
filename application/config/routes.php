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

$route['default_controller'] = "login";
$route['404_override'] = 'error';


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout';
$route['users'] = 'user/userListing';
$route['users/(:num)'] = "user/userListing/$1";
$route['add-user'] = "user/addNew";

$route['addNewUser'] = "user/addNewUser";
$route['edit-user'] = "user/editOld";
$route['edit-user/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";

$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

/*********** SERVER DEFINED ROUTES *******************/
$route['servers'] = 'server/serverListing';
$route['servers/(:num)'] = "server/serverListing/$1";
$route['add-server'] = "server/addNewServer";
$route['addNewServer2'] = "server/addNewServer2";

$route['edit-server'] = "server/editOldServer";
$route['edit-server/(:num)'] = "server/editOldServer/$1";
$route['editServer'] = "server/editServer";
$route['deleteServer'] = "server/deleteServer";

/*********** CLIENT DEFINED ROUTES *******************/
$route['clients'] = 'client/clients';
$route['clients/(:num)'] = "client/clients/$1";
$route['add-client'] = "client/addClient";
$route['addNewClient'] = "client/addClient";

$route['edit-client'] = "client/edit";
$route['edit-client/(:num)'] = "client/edit/$1";
$route['editClient'] = "client/editClient";
$route['deleteClient'] = "client/deleteClient";


/* End of file routes.php */
/* Location: ./application/config/routes.php */