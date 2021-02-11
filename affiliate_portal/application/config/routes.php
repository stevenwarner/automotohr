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
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['login'] = 'users/login';
$route['generate-password'] = 'users/generate_password';
$route['generate-password/(:any)'] = 'users/generate_password/$1';
$route['logout'] = 'users/logout';
$route['dashboard/search'] = 'dashboard/index/';
$route['dashboard/search/(:any)'] = 'dashboard/index/$1';

//private message
$route['private_messages/(:num)'] = 'private_messages/index/$1';
$route['outbox/(:num)'] = 'private_messages/outbox/$1';
$route['compose_message/(:num)'] = 'private_messages/compose_message/$1';
$route['outbox_message_detail/(:num)/(:num)'] = 'private_messages/outbox_message_detail/$1/$2';
$route['inbox_message_detail/(:num)/(:num)'] = 'private_messages/inbox_message_detail/$1/$2';
$route['reply_message/(:num)/(:num)'] = 'private_messages/reply_message/$1/$2';
// Affiliate Dashboard Routes
$route['refer-potential-clients'] = 'refer_client_potential/index';
$route['refer-potential-clients/(:any)'] = "refer_client_potential/index/$1";
$route['view-referral-clients'] = 'view_referral_clients/index';
$route['my-current-paying-clients'] = 'my_current_paying_clients/index';
$route['invoice'] = 'invoice/index';
$route['invoice/(:any)/(:any)/(:any)/(:any)'] = 'invoice/index/$1/$2/$3/$4';
$route['affiliate-advertising'] = 'affiliate_advertising/index';
$route['refer-an-affiliate'] = 'refer_an_affiliate/index';
$route['refer-an-affiliate/(:any)'] = "refer_an_affiliate/index/$1";
$route['view-referral-affiliates'] = 'refer_an_affiliate/affiliate_listing';
$route['edit'] = 'refer_client_potential/edit';
$route['my-current-paying-clients/edit'] = 'my_current_paying_clients/edit';
$route['affiliate-advertising/edit'] = 'affiliate_advertising/edit';
$route['view-profile'] = 'users/view_profile';
$route['login-credentials'] = 'users/login_password';