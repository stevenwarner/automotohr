<?php defined('BASEPATH') OR exit('No direct script access allowed');

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

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['contact_us'] = 'home/contact_us';
$route['join_our_talent_network'] = 'home/join_our_talent_network';
$route['job_fair'] = 'home/job_fair';
$route['job_fair/(:any)'] = 'home/job_fair/$1';
$route['print_ad/(:any)'] = 'home/print_ad/$1';
//$route['job_details'] = 'home/job_details';
$route['index/(:num)'] = 'home/index/$1';
$route['job_details/(:any)'] = 'home/job_details/$1';
$route['preview_job/(:any)'] = 'home/preview_job/$1';
//route for search
//$route['jobs/([^/]*)/([^/]*)/(.*)'] = 'home/index/';
$route['join our team/(.*)'] = 'home/index/';
$route['jobs/(.*)'] = 'home/index/';
$route['recommend_job/(:any)'] = 'home/recommend_job/$1';
//route for pages
$route[':any'] = 'home/index/'; //For All pages.
$route[':any/(:num)'] = 'home/index/'; //For Testimonials Page
$route['sitemap.xml'] = 'Home/sitemap'; //For Testimonials Page

//
$route["cookie/savecookiedata"]["post"] = "home/saveCookieData";
