<?php defined('BASEPATH') OR exit('No direct script access allowed');
//$memory_limit = ini_get('memory_limit');
//echo $memory_limit; 
ini_set("memory_limit","1024M");
//$memory_limit = ini_get('memory_limit');
//echo '<hr>'. $memory_limit; exit;
class Dashboard extends Admin_Controller {
    private $limit;
    private $list_size;
    // Set default response array
    private $resp = array();
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/dashboard_model');
        $this->load->model('manage_admin/users_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // Set default response array
        $this->resp = array('Status' => false, 'Response' => 'Invalid request');
        $this->config->load('calendar_config');
        $this->limit     = $this->config->item('calendar_opt')['calendar_history_limit'];
        $this->list_size = $this->config->item('calendar_opt')['calendar_history_list_size'];
    }

    public function index() {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_admin_groups'); // Param2: Redirect URL, Param3: Function Name
        //View data working starts
        $companies                                                              = $this->dashboard_model->get_companies(); //Get Total employees Information
        //  echo $this->db->last_query();
        //  echo '<pre>'; print_r($companies); echo '</pre>';
        $active_companies                                                       = 0;
        $inactive_companies                                                     = 0;
        $active_number_of_rooftops                                              = 0;
        $inactive_number_of_rooftops                                            = 0;
        $total_companies                                                        = count($companies);
        $today_start                                                            = date('Y-m-d').' 00:00:01';
        $today_end                                                              = date('Y-m-d').' 23:59:59';
        $today_active_companies                                                 = 0;
        $today_not_active_companies                                             = 0;  
        $week_active_companies                                                  = 0;
        $week_not_active_companies                                              = 0;
        $month_active_companies                                                 = 0;
        $month_not_active_companies                                             = 0;
        $year_active_companies                                                  = 0;
        $year_not_active_companies                                              = 0;
        
        $today_date                                                             = new DateTime($today_start);
        $current_week_no                                                        = $today_date->format("W");
        $current_month_no                                                       = $today_date->format("m");
        $current_year_no                                                        = $today_date->format("Y");       

        for($c=0;$c<count($companies);$c++){
            $value = $companies[$c];
            //  }
            // foreach($companies as $key => $value){                                  // Process Companies data
            if($value['active']==1){                                            // check for total active companies
                $active_companies++;
                $active_number_of_rooftops += $value['number_of_rooftops'];
            } else {                                                            // check for total inactive companies
                $inactive_companies++;
                $inactive_number_of_rooftops += $value['number_of_rooftops'];
            }
            
            if($value['registration_date']>=$today_start && $value['registration_date']<=$today_end){ // Check all companies registered today
                if($value['active']==1){                                        // New companies registered today and are active
                    $today_active_companies++;
                } else {                                                        // New companies registered today and are inactive
                    $today_not_active_companies++;
                }
            }
            
            $compnay_registeration_date                                         = $value['registration_date'];
            $company_registeration_week                                         = new DateTime($compnay_registeration_date);
            $company_registeration_week_no                                      = $company_registeration_week->format("W");
            $company_registeration_month_no                                     = $company_registeration_week->format("m");
            $company_registeration_year_no                                      = $company_registeration_week->format("Y");
            
            if ( ($company_registeration_week_no == $current_week_no) && ($company_registeration_year_no == $current_year_no) ) {
                    if($value['active']==1){                                    // New companies registered current week and are active
                        $week_active_companies++;
                    } else {                                                    // New companies registered current week and are inactive
                        $week_not_active_companies++;
                    }
            }
            
            if ( ($company_registeration_month_no == $current_month_no) && ($company_registeration_year_no == $current_year_no) ) {
                    if($value['active']==1){                                    // New companies registered current month and are active
                        $month_active_companies++;
                    } else {                                                    // New companies registered current month and are inactive
                        $month_not_active_companies++;
                    }
            }
                        
            if ( $company_registeration_year_no == $current_year_no ) {
                    if ( $value['active'] == 1 ) {                              // New companies registered current month and are active
                        $year_active_companies++;
                    } else {                                                    // New companies registered current month and are inactive
                        $year_not_active_companies++;
                    }
            }
            unset($value);
        }

        $this->data['total_active_companies']                                   = $active_companies;
        $this->data['total_inactive_companies']                                 = $inactive_companies;      
        $this->data['total_companies']                                          = $total_companies;
        $this->data['total_not_active_companies']                               = $inactive_companies;      
        $this->data['today_active_companies']                                   = $today_active_companies;
        $this->data['today_not_active_companies']                               = $today_not_active_companies;                
        $this->data['week_active_companies']                                    = $week_active_companies;
        $this->data['week_not_active_companies']                                = $week_not_active_companies;
        $this->data['month_active_companies']                                   = $month_active_companies;
        $this->data['month_not_active_companies']                               = $month_not_active_companies;
        $this->data['year_active_companies']                                    = $year_active_companies;
        $this->data['year_not_active_companies']                                = $year_not_active_companies;
        $this->data['active_number_of_rooftops']                                = $active_number_of_rooftops;
        $this->data['inactive_number_of_rooftops']                              = $inactive_number_of_rooftops;
        // echo $active_number_of_rooftops.'<br>'.$inactive_number_of_rooftops;
        $employees                                                              = $this->dashboard_model->get_employers(); //Get Total Employers Information
        $total_employers                                                        = count($employees);
        $total_active_employers                                                 = 0;
        $total_not_active_employers                                             = 0;
        $today_active_employers                                                 = 0;
        $today_not_active_employers                                             = 0;
        $week_active_employers                                                  = 0;
        $week_not_active_employers                                              = 0;       
        $month_active_employers                                                 = 0;
        $month_not_active_employers                                             = 0;       
        $year_active_employers                                                  = 0;
        $year_not_active_employers                                              = 0;  
        
        for($e=0;$e<count($employees);$e++){
            $value = $employees[$e];
        //foreach($employees as $key => $value){                                  // Process employees data
            if($value['active']==1){                                            // check for total active employees
                $total_active_employers++;
            } else {                                                            // check for total inactive employees
                $total_not_active_employers++;
            }
            
            if($value['registration_date']>=$today_start && $value['registration_date']<=$today_end){ // Check all employees registered today
                if($value['active']==1){                                        // New employees registered today and are active
                    $today_active_employers++;
                } else {                                                        // New employees registered today and are inactive
                    $today_not_active_employers++;
                }
            }
            
            $employee_registeration_date                                         = $value['registration_date'];
            $employee_registeration_week                                         = new DateTime($employee_registeration_date);
            $employee_registeration_week_no                                      = $employee_registeration_week->format("W");
            $employee_registeration_month_no                                     = $employee_registeration_week->format("m");
            $employee_registeration_year_no                                      = $employee_registeration_week->format("Y");
            
            if ( ($employee_registeration_week_no == $current_week_no) && ($employee_registeration_year_no == $current_year_no) ) {
                    if($value['active']==1){                                    // New employees registered current week and are active
                        $week_active_employers++;
                    } else {                                                    // New employees registered current week and are inactive
                        $week_not_active_employers++;
                    }
            }
            
            if ( ($employee_registeration_month_no == $current_month_no) && ($employee_registeration_year_no == $current_year_no) ) {
                    if($value['active']==1){                                    // New employees registered current month and are active
                        $month_active_employers++;
                    } else {                                                    // New employees registered current month and are inactive
                        $month_not_active_employers++;
                    }
            }
                        
            if ( $employee_registeration_year_no == $current_year_no ) {
                    if ( $value['active'] == 1 ) {                              // New employees registered current month and are active
                        $year_active_employers++;
                    } else {                                                    // New employees registered current month and are inactive
                        $year_not_active_employers++;
                    }
            }
            unset($value);
        }
              
        $this->data['total_employers']                                          = $total_employers;
        $this->data['total_active_employers']                                   = $total_active_employers;
        $this->data['total_not_active_employers']                               = $total_not_active_employers;
        $this->data['today_active_employers']                                   = $today_active_employers;
        $this->data['today_not_active_employers']                               = $today_not_active_employers;
        $this->data['week_active_employers']                                    = $week_active_employers;
        $this->data['week_not_active_employers']                                = $week_not_active_employers;
        $this->data['month_active_employers']                                   = $month_active_employers;
        $this->data['month_not_active_employers']                               = $month_not_active_employers;
        $this->data['year_active_employers']                                    = $year_active_employers;
        $this->data['year_not_active_employers']                                = $year_not_active_employers;
        // $this->data['total_online_employers']                                   = $this->users_model->get_online_users(10);
        $this->data['total_online_employees_count']                             = $this->users_model->get_online_users_count(10);

        //Get Total Job Listings
        $job_listings = $this->dashboard_model->get_total_job_listings();
        $total_job_listings = count($job_listings);
        $this->data['total_jobs'] = count($job_listings);
        $active_job_listings = 0;
        $inactive_job_listing = 0;
        $organic_job_listing = 0;
        foreach($job_listings as $job_listing){
            if($job_listing['active'] == 1){
                if($job_listing['has_job_approval_rights']==1){
                    if($job_listing['approval_status'] == 'approved'){
                        $active_job_listings++; 
                    }else{
                        $inactive_job_listing++;
                    }
                } else {
                    $active_job_listings++; 
                }
            }else if($job_listing['active'] == 0){
                $inactive_job_listing++;
            }
            // count total organic job  check
            // if(isset($job_listing['expiry_date']) && $job_listing['expiry_date'] != NULL && $job_listing['expiry_date'] != '' && $job_listing['expiry_date'] < date('Y-m-d H:i:s') && $job_listing['organic_feed'] == 1){
            //     $organic_job_listing++;
            // }
        }
        $this->data['total_job_listings']   = $active_job_listings;
        $this->data['inactive_job_listing'] = $inactive_job_listing;
        $this->data['organic_job_listing']  = $this->dashboard_model->getOrganicJobCount();
        //Get Total Job Applications
        $today_applicants = 0;
        $this_week_applicants = 0;
        $this_month_applicants = 0;
        $this_year_applicants = 0;
        
        // $job_applications = $this->dashboard_model->get_total_job_applications();
        // 
        // foreach($job_applications as $value){
        //     if($value['date_applied']>=$today_start && $value['date_applied']<=$today_end){ // Get applicants count for today
        //          $today_applicants++;    
        //     } 
        // 
        //     $application_date                                                   = $value['date_applied'];
        //     $application_week                                                   = new DateTime($application_date);
        //     $application_week_no                                                = $application_week->format("W");
        //     $application_month_no                                               = $application_week->format("m");
        //     $application_year_no                                                = $application_week->format("Y");
        // 
        //     if ( ($application_week_no == $current_week_no) && ($application_year_no == $current_year_no) ) {
        //             $this_week_applicants++;
        //     }
        // 
        //     if ( ($application_month_no == $current_month_no) && ($application_year_no == $current_year_no) ) {
        //             $this_month_applicants++;
        //     }
        // 
        //     if ( $application_year_no == $current_year_no ) {
        //             $this_year_applicants++;
        //     }
        // }
        // 
        // $this->data['total_job_applications']                                   = count($job_applications);
        // $this->data['today_applicants']                                         = $today_applicants;
        // $this->data['this_week_applicants']                                     = $this_week_applicants;
        // $this->data['this_month_applicants']                                    = $this_month_applicants;
        // $this->data['this_year_applicants']                                     = $this_year_applicants;       

        $this->data['total_job_applications']                                   = $this->dashboard_model->get_total_job_applications_all();
        $this->data['today_applicants']                                         = $this->dashboard_model->get_total_job_applications_today();
        $this->data['this_week_applicants']                                     = $this->dashboard_model->get_total_job_applications_week();
        $this->data['this_month_applicants']                                    = $this->dashboard_model->get_total_job_applications_month();
        $this->data['this_year_applicants']                                     = $this->dashboard_model->get_total_job_applications_year();
        
        $excluded_companies                                                     = get_company_sids_excluded_from_reporting();

        //Get Total Earnings For the Month
        $total_earning_this_month                                               = $this->dashboard_model->get_total_earnings(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59'), $excluded_companies);
        $this->data['total_earning_this_month']                                 = $total_earning_this_month;

        //Get Total Earnings For the Year
        $total_earning_this_year                                                = $this->dashboard_model->get_total_earnings(date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y'))), date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, date('Y'))), $excluded_companies);
        $this->data['total_earning_this_year']                                  = $total_earning_this_year;

        //Get Total Earnings
        $total_earning_overall                                                  = $this->dashboard_model->get_total_earnings(NULL, NULL, $excluded_companies);
        $this->data['total_earning_overall']                                    = $total_earning_overall;

        $paid_marketplace_invoices_overall                                      = 0; //$this->dashboard_model->get_marketplace_invoices_total(null, null, 'paid');        


        $my_date                                                                = new DateTime();
        $current_week_day                                                       = intval($my_date->format('w'));
        $first_day                                                              = clone $my_date;
        $last_day                                                               = clone $my_date;

        if ($current_week_day !== 0) {
            $first_day->modify('last sunday');
        }

        if ($current_week_day !== 6) {
            $last_day->modify('next saturday');
        }

        $week_start                                                             = $first_day->format('Y-m-d 00:00:00');
        $week_end                                                               = $last_day->format('Y-m-d 23:59:59');

        // Generate daily, weekly, monthly sales status
        $paid_admin_invoices_today                                              = 0;
        $paid_marketplace_invoices_today                                        = 0;
        $paid_admin_invoices_this_week                                          = 0;
        $paid_marketplace_invoices_this_week                                    = 0;
        $paid_admin_invoices_this_month                                         = 0;
        $paid_marketplace_invoices_this_month                                   = 0; 
        
        $monthly_sales_report                                                   = $this->dashboard_model->get_paid_sales(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59'), $excluded_companies);
        
        if(!empty($monthly_sales_report)){ // process the data
            for($i=0; $i<count($monthly_sales_report); $i++){
            $invoice_type                                                       = $monthly_sales_report[$i]['invoice_type'];
            $amount                                                             = $monthly_sales_report[$i]['amount'];
            $receipt_date                                                       = strtotime($monthly_sales_report[$i]['receipt_date']);
                switch($invoice_type){
                    case 'admin_invoice';
                        if($receipt_date >= strtotime(date('Y-m-d 00:00:00')) && $receipt_date <= strtotime(date('Y-m-d 23:59:59'))){ // today paid admin invoices
                            $paid_admin_invoices_today += $amount;
                        }
                        
                        if($receipt_date >= strtotime($week_start) && $receipt_date <= strtotime($week_end)){ // week paid admin invoices
                            $paid_admin_invoices_this_week += $amount;
                        }
                        
                        if($receipt_date >= strtotime(date('Y-m-01 00:00:00')) && $receipt_date <= strtotime(date('Y-m-t 23:59:59'))){ // month paid admin invoices
                            $paid_admin_invoices_this_month += $amount;
                        }
                    break;
                    case 'market_place';
                       if($receipt_date >= strtotime(date('Y-m-d 00:00:00')) && $receipt_date <= strtotime(date('Y-m-d 23:59:59'))){ // today paid market invoices
                            $paid_marketplace_invoices_today += $amount;
                        }
                        
                        if($receipt_date >= strtotime($week_start) && $receipt_date <= strtotime($week_end)){ // today paid admin invoices
                            $paid_marketplace_invoices_this_week += $amount;
                        }
                        
                        if($receipt_date >= strtotime(date('Y-m-01 00:00:00')) && $receipt_date <= strtotime(date('Y-m-t 23:59:59'))){ // today paid admin invoices
                            $paid_marketplace_invoices_this_month += $amount;
                        }
                    break;

                } 
            }
        }
        
        $this->data['paid_admin_invoices_today']                                = $paid_admin_invoices_today;
        $this->data['paid_marketplace_invoices_today']                          = $paid_marketplace_invoices_today;
        $this->data['paid_admin_invoices_this_week']                            = $paid_admin_invoices_this_week;
        $this->data['paid_marketplace_invoices_this_week']                      = $paid_marketplace_invoices_this_week;
        $this->data['paid_admin_invoices_this_month']                           = $paid_admin_invoices_this_month;
        $this->data['paid_marketplace_invoices_this_month']                     = $paid_marketplace_invoices_this_month;

        //Today
        $unpaid_admin_invoices_today = $this->dashboard_model->get_admin_invoices_total(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'), 'unpaid', $excluded_companies);
        $this->data['unpaid_admin_invoices_today'] = $unpaid_admin_invoices_today;

        $unpaid_marketplace_invoices_today = $this->dashboard_model->get_marketplace_invoices_total(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'), 'unpaid', $excluded_companies);
        $this->data['unpaid_marketplace_invoices_today'] = $unpaid_marketplace_invoices_today;

        //This Week
        $unpaid_admin_invoices_this_week = $this->dashboard_model->get_admin_invoices_total(date('Y-m-d 00:00:00', strtotime('this monday')), date('Y-m-d 23:59:59', strtotime('this sunday')), 'unpaid', $excluded_companies);
        $this->data['unpaid_admin_invoices_this_week'] = $unpaid_admin_invoices_this_week;
        $unpaid_marketplace_invoices_this_week = $this->dashboard_model->get_marketplace_invoices_total(date('Y-m-d 00:00:00', strtotime('this monday')), date('Y-m-d 23:59:59', strtotime('this sunday')), 'unpaid', $excluded_companies);
        $this->data['unpaid_marketplace_invoices_this_week'] = $unpaid_marketplace_invoices_this_week;

        //This Month
        $unpaid_admin_invoices_this_month = $this->dashboard_model->get_admin_invoices_total(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59'), 'unpaid', $excluded_companies);
        $this->data['unpaid_admin_invoices_this_month'] = $unpaid_admin_invoices_this_month;
        $unpaid_marketplace_invoices_this_month = $this->dashboard_model->get_marketplace_invoices_total(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59'), 'unpaid', $excluded_companies);
        $this->data['unpaid_marketplace_invoices_this_month'] = $unpaid_marketplace_invoices_this_month;
        
        //Total Unpaid
        $unpaid_admin_invoices_overall = $this->dashboard_model->get_admin_invoices_total(null, null, 'unpaid', $excluded_companies);
        $this->data['unpaid_admin_invoices_overall'] = $unpaid_admin_invoices_overall;
        $unpaid_marketplace_invoices_overall = $this->dashboard_model->get_marketplace_invoices_total(null, null, 'unpaid', $excluded_companies);
        $this->data['unpaid_marketplace_invoices_overall'] = $unpaid_marketplace_invoices_overall;

        //Get Invoices Count
        $admin_invoices_count = 0;//$this->dashboard_model->get_admin_invoices_count();
        $marketplace_invoices_count = 0;//$this->dashboard_model->get_marketplace_invoices_count();
        
        //Check if Admin - Start
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->id)->result();
        
        foreach ($user_groups as $user_group) {
            if ($user_group->name == 'admin') {
                $this->data['is_admin'] = true;
                break;
            } else {
                $this->data['is_admin'] = false;
            }
        }
        //Check if Admin - end
        $this->data['total_invoices_count'] = intval($admin_invoices_count) + intval($marketplace_invoices_count);
        $this->render('manage_admin/dashboard_view');
    }

    function nice_number($n) {
        $n = (0+str_replace(",", "", $n));

        if (!is_numeric($n)) return false;

        if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
        elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
        elseif ($n > 1000000) return round(($n/1000000), 2).' million';
        elseif ($n > 1000) return round(($n/1000), 2).' thousand';

        return number_format($n);
    }
}