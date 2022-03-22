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

//  Goals
$route['remarket_portal'] = 'Remarket_portal/index';
$route['goal'] = 'goals/Goals/index';
$route['goal/handler'] = 'goals/Goals/Handler';

$route['direct_deposit/update_primary/(:num)'] = 'direct_deposit/update_primary/$1';
$route['direct_deposit/pd/(:any)/(:num)/(:num)/(:any)'] = 'direct_deposit/pd/$1/$2/$3/$4';

// Added on 04-07-2020
$route['hr_documents_management/pd/(:any)/(:any)/(:any)/(:num)/(:any)'] = 'hr_documents_management/print_download/$1/$2/$3/$4/$5';
$route['hr_documents_management/pd/(:any)/(:any)/(:any)/(:num)'] = 'hr_documents_management/print_download/$1/$2/$3/$4';
// Added on 04-02-2020
$route['hr_documents_management/hybrid_document/add'] = 'hr_documents_management/hybrid_document/add';
$route['hr_documents_management/hybrid_document/edit/(:num)'] = 'hr_documents_management/hybrid_document/edit/$1';
$route['hr_documents_management/hybrid_document/handler'] = 'hr_documents_management/handler';
// Added on 03-20-2020
$route['manage_admin/companies/add_approver_process'] = 'manage_admin/Companies/add_approver_process';
$route['manage_admin/companies/add_approver/(:num)'] = 'manage_admin/Companies/add_approver/$1';
$route['manage_admin/companies/edit_approver_process'] = 'manage_admin/Companies/edit_approver_process';
$route['manage_admin/companies/edit_approver/(:num)/(:num)'] = 'manage_admin/Companies/edit_approver/$1/$2';
$route['manage_admin/companies/deactivate_approver'] = 'manage_admin/Companies/deactivate_approver';
$route['manage_admin/companies/activate_approver'] = 'manage_admin/Companies/activate_approver';
$route['manage_admin/companies/timeoff_approvers/(:num)'] = 'manage_admin/Companies/timeoff_approvers/$1';
$route['manage_admin/companies/timeoff_approvers/(:any)/(:any)/(:any)'] = 'manage_admin/Companies/timeoff_approvers/$1/$2/$3';
// Added on 03-20-2020
$route['notifications/get_notifications'] = 'Notifications/get_notifications';
// Added on 12-17-2019
$route['reports/handler'] = 'reports/handler';
$route['reports/driving_license'] = 'reports/driving_license';
$route['reports/driving_license/(.*)'] = 'reports/driving_license';
$route['indeed_feed_new/(:any)'] = 'Indeed_feed_new/index/$1';
$route['indeed_feed_new'] = 'Indeed_feed_new/index';
// Added on 01-01-2020
$route['auto_careers'] = 'Auto_careers/index';
$route['job_feeds/(:any)'] = 'job_feeds/index/$1';
// Added on 11-15-2019
$route['manage_admin/indeed_settings'] = 'manage_admin/indeed_settings/index';
// Added on 11-14-2019
$route['manage-admin/job-feed'] = 'manage_admin/job_feed_iframe';
// Added on: 11-08-2019
$route['manage_admin/copy_documents/handler'] = 'manage_admin/Copy_documents/handler';
$route['manage_admin/copy_documents'] = 'manage_admin/Copy_documents/index';
// Added on: 11-07-2019
$route['incident_reporting_system/handler'] = 'Incident_reporting_system/handler';
// Added on 11-04-2019
// Blue Panel
// Create Time off
$route['timeoff/lms'] = 'Time_off/timeoff_lms';
$route['timeoff/generateFilterSession'] = 'Time_off/generateFilterSession';
$route['timeoff/get_employee_status/(:any)'] = 'Time_off/get_employee_status/$1';
$route['timeoff/get_time_with_format/(:any)/(:any)/(:any)'] = 'Time_off/get_time_with_format/$1/$2/$3';
$route['timeoff/handler/requests_status/(:any)/(:any)/(:any)'] = 'Time_off/requests_status/$1/$2/$3';
// Green panel routes
$route['timeoff/import'] = 'Time_off/import';
$route['timeoff/action/(:any)'] = 'Time_off/action/$1';
$route['timeoff/request-report'] = 'Time_off/request_report';
$route['timeoff/create_employee/(:num)'] = 'Time_off/create_employee/$1';
// Create Time off
$route['timeoff/create-time-off'] = 'Time_off/create_timeoff';
// Create/View Types
$route['timeoff/types'] = 'Time_off/types';
$route['timeoff/types/(:any)'] = 'Time_off/types/$1';
$route['timeoff/types/edit/(:num)'] = 'Time_off/types/edit/$1';
$route['timeoff/types/view/(:any)'] = 'Time_off/types/view/null/$1';
// Create/View Plans
$route['timeoff/plans'] = 'Time_off/plans';
$route['timeoff/plans/(:any)'] = 'Time_off/plans/$1';
$route['timeoff/plans/edit/(:num)'] = 'Time_off/plans/edit/$1';
$route['timeoff/plans/view/(:any)'] = 'Time_off/plans/view/null/$1';
// Create/View Policies
$route['timeoff/policies'] = 'Time_off/policies';
$route['timeoff/policies/(:any)'] = 'Time_off/policies/$1';
$route['timeoff/policies/edit/(:num)'] = 'Time_off/policies/edit/$1';
$route['timeoff/policies/view/(:any)'] = 'Time_off/policies/view/null/$1';
// Create/View Policy Overwrites
$route['timeoff/policy-overwrite'] = 'Time_off/policy_overwrite';
$route['timeoff/policy-overwrite/(:any)'] = 'Time_off/policy_overwrite/$1';
$route['timeoff/policy-overwrite/edit/(:num)'] = 'Time_off/policy_overwrite/edit/$1';
$route['timeoff/policy-overwrite/view/(:any)'] = 'Time_off/policy_overwrite/view/null/$1';
// Report
$route['timeoff/report'] = 'Time_off/report';
$route['timeoff/get_report/(:any)'] = 'Time_off/get_report/$1';
$route['timeoff/report/(:any)'] = 'Time_off/report/$1';
// Balance
$route['timeoff/balance'] = 'Time_off/balance';
$route['timeoff/balance/(:any)'] = 'Time_off/balance/$1';
// Approvers
$route['timeoff/approver/public/(:any)'] = 'Time_off/approver_public/$1';
$route['timeoff/approvers'] = 'Time_off/approvers';
$route['timeoff/approvers/(:any)'] = 'Time_off/approvers/$1';
$route['timeoff/approvers/(:any)/(:any)'] = 'Time_off/approvers/$1/$2';
// Holidays
$route['timeoff/holidays'] = 'Time_off/holidays';
$route['timeoff/holidays/(:any)'] = 'Time_off/holidays/$1';
$route['timeoff/holidays/(:any)/(:any)'] = 'Time_off/holidays/$1/$2';
// Create/View Time Off Requests
$route['timeoff/requests'] = 'Time_off/requests';
$route['timeoff/request/create'] = 'Time_off/request/create';
// Time off Settings
$route['timeoff/settings'] = 'Time_off/settings';
// Blue panels routes
$route['timeoff/my_requests'] = 'Time_off/my_requests';
$route['timeoff/my_requests/create'] = 'Time_off/my_requests/create';
// Request Handler
$route['timeoff/handler'] = 'Time_off/handler';
$route['timeoff/calendar_timeoff_handler'] = 'Time_off/calendar_timeoff_handler';
// Print and Download
$route['timeoff/get_image_base64'] = 'Time_off/get_image_base64';
$route['timeoff/print/(:any)/(:any)'] = 'Time_off/print_and_download/print/$1/$2';
$route['timeoff/download/(:any)/(:any)'] = 'Time_off/print_and_download/download/$1/$2';
$route['timeoff/print/(:any)/(:any)/(:any)'] = 'Time_off/print_and_download/print/$1/$2/$3';
$route['timeoff/download/(:any)/(:any)/(:any)'] = 'Time_off/print_and_download/download/$1/$2/$3';

// $route['timeoff/print/(:any)/(:num)'] = 'Time_off/print_document/$1/$2';
// $route['timeoff/download/(:any)/(:num)'] = 'Time_off/download/$1/$2';
$route['timeoff/public/pd/(:any)/(:num)'] = 'Time_off/print_download/$1/$2';
$route['timeoff/download_file/(:any)'] = 'Time_off/download_file/$1';
// Export time off
$route['export_time_off'] = 'Time_off/export';
$route['timeoff/export'] = 'Time_off/export';
$route['download_export_timeoff/(:num)'] = 'Time_off/download_export_timeoff/$1';

// Added on: 07-10-2019
$route['pto/my/(:any)'] = 'Paid_time_off/my_pto/$1';
// Added on: 12-09-2019
$route['download_forms'] = 'Govt_user/download_forms';
// Added on: 06-09-2019
$route['complynet/(:any)'] = 'Complynet/index/$1';
// Govt user
$route['govt_login/(:any)'] = 'Govt_user/govt_user_login/$1';
// Added on: 22-08-2019
$route['manage_admin/report/copy_applicants_report/handler'] = 'manage_admin/reports/Copy_applicants_report/handler';
$route['manage_admin/report/copy_applicants_report/(:any)/(:any)'] = 'manage_admin/reports/Copy_applicants_report/index';
$route['manage_admin/report/copy_applicants_report'] = 'manage_admin/reports/Copy_applicants_report/index';
$route['manage-admin/copy-applicants/track-job'] = 'manage_admin/Copy_applicants/track_job';
$route['manage-admin/copy-applicants/revoke-job'] = 'manage_admin/Copy_applicants/revoke_job';
// Added on: 21-08-2019
$route['manage-admin/copy-applicants/fetch-applicants-by-job'] = 'manage_admin/Copy_applicants/fetch_applicants_by_job';
////added on 12/18/2019
$route['manage-admin/copy-applicants/move-applicants-new'] = 'manage_admin/Copy_applicants/move_applicants_new';
////
$route['manage-admin/copy-applicants/copy-job-with-applicants'] = 'manage_admin/Copy_applicants/copy_job_with_applicants';
$route['manage-admin/copy-applicants/fetch-jobs'] = 'manage_admin/Copy_applicants/fetch_jobs';
$route['manage-admin/copy-applicants/fetch-applicants-new'] = 'manage_admin/Copy_applicants/fetch_applicants_new';
$route['manage_admin/copy_applicants'] = 'manage_admin/Copy_applicants/index_new';
// Added on: 23-10-2019
$route['manage_admin/copy_employees'] = 'manage_admin/Copy_employees/index';
// Added on: 19-08-2019
$route['employee_profile/delete_file'] = 'employee_management/delete_file';
$route['import-applicant-csv/handler'] = 'import_applicants_csv/handler';
// Added on: 16-08-2019
$route['import-csv/handler'] = 'import_csv/handler';
// Added on: 09-08-2019
$route['assign-bulk-documents/fetch-employees'] = 'assign_bulk_documents/fetch_employees';
$route['assign-bulk-documents/fetch-applicants'] = 'assign_bulk_documents/fetch_applicants_all';
$route['assign-bulk-documents/fetch-applicants/(:any)'] = 'assign_bulk_documents/fetch_applicants/$1';
$route['assign-bulk-documents/upload-assign-document'] = 'assign_bulk_documents/upload_assign_document';
// Added on: 06-08-2019
$route['zip_recruiter_organic/(:any)'] = 'Zip_recruiter_organic/index/$1';
$route['assign_bulk_documents'] = 'Assign_bulk_documents';
// Added on: 05-08-2019
$route['indeed_feed_organic/(:any)'] = 'Indeed_feed_organic/index/$1';
// Added on: 02-08-2019
// $route['test'] = 'Home/test';
$route['modify/(:any)'] = 'Home/update_phonenumber/$1';
$route['update-phonenumber'] = 'Home/update_phonenumber_handler';
// Added on: 01-08-2019
$route['manage_admin/blocked_ips'] = 'manage_admin/Blocked_ips';
$route['manage_admin/blocked_ips/handler'] = 'manage_admin/Blocked_ips/handler';
// Added on: 24-07-2019
// $route['test'] = 'Twilio/test';
// Added on: 23-07-2019Twilio/receive_request';
$route['twilio/callback/(:any)'] = 'Twilio/receive_request';
$route['twilio/(:any)/(:any)'] = 'Twilio/receive_request';
$route['sms_cron'] = 'Twilio/sms_cron';
// Added on: 22-07-2019
$route['applicant_profile/(:any)/(:any)/(:any)'] = 'application_tracking_system/applicant_profile/$1/$2/$3';
$route['manage_admin/sms/(:any)'] = 'manage_admin/Sms/index/$1';
// Added on: 18-07-2019
$route['application_tracking_system/handler'] = 'Application_tracking_system/handler';
// Added on: 17-07-2019
// $route['sms/(:any)/(:any)'] = 'Home/receive_request';
$route['manage-admin/sms/handler'] = 'manage_admin/Sms/handler';
// Added on: 16-07-2019
$route['manage_admin/sms'] = 'manage_admin/Sms/index';
// Added on: 25-06-2019
$route['event-reminder-cron/(:any)'] = 'Home/event_reminder_cron/$1';
// Added on: 24-06-2019
$route['download-file/(:any)/(:num)'] = 'Home/download_file/$1/$2';
$route['download-event-file/(:any)'] = 'Home/download_event_file/$1';
$route['event-detail/(:any)'] = 'home/event_detail/$1';
$route['admin-event-handler'] = 'home/admin_event_handler';
// Added on: 12-03-2019
$route['manage_admin/event-detail/(:num)'] = 'manage_admin/calendar/event_detail/$1';
$route['manage_admin/process-event'] = 'manage_admin/calendar/process_event';
$route['manage_admin/get-events']    = 'manage_admin/calendar/get_events';
// Added on: 10-03-2019
$route['manage_admin/invoice/list_admin_invoices/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/list_admin_invoices/$1/$2/$3/$4/$5';
// Added on: 03-03-2019
$route['order_history'] = 'Order_history/index_new';
$route['order_history/download/(:any)/(:any)'] = 'Order_history/download/$1/$2';
$route['order_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Order_history/index_new/$1/$2/$3/$4/$5/$6';
// $route['order_history'] = 'Order_history/index';
// $route['order_history/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Order_history/index/$1/$2/$3/$4/$5/$6';
// Added on: 31-05-2019
$route['accurate_background/download/(:any)/(:any)'] = 'accurate_background/download/$1/$2';
$route['manage_admin/invoice/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/accurate_background/order_status/(:num)'] = 'manage_admin/accurate_background/order_status/$1';
// Added on: 09-07-2019
$route['manage_admin/accurate_background/manage_document/(:num)'] = 'manage_admin/accurate_background/manage_document/$1';
// Added on: 30-05-2019
$route['manage_admin/reports/accurate_background/download/(:any)/(:any)'] = 'manage_admin/reports/accurate_background/download/$1/$2';
// Added on: 29-05-2019
$route['manage_admin/reports/accurate_background'] = 'manage_admin/reports/accurate_background/index';
$route['manage_admin/reports/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/accurate_background/index/$1/$2/$3/$4/$5';
$route['manage_admin/accurate_background/download/(:any)/(:any)'] = 'manage_admin/accurate_background/download/$1/$2';
// Added on: 28-05-2019
$route['complynet'] = 'Complynet/index';
// Added on: 27-05-2019
$route['download/(:any)/(:num)/(:num)/(:any)/(:any)/(:any)'] = 'Home/download/$1/$2/$3/$4/$5/$6';
$route['download-event/(:any)'] = 'Home/download_event/$1';
// Added on: 21-05-2019
$route['manage_admin/copy_applicants/fetch_applicants_ajax/(:num)/(:num)/(:num)/(:num)/(:num)'] = 'manage_admin/Copy_applicants/fetch_applicants_ajax/$1/$2/$3/$4/$5';
// Added on: 20-05-2019
$route['manage_admin/copy_applicants/move_applicants'] = 'manage_admin/Copy_applicants/move_applicants';
// Added on: 16-05-2019
$route['manage_admin/my-events'] = 'manage_admin/Calendar/index';
// Added on: 10-05-2019
$route['learning_center/get-training-sessions/(:num)/([a-z])'] = 'learning_center/get_training_sessions/$1/$2';
$route['learning_center/get-training-sessions/(:num)/([a-z])/(:num)'] = 'learning_center/get_training_sessions/$1/$2/$3';
$route['calendar/reschedule-training-session'] = 'Calendar/reschedule_training_session';
// Added on: 06-05-2019
$route['calendar/fetch-online-videos'] = 'Calendar/fetch_online_videos';
// Added at: 12-04-2019
$route['calendar/get-reminder-email-history/(:num)/(:num)'] = "Calendar/get_reminder_email_history/$1/$2";
// Added at: 11-04-2019
$route['calendar/get-event-availablity-requests/(:num)/(:num)'] = "Calendar/get_event_availablity_requests/$1/$2";
// Added at: 08-04-2019
$route['event/(:any)'] = "Home/event/$1";
$route['event-handler'] = "Home/event_handler";
// $route['event/(:any)'] = "Home/event/$1";
// 28-03-2019
// calendar
// For AJAX
$route['calendar/event-handler'] = "Calendar/event_handler";
$route['calendar/get-events'] = "Calendar/get_events";
$route['calendar/get-address'] = "Calendar/get_address";
$route['calendar/get-applicant/(:any)'] = "Calendar/get_applicants/$1";
$route['background-check/get-applicant/(:any)'] = "accurate_background/get_applicants/$1";
$route['manage_admin/background-check/get-applicant/(:any)'] = "manage_admin/accurate_background/get_applicants/$1";
// $route['calendar/get-employers'] = "Calendar/get_employers";
$route['calendar/get-employees/(:any)'] = "Calendar/get_employees/$1";
$route['calendar/get-interviewers/(:any)'] = "Calendar/get_interviewers/$1";
// $route['calendar/get-jobs'] = "Calendar/get_jobs";
$route['calendar/get-event-detail/(:num)'] = "Calendar/get_event_detail/$1";
// Cron google hire
// 18-03-2019
$route['cron_google_hire'] = "Cron_google_hire/index";

$route['services/(:any)'] = "home/services/$1";
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//Employer Side
$route['schedule_your_free_demo'] = 'demo/schedule_your_free_demo';
//Register and login
$route['login'] = 'users/login';
$route['employee_login'] = 'users/employee_login';
$route['register'] = 'users/register';
$route['forgot_password'] = 'users/forgot_password';
$route['contact_us'] = 'users/contact_us';
$route['change_password/(:any)/(:any)'] = 'users/change_password/$1/$2';
$route['logout'] = 'users/logout';
//sub-account
//dashboard
$route['welcome'] = 'dashboard/welcome';
$route['authorized_document'] = 'hr_documents_management/authorized_document_listing';
$route['authorized_document/(:any)'] = 'hr_documents_management/authorized_document_listing/$1';
$route['view_assigned_authorized_document/(:any)/(:any)'] = 'hr_documents_management/view_assigned_authorized_document/$1/$2';
$route['employee_management_system'] = 'dashboard/employee_management_system';
$route['manage_admin'] = 'manage_admin/dashboard';
$route['admin'] = 'admin/login';
$route['add_listing'] = 'job_listings/add_listing';
$route['add_listing_advertise/(:any)'] = 'job_listings/add_listing_advertise/$1';
$route['add_listing_share/(:any)'] = 'job_listings/add_listing_share/$1';
$route['add_listing_advertise'] = 'job_listings/add_listing_advertise';
$route['add_listing_share'] = 'job_listings/add_listing_share';
$route['my_listings'] = 'job_listings/my_listings';
$route['my_listings/(:any)/(:any)/(:any)'] = 'job_listings/my_listings/$1/$2/$3';
$route['my_listings/(:any)/(:any)'] = 'job_listings/my_listings/$1/$2';
$route['my_listings/(:any)'] = 'job_listings/my_listings/$1';
$route['edit_listing/(:any)'] = 'job_listings/edit_listing/$1';
$route['edit_listing'] = 'job_listings/edit_listing';
$route['preview_listing'] = 'job_listings/preview_listing';
$route['preview_listing/(:any)'] = 'job_listings/preview_listing/$1';
$route['preview_listing_iframe'] = 'job_listings/preview_listing_iframe';
$route['preview_listing_iframe/(:any)'] = 'job_listings/preview_listing_iframe/$1';
$route['clone_listing/(:any)'] = 'job_listings/clone_listing/$1';
$route['clone_listing'] = 'job_listings/clone_listing';
$route['sponsor_listing/(:any)'] = 'job_listings/clone_listing/$1';
$route['sponsor_listing'] = 'job_listings/clone_listing';
$route['my_events'] = 'calendar/my_events';
$route['my_settings'] = 'settings/my_settings';
$route['my_settings_bak'] = 'settings/my_settings_bak';
$route['my_profile'] = 'employee_management/my_profile';
$route['login_password'] = 'employee_management/login_password';
$route['company_profile'] = 'settings/company_profile';
$route['company_address'] = 'settings/company_address';
$route['seo_tags'] = 'settings/seo_tags';
$route['embedded_code'] = 'settings/embedded_code';
$route['talent_network_content_configuration'] = 'settings/talent_network_content_configuration';
$route['portal_widget'] = 'settings/portal_widget';
$route['web_services'] = 'settings/web_services';
$route['domain_management'] = 'settings/domain_management';
$route['social_links'] = 'settings/social_links';
$route['user_management'] = 'settings/user_management';
$route['full_employment_application'] = 'settings/full_employment_application';
$route['full_employment_application/(:num)'] = 'settings/full_employment_application/$1';
// $route['export_time_off'] = 'timeoff/export';
$route['applicant_full_employment_application'] = 'form_full_employment_application/applicant_full_employment_application';
$route['applicant_full_employment_application/(:num)'] = 'form_full_employment_application/applicant_full_employment_application/$1';
$route['applicant_full_employment_application/(:num)/(:num)'] = 'form_full_employment_application/applicant_full_employment_application/$1/$2';
$route['cc_management'] = 'misc/cc_management';
$route['cc_management/(:any)'] = 'misc/cc_management/$1';
$route['edit_card/(:any)'] = 'misc/edit_card/$1';
$route['market_place'] = 'market_place';
$route['market_place/(:any)'] = 'market_place/index/$1';
$route['marketplace_details'] = 'market_place/marketplace_details';
$route['marketplace_details/(:num)'] = 'market_place/marketplace_details/$1';
/********NEW Emergency contacts - start - **********/
//$route['emergency_contacts'] = 'settings/emergency_contacts';
$route['emergency_contacts/ajax_handler'] = 'emergency_contacts/ajax_handler';
$route['emergency_contacts/(:any)'] = 'emergency_contacts/index/$1';
$route['emergency_contacts/(:any)/(:any)'] = 'emergency_contacts/index/$1/$2';
$route['emergency_contacts/(:any)/(:any)/(:any)'] = 'emergency_contacts/index/$1/$2/$3';
$route['add_emergency_contacts'] = 'emergency_contacts/add_emergency_contacts';
$route['add_emergency_contacts/(:any)'] = 'emergency_contacts/add_emergency_contacts/$1';
$route['add_emergency_contacts/(:any)/(:any)'] = 'emergency_contacts/add_emergency_contacts/$1/$2';
$route['add_emergency_contacts/(:any)/(:any)/(:any)'] = 'emergency_contacts/add_emergency_contacts/$1/$2/$3';
$route['edit_emergency_contacts'] = 'emergency_contacts/edit_emergency_contacts';
$route['edit_emergency_contacts/(:any)'] = 'emergency_contacts/edit_emergency_contacts/$1';
$route['edit_emergency_contacts/(:any)/(:any)'] = 'emergency_contacts/edit_emergency_contacts/$1/$2';
$route['edit_emergency_contacts/(:any)/(:any)/(:any)'] = 'emergency_contacts/edit_emergency_contacts/$1/$2/$3';
$route['edit_emergency_contacts/(:any)/(:any)/(:any)/(:any)'] = 'emergency_contacts/edit_emergency_contacts/$1/$2/$3/$4';
/********************NEW Emergency contacts - end - ********************************************************/


//$route['emergency_contacts'] = 'settings/emergency_contacts';
//$route['emergency_contacts/(:any)'] = 'settings/emergency_contacts/$1';
//$route['emergency_contacts/(:any)/(:any)'] = 'settings/emergency_contacts/$1/$2';
//$route['emergency_contacts/(:any)/(:any)/(:any)'] = 'settings/emergency_contacts/$1/$2/$3';

//$route['add_emergency_contacts'] = 'settings/add_emergency_contacts';
//$route['add_emergency_contacts/(:any)'] = 'settings/add_emergency_contacts/$1';
//$route['add_emergency_contacts/(:any)/(:any)'] = 'settings/add_emergency_contacts/$1/$2';
//$route['add_emergency_contacts/(:any)/(:any)/(:any)'] = 'settings/add_emergency_contacts/$1/$2/$3';
//
//$route['edit_emergency_contacts'] = 'settings/edit_emergency_contacts';
//$route['edit_emergency_contacts/(:any)'] = 'settings/edit_emergency_contacts/$1';
//$route['edit_emergency_contacts/(:any)/(:any)'] = 'settings/edit_emergency_contacts/$1/$2';

/*
$route['i9form'] = 'i9form/index';
$route['i9form/(:any)'] = 'i9form/index/$1';
$route['i9form/(:any)/(:any)'] = 'i9form/index/$1/$2';

$route['w4form'] = 'settings/w4form';
$route['w4form/(:any)'] = 'settings/w4form/$1';
$route['w4form/(:any)/(:any)'] = 'settings/w4form/$1/$2';
*/

/***********  New Dependents Route  ***********/
$route['dependants'] = 'dependents/index';
$route['dependants/(:any)'] = 'dependents/index/$1';
$route['dependants/(:any)/(:any)'] = 'dependents/index/$1/$2';
$route['dependants/(:any)/(:any)/(:any)'] = 'dependents/index/$1/$2/$3';
$route['add_dependant_information'] = 'dependents/add_dependant_information';
$route['add_dependant_information/(:any)'] = 'dependents/add_dependant_information/$1';
$route['add_dependant_information/(:any)/(:any)'] = 'dependents/add_dependant_information/$1/$2';
$route['add_dependant_information/(:any)/(:any)/(:any)'] = 'dependents/add_dependant_information/$1/$2/$3';
$route['edit_dependant_information'] = 'dependents/edit_dependant_information';
$route['edit_dependant_information/(:any)'] = 'dependents/edit_dependant_information/$1';
$route['edit_dependant_information/(:any)/(:any)'] = 'dependents/edit_dependant_information/$1/$2';
$route['edit_dependant_information/(:any)/(:any)/(:any)'] = 'dependents/edit_dependant_information/$1/$2/$3';
$route['edit_dependant_information/(:any)/(:any)/(:any)/(:any)'] = 'dependents/edit_dependant_information/$1/$2/$3/$4';
/**********    End   **********/
/***********  Old Dependents Route
 * $route['dependants'] = 'settings/dependants';
 * $route['dependants/(:any)'] = 'settings/dependants/$1';
 * $route['dependants/(:any)/(:any)'] = 'settings/dependants/$1/$2';
 * $route['dependants/(:any)/(:any)/(:any)'] = 'settings/dependants/$1/$2/$3';
 *
 * $route['add_dependant_information'] = 'settings/add_dependant_information';
 * $route['add_dependant_information/(:any)'] = 'settings/add_dependant_information/$1';
 * $route['add_dependant_information/(:any)/(:any)'] = 'settings/add_dependant_information/$1/$2';
 * $route['add_dependant_information/(:any)/(:any)/(:any)'] = 'settings/add_dependant_information/$1/$2/$3';
 *
 * $route['edit_dependant_information'] = 'settings/edit_dependant_information';
 * $route['edit_dependant_information/(:any)'] = 'settings/edit_dependant_information/$1';
 * $route['edit_dependant_information/(:any)/(:any)'] = 'settings/edit_dependant_information/$1/$2';
 **********    End   **********/
$route['drivers_license_info'] = 'settings/drivers_license_info';
$route['drivers_license_info/(:any)'] = 'settings/drivers_license_info/$1';
$route['drivers_license_info/(:any)/(:any)'] = 'settings/drivers_license_info/$1/$2';
$route['drivers_license_info/(:any)/(:any)/(:any)'] = 'settings/drivers_license_info/$1/$2/$3';
$route['occupational_license_info'] = 'settings/occupational_license_info';
$route['occupational_license_info/(:any)'] = 'settings/occupational_license_info/$1';
$route['occupational_license_info/(:any)/(:any)'] = 'settings/occupational_license_info/$1/$2';
$route['occupational_license_info/(:any)/(:any)/(:any)'] = 'settings/occupational_license_info/$1/$2/$3';
$route['equipment_info'] = 'settings/equipment_info';
$route['equipment_info/(:any)'] = 'settings/equipment_info/$1';
$route['equipment_info/(:any)/(:any)'] = 'settings/equipment_info/$1/$2';
$route['equipment_info/(:any)/(:any)/(:any)'] = 'settings/equipment_info/$1/$2/$3';
$route['archived_employee'] = 'employee_management/archived_employee';
$route['terminated_employee'] = 'employee_management/terminated_employee';
$route['employee_management'] = 'employee_management/employee_management';
$route['invite_colleagues'] = 'employee_management/invite_colleagues';
$route['send_offer_letter_documents'] = 'employee_management/send_offer_letter_documents';
$route['send_offer_letter_documents/(:num)'] = 'employee_management/send_offer_letter_documents/$1';
$route['send_hr_documents'] = 'employee_management/send_hr_documents';
$route['send_hr_documents/(:num)'] = 'employee_management/send_hr_documents/$1';
$route['employee_profile'] = 'employee_management/employee_profile';
$route['employee_profile/(:num)'] = 'employee_management/employee_profile/$1';
$route['employee_login_credentials'] = 'employee_management/employee_login_credentials';
$route['employee_login_credentials/(:num)'] = 'employee_management/employee_login_credentials/$1';
//private message
$route['outbox'] = 'private_messages/outbox';
$route['compose_message'] = 'private_messages/compose_message';
$route['outbox_message_detail/(:num)'] = 'private_messages/outbox_message_detail/$1';
$route['inbox_message_detail/(:num)'] = 'private_messages/inbox_message_detail/$1';
$route['reply_message/(:num)'] = 'private_messages/reply_message/$1';
//Appearance
$route['fmana'] = 'appearance/customize_appearance';
$route['customize_appearance/(:num)'] = 'appearance/customize_appearance/$1';
$route['enterprise_theme_activation'] = 'appearance/enterprise_theme_activation';
$route['appearance/get_pages_name'] = 'appearance/get_pages_name';
$route['appearance/add_additional_sections/(:num)'] = 'appearance/add_additional_sections/$1';
//Edit manual candidates
$route['edit_candidate/(:num)'] = 'manual_candidate/edit_candidate/$1';
$route['edit_applicant/(:num)'] = 'manual_candidate/edit_applicant/$1';
$route['application_tracking_system/ajax_responder'] = 'application_tracking_system/ajax_responder';
$route['application_tracking_system/(:any)'] = 'application_tracking_system/index/$1';
$route['application_tracking_system/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2';
$route['application_tracking_system/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5/$6';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5/$6/$7';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5/$6/$7/$8';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['application_tracking_system/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/index/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
$route['application_tracking_system/update_status'] = 'application_tracking_system/update_status';
// $route['application_tracking_system/applicant_profile/(:any)'] = 'application_tracking_system/applicant_profile/$1';
$route['applicant_profile'] = 'application_tracking_system/applicant_profile';
$route['applicant_profile/send_kpa_onboarding'] = 'application_tracking_system/send_kpa_onboarding';
$route['applicant_profile/insert_notes'] = 'application_tracking_system/insert_notes';
$route['update_notes_from_popup'] = 'application_tracking_system/update_notes_from_popup';
$route['insert_review_from_popup'] = 'application_tracking_system/insert_review_from_popup';
$route['applicant_profile/applicant_message'] = 'application_tracking_system/applicant_message';
$route['applicant_profile/save_rating'] = 'application_tracking_system/save_rating';
$route['applicant_profile/updateEmployerStatus'] = 'application_tracking_system/updateEmployerStatus';
$route['applicant_profile/event_schedule'] = 'application_tracking_system/event_schedule';
$route['applicant_profile/deleteEvent'] = 'application_tracking_system/deleteEvent';
$route['applicant_profile/deleteMessage'] = 'application_tracking_system/deleteMessage';
$route['applicant_profile/resend_message'] = 'application_tracking_system/resend_message';
$route['applicant_profile/delete_note'] = 'application_tracking_system/delete_note';
$route['applicant_profile/send_reference_request_email'] = 'application_tracking_system/send_reference_request_email';
$route['applicant_profile/upload_attachment'] = 'application_tracking_system/upload_attachment';
$route['applicant_profile/upload_extra_attachment'] = 'application_tracking_system/upload_extra_attachment';
$route['applicant_profile/downloadFile'] = 'application_tracking_system/downloadFile';
$route['applicant_profile/delete_file'] = 'application_tracking_system/delete_file';
$route['applicant_profile/send_reference_request_email'] = 'application_tracking_system/send_reference_request_email';
$route['applicant_profile/delete_single_applicant'] = 'application_tracking_system/delete_single_applicant';
$route['applicant_profile/active_single_applicant'] = 'application_tracking_system/active_single_applicant';
$route['applicant_profile/archive_single_applicant'] = 'application_tracking_system/archive_single_applicant';
$route['applicant_profile/event_schedule'] = 'application_tracking_system/event_schedule';
$route['applicant_profile/deleteEvent'] = 'application_tracking_system/deleteEvent';
$route['applicant_profile/ajax_responder'] = 'application_tracking_system/ajax_responder';
$route['applicant_profile/upload_attachment/(:any)'] = 'application_tracking_system/upload_attachment/$1';
$route['applicant_profile/downloadFile/(:any)'] = 'application_tracking_system/downloadFile/$1';
$route['applicant_profile/(:num)'] = 'application_tracking_system/applicant_profile/$1';
$route['applicant_profile/(:num)/(:num)'] = 'application_tracking_system/applicant_profile/$1/$2';
$route['archive_single_applicant'] = 'application_tracking_system/archive_single_applicant';
$route['active_single_applicant'] = 'application_tracking_system/active_single_applicant';
$route['delete_single_applicant'] = 'application_tracking_system/delete_single_applicant';
$route['archived_applicants'] = 'application_tracking_system/archived_applicants';
$route['archived_applicants/(:any)'] = 'application_tracking_system/archived_applicants/$1';
$route['archived_applicants/(:any)/(:any)'] = 'application_tracking_system/archived_applicants/$1/$2';
$route['archived_applicants/(:any)/(:any)/(:any)'] = 'application_tracking_system/archived_applicants/$1/$2/$3';
$route['archived_applicants/(:any)/(:any)/(:any)/(:any)'] = 'application_tracking_system/archived_applicants/$1/$2/$3/$4';
//Admin Side routes
//$route['manage_admin/employers/add_employer/(:num)'] = 'manage_admin/employers/add_employer/$1';
//$route['manage_admin/employers/edit_employer/(:num)'] = 'manage_admin/employers/edit_employer/$1';
//$route['manage_admin/employers/(:any)'] = 'manage_admin/employers/index/$1';
//$route['manage_admin/employers/(:any)/(:any)'] = 'manage_admin/employers/index/$1/$2';
//$route['manage_admin/employers/(:any)/(:any)/(:any)'] = 'manage_admin/employers/index/$1/$2/$3';
//$route['manage_admin/employers/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/employers/index/$1/$2/$3/$4';
$route['manage_admin/employers/add_employer/(:num)'] = 'manage_admin/employers/add_employer/$1';
$route['manage_admin/employers/edit_employer/(:num)'] = 'manage_admin/employers/edit_employer/$1';
$route['manage_admin/employers/(:num)'] = 'manage_admin/employers/index/$1';
$route['manage_admin/employers/(:any)/(:num)'] = 'manage_admin/employers/index/$1/$2';
$route['manage_admin/employers/(:any)/(:num)/(:any)'] = 'manage_admin/employers/index/$1/$2/$3';
$route['manage_admin/employers/(:any)/(:num)/(:any)/(:any)'] = 'manage_admin/employers/index/$1/$2/$3/$4';
$route['manage_admin/employers/(:any)/(:num)/(:any)/(:any)/(:num)'] = 'manage_admin/employers/index/$1/$2/$3/$4/$5';
$route['manage_admin/companies/ajax_change_status'] = 'manage_admin/companies/ajax_change_status';
$route['manage_admin/companies/(:num)'] = 'manage_admin/companies/index/$1';
$route['manage_admin/companies/search_company/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/companies/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/companies/search_company/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/companies/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/companies/add_company'] = 'manage_admin/companies/add_company';
$route['manage_admin/companies/ajax_responder'] = 'manage_admin/companies/ajax_responder';
$route['manage_admin/company_security_settings/(:any)'] = 'manage_admin/company_security_settings/index/$1';
$route['manage_admin/reports/applicants_report/ajax_responder'] = 'manage_admin/reports/applicants_report/ajax_responder';
//Contact us logs
$route['manage_admin/contactus_enquiries'] = 'manage_admin/logs/contactus_enquiries';
$route['manage_admin/log_detail/(:num)'] = 'manage_admin/logs/log_detail/$1';
//Email logs
$route['manage_admin/email_enquiries'] = 'manage_admin/logs/email_enquiries';
$route['manage_admin/email_enquiries/(:num)'] = 'manage_admin/logs/email_enquiries/$1';
$route['manage_admin/email_enquiries/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/email_enquiries/$1/$2/$3/$4';
$route['manage_admin/email_enquiries/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/email_enquiries/$1/$2/$3/$4/$5';
$route['manage_admin/email_enquiries/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'manage_admin/logs/email_enquiries/$1/$2/$3/$4/$5/$6';
$route['manage_admin/email_log/(:num)'] = 'manage_admin/logs/email_log/$1';
$route['manage_admin/resend_email/(:num)'] = 'manage_admin/logs/resend_email/$1';
$route['manage_admin/sms_log/(:num)'] = 'manage_admin/logs/sms_log/$1';
$route['manage_admin/sms_enquiries'] = 'manage_admin/logs/sms_enquiries';
$route['manage_admin/sms_enquiries/(:num)'] = 'manage_admin/logs/sms_enquiries/$1';
$route['manage_admin/sms_enquiries/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/sms_enquiries/$1/$2/$3/$4';
$route['manage_admin/sms_enquiries/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/sms_enquiries/$1/$2/$3/$4/$5';
$route['manage_admin/sms_enquiries/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/sms_enquiries/$1/$2/$3/$4/$5/$6';
$route['manage_admin/sms_enquiries/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'manage_admin/logs/sms_enquiries/$1/$2/$3/$4/$5/$6/$7';

////Admin TimeOff
$route['manage_admin/default_policy_listing'] = 'manage_admin/time_off/default_policy_listing';
$route['manage_admin/add_default_policy'] = 'manage_admin/time_off/add_default_policy';

$route['manage_admin/manage_policies/(:num)'] = 'manage_admin/time_off/index/$1';
$route['manage_admin/manage_approvers/(:num)'] = 'manage_admin/time_off/manage_approvers/$1';
$route['manage_admin/time_off_settings/(:num)'] = 'manage_admin/time_off/time_off_settings/$1';
$route['manage_admin/manage_time_off_icons/(:num)'] = 'manage_admin/time_off/manage_time_off_icons/$1';
$route['manage_admin/time_off/handler'] = 'manage_admin/time_off/handler';


////Modules
$route['manage_admin/modules'] = 'manage_admin/logs/modules';
$route['manage_admin/modules/(:any)'] = 'manage_admin/logs/modules/$1';
$route['manage_admin/modules/(:any)/(:any)'] = 'manage_admin/logs/modules/$1/$2';
$route['manage_admin/modules/(:any)/(:any)/(:any)'] = 'manage_admin/logs/modules/$1/$2/$3';
$route['manage_admin/modules/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/modules/$1/$2/$3/$4';
$route['manage_admin/modules/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/logs/modules/$1/$2/$3/$4/$5';
$route['manage_admin/edit_module/(:num)'] = 'manage_admin/logs/edit_module/$1';
$route['manage_admin/company_module/(:num)'] = 'manage_admin/logs/company_module/$1';
$route['manage_admin/change_company_status'] = 'manage_admin/logs/change_company_status';
$route['manage_admin/company_onboarding/(:num)'] = 'manage_admin/logs/company_onboarding/$1';
//Notification Email logs
$route['manage_admin/notification_email_log'] = 'manage_admin/logs/notification_email_log';
$route['manage_admin/notification_email_log_view/(:num)'] = 'manage_admin/logs/notification_log/$1';
$route['manage_admin/notification_email_log/(:any)/(:any)/(:any)'] = 'manage_admin/logs/notification_email_log/$1/$2/$3';
$route['manage_admin/notification_email_log/(:any)/(:any)/(:any)/(:num)'] = 'manage_admin/logs/notification_email_log/$1/$2/$3/$4';
//private messages
$route['manage_admin/inbox_message_detail/(:num)'] = 'manage_admin/private_messages/inbox_message_detail/$1';
$route['manage_admin/outbox_message_detail/(:num)'] = 'manage_admin/private_messages/outbox_message_detail/$1';
$route['manage_admin/outbox_message_detail/(:num)/(:num)'] = 'manage_admin/private_messages/outbox_message_detail/$1/$2';
$route['manage_admin/outbox'] = 'manage_admin/private_messages/outbox';
$route['manage_admin/compose_message'] = 'manage_admin/private_messages/compose_message';
$route['manage_admin/reply_message/(:num)'] = 'manage_admin/private_messages/reply_message/$1';
$route['manage_admin/enquiry_message_details/(:num)'] = 'manage_admin/free_demo/enquiry_message_details/$1';
$route['manage_admin/demo_admin_reply/(:any)'] = 'manage_admin/free_demo/demo_admin_reply/$1';
$route['manage_admin/view_reply/(:any)'] = 'manage_admin/free_demo/view_reply/$1';
$route['manage_admin/demo_admin_reply'] = 'manage_admin/free_demo/demo_admin_reply';
$route['manage_admin/free_demo/(:num)'] = 'manage_admin/free_demo/index/$1';
$route['manage_admin/edit_demo_request/(:any)'] = 'manage_admin/free_demo/add_demo_request/$1';
//******* HASSAN WORKING AREA --- START --- \\

$route['manage_admin/referred_clients'] = 'manage_admin/free_demo/index';
$route['manage_admin/referred_clients/(:num)'] = 'manage_admin/free_demo/index/$1';
$route['manage_admin/referred_clients/enquiry_message_details/(:num)'] = 'manage_admin/free_demo/enquiry_message_details/$1';
$route['manage_admin/referred_clients/demo_admin_reply/(:any)'] = 'manage_admin/free_demo/demo_admin_reply/$1';
$route['manage_admin/referred_clients/view_demo_email_reply/(:any)'] = 'manage_admin/free_demo/view_demo_email_reply/$1';
$route['manage_admin/referred_clients/add_demo_request'] = 'manage_admin/free_demo/add_demo_request';
$route['manage_admin/referred_clients/edit_demo_request/(:any)'] = 'manage_admin/free_demo/add_demo_request/$1';

//******* HASSAN WORKING AREA ---  END  --- \\
$route['background_check/list_packages'] = 'background_check/list_packages';
$route['applicant_bg_check/(:any)'] = 'background_check/applicant_bg_check/$1';
$route['applicant_bg_check'] = 'background_check/applicant_bg_check';
$route['hr_documents'] = 'hr_documents/index';
$route['add_hr_document'] = 'hr_documents/add_hr_document';
$route['employee_document'] = 'hr_documents/employee_document';
$route['employee_document/(:any)'] = 'hr_documents/employee_document/$1';
$route['edit_hr_document'] = 'hr_documents/edit_hr_document';
$route['edit_hr_document/(:any)'] = 'hr_documents/edit_hr_document/$1';
$route['archived_document'] = 'hr_documents/archived_document';
$route['upload_response_file'] = 'my_hr_documents/upload_response_file';
$route['my_hr_documents'] = 'my_hr_documents/index';
$route['my_hr_documents/textToWordFile/(:any)'] = 'my_hr_documents/textToWordFile/$1';
$route['my_hr_documents/textToWordUploadedFile/(:any)'] = 'my_hr_documents/textToWordUploadedFile/$1';
$route['my_hr_documents/(:any)/(:num)'] = 'my_hr_documents/index/$1/$2';
$route['background_check/activate'] = 'background_check/activate';
$route['background_check/activate_old'] = 'background_check/activate_old';
$route['background_check'] = 'background_check/index';
$route['background_check/(:any)'] = 'background_check/index/$1';
$route['background_check/(:any)/(:any)'] = 'background_check/index/$1/$2';
$route['background_check/(:any)/(:any)/(:any)'] = 'background_check/index/$1/$2/$3';
$route['background_report'] = 'background_check/background_report';
$route['background_report/(:any)'] = 'background_check/background_report/$1';
$route['save_offer_letter'] = 'hr_documents/save_offer_letter';
$route['update_offer_letter'] = 'hr_documents/update_offer_letter';
$route['received_documents/(:any)'] = 'received_documents/index/$1';
$route['employee_registration/(:any)'] = 'received_documents_onboarding/index/$1';
$route['employee_registration'] = 'received_documents_onboarding/index';
$route['onboard_eligibility_verification/(:any)'] = 'received_documents_onboarding/onboard_eligibility_verification/$1';
$route['onboard_eligibility_verification'] = 'received_documents_onboarding/onboard_eligibility_verification';
$route['received_offer_letter/(:any)'] = 'received_documents_onboarding/received_offer_letter/$1';
$route['received_offer_letter'] = 'received_documents_onboarding/received_offer_letter';
$route['onboard_received_document/(:any)'] = 'received_documents_onboarding/onboard_received_document/$1';
$route['onboard_received_document'] = 'received_documents_onboarding/onboard_received_document';
$route['drug_test'] = 'background_check/drug_test';
$route['drug_test/(:any)'] = 'background_check/drug_test/$1';
$route['drug_test/(:any)/(:any)'] = 'background_check/drug_test/$1/$2';
$route['drug_test/(:any)/(:any)/(:any)'] = 'background_check/drug_test/$1/$2/$3';
$route['drug_report'] = 'background_check/drug_report';
$route['drug_report/(:any)'] = 'background_check/drug_report/$1';
$route['order_detail'] = 'order_history/order_detail';
$route['order_detail/(:any)'] = 'order_history/order_detail/$1';
//Reference Checks Routes
$route['reference_checks'] = 'reference_checks/index';
$route['reference_checks/(:any)'] = 'reference_checks/index/$1';
$route['reference_checks/(:any)/(:any)'] = 'reference_checks/index/$1/$2';
$route['reference_checks/(:any)/(:any)/(:any)'] = 'reference_checks/index/$1/$2/$3';
$route['edit_reference_checks'] = 'reference_checks/edit';
$route['edit_reference_checks/(:any)'] = 'reference_checks/edit/$1';
$route['edit_reference_checks/(:any)/(:any)'] = 'reference_checks/edit/$1/$2';
$route['edit_reference_checks/(:any)/(:any)/(:any)'] = 'reference_checks/edit/$1/$2/$3';
$route['edit_reference_checks/(:any)/(:any)/(:any)/(:any)'] = 'reference_checks/edit/$1/$2/$3/$4';
$route['add_reference_checks/(:any)/(:any)'] = 'reference_checks/edit/$1/$2';
$route['add_reference_checks/(:any)/(:any)/(:any)'] = 'reference_checks/edit/$1/$2/$3';
$route['add_reference_checks/(:any)/(:any)/(:any)/(:any)'] = 'reference_checks/edit/$1/$2/$3/$4';
$route['reference_checks_questionnaire'] = 'reference_checks/questionnaire';
$route['reference_checks_questionnaire/(:any)'] = 'reference_checks/questionnaire/$1';
$route['reference_checks_questionnaire/(:any)/(:any)'] = 'reference_checks/questionnaire/$1/$2';
$route['reference_checks_questionnaire/(:any)/(:any)/(:any)'] = 'reference_checks/questionnaire/$1/$2/$3';
$route['reference_checks_questionnaire/(:any)/(:any)/(:any)/(:any)'] = 'reference_checks/questionnaire/$1/$2/$3/$4';
$route['reference_questionnaire'] = 'reference_checks_questionnaire_public/index';
$route['reference_questionnaire/(:any)'] = 'reference_checks_questionnaire_public/index/$1';
$route['kpa_onboarding'] = 'settings/kpa_onboarding';
$route['outsourced_hr_compliance_and_onboarding'] = 'settings/kpa_onboarding';
$route['reference_checks_public/(:any)'] = 'reference_checks_public/index/$1';
$route['facebook_configuration'] = 'facebook_configuration/facebook_configuration';
//$route['facebook_configuration/(:any)'] = 'facebook_configuration/facebook_configuration';
$route['facebook_configuration/instructions'] = 'facebook_configuration/facebook_api_instructions';
$route['jobs_list/facebook/(:any)'] = 'jobs_for_facebook/index/$1';
//Job Listing Templates
$route['manage_admin/job_templates'] = 'manage_admin/job_listing_templates/index';
$route['manage_admin/job_templates/add'] = 'manage_admin/job_listing_templates/add_edit';
$route['manage_admin/job_templates/edit/(:any)'] = 'manage_admin/job_listing_templates/add_edit/$1';
//Job Listing Template Groups
$route['manage_admin/job_template_groups'] = 'manage_admin/job_listing_templates/index';
$route['manage_admin/job_template_groups/add'] = 'manage_admin/job_listing_templates/add_edit_group';
$route['manage_admin/job_template_groups/edit/(:any)'] = 'manage_admin/job_listing_templates/add_edit_group/$1';
// Security Access Level
$route['security_access_level'] = 'security_access_level/index';
//Account activation
$route['account_activation'] = 'account_activation/index';
$route['account_activation/(:any)'] = 'account_activation/index/$1';
$route['user_validation'] = 'account_activation/user_validation';
//Import CSV
$route['import_csv'] = 'import_csv/index';
$route['import_csv/upload'] = 'import_csv/do_upload';
//Accurate Background Check Report Admin Side
$route['manage_admin/accurate_background/report'] = 'manage_admin/accurate_background/report';
$route['manage_admin/accurate_background/report/(:any)'] = 'manage_admin/accurate_background/report/$1';
// accurate background
$route['accurate_background/(:any)'] = 'accurate_background/index/$1';
// tickets/technical support
$route['support_tickets/process_ticket'] = 'support_tickets/process_ticket';
$route['support_tickets/add'] = 'support_tickets/add';
$route['support_tickets/view'] = 'support_tickets/view';
$route['support_tickets/(:any)'] = 'support_tickets/index/$1';
// Event Announcement
$route['announcements/add'] = 'announcements/add';
$route['announcements/delete_record_ajax'] = 'announcements/delete_record_ajax';
$route['announcements/ajax_handler'] = 'announcements/ajax_handler';
$route['announcements/edit/(:any)'] = 'announcements/edit/$1';
$route['announcements/view/(:any)'] = 'announcements/view/$1';
$route['announcements/change_status'] = 'announcements/change_status';
$route['announcements/(:any)'] = 'announcements/index/$1';
$route['list_announcements'] = 'announcements/manage_announcements';
// tickets/technical support for manage_admin
$route['manage_admin/support_tickets/(:any)'] = 'manage_admin/support_tickets/index/$1';
// notification emails under manage_admin
$route['manage_admin/notification_emails/ajax_responder'] = 'manage_admin/notification_emails/ajax_responder';
$route['manage_admin/notification_emails/(:any)'] = 'manage_admin/notification_emails/index/$1';
$route['manage_admin/notification_emails/remove_contact_ajax'] = 'manage_admin/notification_emails/remove_contact_ajax';
//Reference Network
$route['referral_network'] = 'reference_network/reference_network';
$route['referral_network/(:any)'] = 'reference_network/reference_network/$1';
$route['referral_network/(:any)/(:any)'] = 'reference_network/reference_network/$1/$2';
$route['my_referral_network'] = 'reference_network';
$route['my_referral_network/add'] = 'reference_network/add_edit';
$route['my_referral_network/(:any)'] = 'reference_network/index/$1';
$route['my_referral_network/view/(:any)'] = 'reference_network/view/$1';
//Job Listing Categories Manager
$route['job_listing_categories'] = 'job_listing_categories_manager';
$route['job_listing_categories/(:num)'] = 'job_listing_categories_manager/index/$1';
$route['job_listing_categories/add'] = 'job_listing_categories_manager/add_edit';
$route['job_listing_categories/edit/(:any)'] = 'job_listing_categories_manager/add_edit/$1';
//Google Drive
$route['google/(:any)'] = 'google/index/$1';
//Reports
$route['reports/candidates_between_period'] = 'reports/generate_candidates_between_certain_period';
$route['reports/candidates_between_period/(:any)/(:any)/(:any)'] = 'reports/generate_candidates_between_certain_period/$1/$2/$3';
$route['reports/candidates_between_period/(:any)/(:any)/(:any)/(:any)'] = 'reports/generate_candidates_between_certain_period/$1/$2/$3/$4';
$route['reports/candidates_between_period/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/generate_candidates_between_certain_period/$1/$2/$3/$4/$5';
$route['reports/candidates_between_period/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/generate_candidates_between_certain_period/$1/$2/$3/$4/$5/$6';
$route['reports/candidates_between_period/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/generate_candidates_between_certain_period/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/recurring_payments'] = 'manage_admin/recurring_payments/index';
$route['manage_admin/recurring_payments/add'] = 'manage_admin/recurring_payments/add_edit_recurring_payment';
$route['manage_admin/recurring_payments/edit/(:num)'] = 'manage_admin/recurring_payments/add_edit_recurring_payment/$1';
$route['form_recurring_payments_authorization/(:any)'] = 'form_recurring_payments_authorization/index/$1';
$route['form_end_user_license_agreement/(:any)'] = 'form_end_user_license_agreement/index/$1';
$route['form_end_user_license_agreement/(:any)/(:any)'] = 'form_end_user_license_agreement/index/$1/$2';
$route['form_affiliate_end_user_license_agreement/(:any)'] = 'form_affiliate_end_user_license_agreement/index/$1';
$route['form_affiliate_end_user_license_agreement/(:any)/(:any)'] = 'form_affiliate_end_user_license_agreement/index/$1/$2';
$route['form_credit_card_authorization/(:any)'] = 'form_credit_card_authorization/index/$1';
$route['form_credit_card_authorization/regenerate/(:any)'] = 'form_credit_card_authorization/regenerate_credit_card_authorization/$1';
$route['form_credit_card_authorization/(:any)/(:any)'] = 'form_credit_card_authorization/index/$1/$2';
$route['form_company_contacts/(:any)'] = 'form_company_contacts/index/$1';
$route['form_company_contacts/(:any)/(:any)'] = 'form_company_contacts/index/$1/$2';
$route['form_company_agreements/(:any)'] = 'form_company_agreements/index/$1';
$route['form_full_employment_application/send_form'] = 'form_full_employment_application/send_form';
$route['form_full_employment_application/(:any)'] = 'form_full_employment_application/index/$1';
$route['Job_screening_questionnaire/(:any)'] = 'Job_screening_questionnaire/index/$1';
$route['Job_screening_questionnaire/(:any)/(:any)'] = 'Job_screening_questionnaire/index/$1/$2';
//$route['Job_screening_questionnaire/send_interview_questionnaires'];
$route['send_interview_questionnaires'] = 'Job_screening_questionnaire/send_interview_questionnaires';
$route['send_interview_questionnaires/(:num)'] = 'Job_screening_questionnaire/send_interview_questionnaires/$1';
$route['send_interview_questionnaires/(:num)/(:num)'] = 'Job_screening_questionnaire/send_interview_questionnaires/$1/$2';
$route['send_interview_questionnaires/(:num)/(:num)/(:num)'] = 'Job_screening_questionnaire/send_interview_questionnaires/$1/$2/$3';
$route['send_interview_questionnaires/(:num)/(:num)/(:num)/(:num)'] = 'Job_screening_questionnaire/send_interview_questionnaires/$1/$2/$3/$4';
//social setting
$route['manage_admin/social_settings'] = 'manage_admin/settings/social_settings';
//Company Notes
$route['manage_admin/company_notes/add/(:any)'] = 'manage_admin/companies/add_edit_company_note/$1';
$route['manage_admin/company_note/edit/(:any)/(:any)'] = 'manage_admin/companies/add_edit_company_note/$1/$2';
$route['manage_admin/company_note/delete'] = 'manage_admin/companies/delete_admin_company_note';
//Super Admin Documents
$route['manage_admin/documents/send/(:any)'] = 'manage_admin/documents/send/$1';
$route['manage_admin/documents/(:any)'] = 'manage_admin/documents/index/$1';
$route['manage_admin/documents/regenerate_credit_card_authorization/(:any)'] = 'manage_admin/documents/regenerate_credit_card_authorization/$1';
$route['manage_admin/documents/regenerate_enduser_license_agreement/(:any)'] = 'manage_admin/documents/regenerate_enduser_license_agreement/$1';
$route['manage_admin/documents/regenerate_company_contacts_document/(:any)'] = 'manage_admin/documents/regenerate_company_contacts_document/$1';
$route['manage_admin/documents/(:any)/(:any)'] = 'manage_admin/documents/index/$1/$2';
$route['manage_admin/documents'] = 'manage_admin/documents';
$route['manage_admin/documents/check_signed_forms'] = 'manage_admin/documents/check_signed_forms';
//Marketing Agency Documents
$route['manage_admin/marketing_agency_documents/assign_w9_form/(:any)'] = 'manage_admin/marketing_agency_documents/assign_w9_form/$1';
$route['manage_admin/marketing_agency_documents/send/(:any)'] = 'manage_admin/marketing_agency_documents/send/$1';
$route['manage_admin/marketing_agency_documents/ajax_responder'] = 'manage_admin/marketing_agency_documents/ajax_responder';
$route['manage_admin/marketing_agency_documents/print_download_w9_form/(:any)/(:any)'] = 'manage_admin/marketing_agency_documents/print_download_w9_form/$1/$2';
$route['manage_admin/marketing_agency_documents/(:any)'] = 'manage_admin/marketing_agency_documents/index/$1';
$route['manage_admin/marketing_agency_documents/(:any)/(:any)'] = 'manage_admin/marketing_agency_documents/index/$1/$2';
//Company Billing Contacts
$route['manage_admin/company_billing_contacts/(:any)'] = 'manage_admin/company_billing_contacts/index/$1';
$route['manage_admin/company_billing_contacts/add/(:num)/(:num)'] = 'manage_admin/company_billing_contacts/add_edit/$1/$2';
$route['manage_admin/company_billing_contacts/edit/(:num)/(:num)'] = 'manage_admin/company_billing_contacts/add_edit/$1/$2';
//Company Portal Email Templates
$route['manage_admin/portal_email_templates/(:num)'] = 'manage_admin/portal_email_templates/index/$1';
$route['eeo/form'] = 'eeo/form';
$route['eeo/form/(:any)/(:num)'] = 'eeo/form/$1/$2';
$route['eeo/form/(:any)/(:num)/(:num)'] = 'eeo/form/$1/$2/$3';
$route['eeo/(:any)'] = 'eeo/index/$1';
$route['eeo/(:any)/(:any)'] = 'eeo/index/$1/$2';
$route['eeo/(:any)/(:any)/(:any)'] = 'eeo/index/$1/$2/$3';
$route['eeo/(:any)/(:any)/(:any)/(:any)'] = 'eeo/index/$1/$2/$3/$4';
$route['eeo/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'eeo/index/$1/$2/$3/$4/$5';
$route['eeo/export_excel'] = 'eeo/export_excel';
$route['job_products_report'] = 'order_history/job_products_report';
$route['job_products_report/(:any)'] = 'order_history/job_products_report/index/$1';
$route['order_history/(:any)'] = 'order_history/index/$1';
$route['manage_admin/reports/job_products_report/(:any)'] = 'manage_admin/reports/job_products_report/index/$1';
$route['manage_admin/reports/applicant_source_report_daily/(:any)'] = 'manage_admin/reports/applicant_source_report_daily/index/$1';
$route['manage_admin/reports/main/facebook_job_report'] = 'manage_admin/reports/Main/facebook_job_report';
$route['manage_admin/reports/main/blacklist_email'] = 'manage_admin/reports/Main/blacklist_email';
//Resumes Module
$route['resume_database'] = 'resume_database/index';
$route['resume_database/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'resume_database/index/$1/$2/$3/$4/$5/$6';
$route['resume_database/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)'] = 'resume_database/index/$1/$2/$3/$4/$5/$6/$7';
//Job Listing Categories Super Admin
$route['manage_admin/job_categories_manager'] = 'manage_admin/job_categories_manager/index';
$route['manage_admin/job_categories_manager/(:num)'] = 'manage_admin/job_categories_manager/index/$1';
$route['manage_admin/job_categories_manager/add_job_category'] = 'manage_admin/job_categories_manager/add_job_category';
$route['manage_admin/job_categories_manager/edit_job_category/(:num)'] = 'manage_admin/job_categories_manager/edit_job_category/$1';
//Interview Questionnaires
$route['interview_questionnaires/(:any)/(:num)/(:num)'] = 'interview_questionnaire/launch/$1/$2/$3';
$route['interview_questionnaires/(:any)/(:num)/(:num)/(:num)'] = 'interview_questionnaire/launch/$1/$2/$3/$4';
//Applicant Origination Tracker Report
//$route['manage_admin/reports/applicant_origination_tracker/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_origination_report/$1/$2/$3/$4/$5';
//Applicant Interview Scores Report
$route['manage_admin/reports/applicant_interview_scores/(:any)/(:any)'] = 'manage_admin/reports/applicant_interview_scores/index/$1/$2';
// for corporate management in manage_admin
$route['manage_admin/corporate_management/ajax_responder'] = 'manage_admin/corporate_management/ajax_responder';
$route['manage_admin/corporate_management/add_corporate_site'] = 'manage_admin/corporate_management/add_corporate_site';
$route['manage_admin/corporate_management/edit_corporate_site'] = 'manage_admin/corporate_management/edit_corporate_site';
$route['manage_admin/corporate_management/(:any)'] = 'manage_admin/corporate_management/index/$1';
// changing from automotive groups to corporate groups
//$route['manage_admin/corporate_groups'] = 'manage_admin/automotive_groups/index';
$route['manage_admin/corporate_groups/add'] = 'manage_admin/corporate_groups/add';
$route['manage_admin/corporate_groups/(:any)'] = 'manage_admin/corporate_groups/index/$1';
//$route['manage_admin/corporate_groups/add'] = 'manage_admin/automotive_groups/add';
//$route['manage_admin/corporate_groups/edit/(:any)'] = 'manage_admin/automotive_groups/edit/$1';
//$route['manage_admin/corporate_groups/member_companies/(:any)'] = 'manage_admin/automotive_groups/member_companies/$1';
$route['video_interview_system/candidate_instructions'] = 'video_interview_system/candidate_instructions';
$route['video_interview_system/how_to'] = 'video_interview_system/how_to';
$route['video_interview_system/upload'] = 'video_interview_system/upload';
$route['video_interview_system/add'] = 'video_interview_system/add';
$route['video_interview_system/add/(:any)'] = 'video_interview_system/add/$1';
$route['video_interview_system/manage_template/(:any)'] = 'video_interview_system/manage_template/$1';
$route['video_interview_system/edit'] = 'video_interview_system/edit';
$route['video_interview_system/edit/(:any)'] = 'video_interview_system/edit/$1';
$route['video_interview_system/edit/(:any)/(:any)'] = 'video_interview_system/edit/$1/$2';
$route['video_interview_system/rating'] = 'video_interview_system/rating';
$route['video_interview_system/add_default'] = 'video_interview_system/add_default';
$route['video_interview_system/templates'] = 'video_interview_system/templates';
$route['video_interview_system/edit_template'] = 'video_interview_system/edit_template';
$route['video_interview_system/add_template'] = 'video_interview_system/add_template';
$route['video_interview_system/ajax_responder'] = 'video_interview_system/ajax_responder';
$route['video_interview_system/send/(:any)'] = 'video_interview_system/send/$1';
$route['video_interview_system/record'] = 'video_interview_system/record';
$route['video_interview_system/(:any)'] = 'video_interview_system/index/$1';
$route['video_interview_system/response/(:any)/(:any)'] = 'video_interview_system/response/$1/$2';
//Resource Page
$route['resource_page'] = 'home/resource_page';
//Direct Deposit
$route['direct_deposit'] = 'direct_deposit/index';
$route['direct_deposit/(:any)/(:num)'] = 'direct_deposit/index/$1/$2';
$route['direct_deposit/(:any)/(:num)/(:num)'] = 'direct_deposit/index/$1/$2/$3';
//Change password route
$route['generate-password/(:any)'] = 'users/generate_password/$1';
//Manage Admin Applicants Report
$route['manage_admin/reports/applicants_report'] = 'manage_admin/reports/applicants_report/index';
$route['manage_admin/reports/applicants_report/(:any)'] = 'manage_admin/reports/applicants_report/index/$1';
$route['manage_admin/reports/applicants_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/reports/applicants_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7/$8';
$route['manage_admin/reports/applicants_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['manage_admin/reports/applicants_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
//Manage Admin Applicant Status Report
$route['manage_admin/reports/applicant_status_report'] = 'manage_admin/reports/applicant_status_report/index';
$route['manage_admin/reports/applicant_status_report/(:any)'] = 'manage_admin/reports/applicant_status_report/index/$1';
$route['manage_admin/reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_status_report/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_status_report/index/$1/$2/$3/$4/$5/$6/$7/$8';
$route['manage_admin/reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_status_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['manage_admin/reports/applicant_status_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_status_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
//Manage Admin Advanced reports
$route['manage_admin/reports/advanced_jobs_report'] = 'manage_admin/reports/advanced_jobs_report/index';
$route['manage_admin/reports/advanced_jobs_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/advanced_jobs_report/index/$1/$2/$3';
$route['manage_admin/reports/advanced_jobs_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/advanced_jobs_report/index/$1/$2/$3/$4';
$route['manage_admin/reports/advanced_jobs_report/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/advanced_jobs_report/index/$1/$2/$3/$4/$5';
//Manage Admin Applicant Source Report
$route['manage_admin/reports/applicant_source_report'] = 'manage_admin/reports/applicant_source_report/index';
$route['manage_admin/reports/applicant_source_report/(:any)'] = 'manage_admin/reports/applicant_source_report/index/$1';
$route['manage_admin/reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6/$7/$8';
$route['manage_admin/reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['manage_admin/reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_source_report/index/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
$route['manage_admin/reports/applicant_source_report/ajax_responder'] = 'manage_admin/reports/applicant_source_report/ajax_responder';
//Manage Admin Applicant Origination Report
$route['manage_admin/reports/applicant_origination_report'] = 'manage_admin/reports/applicant_origination_report/index';
$route['manage_admin/reports/applicant_origination_report/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_origination_report/index/$1/$2/$3/$4/$5';
$route['manage_admin/reports/applicant_origination_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_origination_report/index/$1/$2/$3/$4/$5/$6';
//Manage Admin Applicant Offers reports
$route['manage_admin/reports/applicant_offers_report'] = 'manage_admin/reports/applicant_offers_report/index';
$route['manage_admin/reports/applicant_offers_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_offers_report/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/reports/applicant_offers_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicant_offers_report/index/$1/$2/$3/$4/$5/$6/$7';
//Manage Admin Jobs Per Month Report
$route['manage_admin/reports/jobs_per_month_report'] = 'manage_admin/reports/jobs_per_month_report/index';
$route['manage_admin/reports/jobs_per_month_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/jobs_per_month_report/index/$1/$2/$3/$4';
//Manage Admin Interviews Report
$route['manage_admin/reports/interviews_report'] = 'manage_admin/reports/interviews_report/index';
$route['manage_admin/reports/interviews_report/(:any)/(:any)'] = 'manage_admin/reports/interviews_report/index/$1/$2';
//Manage Admin Applicants Between Period Report
$route['manage_admin/reports/applicants_between_period_report'] = 'manage_admin/reports/applicants_between_period_report/index';
$route['manage_admin/reports/applicants_between_period_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_between_period_report/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/reports/applicants_between_period_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/applicants_between_period_report/index/$1/$2/$3/$4/$5/$6/$7';
//Manage Admin Time To Fill Report
$route['manage_admin/reports/time_to_fill_job_report'] = 'manage_admin/reports/time_to_fill_job_report/index';
$route['manage_admin/reports/time_to_fill_job_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/time_to_fill_job_report/index/$1/$2/$3';
$route['manage_admin/reports/time_to_fill_job_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/time_to_fill_job_report/index/$1/$2/$3/$4';
//Manage Admin Time To Hire Job Report
$route['manage_admin/reports/time_to_hire_job_report'] = 'manage_admin/reports/time_to_hire_job_report/index';
$route['manage_admin/reports/time_to_hire_job_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/time_to_hire_job_report/index/$1/$2/$3';
$route['manage_admin/reports/time_to_hire_job_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/time_to_hire_job_report/index/$1/$2/$3/$4';
//Manage Admin Job Category Report
$route['manage_admin/reports/job_categories_report'] = 'manage_admin/reports/job_categories_report/index';
$route['manage_admin/reports/job_categories_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_categories_report/index/$1/$2/$3';
$route['manage_admin/reports/job_categories_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_categories_report/index/$1/$2/$3/$4';
//Manage Admin New Hires Report
$route['manage_admin/reports/new_hires_report'] = 'manage_admin/reports/new_hires_report/index';
$route['manage_admin/reports/new_hires_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/new_hires_report/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/reports/new_hires_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/new_hires_report/index/$1/$2/$3/$4/$5/$6/$7';
//Manage Admin New Hires Onboarding Report
$route['manage_admin/reports/new_hires_onboarding_report'] = 'manage_admin/reports/new_hires_onboarding_report/index';
$route['manage_admin/reports/new_hires_onboarding_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/new_hires_onboarding_report/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/reports/new_hires_onboarding_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/new_hires_onboarding_report/index/$1/$2/$3/$4/$5/$6/$7';
//Manage Admin Job View Report
$route['manage_admin/reports/job_views_report'] = 'manage_admin/reports/job_views_report/index';
$route['manage_admin/reports/job_views_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_views_report/index/$1/$2/$3';
$route['manage_admin/reports/job_views_report/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_views_report/index/$1/$2/$3/$4';
//Manage Admin Job Product Report
$route['manage_admin/reports/job_products_report/(:any)'] = 'manage_admin/reports/job_products_report/index/$1';
$route['manage_admin/reports/job_products_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_products_report/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/reports/job_products_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_products_report/index/$1/$2/$3/$4/$5/$6/$7/$8';
//Applicant Source Report
$route['reports/applicant_source_report/(:any)'] = 'reports/applicant_source_report/$1';
$route['reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_source_report/$1/$2/$3/$4/$5/$6';
$route['reports/applicant_source_report/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reports/applicant_source_report/$1/$2/$3/$4/$5/$6/$7';
//Applicant Interview Scores Report
$route['reports/applicant_interview_scores_report/(:any)'] = 'reports/applicant_interview_scores_report/$1';
$route['reports/applicant_interview_scores_report/(:any)/(:any)'] = 'reports/applicant_interview_scores_report/$1/$2';
$route['reports/applicant_interview_scores_report/(:any)/(:any)/(:any)'] = 'reports/applicant_interview_scores_report/$1/$2/$3';
//Employee Login Footer Text
$route['employee_login_link'] = 'employee_login_text/index';
//additional content boxed
$route['manage_admin/additional_content_boxes/(:num)'] = 'manage_admin/additional_content_boxes/index/$1';
//Resend Screening Questionnaire
$route['resend_screening_questionnaire/(:any)/(:any)/(:any)'] = 'resend_screening_questionnaire/index/$1/$2/$3';
$route['resend_screening_questionnaire/(:any)/(:any)/(:any)/(:any)'] = 'resend_screening_questionnaire/index/$1/$2/$3/$4';
//Manage Admin Invoice Items Usage report
$route['manage_admin/reports/invoice_item_usage/'] = 'manage_admin/reports/invoice_item_usage/index/';
$route['manage_admin/reports/invoice_item_usage/(:any)/(:any)/(:any)'] = 'manage_admin/reports/invoice_item_usage/index/$1/$2/$3';
$route['manage_admin/reports/invoice_item_usage/(:any)/(:any)/(:any)/(:num)'] = 'manage_admin/reports/invoice_item_usage/index/$1/$2/$3/$4';
//Manage Admin Credit Card Statuses
$route['manage_admin/cc_expires/(:any)'] = 'manage_admin/cc_expires/index/$1';
$route['manage_admin/cc_expires/(:any)/(:any)'] = 'manage_admin/cc_expires/index/$1/$2';
//Manage Admin Job Statuses Report
$route['manage_admin/reports/job_status_report/(:any)'] = 'manage_admin/reports/job_status_report/index/$1';
$route['manage_admin/reports/job_status_report/(:any)/(:any)'] = 'manage_admin/reports/job_status_report/index/$1/$2';
$route['manage_admin/reports/job_status_report/(:any)/(:any)/(:any)'] = 'manage_admin/reports/job_status_report/index/$1/$2/$3';
//Export Candidate Filters
$route['export_applicants_csv/(:any)'] = 'export_applicants_csv/index/$1';
$route['export_applicants_csv/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2';
$route['export_applicants_csv/(:any)/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2/$3';
$route['export_applicants_csv/(:any)/(:any)/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2/$3/$4';
$route['export_applicants_csv/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2/$3/$4/$5';
$route['export_applicants_csv/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2/$3/$4/$5/$6';
$route['export_applicants_csv/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'export_applicants_csv/index/$1/$2/$3/$4/$5/$6/$7';
//Assign candidate to other company job
$route['re_assign_candidate/(:any)'] = 're_assign_candidate/index/$1';
$route['re_assign_candidate/(:any)/(:any)'] = 're_assign_candidate/index/$1/$2';
$route['re_assign_candidate/(:any)/(:any)/(:any)'] = 're_assign_candidate/index/$1/$2/$3';
$route['re_assign_candidate/ajax_responder'] = 're_assign_candidate/ajax_responder';
//Manage Companies for Re-Assigned Candidates to other company jobs
$route['manage_admin/company_job_share_management/(:any)'] = 'manage_admin/company_job_share_management/index/$1';
//W9 Form
$route['form_w9'] = 'form_w9/index';
$route['form_w9/(:any)/(:num)'] = 'form_w9/index/$1/$2';
$route['form_w9/(:any)/(:num)/(:num)'] = 'form_w9/index/$1/$2/$3';
//W4 Form
$route['form_w4/admin_reply/(:any)/(:num)/(:num)'] = 'form_w4/admin_reply/$1/$2/$3';
$route['form_w4'] = 'form_w4/index';
$route['form_w4/(:any)/(:num)'] = 'form_w4/index/$1/$2';
$route['form_w4/(:any)/(:num)/(:num)'] = 'form_w4/index/$1/$2/$3';
//I9 Form
$route['form_i9'] = 'form_i9/index';
$route['form_i9/preview_i9form/(:any)'] = 'form_i9/preview_i9form/$1';
$route['form_i9/(:any)/(:num)'] = 'form_i9/index/$1/$2';
$route['form_i9/(:any)/(:num)/(:num)'] = 'form_i9/index/$1/$2/$3';
$route['form_i9/ajax_handler'] = 'form_i9/ajax_handler';
//Resource Center
$route['resource_center'] = 'resource_center/index';
$route['resource_center/(:any)'] = 'resource_center/index/$1';
$route['resource_center/(:any)/(:any)'] = 'resource_center/index/$1/$2';
$route['resource_center/(:any)/(:any)/(:any)'] = 'resource_center/index/$1/$2/$3';
$route['update_document_library_files'] = 'resource_center/update_document_library_files';
$route['resource_center_search'] = 'resource_center/resource_center_search';
$route['resource_center_copy_to'] = 'resource_center/copy_to_DMS';
//Manage Admin Marketplace Invoice
//$route['manage_admin/invoice'] = 'manage_admin/invoice/index';
$route['manage_admin/invoice/index/(:any)'] = 'manage_admin/invoice/index/$1';
$route['manage_admin/invoice/index/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2';
$route['manage_admin/invoice/index/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3';
$route['manage_admin/invoice/index/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3/$4';
$route['manage_admin/invoice/index/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3/$4/$5';
$route['manage_admin/invoice/index/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/invoice/index/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/index/$1/$2/$3/$4/$5/$6/$7';
//$route['manage_admin/list_admin_invoices'] = 'manage_admin/invoice/list_admin_invoices';
//$route['manage_admin/list_admin_invoices/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/invoice/list_admin_invoices/$1/$2/$3/$4/$5';
//$route['manage_admin/pending_invoices'] = 'manage_admin/invoice/pending_invoices';
//Order History Details PDF Function
$route['get_pdf'] = 'order_history/get_pdf';
//Incident Reporting System
$route['incident_reporting_system'] = 'incident_reporting_system/index';
$route['safety_checklist'] = 'incident_reporting_system/safety_checklist';
$route['safety_checklist/(:num)'] = 'incident_reporting_system/view_safety_checklist/$1';
$route['incident_reported'] = 'incident_reporting_system/incident_reported';
$route['incident_reported/(:num)'] = 'incident_reporting_system/view_incident_reported/$1';
$route['uploaded_checklist/(:num)'] = 'incident_reporting_system/uploaded_checklist/$1';
//My Learning Center
$route['my_learning_center'] = 'learning_center/my_learning_center';
$route['learning_center/my_learning_center'] = 'learning_center/my_learning_center';
//Affiliation Program
$route['can-we-send-you-a-check-every-month'] = 'affiliates/affiliationform';
$route['affiliate-program'] = 'affiliates/index';
// $route['manage_admin/referred'] = 'manage_admin/affiliates/referred';
// $route['manage_admin/referred/view_details/(:num)'] = 'manage_admin/affiliates/view_details/$1';
// $route['manage_admin/referred/accept_reject'] = 'manage_admin/affiliates/accept_reject';
// $route['manage_admin/referred/ajax_handler'] = 'manage_admin/affiliates/ajax_handler';
// $route['manage_admin/referred/edit_details/(:num)'] = 'manage_admin/affiliates/edit_details/$1';
// $route['manage_admin/referred/send_reply/(:num)'] = 'manage_admin/affiliates/send_reply/$1';

$route['thank_you'] = 'demo/thank_you';
$route['demo'] = 'demo/schedule_your_free_demo';
$route['manage_admin/referred_affiliates'] = 'manage_admin/affiliates/referred';
$route['manage_admin/referred_affiliates/ajax_handler'] = 'manage_admin/affiliates/ajax_handler';
$route['manage_admin/referred_affiliates/view_details/(:num)'] = 'manage_admin/affiliates/view_details/$1';
$route['manage_admin/referred_affiliates/edit_details/(:num)'] = 'manage_admin/affiliates/edit_details/$1';
$route['manage_admin/referred_affiliates/send_reply/(:num)'] = 'manage_admin/affiliates/send_reply/$1';
$route['manage_admin/referred_affiliates/view_reply/(:num)'] = 'manage_admin/affiliates/view_reply/$1';
$route['manage_admin/referred_affiliates/accept_reject'] = 'manage_admin/affiliates/accept_reject';

$route['manage_admin/recurring_payments/(:any)'] = 'manage_admin/recurring_payments/index/$1';


$route['reports/generate_interviews_scheduled_by_recruiters/'] = 'reports/generate_interviews_scheduled_by_recruiters2/';
$route['reports/generate_interviews_scheduled_by_recruiters/(:any)/(:any)'] = 'reports/generate_interviews_scheduled_by_recruiters/$1/$2';
//Manage EMS Notification
$route['add_ems_notification'] = 'manage_ems/ems_notification';
$route['edit_ems_notification/(:num)'] = 'manage_ems/edit_ems_notification/$1';

//Admin panel Email Template module
$route['manage_admin/email_templates_listing'] = 'manage_admin/email_templates/email_templates_listing';
$route['manage_admin/add_email_template'] = 'manage_admin/email_templates/add_email_template';
$route['manage_admin/edit_email_template/(:num)'] = 'manage_admin/email_templates/edit_email_template/$1';

$route['accurate_background'] = 'accurate_background/index';
$route['accurate_background/(:any)/(:any)'] = 'accurate_background/index/$1/$2';
$route['accurate_background/(:any)/(:any)/(:any)'] = 'accurate_background/index/$1/$2/$3';
$route['accurate_background/(:any)/(:any)/(:any)/(:any)'] = 'accurate_background/index/$1/$2/$3/$4';
$route['accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'accurate_background/index/$1/$2/$3/$4/$5';
$route['accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'accurate_background/index/$1/$2/$3/$4/$5/$6';
$route['accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'accurate_background/index/$1/$2/$3/$4/$5/$6/$7';

$route['manage_admin/accurate_background'] = 'manage_admin/accurate_background/index';
$route['manage_admin/accurate_background/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4/$5';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4/$5/$6';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4/$5/$6/$7';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4/$5/$6/$7/$8';
$route['manage_admin/accurate_background/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'manage_admin/accurate_background/index/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['department_management'] = 'department_management/index';

$route['employee_status/ajax_handler'] = 'Terminate_employee/ajax_handler';
$route['employee_status/(:any)'] = 'Terminate_employee/index/$1';
$route['change_status/(:any)'] = 'Terminate_employee/change_status/$1';
$route['edit_status/(:any)/(:any)'] = 'Terminate_employee/edit_status/$1/$2';


// Added on: 29-08-2019
$route['pto_plan'] = 'Paid_time_off/pto_plan';
$route['pto_request_report'] = 'Paid_time_off/pto_request_report';
// Added on: 26-08-2019
$route['pto_my_requests'] = 'Paid_time_off/pto_request';
$route['pto_request'] = 'Paid_time_off/pto_request/add';
$route['pto_report'] = 'Paid_time_off/pto_report';
$route['pto_requests'] = 'Paid_time_off/pto_requests';
$route['pto_employees/(:any)/(:any)'] = 'Paid_time_off/pto_employees/$1/$2';
$route['pto_employees/(:any)'] = 'Paid_time_off/pto_employees/$1';
$route['pto_employees'] = 'Paid_time_off/pto_employees';
$route['paid_time_off/handler'] = 'Paid_time_off/handler';
$route['paid_time_off/pto_email_handler'] = 'Paid_time_off/pto_email_handler';
$route['paid_time_off/pto_employee_email_handler'] = 'Paid_time_off/pto_employee_email_handler';
// Added on: 23-08-2019
$route['paid_time_off/(:any)/(:any)'] = 'Paid_time_off/index/$1/$2';
$route['paid_time_off/(:any)'] = 'Paid_time_off/index/$1';
$route['paid_time_off'] = 'Paid_time_off/index';
$route['pto_approvers'] = 'Paid_time_off/pto_approvers';
$route['pto_employee_requests'] = 'Paid_time_off/pto_employee_requests';
$route['pto_admin_request'] = 'Paid_time_off/pto_admin_request';
// Added on: 10-10-2019 PTO Emails Handler
$route['new_pto'] = 'Paid_time_off/responseToEmail';
$route['new_pto/(:any)'] = 'Paid_time_off/responseToEmail/$1';
$route['new_pto/(:any)/(:any)'] = 'Paid_time_off/responseToEmail/$1/$2';
//Employee Public link routes
$route['employee_view'] = 'Paid_time_off/responseToTlEmail';
$route['employee_view/(:any)'] = 'Paid_time_off/responseToTlEmail/$1';
$route['employee_view/(:any)/(:any)'] = 'Paid_time_off/responseToTlEmail/$1/$2';

//SMS short Link
$route['event-link/(:any)'] = 'calendar/handle_short_link/$1';

//Performance Review
// Templates
$route['performance/new_page'] = 'Performance_review/new_page';
$route['performance_review/ajax_handler'] = 'Performance_review/ajax_handler';
$route['performance_review/get_conductors_detail'] = 'Performance_review/get_conductors_detail';
$route['performance_review/get_all_review_title'] = 'Performance_review/get_all_review_title';
$route['performance_review/get_all_reviewee'] = 'Performance_review/get_all_reviewee';
$route['performance_review/update_conductors_detail'] = 'Performance_review/update_conductors_detail';
$route['performance_review/add_new_review_detail'] = 'Performance_review/add_new_review_detail';
$route['performance_review/change_employee_review_status'] = 'Performance_review/change_employee_review_status';
$route['performance/statics'] = 'Performance_review/reviews_statics';
$route['performance/template/(:any)'] = 'Performance_review/template/$1';
$route['performance/template/(:any)/(:num)'] = 'Performance_review/template/$1/$2';
// Reviews
$route['performance/edit/(:any)'] = 'Performance_review/edit_review_info/$1';
$route['performance/edit'] = 'Performance_review/edit_review_info';
$route['performance/review/(:any)'] = 'Performance_review/review/$1';
$route['performance/review/(:any)/(:num)'] = 'Performance_review/review/$1/$2';
$route['performance/review/(:any)/(:num)/(:num)'] = 'Performance_review/review/$1/$2/$3';
$route['performance/review/(:any)/(:num)/(:num)/(:num)'] = 'Performance_review/review/$1/$2/$3/$4';
$route['performance_review/(:any)'] = 'Performance_review/employee_performance_review/$1';
$route['performance/template/(:any)'] = 'Performance_review/template/$1';
$route['performance/template/(:any)/(:num)'] = 'Performance_review/template/$1/$2';
// Reviews
$route['performance/edit'] = 'Performance_review/edit_review_info';
$route['performance/review/(:any)'] = 'Performance_review/review/$1';
$route['performance/review/(:any)/(:num)'] = 'Performance_review/review/$1/$2';
$route['performance/review/(:any)/(:num)/(:num)'] = 'Performance_review/review/$1/$2/$3';
$route['performance/review/(:any)/(:num)/(:num)/(:num)'] = 'Performance_review/review/$1/$2/$3/$4';
$route['performance_review/(:any)'] = 'Performance_review/employee_performance_review/$1';
// Assigned
$route['performance/assigned'] = 'Performance_review/assigned/view';
$route['performance/assigned/(:any)'] = 'Performance_review/assigned/$1';
$route['performance/assigned/(:any)/(:num)'] = 'Performance_review/assigned/$1/$2';
// Print & Download
$route['performance/pd'] = 'Performance_review/pd/view';
$route['performance/pd/(:any)/(:num)'] = 'Performance_review/pd/$1/$2';
$route['performance/pd/(:any)/(:num)/(:num)/(:num)'] = 'Performance_review/pd/$1/$2/$3/$4';
// Report
$route['performance/report'] = 'Performance_review/report';
// AJAX handler
$route['performance/handler'] = 'Performance_review/handler';
// Feedback
$route['performance/feedback'] = 'Performance_review/feedback/view';
$route['performance/feedback/(:any)/(:num)'] = 'Performance_review/feedback/$1/$2';
$route['performance/feedback/(:any)/(:num)/(:num)'] = 'Performance_review/feedback/$1/$2';
//
$route['cron_review'] = 'Performance_review/cron_review';
// AJAX handler
$route['performance/handler'] = 'Performance_review/handler';
$route['cron_review'] = 'Performance_review/cron_review';


$route['pto/(:any)'] = 'Paid_time_off/pto_react_transformation/$1';

//Automatic Assign Document Cronjob
$route['assign_documents_cronJob'] = 'hr_documents_management/automaticAssignDocumentsCronJob';
$route['setup_default_config'] = 'setup_default_configuration_of_company/index';
$route['setup_default_config/(:any)'] = 'setup_default_configuration_of_company/index/$1';


// Download documents
$route['download/(:any)/(:num)/(:any)'] = 'hr_documents_management/download/$1/$2/$3';
$route['export_documents/(:any)'] = 'hr_documents_management/export_documents/$1';
$route['export_documents/(:any)/(:any)'] = 'hr_documents_management/export_documents/$1/$2';
$route['hr_documents_management/getSubmittedDocument/(:num)/(:any)/(:any)'] = 'hr_documents_management/getSubmittedDocument/$1/$2/$3';
$route['hr_documents_management/getSubmittedDocument/(:num)/(:any)/(:any)/(:any)'] = 'hr_documents_management/getSubmittedDocument/$1/$2/$3/$4';

// Complete Single Document - Public Link
$route['document/(:any)'] = 'onboarding/document/$1';

// Expire document links
$route['expire_document_links'] = 'onboarding/expire_document_links';

// Direct Deposit
$route['direct_deposit/add'] = 'direct_deposit/add';
$route['direct_deposit/edit'] = 'direct_deposit/edit';

//
$route['general_info/(:any)'] = 'general_info/index/$1';

/**
 * @employee: Mubashir Ahmed
 * @date: 10/20/2020 
 * @desc: Modify document categories
 * */ 
$route['category_manager/(:any)'] = 'Hr_documents_management/categoryManager/$1';
$route['category_manager/(:any)/(:any)'] = 'Hr_documents_management/categoryManager/$1/$2';
$route['category_manager/(:any)/(:any)/(:any)'] = 'Hr_documents_management/categoryManager/$1/$2/$3';
/**
 * @employee: Mubashir Ahmed
 * @date: 10/29/2020 
 * @desc: Scheduled documents
 * */ 
$route['scheduled_documents'] = 'Hr_documents_management/scheduled_documents';
$route['scheduled_documents/(:any)'] = 'Hr_documents_management/get_scheduled_documents/$1';

$route['push_default_policy'] = 'Time_off/test';
$route['get_jobs_json'] = 'job_listings/get_jobs_json';

/**
 * @employee: Mubashir Ahmed
 * @date: 01/13/2021
 */
$route['manage_admin/timeoff'] = 'manage_admin/Time_off/types';
$route['manage_admin/timeoff/policies'] = 'manage_admin/Time_off/policies';
$route['manage_admin/timeoff/setting'] = 'manage_admin/Time_off/setting';
$route['manage_admin/timeoff/icons'] = 'manage_admin/Time_off/icons';
$route['manage_admin/timeoff/handler'] = 'manage_admin/Time_off/handler';
$route['timeoff/public/(:any)'] = 'Time_off/public_action/$1';

// Sync
$route['sync_jobs']['cli'] = 'Testing/sj';
$route['sync_applicants']['cli'] = 'Testing/sja';
$route['sync_applicant_jobs']['cli'] = 'Testing/sajl';

// AC REader
$route['recheck_app']['cli'] = 'Auto_careers/mover';
// LEarning center revoke / assign routes
$route['learning_center/video_access']['post'] = 'Learning_center/video_access';
$route['timeoff/report'] = 'Time_off/report';
$route['timeoff/report/(:any)/(:any)'] = 'Time_off/pd_report/$1/$2';
$route['timeoff/get_my_timeoff/(:num)'] = 'Time_off/getTimeOffs/$1';


// Send Reminder Email - Routes
$route['get_send_reminder_email_body']['get'] = 'Common_ajax/get_send_reminder_email_body';
$route['send_reminder_email_by_type']['post'] = 'Common_ajax/send_reminder_email_by_type';
$route['get_send_reminder_email_history/(:num)/(:any)']['get'] = 'Common_ajax/get_send_reminder_email_history/$1/$2';
$route['auto_email_reminder/(:any)']['cli'] = 'Cron_common/auto_email_reminder/$1';


// 
$route['send_eeoc_form'] = 'Hr_documents_management/send_eeoc_form';
$route['eeoc_form/(:any)']['get'] = 'Home/eeoc_form/$1';
$route['eeoc_form_submit']['post'] = 'Home/eeoc_form_submit';

/**
 * Performance Mnagement - Routes
 * 
 * @employee Mubashir Ahmed
 * @date     02/02/2021
 */
// Overview
$route['performance-management/dashboard'] = 'Performance_management/dashboard';
$route['performance-management/goals'] = 'Performance_management/goals';
$route['performance-management/pd_goal/(:num)']['get'] = 'Performance_management/pd_goal/$1';
$route['performance-management/pd/(:any)/(:num)/(:num)/(:num)']['get'] = 'Performance_management/pd/$1/$2/$3/$4';
// Create
$route['performance-management/review/create'] = 'Performance_management/create_review';
$route['performance-management/review/create/(:num)'] = 'Performance_management/create_review/$1';
$route['performance-management/review/create/(:num)/(:any)'] = 'Performance_management/create_review/$1/$2';
// Create template
$route['performance-management/templates']['get'] = 'Performance_management/templates';
$route['performance-management/template/create'] = 'Performance_management/create_template';
$route['performance-management/template/create/(:num)'] = 'Performance_management/create_template/$1';
// Report
$route['performance-management/report'] = 'Performance_management/report';
$route['performance-management/report/(:any)/(:any)/(:any)/(:any)'] = 'Performance_management/report/$1/$2/$3/$4';
//
$route['performance-management/review/(:num)']['get'] = 'Performance_management/SingleReview/$1';
$route['performance-management/review/(:num)/(:num)/(:num)']['get'] = 'Performance_management/review/$1/$2/$3';
$route['performance-management/feedback/(:num)/(:num)/(:num)']['get'] = 'Performance_management/feedback/$1/$2/$3';
//
$route['performance-management/reviews'] = 'Performance_management/reviews';
$route['performance-management/my-reviews'] = 'Performance_management/MyReviews';
//
$route['performance-management/settings'] = 'Performance_management/settings';

// AJAX
$route['performance-management/get-template-questions/(:any)/(:num)']['get'] = 'Performance_management/template_questions/$2/$1';
$route['performance-management/get-single-template/(:any)/(:num)']['get'] = 'Performance_management/single_template/$2/$1';
$route['performance-management/save_review_step']['post'] = 'Performance_management/SaveReviewStep';
$route['performance-management/save_template_step']['post'] = 'Performance_management/SaveTemplateStep';
$route['performance-management/save_answer']['post'] = 'Performance_management/SaveFeedbackAnswer';
$route['performance-management/upload_question_file']['post'] = 'Performance_management/UploadQuestionAttachment';
$route['performance-management/get_reviewee_reviewes/(:num)/(:num)']['get'] = 'Performance_management/GetReviewReviewers/$1/$2';
$route['performance-management/save_review_reviewers']['post'] = 'Performance_management/UpdateRevieweeReviewers';
$route['performance-management/archive_review']['post'] = 'Performance_management/ArchiveReview';
$route['performance-management/activate_review']['post'] = 'Performance_management/ActivateReview';
$route['performance-management/stop_review']['post'] = 'Performance_management/StopReview';
$route['performance-management/start_review']['post'] = 'Performance_management/StartReview';
$route['performance-management/stop_reviewee_review']['post'] = 'Performance_management/StopReviweeReview';
$route['performance-management/start_reviewee_review']['post'] = 'Performance_management/StartReviweeReview';
$route['performance-management/update_reviewee']['post'] = 'Performance_management/UpdateReviewee';
$route['performance-management/get_visibility/(:num)']['get'] = 'Performance_management/GetReviewVisibility/$1';
$route['performance-management/update_visibility_post']['post'] = 'Performance_management/UpdateVisibility';
$route['performance-management/get_goal_body']['get'] = 'Performance_management/GetGoalBody';
$route['performance-management/save_goal']['post'] = 'Performance_management/SaveGoal';
$route['performance-management/close_goal']['post'] = 'Performance_management/CloseGoal';
$route['performance-management/open_goal']['post'] = 'Performance_management/OpenGoal';
$route['performance-management/update_goal']['post'] = 'Performance_management/UpdateGoal';
$route['performance-management/add_comment']['post'] = 'Performance_management/AddComment';
$route['performance-management/goal_comments/(:num)']['get'] = 'Performance_management/GetGoalComments/$1';
$route['performance-management/update_settings']['post'] = 'Performance_management/UpdateSettings';
$route['performance-management/save_template']['post'] = 'Performance_management/SaveTemplate';
// Cron
// Replicate and Start/End cron job
$route['review_start_and_replicate/(:any)']['cli'] = 'Cron_common/PMMCronStartAndEndReplicate/$1';
// Send reminder emails
$route['review_reminder_emails/(:any)']['cli'] = 'Cron_common/SendNotificationEmails/$1';

// 
$route['manage_admin/accurate_background/remove_background_check']['post'] = 'manage_admin/Accurate_background/RemoveBackgroundCheck';
$route['manage_admin/accurate_background/revert_background_check']['post'] = 'manage_admin/Accurate_background/RevertBackgroundCheck';
//
$route['performance-management/employee/goals/(:num)']['get'] = 'Employee_management/employee_goals/$1';
$route['performance-management/employee/reviews/(:num)']['get'] = 'Employee_management/employee_reviews/$1';
$route['performance-management/reviews/all']['get'] = 'Performance_management/all_reviews';
$route['performance-management/feedbacks/all']['get'] = 'Performance_management/all_feedbacks';
$route['performance-management/review/detail/(:num)']['get'] = 'Performance_management/review_details/$1';
//
$route['get_employee_profile/(:num)']['get'] = 'Employee_management/GetEmployeeProfile/$1';
$route['get_all_company_employees']['get'] = 'Employee_management/GetAllEmployees';
$route['manage_admin/reports/daily_activity_report/get_employee/(:num)']['post'] = 'manage_admin/reports/Daily_activity_report/GetEmployeeReport/$1';
// Send manual reminder email to employee
// 16/8/2021
$route['send_manual_reminder_email_to_employee']['post'] = 'ajax/Email_manager/SendManualEmailReminderToEmployee';
$route['send_manual_reminder_email_to_manager']['post'] = 'ajax/Email_manager/SendManualEmailReminderToManager';



//


// // AJAX Get
// $route['payroll/get_add_bank_account/(:num)']['get'] = 'payroll/Payroll/GetAddBankAccount/$1';
// $route['payroll/get_edit_bank_account/(:num)/(:num)']['get'] = 'payroll/Payroll/GetEditBankAccount/$1/$2';
// $route['cancel_payroll']['post'] = 'payroll/Payroll/CancelPayroll';

// $route['add_employee_to_company']['get'] = 'payroll/Payroll/AddEmployeeToCompany';
// $route['refresh_token']['post'] = 'payroll/Payroll/RefreshToken';
// $route['payroll/add_employee_to_payroll']['post'] = 'payroll/Payroll/AddEmployeeToPayroll';
// $route['payroll/add_bc_to_payroll']['post'] = 'payroll/Payroll/AddBankAccountToPayroll';
// $route['payroll/add_company_payroll_bank_account']['post'] = 'payroll/Payroll/AddCompanyBankAccountToPayroll';
// $route['payroll/edit_company_payroll_bank_account']['post'] = 'payroll/Payroll/EditCompanyBankAccountToPayroll';
// $route['payroll/remove_company_bank_account']['post'] = 'payroll/Payroll/RemoveCompanyBankAccounts';
// $route['payroll/update_bank_account_to_payroll']['post'] = 'payroll/Payroll/UpdateCompanyBankAccount';
// //
// $route['update_payroll_module']['post'] = 'manage_admin/Logs/UpdatePayroll';



/**
 * Payroll routes
 */
// Employee listing
$route["payroll/company"]['get'] = "payroll/Payroll/CompanyOnboard";
$route["payroll/employees"]['get'] = "payroll/Payroll/EmployeeList/normal";
$route["payroll/employees/payroll"]['get'] = "payroll/Payroll/EmployeeList/payroll";
$route["payroll/employees/normal"]['get'] = "payroll/Payroll/EmployeeList/normal";
$route["payroll/my"]['get'] = "payroll/Payroll/MyPayStubs";
$route["payroll/history"]['get'] = "payroll/Payroll/PayrollHistory";
$route["payroll/history/(:num)"]['get'] = "payroll/Payroll/PayrollSingleHistory/$1";
//
$route["payroll/p/(:any)/(:num)"]['get'] = "payroll/Payroll/PrivateFile/$1/$2";


$route['company_tax']['get'] = 'payroll/Payroll/CompanyTax';
$route['payroll/run']['get'] = 'payroll/Payroll/Run';
$route['payroll/run/(:any)/(:any)']['get'] = 'payroll/Payroll/RunSingle/$1/$2';
$route['payroll/run/(:any)']['get'] = 'payroll/Payroll/RunSingle/$1';

// AJAX Get
$route['payroll/employees']['get'] = 'payroll/Payroll_ajax/GetEmployees';


$route['payroll/update_payroll']['post'] = 'payroll/Payroll/UpdatePayroll';
$route['payroll/submit']['post'] = 'payroll/Payroll/SubmitPayroll';
$route['cancel_payroll']['post'] = 'payroll/Payroll/CancelPayroll';
$route['refresh_token']['post'] = 'payroll/Payroll/RefreshToken';
$route['update_payroll_module']['post'] = 'manage_admin/Logs/UpdatePayroll';
$route['create_partner_company']['post'] = 'payroll/Payroll/CreatePartnerCompany';



/**
 * API - Authentication
 * Authenticates the company request
 *
 * @param String $token
 */
$route['verify_my_token/(:any)']['post'] = 'Authentication/VerifyToken/$1';


/**
 * Company Routes
 * All the company related routes will 
 * be defined here
 * 
 */
$route['company/taxes']['get'] = 'company/Company/Taxes';
$route['company/bank_account']['get'] = 'company/Company/BankAccount';
$route['company/locations']['get'] = 'company/Company/Locations';
$route['company/pay_periods']['get'] = 'company/Company/PayPeriods';
// 
$route['employee/add/(:num)']['get'] = 'company/Company/AddEmployee/$1';
//
$route['get_job_detail/(:num)']['get'] = 'company/Company/GetJobDetailPage/$1';

// Company Onboard
$route['company_payroll']['get'] = 'company/Company/CompanyOnboard';


/**
 * 
 */
$route['get_payroll_page/(:any)'] = 'payroll/Payroll_ajax/GetPage/$1';
$route['get_payroll_page/(:any)/(:num)'] = 'payroll/Payroll_ajax/GetPage/$1/$2';
//
$route['save_payroll_admin/(:num)'] = 'payroll/Payroll_ajax/SaveAdmin/$1';

/**
 * 
 */
$route['payroll/onboard_company/(:num)']['post'] = "payroll/Payroll_onboard/OnboardCompany/$1";
$route['payroll/onboard_employee/(:num)']['post'] = "payroll/Payroll_onboard/OnboardEmployee/$1";
$route['payroll/onboard_employee/(:num)/(:num)']['delete'] = "payroll/Payroll_onboard/DeleteEmployeeFromPayroll/$1/$2";
$route['payroll/onboard_employee/(:any)/(:num)']['post'] = "payroll/Payroll_onboard/EmployeeOnboardPiece/$1/$2";
$route['payroll/onboard_employee/(:any)/(:num)/(:num)']['get'] = "payroll/Payroll_onboard/GetEmployeeOnboardSection/$1/$2/$3";
$route['payroll/onboard_employee/(:any)/(:num)/(:num)/(:any)']['delete'] = "payroll/Payroll_onboard/DeleteEmployeeOnboardSection/$1/$2/$3/$4";
$route['payroll/onboard_status/(:num)/(:num)']['get'] = "payroll/Payroll_onboard/OnboardStatus/$1/$2";
//
$route["payroll/get/(:num)/(:any)"] = "payroll/Payroll_onboard/Get/$1/$2";