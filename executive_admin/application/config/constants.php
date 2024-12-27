<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
define('STORE_DOMAIN', 'automotohr.com');
define('STORE_FULL_URL', STORE_PROTOCOL . 'www.' . STORE_DOMAIN);
define('AWS_S3_BUCKET_NAME', 'automotohrattachments');
define('AWS_S3_BUCKET_URL', STORE_PROTOCOL_SSL . AWS_S3_BUCKET_NAME . '.s3.amazonaws.com/');
define('SERVER_DNS_PRIMARY', 'NS1.M2408.SGDED.COM');
define('SERVER_DNS_SECONDARY', 'NS2.M2408.SGDED.COM');
define('STORE_FULL_URL_SSL', STORE_PROTOCOL_SSL . 'www.' . STORE_DOMAIN);

define('FROM_EMAIL_STEVEN', 'steven@' . STORE_DOMAIN);
define('TO_EMAIL_STEVEN', 'steven@' . STORE_DOMAIN);
define('FROM_EMAIL_DEV', 'dev@' . STORE_DOMAIN);
define('TO_EMAIL_DEV', 'dev@' . STORE_DOMAIN);
define('FROM_STORE_NAME', STORE_NAME);
define('FROM_EMAIL_INFO', 'info@' . STORE_DOMAIN);
define('TO_EMAIL_INFO', 'info@' . STORE_DOMAIN);
define('REPLY_TO', 'notifications@' . STORE_DOMAIN);
define('FROM_EMAIL_NOTIFICATIONS', 'notifications@' . STORE_DOMAIN);


defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// custom definitions
define('TALENT_NETWORK_SALE_CONTACTNO', ' 888-794-0794 ext 1'); // Talent network sales contact No
define('TALENT_NETWORK_SALES_EMAIL', 'Accounts@AutomotoHR.com'); // Talent network sales email address
define('TALENT_NETWORK_SUPPORT_CONTACTNO', ' 888-794-0794 ext 2'); // Talent network support contact No
define('TALENT_NETOWRK_SUPPORT_EMAIL', 'Support@AutomotoHR.com'); // Talent network support email address


//
define('SIGNATURE_CONSENT_HEADING', '<strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</strong> FOR <strong>{{company_name}}</strong><br/>'); // Signature consent heading text
define('SIGNATURE_CONSENT_TITLE', '1. Electronic Signature Agreement.'); // Signature consent title text
define('SIGNATURE_CONSENT_DESCRIPTION', "By selecting the &quot;I Accept&quot; button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting &quot;I Accept&quot; you consent to be legally bound by this Agreement's terms and conditions. You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise provide {{company_name}}, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as &quot;E-Signature&quot;), acceptance and agreement as if actually signed by you in writing. You also agree that no certification authority or other third party verification is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and {{company_name}}. You also represent that you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement. You further agree that each use of your E-Signature in obtaining a {{company_name}} service constitutes your agreement to be bound by the terms and conditions of the {{company_name}} Disclosures and Agreements as they exist on the date of your E-Signature."); // Signature consent discription text
define('SIGNATURE_CONSENT_CHECKBOX', 'I understand that checking this box constitutes a legal signature confirming that I acknowledge and agree to the below Terms of Acceptance.'); // Signature consent checkbox label text
define('SIGNATURE_CONSENT_BUTTON', 'I Consent and Accept the Terms of this Electronic Signature Agreement'); // Signature consent submit button text





$email_header = '';
$email_header .= '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative">';
$email_header .= '<div style="width: calc(100% - 40px); padding:20px; text-align:center; box-sizing:border-box; background:url(' . STORE_FULL_URL_SSL . 'assets/images/bg-body.jpg); opacity:0.9;  top:0; left:0;">';
$email_header .= '<img src="' . STORE_FULL_URL_SSL . '/assets/images/email-new-logo.png">';
$email_header .= '</div>';
$email_header .= '<div class="body-content" style="width: calc(100% - 40px); float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(' . STORE_FULL_URL_SSL . '/assets/images/bg-body.jpg);">';

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
$email_footer .= '<div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Sales Executive</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;" href="tel:+ 888-794-0794 ext 1"><img src="' . STORE_FULL_URL_SSL . '/assets/images/phone-icon.png">&nbsp;&nbsp;<strong>' . TALENT_NETWORK_SALE_CONTACTNO . '</strong></a></div>';
$email_footer .= '</div>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<span style=" font-weight:600;"><img src="' . STORE_FULL_URL_SSL . '/assets/images/email-icon.png">&nbsp;&nbsp;' . TALENT_NETWORK_SALES_EMAIL . '</span>';
$email_footer .= '</div>';
$email_footer .= '</td>';

$email_footer .= '<td>';
$email_footer .= '<div style="text-align:center !important; color: #000;">';
$email_footer .= '<div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Technical Support</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;" href="tel:+ 888-794-0794 ext 2"><strong><img src="' . STORE_FULL_URL_SSL . '/assets/images/phone-icon.png">&nbsp;&nbsp;' . TALENT_NETWORK_SUPPORT_CONTACTNO . '</strong></a></div>';
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
define('PAGINATION_RECORDS_PER_PAGE', 100);
define('APPLICANT_TYPE_ATS', 'Applicant,Talent Network,Manual Candidate,Job Fair,Re-Assigned Candidates');

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

// Comply net
define('COMPLYNET_URL', 'https://complynet.learn.taleo.net/login.asp?id=178443&requestedurl=%2fPage%2f596%3fh%3d1%26deepLink%3d1&secure=true');

// 8-07-2019
// Default timezone for webapp
define('STORE_DEFAULT_TIMEZONE_ABBR', 'PST');


// 8-07-2019
// Wether to show or hide timezone
define('IS_TIMEZONE_ACTIVE', TRUE);

define('DOB_LIMIT', '1900:+1');

//
define('MODULE_LMS', 'lms');