<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'Home/home';
$route['dang-nhap'] = 'Home/login';
// $route['dang-nhap-nguoi-mua'] = 'Home/login_user';
// $route['dang-nhap-nguoi-ban'] = 'Home/login_sell';
$route['dang-ky'] = 'Home/register';
$route['dang-ky-nguoi-mua'] = 'Home/register_user';
$route['dang-ky-nguoi-ban'] = 'Home/register_sell';
$route['xac-thuc-tai-khoan'] = 'Home/active_user';
//
$route['nap-the'] = 'Service/nap_the';

$route['the-game-garena'] = 'Service/ban_the';
$route['quan-ly-tai-khoan'] = 'Manager/quan_ly';
$route['lich-su-nap-the'] = 'Manager/ls_mua_hang';

$route['admin'] = 'Admin/admin';
$route['admin/his_card'] = 'Admin/his_card';
$route['admin/member'] = 'Admin/member';
$route['admin/list_game'] = 'Admin/list_game';


//KOL
$route['danh-sach-idol'] = 'Home/list_kol';
$route['lich-su-choi'] = 'Home/his_playdoul';
$route['idol-(:num)'] = 'Home/detail_kol/$1';
// cộng đồng
$route['cong-dong'] = 'Home/community';

include('routes_chat.php');
include('routes_ajax.php');
