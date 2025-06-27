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
// Added on 11-14-2019
$route['job-feed'] = 'Home/job_feed';
$route['job-feed/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Home/job_feed/$1/$2/$3/$4/$5';
$route['job_feed/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Home/job_feed/$1/$2/$3/$4/$5';
$route['job-feed-details/(:any)'] = 'Home/job_feed_details/$1';

$route['cron_ams'] = 'Home/cron';
$route['cron_ams/(:any)'] = 'Home/cron/$1';
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['contact_us'] = 'home/contact_us';
$route['contact-us'] = 'home/contact_us';
$route['ams_google_feed'] = 'home/ams_google_feed';
$route['organic_feed_test'] = 'home/organic_feed_test';
$route['contact'] = 'home/contact_us';
$route['terms-of-use'] = 'home/terms_of_use';
$route['site-map'] = 'home/sitemap';
$route['sitemap'] = 'home/sitemap';
$route['join_our_talent_network'] = 'home/join_our_talent_network';
$route['job_fair'] = 'home/job_fair';
$route['print_ad/(:any)'] = 'home/print_ad/$1';
$route['index/(:num)'] = 'home/index/$1';
$route['job_details/(:any)'] = 'home/job_details/$1';
$route['job_details/(:any)/(:any)'] = 'home/job_details/$1/$2';
$route['display-job/(:any)'] = 'home/job_details/$1';
$route['display-job/(:any)/(:any)'] = 'home/job_details/$1/$2';
$route['listing_feeds'] = 'home/listing_feeds_sjb';
$route['listing-feeds'] = 'home/listing_feeds_sjb';
//route for search
$route['jobs/(.*)'] = 'home/index/';
$route['recommend_job/(:any)'] = 'home/recommend_job/$1';
//route for pages
$route[':any'] = 'home/index/'; //For All pages.
$route[':any/(:num)'] = 'home/index/'; //For Testimonials Page
$route['(jobs-at)'] = 'Home/index/Jobs';
$route["cookie/savecookiedata"]["post"] = "home/saveCookieData";
