<?php defined('BASEPATH') or exit('No direct script access allowed');

// default path
$route['payroll'] = 'Main/index';

/*
 | -------------------------------------
 | Create partner company
 | -------------------------------------
 */
$route['payroll/company/(:num)/requirements']["get"] =
    'Company/checkCompanyRequirements/$1';

$route['payroll/company/(:num)/partner/welcome']["get"] =
    'Company/getWelcomePage/$1';
$route['payroll/company/(:num)/partner/employees']["get"] =
    'Company/getEmployees/$1';
$route['payroll/company/(:num)/partner/admin']["get"] =
    'Company/getAdmin/$1';
$route['payroll/company/(:num)/partner/admin/add']["get"] =
    'Company/addAdmin/$1';
$route['payroll/company/(:num)/partner/admin/add']["post"] =
    'Company/saveAdmin/$1';
$route['payroll/company/(:num)/partner/admin/view']["get"] =
    'Company/viewAdmin/$1';
$route['payroll/company/(:num)/partner/create']["post"] =
    'Company/createPartnerCompany/$1';

/*
 | -------------------------------------
 | Agreement
 | -------------------------------------
 */
$route['payroll/company/(:num)/agreement']['get'] =
    'Company/getAgreement/$1';
$route['payroll/company/(:num)/agreement/sign']['post'] =
    'Company/signAgreement/$1';
