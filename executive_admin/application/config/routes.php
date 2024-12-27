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
$route['search/(:any)'] = 'dashboard/search/$1';
$route['search/(:any)/(:any)'] = 'dashboard/search/$1/$2';
$route['dashboard/search'] = 'dashboard/index/';
$route['dashboard/search/(:any)'] = 'dashboard/index/$1';
//$route['dashboard/company_login'] = 'dashboard/company_login';
//$route['dashboard/change_password'] = 'dashboard/change_password';
//$route['dashboard/manage_admin_companies'] = 'dashboard/manage_admin_companies';
//$route['dashboard/reports'] = 'dashboard/reports';

//Applicants Report
$route['reports/applicants_report/(:num)'] = 'reports/applicants_report/index/$1';
$route['reports/applicants_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7';
$route['reports/applicants_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7/$8';

//Job Products Report
$route['reports/job_products_report/(:num)'] = 'reports/job_products_report/index/$1';
$route['reports/job_products_report/(:num)/(:any)/(:any)/(:any)/(:any)'] = 'reports/job_products_report/index/$1/$2/$3/$4/$5';
$route['reports/job_products_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/job_products_report/index/$1/$2/$3/$4/$5/$6';

//Applicant Status Report
$route['reports/applicant_status_report/(:num)'] = 'reports/applicant_status_report/index/$1';
$route['reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_status_report/index/$1/$2/$3/$4/$5';
$route['reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'reports/applicant_status_report/index/$1/$2/$3/$4/$5/$6';

//Applicant Source Report
$route['reports/applicant_source_report/(:num)'] = 'reports/applicant_source_report/index/$1';
$route['reports/applicant_source_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6';
$route['reports/applicant_source_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6/$7';

//Applicant Between Period
$route['reports/applicants_between_period_report/(:num)'] = 'reports/applicants_between_period_report/index/$1';
$route['reports/applicants_between_period_report/(:num)/(:any)/(:any)'] = 'reports/applicants_between_period_report/index/$1/$2/$3';
$route['reports/applicants_between_period_report/(:num)/(:any)/(:any)/(:any)'] = 'reports/applicants_between_period_report/index/$1/$2/$3/$4';
$route['reports/applicants_between_period_report/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'reports/applicants_between_period_report/index/$1/$2/$3/$4/$5';


$route['reports/applicants_referrals_report/(:num)'] = 'reports/applicants_referrals_report/index/$1';
$route['reports/interviews_report/(:num)'] = 'reports/interviews_report/index/$1';
$route['reports/jobs_per_month_report/(:num)'] = 'reports/jobs_per_month_report/index/$1';
$route['reports/time_to_fill_job_report/(:num)'] = 'reports/time_to_fill_job_report/index/$1';
$route['reports/time_to_fill_job_report/(:num)/(:any)'] = 'reports/time_to_fill_job_report/index/$1/$2';
$route['reports/time_to_hire_job_report/(:num)'] = 'reports/time_to_hire_job_report/index/$1';
$route['reports/time_to_hire_job_report/(:num)/(:any)'] = 'reports/time_to_hire_job_report/index/$1/$2';
$route['reports/job_categories_report/(:num)'] = 'reports/job_categories_report/index/$1';
$route['reports/new_hires_report/(:num)'] = 'reports/new_hires_report/index/$1';
$route['reports/new_hires_onboarding_report/(:num)'] = 'reports/new_hires_onboarding_report/index/$1';
$route['reports/job_views_report/(:num)'] = 'reports/job_views_report/index/$1';
$route['reports/company_daily_activity_report/(:num)'] = 'reports/company_daily_activity_report/index/$1';
$route['reports/employer_login_duration/(:num)'] = 'reports/employer_login_duration/index/$1';
$route['reports/company_weekly_activity_report/(:num)'] = 'reports/company_weekly_activity_report/index/$1';
$route['reports/daily_activity_report/(:num)'] = 'reports/daily_activity_report/index/$1';
$route['reports/weekly_activity_report/(:num)'] = 'reports/weekly_activity_report/index/$1';
$route['reports/daily_inactivity_report/(:num)'] = 'reports/daily_inactivity_report/index/$1';
$route['reports/weekly_inactivity_report/(:num)'] = 'reports/weekly_inactivity_report/index/$1';
$route['reports/daily_activity_overview_report/(:num)'] = 'reports/daily_activity_overview_report/index/$1';
$route['reports/weekly_activity_overview_report/(:num)'] = 'reports/weekly_activity_overview_report/index/$1';
$route['reports/daily_activity_detailed_overview_report/(:num)'] = 'reports/daily_activity_detailed_overview_report/index/$1';
$route['reports/applicant_offers_report/(:num)'] = 'reports/applicant_offers_report/index/$1';
$route['reports/applicant_interview_scores_report/(:num)'] = 'reports/applicant_interview_scores_report/index/$1';
$route['reports/applicant_interview_scores_report/(:num)/(:any)'] = 'reports/applicant_interview_scores_report/index/$1/$2';
//Origination Tracking Report
$route['reports/applicant_origination_tracker_report/(:num)'] = 'reports/applicant_origination_tracker_report/index/$1';
$route['reports/applicant_origination_tracker_report/(:num)/(:any)'] = 'reports/applicant_origination_tracker_report/index/$1/$2';
$route['reports/applicant_origination_tracker_report/(:num)/(:any)/(:any)'] = 'reports/applicant_origination_tracker_report/index/$1/$2/$3';
$route['reports/applicant_origination_tracker_report/(:num)/(:any)/(:any)/(:any)'] = 'reports/applicant_origination_tracker_report/index/$1/$2/$3/$4';
$route['reports/applicant_origination_tracker_report/(:num)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_origination_tracker_report/index/$1/$2/$3/$4/$5';
$route['reports/applicant_origination_tracker_report/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_origination_tracker_report/index/$1/$2/$3/$4/$5/$6';
//Origination Statistics Report
$route['reports/applicant_origination_statistics_report/(:num)'] = 'reports/applicant_origination_statistics_report/index/$1';

//private message
$route['private_messages/(:num)'] = 'private_messages/index/$1';
$route['outbox/(:num)'] = 'private_messages/outbox/$1';
$route['compose_message/(:num)'] = 'private_messages/compose_message/$1';
$route['outbox_message_detail/(:num)/(:num)'] = 'private_messages/outbox_message_detail/$1/$2';
$route['inbox_message_detail/(:num)/(:num)'] = 'private_messages/inbox_message_detail/$1/$2';
$route['reply_message/(:num)/(:num)'] = 'private_messages/reply_message/$1/$2';

//ComplyNet
$route['complynet/(:num)'] = 'complynet/index/$1';
$route['lms_company_report/(:num)'] = 'reports/LMS_company_report/index/$1';
