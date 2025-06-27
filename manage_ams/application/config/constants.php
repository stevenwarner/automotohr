<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);
define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
define('SHOW_DEBUG_BACKTRACE', TRUE);
define('STORE_ROOT_USER', '');
define('STORE_PROTOCOL', 'http://');
define('STORE_PROTOCOL_SSL', 'https://');
define('STORE_NAME', 'AutomotoSocial');
define('STORE_DOMAIN', 'automotosocial.com');
define('STORE_FULL_URL', STORE_PROTOCOL.'www.'.STORE_DOMAIN.'/');
define('AWS_S3_BUCKET_NAME', 'automotohrattachments');
define('AWS_S3_BUCKET_URL', STORE_PROTOCOL_SSL . AWS_S3_BUCKET_NAME . '.s3.amazonaws.com/');
define('SERVER_DNS_PRIMARY', 'need to get new aws NS Primary');
define('SERVER_DNS_SECONDARY', 'need to get new aws NS Secondary');
define('STORE_FULL_URL_SSL', STORE_PROTOCOL_SSL.'www.'.STORE_DOMAIN.'/');
define('STAGING_SERVER_URL', 'http://localhost/ahr/');
define('SIGNATURE_MAX_HEIGHT', '75px'); // Signature consent submit button text
define('SEND_RESUME_REQUEST', 379);
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('FROM_EMAIL_NOTIFICATIONS', 'notifications@' . STORE_DOMAIN); // automotoHR notifications email
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('FROM_EMAIL_STEVEN', 'steven@'.STORE_DOMAIN); // automotoHR admin email
define('TO_EMAIL_STEVEN', 'steven@'.STORE_DOMAIN); // automotoHR admin email
define('FROM_EMAIL_DEV', 'dev@'.STORE_DOMAIN); // automotoHR developer email
define('TO_EMAIL_DEV', 'dev@automotohr.com'); // automotoHR developer email
define('FROM_EMAIL_INFO', 'info@'.STORE_DOMAIN); // automotoHR info email
define('TO_EMAIL_INFO', 'info@automotohr.com'); // automotoHR info email
define('REPLY_TO', 'notifications@'.STORE_DOMAIN); // system emails to store in pm
define('FROM_STORE_NAME', STORE_NAME.'.com'); // automotoHR developer email
define('FROM_EMAIL_EVENTS', 'events@' . STORE_DOMAIN); // All Events Related Emails
define('THUMBNAIL_IMAGE_MAX_WIDTH', 300);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 300);
define('HTTP', 'http://');
define('EMPLOYER_LOGIN_TEXT', 'Employee Log In');
define('EMPLOYER_LOGIN_SUBTITLE', 'Employee / Team member Login');
define('EMPLOYER_LOGIN_LINK', 'https://www.automotohr.com/employee_login/');
define('APPLY_ON_JOB_EMAIL_ID', 18); //  Invoice notification email
define('GOOGLE_TRANSLATE_SNIPPET', "<script type='text/javascript'>function googleTranslateElementInit(){new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'de,es,fr,pt,it,zh-CN,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}</script><script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>");

define('DEFAULT_JOB_IMAGE', 'no_job_image.jpg');
define('JOB_LISTING_SHARE_TO_EMAIL_ADDRESS', 334);
define('TELL_A_FRIEND_JOB_SHARE', 350);
define('PAGINATION_RECORDS_PER_PAGE', 50);
define('DEF_EMAIL_BTN_STYLE_PRIMARY', 'background-color: #007bff; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Light Blue
define('DEF_EMAIL_BTN_STYLE_SECONDARY', 'background-color: #5a6268; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Gray
define('DEF_EMAIL_BTN_STYLE_SUCCESS', 'background-color: #218838; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Green
define('DEF_EMAIL_BTN_STYLE_DANGER', 'background-color: #c82333; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Red
define('DEF_EMAIL_BTN_STYLE_WARNING', 'background-color: #ffc107; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Yellow
define('DEF_EMAIL_BTN_STYLE_INFO', 'background-color: #138496; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: No shine light blue
define('DEF_EMAIL_BTN_STYLE_LIGHT', 'background-color: #e2e6ea; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #000; border-radius: 5px; text-align: center; display:inline-block');  // color: Light grey - white
define('DEF_EMAIL_BTN_STYLE_DARK', 'background-color: #23272b; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block');  // color: Dark - similar to black
$info_email_disclaimer = '';
$info_email_disclaimer .= '<br /><br /><br /><div style="clear: both; text-align: center; padding: 15px 0;">';
$info_email_disclaimer .= '<p>';
$info_email_disclaimer .= '***** This is an automated system email, please do not reply or redistribute this email *****';
$info_email_disclaimer .= '</p>';
$info_email_disclaimer .= '</div>';
define('UPLOAD_VIDEO_SIZE', 40.00);
define('UPLOAD_AUDIO_SIZE', 40.00);
define('ERROR_UPLOAD_VIDEO_SIZE', 'The uploaded video is too long, You can upload video up to 40MB.');
define('ERROR_UPLOAD_AUDIO_SIZE', 'The uploaded audio is too long, You can upload audio up to 40MB.');
define('FROM_INFO_EMAIL_DISCLAIMER_MSG', $info_email_disclaimer);
if(preg_match('/(automotohr)/', APPPATH))
  define('REMARKET_PORTAL_BASE_URL', 'https://auto.careers');
else if(preg_match('/(applybuz)/', APPPATH))
  define('REMARKET_PORTAL_BASE_URL', 'https://bigboxhr.com');
else
  define('REMARKET_PORTAL_BASE_URL', 'http://localhost:81');
define('REMARKET_PORTAL_SAVE_APPLICANT_URL', REMARKET_PORTAL_BASE_URL.'/savelist/wenneuy3244862-384mhet-fsfjey3759560o-ptrut2350587t2-fvszaqe');

// AWS SES Credentials
define('SES_HOST', 'email-smtp.us-west-1.amazonaws.com');
define('SES_USER', 'AKIAVVRKKAOCQ7RYZ7Y2');
define('SES_PASS', 'BJ64LLCLm1H9z/GhygOU2F+jnEgBo+PziG6je998Ice9');
define('SES_PORT', '587');

// Added on- 27/01/2022
define('FULL_EMPLOYMENT_APPLICATION_REQUEST', 333);

define('MAIN_STORE_FULL_URL_SSL', STORE_PROTOCOL_SSL.'www.automotohr.com/');
