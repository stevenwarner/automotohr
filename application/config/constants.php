<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
define('STORE_ROOT_USER', '');
define('STORE_PROTOCOL', 'http://');
define('STORE_PROTOCOL_SSL', 'https://');
define('STORE_NAME', 'AutomotoHR');
define('STORE_CODE', 'AHR');

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.applybuz.com' || $_SERVER['HTTP_HOST'] == 'applybuz.com') {
  define('DOC_ROOT', 'home/applybuz/public_html/');
  define('STORE_DOMAIN', 'applybuz.com');
  define('THEME_AUTH', 'abz');
} else {
  define('DOC_ROOT', 'home/automotohr/public_html/');
  define('STORE_DOMAIN', 'automotohr.com');
  define('THEME_AUTH', 'ahr');
}

define('STORE_FULL_URL', STORE_PROTOCOL . 'www.' . STORE_DOMAIN);
define('AWS_S3_BUCKET_NAME', 'automotohrattachments');
define('AWS_S3_BUCKET_URL', STORE_PROTOCOL_SSL . AWS_S3_BUCKET_NAME . '.s3.amazonaws.com/');
define('SERVER_DNS_PRIMARY', 'NS1.AUTOMOTOHR.COM');
define('SERVER_DNS_SECONDARY', 'NS2.AUTOMOTOHR.COM');
define('STORE_FULL_URL_SSL', STORE_PROTOCOL_SSL . 'www.' . STORE_DOMAIN . '/');
define('STAGING_SERVER_URL', 'http://localhost/ahr/');
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('FROM_EMAIL_STEVEN', 'steven@' . STORE_DOMAIN); // automotoHR admin email
define('TO_EMAIL_STEVEN', 'steven@' . STORE_DOMAIN); // automotoHR admin email
define('TO_EMAIL_ALEX', 'amiller@theautomotivepartners.com'); // automotoHR Alex Emal
define('FROM_EMAIL_DEV', 'dev@' . STORE_DOMAIN); // automotoHR developer email
define('DEV_EMAIL_PM', 'mubashar@' . STORE_DOMAIN); // automotoHR developer email
define('FROM_EMAIL_ACCOUNTS', 'accounts@' . STORE_DOMAIN); // automotoHR developer email
define('TO_EMAIL_DEV', 'dev@' . STORE_DOMAIN); // automotoHR developer email
define('FROM_STORE_NAME', STORE_NAME . '.com'); // automotoHR developer email
define('FROM_EMAIL_INFO', 'info@' . STORE_DOMAIN); // automotoHR info email
define('FROM_EMAIL_NOTIFICATIONS', 'notifications@' . STORE_DOMAIN); // automotoHR notifications email
define('TO_EMAIL_INFO', 'info@' . STORE_DOMAIN); // automotoHR info email
define('FROM_EMAIL_EVENTS', 'events@' . STORE_DOMAIN); // All Events Related Emails
define('TALENT_NETWORK_SALE_CONTACTNO', '(888) 794-0794 ext 1'); // Talent network sales contact No
define('TALENT_NETWORK_SALES_EMAIL', 'Accounts@AutomotoHR.com'); // Talent network sales email address
define('TALENT_NETWORK_SUPPORT_CONTACTNO', '(888) 794-0794 ext 2'); // Talent network support contact No
define('TALENT_NETOWRK_SUPPORT_EMAIL', 'Support@AutomotoHR.com'); // Talent network support email address
define('DEFAULT_SIGNATURE_CONSENT_HEADING', '<strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</strong> FOR <strong>AutomotoSocial LLC / ' . STORE_DOMAIN . '</strong><br/>'); // Default signature consent heading text
define('DEFAULT_SIGNATURE_CONSENT_TITLE', '1. Electronic Signature Agreement.'); // Default signature consent title text
define('DEFAULT_SIGNATURE_CONSENT_DESCRIPTION', "By selecting the &quot;I Accept&quot; button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting &quot;I Accept&quot; you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide AutomotoSocial LLC / " . STORE_DOMAIN . ", or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as &quot;E-Signature&quot;), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and AutomotoSocial LLC / " . STORE_DOMAIN . ". You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a AutomotoSocial LLC / " . STORE_DOMAIN . " service constitutes your agreement to be bound by the terms and conditions of the AutomotoSocial LLC / " . STORE_DOMAIN . " Disclosures and Agreements as they exist on the date of your E-Signature."); // Default signature consent discription text
define('DEFAULT_SIGNATURE_CONSENT_CHECKBOX', 'I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.'); // Default signature consent checkbox label text
define('DEFAULT_SIGNATURE_CONSENT_BUTTON', 'I Consent and Accept the Terms of this Electronic Signature Agreement'); // Default signature consent submit button text
define('SIGNATURE_CONSENT_HEADING', '<strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</strong> FOR <strong>{{company_name}}</strong><br/>'); // Signature consent heading text
define('SIGNATURE_CONSENT_TITLE', '1. Electronic Signature Agreement.'); // Signature consent title text
define('SIGNATURE_CONSENT_DESCRIPTION', "By selecting the &quot;I Accept&quot; button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting &quot;I Accept&quot; you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide {{company_name}}, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as &quot;E-Signature&quot;), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and {{company_name}}. You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a {{company_name}} service constitutes your agreement to be bound by the terms and conditions of the {{company_name}} Disclosures and Agreements as they exist on the date of your E-Signature."); // Signature consent discription text
define('SIGNATURE_CONSENT_CHECKBOX', 'I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.'); // Signature consent checkbox label text
define('SIGNATURE_CONSENT_BUTTON', 'I Consent and Accept the Terms of this Electronic Signature Agreement'); // Signature consent submit button text
define('SIGNATURE_MAX_HEIGHT', '75px'); // Signature consent submit button text
define('DOCUMENT_SEND_DURATION', 2); // Document will be sent after this hours

//Email Header Footer Start
$email_header = '';
$email_header .= '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative">';
$email_header .= '<div style="width: 100%; padding:20px; text-align:center; box-sizing:border-box; background:url(' . STORE_FULL_URL_SSL . '/assets/images/bg-body.jpg); opacity:0.9;  top:0; left:0;">';
$email_header .= '<img src="' . STORE_FULL_URL_SSL . '/assets/images/email-new-logo.png">';
$email_header .= '</div>';
$email_header .= '<div class="body-content" style="width: 100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(' . STORE_FULL_URL_SSL . '/assets/images/bg-body.jpg);">';
define('EMAIL_HEADER', $email_header); // email header

$email_footer = '';
$email_footer .= '</div>';
$email_footer .= '<table cellspacing="0" cellpadding="10" border="0" width="100%" background="' . STORE_FULL_URL_SSL . '/assets/images/bg-body.jpg">';
$email_footer .= '<thead>';
$email_footer .= '<th style="text-align:center !important; color: #000; padding:10px 0;" colspan="2"><strong style="font-size:16px;">CONTACT ONE OF OUR TALENT NETWORK PARTNERS AT</strong></th>';
$email_footer .= '</thead>';
$email_footer .= '<tbody>';
$email_footer .= '<tr>';
$email_footer .= '<td>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Sales Executive</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;" href="tel:+888-871-3096 ext 1"><img src="' . STORE_FULL_URL_SSL . '/assets/images/phone-icon.png">&nbsp;&nbsp;<strong>' . TALENT_NETWORK_SALE_CONTACTNO . '</strong></a></div>';
$email_footer .= '</div>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<span style=" font-weight:600;"><img src="' . STORE_FULL_URL_SSL . '/assets/images/email-icon.png">&nbsp;&nbsp;' . TALENT_NETWORK_SALES_EMAIL . '</span>';
$email_footer .= '</div>';
$email_footer .= '</td>';
$email_footer .= '<td>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Technical Support</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;" href="tel:+888-871-3096 ext 2"><strong><img src="' . STORE_FULL_URL_SSL . '/assets/images/phone-icon.png">&nbsp;&nbsp;' . TALENT_NETWORK_SUPPORT_CONTACTNO . '</strong></a></div>';
$email_footer .= '</div>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<span style=" font-weight:600;"><img src="' . STORE_FULL_URL_SSL . '/assets/images/email-icon.png">&nbsp;&nbsp;' . TALENT_NETOWRK_SUPPORT_EMAIL . '</span>';
$email_footer .= '</div>';
$email_footer .= '</td>';
$email_footer .= '</tr>';
$email_footer .= '</tbody>';
$email_footer .= '</table>';
$email_footer .= '</div>';
define('EMAIL_FOOTER', $email_footer); // email footer

//Email Header Footer Ends
define('THUMBNAIL_IMAGE_MAX_WIDTH', 300);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 300);
define('REPLY_TO', 'notifications@' . STORE_DOMAIN); // Reply to Email for Applicant Message Module


define('GOOGLE_API_KEY', 'AIzaSyDDFZfLFBCSQmBgyIdcn91CWQ0M935psho');
define('GOOGLE_MAP_API_KEY', 'AIzaSyAcktQ26-TuAGDbQxMRsYcQ9anvKeFazGI');

//Email Template IDs
define('EMPLOYER_WELCOME_EMAIL_ID', 48); // Employer register welcome email id
define('ADMIN_WELCOME_EMAIL_ID', 3); //  Admin, Employer register welcome email id
define('PASSWORD_RECOVERY', 36); //  Password recovery email
define('PASSWORD_CHANGE', 38); //  Password changed email
define('INVOICE_NOTIFICATION', 300); //  Invoice notification email
define('DEMO_REQUEST_THANKYOU', 359); //  Schedule free demo notification email
define('SECURITY_PERMISSIONS_ERROR', "<b>Error:</b> You don't have permissions to access this module."); //  Access Level permission error
define('NEW_ACCOUNT_EXPIRY_DAYS', 7); //  CREATE YOUR FREE COMPANY ACCOUNT FOR 7 DAYS
define('REACTIVE_EXPIRED_ACCOUNT', 4); //  reactivating expired account
define('ACCURATE_BACKGROUND_DOCUMENT_REQUEST', 305); //  Accurate Background Activation Document Request
define('ACCURATE_BACKGROUND_ACTIVATION_DOCUMENT_SEND', 306); // Accurate Background Activation Document To Accurate Background Team
define('CSV_EMPLOYER_IMPORT', 307); // CSV Employer Import
define('REFER_A_JOB', 308); // Refer a job
define('ENTERPRISE_THEME_ACTIVATION_TO_ADMIN', 309); // Enterprise Theme Activation To Admin
define('HR_DOCUMENTS_NOTIFICATION', 311); // HR Documents Notification.
define('HR_DOCUMENTS_NOTIFICATION_WITHOUT_USERNAME', 312); // HR Documents Notification.
define('ACCOUNT_EXPIRATION_AUTO_NOTIFICATION', 313);
define('JOB_LISTING_STATUS_NOTIFICATION', 314);
define('NEW_LISTING_NOTIFICATION_TO_USER_WITH_APPROVAL_RIGHTS', 315);
define('JOB_LISTING_SHARE_TO_EMPLOYEES', 316);
define('RECURRING_PAYMENTS_AUTHORIZATION_REQUEST_ADMIN', 317);
define('RECURRING_PAYMENTS_AUTHORIZATION_REQUEST_COMPANY_ADMIN', 318);
define('END_USER_LICENSE_AGREEMENT_NOTIFICATION_SUPER_ADMIN', 319);
define('END_USER_LICENSE_AGREEMENT_NOTIFICATION_COMPANY_ADMIN', 320);
define('CREDIT_CARD_AUTHORIZATION_NOTIFICATION_SUPER_ADMIN', 321);
define('CREDIT_CARD_AUTHORIZATION_NOTIFICATION_COMPANY_ADMIN', 322);
define('NEW_APPLICANT_HIRING_APPROVAL_REQUEST', 324);
define('APPLICANT_HIRING_APPROVAL_STATUS_CHANGE', 325);
define('FORMS_NOTIFICATION_TO_ADMIN', 326);
define('FORMS_NOTIFICATION_TO_CLIENT', 327);
define('ADMIN_INVOICE_PAYMENT_NOTIFICATION', 328);
define('NOTIFICATION_TO_EMPLOYER_ON_NEW_ACCOUNT_CREATION_FROM_SUPER_ADMIN', 330);
define('ON_BOARDING_REQUEST', 331);
define('APPLICANT_HIRING_CONDITIONAL_REJECTION_NOTIFICATION', 332);
define('FULL_EMPLOYMENT_APPLICATION_REQUEST', 333);
define('JOB_LISTING_SHARE_TO_EMAIL_ADDRESS', 334);
define('CREDIT_CARD_EXPIRATION_NOTIFICATION', 335);
define('PAYMENT_VOUCHER_EMAIL', 344);
define('PAGINATION_RECORDS_PER_PAGE', 100);
define('ENTERPRISE_THEME_PACKAGE', "20,24"); //  reactivating expired account
define('ACCOUNT_PACKAGE_BUY_NOW_MESSAGE', 'You are already subscribed to our platform package.');
define('AUTORESPONDER_START_DAYS', 5); //Number of days to start sending expiration emails.
define('CREDIT_CARD_EXPIRATION_NOTIFICATION_START_DAYS', 10); //Number of days to start sending expiration emails.
define('TURNOVER_COST_CALCULATION_REPORT_EMAIL', 336);
define('INDEED_ALREADY_APPLIED_NOTIFICATION', 337);
define('VIDEO_INTERVIEW_QUESTIONNAIRES', 338);
define('VIDEO_INTERVIEW_QUESTIONNAIRE_RESPONSE', 339);
define('DUE_INVOICES_DETAIL', 340);
define('UPDATE_CREDIT_CARD_REQUEST', 341);
define('ADMIN_NOTIFICATION_ON_DEMO_SCHEDULE', 342);
define('APPLICANT_ASSIGNMENT_NOTIFICATION', 343);
define('APPLICANT_ONBOARDING_WELCOME', 345);
define('NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION', 349);
define('RESEND_SCREENING_QUESTIONNAIRE', 351);
define('INCIDENT_REPORT_NOTIFICATION', 352);
define('EXPIRATIONS_MANAGER_NOTIFICATION', 353);
define('JOBS_AUTO_EXPIRATION_NOTIFICATION', 357);
define('SEND_CANDIDATES_INFO', 354);
define('FULL_EMPLOYMENT_APPLICATION_SIGNED', 347);
define('SCREENING_QUESTIONNAIRE_FOR_JOB', 346);
define('NEW_EXECUTIVE_ADMIN_INFO', 348);
define('NEW_AFFILIATE_PROGRAM_LOGIN_REQUEST', 356);
define('TRAINING_SESSION_EMAIL_TEMPLATE', 358);
define('AFFILIATE_END_USER_LICENSE_NOTIFICATION', 361);
define('EVENT_REMINDER_NOTIFICATION', 355);
define('ONBOARDING_REQUEST_COMPLETED', 377);
define('OFFER_LETTER_SIGNED_NOTIFICATION', 378);
define('SEND_RESUME_REQUEST', 379);
// Added on: 10-02-2019
define('HR_DOCUMENTS_NOTIFICATION_EMS', 380);
// Added on: 16-08-2021
define('HR_DOCUMENTS_REMINDER_NOTIFICATION', 416);
// Added on: 10-09-2019
define('NEW_PTO_REQUESTED', 381);
// Added on: 11-18-2019
define('NEW_ANNOUNCEMENT_NOTIFICATION', 381);
define('EDIT_ANNOUNCEMENT_NOTIFICATION', 383);
define('HR_AUTHORIZED_DOCUMENTS_NOTIFICATION', 410);

// Added on- 09/04/2020
define('HR_DOCUMENTS_FOR_APPLICANT', 387);

/**
 * Holds the id of dcocument approval flow
 * 
 * 04/14/2022 
 */
define('HR_DOCUMENTS_APPROVAL_FLOW', 419);
define('HR_DOCUMENTS_APPROVAL_FLOW_APPROVED', 420);
define('HR_DOCUMENTS_APPROVAL_FLOW_REJECTED', 421);


// Added on- 18/01/2022
define('EMPLOYEE_PROFILE_UPDATE', 417);

//Db Config for Dealers Account
define('ROOT_DB_NAME', 'dealers2_sjb42_upgrade');

/** MySQL database username */
define('ROOT_DB_USER', 'dealers2_readonl');

/** MySQL database password */
define('ROOT_DB_PASSWORD', 'E0U&ta78;O@k');

/** MySQL hostname */
define('ROOT_DB_HOST', 'localhost');

//Accurate Background Check Api Mode
define('AB_API_MODE', 'live'); // live or dev

//Jobs2Career Check Api Mode
define('JTC_API_MODE', 'dev'); // live or dev
define('JTC_ACCOUNT_ID', '2147');
define('JTC_POOL_ID', '2947');
define('JTC_SOURCE_ID', '3671');
define('JTC_STAGING_CAMPAIGN_ID', 5029);
define('JTC_STAGING_API_KEY', 'Ps64DutOfBaGj5eS3JE162D4qjObL6Qs2Xf6eQWh');
define('JTC_STAGING_CAMPAIGN_BASE_URL', 'http://fr-api-stage-1256714631.us-east-1.elb.amazonaws.com:8007/v0.9');
define('JTC_STAGING_JOBS_BASE_URL', 'http://fr-api-stage-1256714631.us-east-1.elb.amazonaws.com:8001/v0.9');
define('JTC_STAGING_BILLING_BASE_URL', 'http://fr-api-stage-1256714631.us-east-1.elb.amazonaws.com:8008/v0.9');
define('JTC_STAGING_PREDICTION_BASE_URL', 'http://fr-api-stage-1256714631.us-east-1.elb.amazonaws.com:8006/v0.9');
define('JTC_LIVE_CAMPAIGN_ID', 5030);
define('JTC_LIVE_API_KEY', 'sqDgkeuvkj7ePvaAKij4q16QFTM7qyZv7YU2pd7Q');
define('JTC_LIVE_CAMPAIGN_BASE_URL', '');
define('JTC_LIVE_JOBS_BASE_URL', '');
define('JTC_LIVE_BILLING_BASE_URL', '');
define('JTC_LIVE_PREDICTION_BASE_URL', '');
//CSSI API
define('CSSI_API_MODE', 'dev'); // dev or live
define('APPLY_ON_JOB_EMAIL_ID', 18); //  Invoice notification email
//Color Codes for Different Websites
define('COLOR_INDEED', '#3163f2');
define('COLOR_AUTOMOTOSOCIAL', '#09f');
define('COLOR_GLASSDOOR', '#006C3E');
define('COLOR_JUJU', '#000268');
define('COLOR_AUTOMOTOHR', '#81b431');
define('COLOR_BIGBOXHR', '#06f');
define('COLOR_JOBSTOCAREERS', '#ff6900');
define('COLOR_ZIPRECRUITER', '#9004d5');
//Color Codes for Different Websites

// cloud gallery constant
define('CLOUD_GALLERY', 'cloud-gallery-ahr');
// cloud gallery library constant
define('CLOUD_VIDEO_LIBRARY', 'cloud-video-library-ahr');
define('CLOUD_VIDEO_LIBRARY_URL', STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/');

//Testing Companies Ids
$test_companies = '3,31,57';
define('TEST_COMPANIES', $test_companies);
define('DEF_EMAIL_BTN_STYLE_PRIMARY', 'background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Light Blue
define('VIDEO_INTERVIEW_EMAIL_BTN_STYLE', 'background-color: #0000ff; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Light Blue
define('DEF_EMAIL_BTN_STYLE_SECONDARY', 'background-color: #5a6268; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Gray
define('DEF_EMAIL_BTN_STYLE_SUCCESS', 'background-color: #218838; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Green
define('DEF_EMAIL_BTN_STYLE_DANGER', 'background-color: #c82333; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Red
define('DEF_EMAIL_BTN_STYLE_WARNING', 'background-color: #ffc107; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Yellow
define('DEF_EMAIL_BTN_STYLE_INFO', 'background-color: #FFA500; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: No shine light blue
define('DEF_EMAIL_BTN_STYLE_LIGHT', 'background-color: #e2e6ea; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #000; border-radius: 5px; text-align: center; display:inline-block');  // color: Light grey - white
define('DEF_EMAIL_BTN_STYLE_DARK', 'background-color: #23272b; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Dark - similar to black
define('APPLICANT_TYPE_ATS', 'Applicant,Talent Network,Manual Candidate,Job Fair,Re-Assigned Candidates,Archived');

$info_email_disclaimer = '';
$info_email_disclaimer .= '<br /><br /><br /><div style="clear: both; text-align: center; padding: 15px 0;">';
$info_email_disclaimer .= '<p>';
$info_email_disclaimer .= '***** This is an automated system email, please do not reply or redistribute this email *****';
$info_email_disclaimer .= '</p>';
$info_email_disclaimer .= '</div>';

define('FROM_INFO_EMAIL_DISCLAIMER_MSG', $info_email_disclaimer);

$blue_panel_companies = array();
$blue_panel_companies[] = 3;
$blue_panel_companies[] = 51;
$blue_panel_companies[] = 57;
define('BLUE_PANEL_COMPANIES', implode(',', $blue_panel_companies));
define('ENABLE_BLUE_PANEL_FOR_ALL', false);

$blue_panel_modules = array();
$blue_panel_modules[] = 'my_profile';
$blue_panel_modules[] = 'login_password';
$blue_panel_modules[] = 'incident_reporting_system';
$blue_panel_modules[] = 'calendar';
$blue_panel_modules[] = 'my_events';
$blue_panel_modules[] = 'e_signature';
$blue_panel_modules[] = 'dependants';
$blue_panel_modules[] = 'edit_dependant_information';
$blue_panel_modules[] = 'emergency_contacts';
$blue_panel_modules[] = 'edit_emergency_contacts';
$blue_panel_modules[] = 'occupational_license_info';
$blue_panel_modules[] = 'drivers_license_info';
$blue_panel_modules[] = 'applicant_profile';
$blue_panel_modules[] = 'employee_profile';
$blue_panel_modules[] = 'dashboard';
$blue_panel_modules[] = 'my_settings';
$blue_panel_modules[] = 'my_referral_network';
$blue_panel_modules[] = 'documents_management';
$blue_panel_modules[] = 'eeo';
$blue_panel_modules[] = 'direct_deposit';
$blue_panel_modules[] = 'full_employment_application';
$blue_panel_modules[] = 'private_messages';
$blue_panel_modules[] = 'outbox';
$blue_panel_modules[] = 'compose_message';
$blue_panel_modules[] = 'outbox_message_detail';
$blue_panel_modules[] = 'inbox_message_detail';
$blue_panel_modules[] = 'reply_message';
$blue_panel_modules[] = 'manage_ems';
$blue_panel_modules[] = 'announcements';
$blue_panel_modules[] = 'manage_announcements';
$blue_panel_modules[] = 'list_announcements';
$blue_panel_modules[] = 'learning_center';
$blue_panel_modules[] = 'my_learning_center';
$blue_panel_modules[] = 'application_tracking_system';
$blue_panel_modules[] = 'form_w4';
$blue_panel_modules[] = 'form_w9';
$blue_panel_modules[] = 'form_i9';
$blue_panel_modules[] = 'support_tickets';
$blue_panel_modules[] = 'safety_sheets';
$blue_panel_modules[] = 'onboarding';
$blue_panel_modules[] = 'equipment_info';
$blue_panel_modules[] = 'hr_documents_management';
$blue_panel_modules[] = 'general_info';
$blue_panel_modules[] = 'add_ems_notification';
$blue_panel_modules[] = 'edit_ems_notification';
$blue_panel_modules[] = 'complynet';
// 
$blue_panel_modules[] = 'authorized_document';
define('BLUE_PANEL_MODULES', implode(',', $blue_panel_modules));

$public_modules = array();
$public_modules[] = 'home';
$public_modules[] = 'services';
$public_modules[] = 'turnover_cost_calculator';
$public_modules[] = 'login';
$public_modules[] = 'contact_us';
define('PUBLIC_MODULES', implode(',', $public_modules));

//video Option List and Upload Video Limit
define('NO_VIDEO', 'Not Required');
define('YOUTUBE_VIDEO', 'Youtube');
define('VIMEO_VIDEO', 'Vimeo');
define('UPLOAD_VIDEO', 'Upload Video');
define('UPLOAD_AUDIO', 'Upload Audio');
define('UPLOAD_VIDEO_SIZE', 200.00);
define('UPLOAD_AUDIO_SIZE', 40.00);
define('VIDEO_LOADER_MESSAGE', 'Please wait, while we are processing your request.');
define('ERROR_UPLOAD_VIDEO_SIZE', 'The uploaded video is too long, You can upload video up to 40MB.');
define('ERROR_UPLOAD_AUDIO_SIZE', 'The uploaded audio is too long, You can upload audio up to 40MB.');

// Set maximum event history
// limit
define('MAX_EVENT_HISTORY_ENTRIES', FALSE);
// define('MAX_EVENT_HISTORY_ENTRIES', 3, TRUE);
// Set calendar event reminder email template id
define('EVENT_REMINDER_EMAIL_NOTIFICATION', 362);
// Creator email
// For staging server
define('EVENT_STATUS_EMAIL_NOTIFICATION_FOR_CREATOR', 371);
// For live site
// define('EVENT_STATUS_EMAIL_NOTIFICATION_FOR_CREATOR', 372, TRUE);
// For localhost
// define('EVENT_STATUS_EMAIL_NOTIFICATION_FOR_CREATOR', 404, TRUE);

// Comply net
define('COMPLYNET_URL', 'https://able.complynet.com/Training/Login');
// define('COMPLYNET_URL', 'https://complynet.learn.taleo.net/login.asp?id=178443&requestedurl=%2fPage%2f596%3fh%3d1%26deepLink%3d1&secure=true');

// Special Character remover regex
define('SC_REGEX', '/[^(\x20-\x7F)\x0A\x0D]*/i');

// 26-06-2019
// Default timezone for webapp
define('STORE_DEFAULT_TIMEZONE_ABBR', 'PST');


// 04-07-2019
// Wether to show or hide timezone
define('IS_TIMEZONE_ACTIVE', TRUE);


// 12-07-2019
// Twilio Sandbox credentials
define('TWILIO_SANDBOX_SID', 'AC625d8274289680ae299d41c4253460d1');
define('TWILIO_SANDBOX_TOKEN', 'd8109cb5ac959835b414b5645879451c');
// Twilio live credentials
define('TWILIO_SID', 'AC625d8274289680ae299d41c4253460d1');
define('TWILIO_TOKEN', 'd8109cb5ac959835b414b5645879451c');

// Twilio Message Services SIDs
// Sandbox Message Service SID
define('SANDBOX_SERVICE', 'MG359e34ef1e42c763d3afc96c5ff28eaf');
define('ATS_SERVICE', 'GG359e34ef1e42c763d3afc96c5ff28eae');
define('ADMIN_SERVICE', 'MG8e049516f449aa23989691750e3468d4');

// Sandbox Checks
define('IS_SANDBOX', 0);
// define('SMS_MODE', 'sandbox');
// define('SMS_MODE', 'staging');
define('SMS_MODE', 'production');

//Sorting settings
define('SORT_COLUMN', 'first_name');
define('SORT_ORDER', 'ASC');
define('IS_PTO_ENABLED', 1);
define('IS_NOTIFICATION_ENABLED', 1);

//PTO default slot
define('PTO_DEFAULT_SLOT', 8);
define('PTO_DEFAULT_SLOT_MINUTES', 0);

//
define('DISALLOWEDCOMPANIES', '3,51,57');
define('DISALLOWEDMODULES', 'indeed_sponsor,timeoff');

//
define('GOVERMENT_EMAIL_TEMPLATE_SID', 382);
define('VIDEO_TEMPLATE_SID', 385);


define('PP_CATEGORY_SID', 27);

// Remarketing URL
// define('REMARKET_PORTAL_BASE_URL', 'https://bigboxhr.com');
if (preg_match('/(automotohr)/', APPPATH))
  define('REMARKET_PORTAL_BASE_URL', 'https://auto.careers');
else if (preg_match('/(applybuz)/', APPPATH))
  define('REMARKET_PORTAL_BASE_URL', 'https://bigboxhr.com');
else
  define('REMARKET_PORTAL_BASE_URL', 'http://localhost:81');

define('REMARKET_PORTAL_KEY', 'wenneuy3244862-384mhet-fsfjey3759560o-ptrut2350587t2-fvszaqe');
define('REMARKET_PORTAL_SAVE_APPLICANT_URL', REMARKET_PORTAL_BASE_URL . '/savelist/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_SAVE_CAMPAIGN_URL', REMARKET_PORTAL_BASE_URL . '/save_campaign/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_LIST_CAMPAIGNS_URL', REMARKET_PORTAL_BASE_URL . '/list_campaigns/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_GET_CAMPAIGN_URL', REMARKET_PORTAL_BASE_URL . '/get_campaign_details/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_DELETE_CAMPAIGN_URL', REMARKET_PORTAL_BASE_URL . '/delete_campaign/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_SYNC_COMPANY_URL', REMARKET_PORTAL_BASE_URL . '/sync_company_details/' . REMARKET_PORTAL_KEY);
define('REMARKET_PORTAL_SYNC_THEMES_URL', REMARKET_PORTAL_BASE_URL . '/sync_company_themes/' . REMARKET_PORTAL_KEY);

// FMLA content
define('FMLA_DESIGNATION', 'To be eligible an employee must have worked for an employer for at least 12 months, meet the hours of service requirement in the 12 months preceding the leave, and work at a site with at least 50 employees within 75 miles.');
define('FMLA_CERTIFICATION_OF_HEALTH', 'The Family and Medical Leave Act (FMLA) provides that an employer may require an employee seeking FMLA protections because of a need for leave due to a serious health condition to submit a medical certification issued by the employee’s health care provider.');
define('FMLA_RIGHTS', 'To be eligible an employee must have worked for an employer for at least 12 months, meet the hours of service requirement in the 12 months preceding the leave, and work at a site with at least 50 employees within 75 miles.');

//
define('TIMEOFFYEARLYCOMPANY', '58,5635');
//
define('ASSIGNEDOCIMPL', TRUE);
define('DOB_LIMIT', '1900:+1');
define('JOINING_DATE_LIMIT', '1900:+5');
define('STARTING_DATE_LIMIT', '1900:+20');
define('DATE', 'M d Y, D');
define('SITE_DATE', 'm/d/Y');
define('DB_DATE', 'Y-m-d');
define('DATE_WITH_TIME', 'M d, Y, D H:i:s');
define('TIME', 'H:i:s');
define('MD', 'H:i');
define('DB_DATE_WITH_TIME', 'Y-m-d H:i:s');
//
define('XSYM', '✖');
define('XSYM_PREG', '/' . XSYM . '/');
// Assure Hire
define('ASSUREHIR_STATUS', 'development');
define('ASSUREHIR_PACKAGES_URL', 'https://assurehire.com/partners/automoto/packages');
define('ASSUREHIR_ORDER_URL', 'https://assurehire.com/partners/automoto/webhook');
define('ASSUREHIRE_APIKEY', ASSUREHIR_STATUS == 'development' ? '203f003fdf96a3cf1cf7fcbf1bdf591f' : '203f003fdf96a3cf1cf7fcbf1bdf591f');
define("ASSUREHIR_USR", "mubashir@automoto.com");
define("ASSUREHIR_PWD", "ahautomotopw");

// Module Slugs
define("ASSUREHIRE_SLUG", "assurehire");

// Template
define("SINGLE_DOCUMENT_EMAIL_TEMPLATE", 387);

// Document completion alert emails
define("DOCUMENT_NOTIFICATION_TEMPLATE", 388);
define("DOCUMENT_NOTIFICATION_ACTION_TEMPLATE", 389);
define("DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE", 390);

// 
define('DOCUMENT_COMPLETION_REPOSRT_TEMPLATE_SID', 391);

// Time off magic quotes
define('TIMEOFF_MAGIC_QUOTES', implode(',', [
  'approver_first_name',
  'approver_last_name',
  'requester_first_name',
  'requester_last_name',
  'policy_name',
  'requested_date',
  'reason',
  'comment',
  'approve_link',
  'reject_link',
  'view_link',
  'cancel_link',
  'action_taken',
  'company_name',
  'company_address',
  'company_phone',
  'career_site_url',
  'first_name',
  'last_name'
]));

// Performance management email ids
define('REVIEW_ADDED', 415);
define('REVIEW_EXPIRING', 400);

// Goal template ids
define('GOAL_CREATED_BY_ADMIN', 401);
define('GOAL_EXPIRY_7', 402);
define('PM_ASSIGNED', 403);

// Time off email template code
define('TIMEOFF_CREATE_FOR_EMPLOYEE', 394); // on request creation to employee
define('TIMEOFF_CREATE_FOR_APPROVER', 392); // when the request was created
define('TIMEOFF_UPDATE_FOR_APPROVER', 393); // when the requestw as updated
// Time off email template code
define('USER_TIMEOFF_REQUEST', 414); // Send approve email to employee
define('APPROVER_TIMEOFF_REQUEST_UPDATE', 412); // Send approve/reject email to approvers
define('APPROVER_TIMEOFF_REQUEST', 413); // Send request email
define('CANCELED_TIMEOFF_REQUEST', 423); // Send request email

//
define('SHIFT_START', '9:00 AM'); // Default Shift Start
define('SHIFT_END', '6:00 PM'); // Default Shift End
define('DAY_OFF', ''); // Default Day Off
define('BREAK_HOURS', '1'); // Default Break Hours
define('BREAK_MINUTES', '0'); // Default Break Minutes


define('BILLING_AND_INVOICE', 'The selected contacts will be notified by email when an invoice is generated.');
define('NEW_APPLICANT', 'The selected contacts will be notified by email when an applicant applies for a job.');
define('VIDEO_INTERVIEW', 'The selected contacts will be notified by email when a video interview is created.');
define('APPROVAL_RIGHTS', 'The selected contacts will be notified by email when a job is accepted/rejected.');
define('FULL_EMPLOYMENT', 'The selected contacts will be notified by email when a Full Employment Application is completed.');
define('EXPIRATION_MANAGER', 'The selected contacts will be notified by email when the employees\' license is expiring within 30 days.');
define('ONBOARDING_REQUEST', 'The selected contacts= will be notified by email when an applicant sends to onboarding.');
define('OFFER_LETTER', 'The selected contacts will be notified by email when an offer letter/pay plan is assigned to the employees/applicants.');
define('DOCUMENT_ASSIGN', 'The selected contacts will be notified by email when a document is assigned to the employees/applicants.');
define('GENERAL_DOCUMENT', 'The selected contacts will be notified by email when a general information document is assigned to the employees/applicants.');
define('EMPLOYEE_PROFILE', 'The selected contacts will be notified by email when an employee changes their profile info.');
define('DEFAULT_APPROVERS', 'The selected contacts will be notified by email when a document is assigned for an approval.');
define('PRIVATE_MESSAGE', 'The recipient will be notified by email when any employee sends a private message to another employee, or a Candidate or person responds to an email that was sent by the employee from within the system.');
define('COURSE_MESSAGE', 'The LMS Managers will be notified about the course reports of their team members.');
define('DOCUMENT_REPORT_NOTIFICATION', 'The selected contacts will recieeved a weekly report of employees documents through email.');

define('I9_EXPIRES', '10/31/2022');
define('I9_NEW_EXPIRES', '07/31/2026');
define('W4_YEAR', '2023');
define('W4_EXEMPTION_FROM_WITHHOLDING', 'You may claim exemption from withholding for 2022 if you meet both of the following conditions: you had no federal income tax liability in 2021 and you expect to have no federal income tax liability in 2022. You had no federal income tax liability in 2021 if (1) your total tax on line 24 on your 2021 Form 1040 or 1040-SR is zero (or less than the sum of lines 27a, 28, 29, and 30), or (2) you were not required to file a return because your income was below the filing threshold for your correct filing status. If you claim exemption, you will have no income tax withheld from your paycheck and may owe taxes and penalties when you file your 2022 tax return. To claim exemption from withholding, certify that you meet both of the conditions above by writing “Exempt” on Form W-4 in the space below Step 4(c). Then, complete Steps 1(a), 1(b), and 5. Do not complete any other steps. You will need to submit a new Form W-4 by February 15, 2024.');
//
define('FEED_STRIP_TAGS', '<b><h1><h2><h3><h4><h5><h6><p><br><ul><ol><li></li><strong><em><table><tbody><th><tr><td>');
//
define('CHARACTER_SHOW', 2);
define('GUSTO_PAYROLL_TIME', '03:30 pm PDT');

//
define("WORK_WEEK_HOURS", 40);
//
define('PRIVATE_MESSAGE_NOTIFICATION', 424);
define('TIMEOFF_THEME_ONE', 1);
define('TIMEOFF_THEME_TWO', 2);


/**
 * Employee survey constants
 */
define('EMPLOYEE_SURVEYS', 'employeesurvey');
define('PAYROLL', 'payroll');


// Holds the EFFECT MAGIC CODES
define('EFFECT_MAGIC_CODE_LIST', ['{{signature}}', '{{inital}}']);

//
define('DEV_TO_EMAIL', 'mubashar.ahmed@egenienext.com'); // automotoHR developer email
// Interview email confirmation
define('INTERVIEW_EMAIL_CONFIRMATION', 426);
define('CREATE_INTERVIEW_EMAIL', 427);
define('CALENDAR_CREATE_EMAIL_CALL', 428);
define('CALENDAR_CREATE_EMAIL_EMAIL', 429);
define('CALENDAR_CREATE_EMAIL_TRAINING', 430);
define('CALENDAR_CREATE_EMAIL_MEETING', 431);
define('CALENDAR_EVENT_UPDATE', 432);
define('CALENDAR_EVENT_REMINDER', 433);
// Payroll Fast Payment Limit
define('FAST_PAYMENT_LIMIT', 500);
// module constants
// lms
define('MODULE_LMS', 'lms');
define('USA_CODE', 227);
define('COURSES_REMINDER_NOTIFICATION', 434);
define('ASSIGNED_COURSES_REMINDER_NOTIFICATION', 435);
define('DUE_SOON_COURSES_REMINDER_NOTIFICATION', 436);

// W4 amounts
define("W4_DEPENDENTS_UNDER_AGE_AMOUNT", 2000);
define("W4_DEPENDENTS_AMOUNT", 500);


define('W4_EXEMPTION_FROM_WITHHOLDING_23', 'You may claim exemption from withholding for 2023 if you meet both of the following conditions: you had no federal income tax liability in 2022 and you expect to have no federal income tax liability in 2023. You had no federal income tax liability in 2022 if (1) your total tax on line 24 on your 2022 Form 1040 or 1040-SR is zero (or less than the sum of lines 27a, 28, 29), or (2) you were not required to file a return because your income was below the filing threshold for your correct filing status. If you claim exemption, you will have no income tax withheld from your paycheck and may owe taxes and penalties when you file your 2023 tax return. To claim exemption from withholding, certify that you meet both of the conditions above by writing “Exempt” on Form W-4 in the space below Step 4(c). Then, complete Steps 1(a), 1(b), and 5. Do not complete any other steps. You will need to submit a new Form W-4 by February 15, 2024.');


define("SCHEDULE_MODULE", "schedule");
// modules
define('MODULE_ATTENDANCE', 'attendance');
// web page cache time
define("WEB_PAGE_CACHE_TIME_IN_MINUTES", 0);


define("META_TITLE", "AutomotoHR Helps you differentiate your business and Brand from everyone else.");
define("META_DESCRIPTION", "AutomotoHR Helps you differentiate your business and Brand from everyone else, with our People Operations platform Everything is in one place on one system Hire to Retire. So HOW DOES YOUR COMPANY STAND OUT?");
define("META_KEYWORDS", "AutomotoHR, People Operations platform, Business Differentiation, Brand Identity, One System Solution, Hire to Retire, Company Distinctiveness, HR Innovation, Unified HR Management, Branding Strategy, Employee Lifecycle, Streamlined Operations, Personnel Management, HR Efficiency, Competitive Advantage, Employee Experience, Seamless Integration, Organizational Uniqueness, HR Transformation, Comprehensive HR Solution, HRIS, HCM, Human Resources Software, Employee Management, Payroll Integration, Benefits Administration, Time and Attendance, Applicant Tracking System (ATS), Onboarding, Performance Management, Employee Self-Service, Compliance Management, HR Analytics, HR Automation, HR Reporting, Talent Management, Workforce Planning, HR Compliance, Employee Records Management, HR Cloud Software, HR Solutions, HR Technology, Employee Benefits, Leave Management, HRIS Integration, HR Software Solutions, HR Administration, HR Dashboard, HR Tools, HR Platform");

define("ALLOWED_EXTENSIONS", "image/*, video/mp4, video/mov");

// set email template for next day
// shift reminder
define("NEXT_DAY_SHIFT_REMINDER_EMAIL", 435);
define("ARE_YOU_STILL_INTERESTED", 436);

// set database timezone
define("DB_TIMEZONE", "UTC"); // just for clock in module

//set Published Shifts BG Collor
define("DB_UNPUBLISHED_SHIFTS", "#df8279");
define("FONT_COLOR_UNPUBLISHED_SHIFTS", "#ffffff");

//
define("SHIFTS_PUBLISH_CONFIRMATION", 447);

//
define('EMPLOYEE_PERFORMANCE_EVALUATION_MODULE', 'employeeperformanceevaluation');
define('PERFORMANCE_VERIFICATION_EMAIL', 448);


define('W4_EXEMPTION_FROM_WITHHOLDING_24', 'You may claim exemption
from withholding for 2024 if you meet both of the following
conditions: you had no federal income tax liability in 2023
and you expect to have no federal income tax liability in
2024. You had no federal income tax liability in 2023 if (1)
your total tax on line 24 on your 2023 Form 1040 or 1040-SR
is zero (or less than the sum of lines 27, 28, and 29), or (2)
you were not required to file a return because your income
was below the filing threshold for your correct filing status. If
you claim exemption, you will have no income tax withheld
from your paycheck and may owe taxes and penalties when
you file your 2024 tax return. To claim exemption from
withholding, certify that you meet both of the conditions
above by writing “Exempt” on Form W-4 in the space below
Step 4(c). Then, complete SteAction Required: Courses Pendingps 1(a), 1(b), and 5. Do not
complete any other steps. You will need to submit a new
Form W-4 by February 15, 2025');

define('W4_YEAR_24', '2024');

define('SHIFTS_SWAP_ADMIN_REJECTED', 439); 
define('SHIFTS_SWAP_AWAITING_CONFIRMATION', 440); 
define('SHIFTS_SWAP_EMPLOYEE_REJECTION', 449);
define('SHIFTS_SWAP_ADMIN_APPROVAL', 450);
define('SHIFTS_SWAP_ADMIN_APPROVED', 451);

// Courses
define("COURSE_UNCOMPLETED_REMINDER_EMAILS", 470);
define("COURSE_REPORT_EMAILS", 471);
define("COURSE_DUE_SOON_DAYS", 4);


////
define('W4_EXEMPTION_FROM_WITHHOLDING_25', 'You may claim exemption
from withholding for 2025 if you meet both of the following
conditions: you had no federal income tax liability in 2024
and you expect to have no federal income tax liability in
2025. You had no federal income tax liability in 2024 if (1)
your total tax on line 24 on your 2024 Form 1040 or 1040-SR
is zero (or less than the sum of lines 27, 28, and 29), or (2)
you were not required to file a return because your income
was below the filing threshold for your correct filing status. If
you claim exemption, you will have no income tax withheld
from your paycheck and may owe taxes and penalties when
you file your 2025 tax return. To claim exemption from
withholding, certify that you meet both of the conditions
above by writing “Exempt” on Form W-4 in the space below
Step 4(c). Then, complete SteAction Required: Courses Pendingps 1(a), 1(b), and 5. Do not
complete any other steps. You will need to submit a new
Form W-4 by February 17, 2026');


define('W4_YEAR_25', '2025');
define('I9_NEW_EXPIRES_2025', '05/31/2027');
define('COMPLIANCE_REPORT_NOTIFICATION', 472);
define("DOCUMENT_REPORT_EMAILS", 473);



