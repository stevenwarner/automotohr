<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Financial_reports extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('manage_admin/financial_reports_model');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'financial_reports_index';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Financial Reports';
            $this->render('manage_admin/financial_reports/index', 'admin_master');
        } else {

        }
    }

    public function monthly_sales($year = null, $month = null)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_sales';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if ($this->form_validation->run() == false) {

            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $start_unix_date = mktime(0, 0, 0, $month, 1, $year);
            $end_unix_date = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);

            $month_first_date = intval(date('d', $start_unix_date));
            $month_last_date = intval(date('d', $end_unix_date));

            $months_sale_super_admin = array();
            $months_sale_employer_portal = array();

            for ($day = $month_first_date; $day <= $month_last_date; $day++) {
                $start_unix_date = mktime(0, 0, 0, $month, $day, $year);
                $end_unix_date = mktime(23, 59, 59, $month, $day, $year);

                $start_date = date('Y-m-d H:i:s', $start_unix_date);
                $end_date = date('Y-m-d H:i:s', $end_unix_date);

                $months_sale_super_admin[$day] = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
                $months_sale_employer_portal[$day] = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');
            }

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            $this->data['months_sale_super_admin'] = $months_sale_super_admin;
            $this->data['months_sale_employer_portal'] = $months_sale_employer_portal;

            $this->data['months'] = $months;
            $this->data['month'] = intval($month);
            $this->data['year'] = intval($year);

            $this->data['month_start'] = $month_first_date;
            $this->data['month_end'] = $month_last_date;

            //Graph Data
            $total_sale_super_admin = 0;
            $total_sale_employer_portal = 0;


            $days = array();
            $days_sales_super_admin = array();
            for ($day = $month_first_date; $day <= $month_last_date; $day++) {
                $days[] = $day;
                $days_sales_super_admin[] = $months_sale_super_admin[$day];
                $days_sales_employer_portal[] = $months_sale_employer_portal[$day];

                $total_sale_super_admin = $total_sale_super_admin + $months_sale_super_admin[$day];
                $total_sale_employer_portal = $total_sale_employer_portal + $months_sale_employer_portal[$day];
            }

            $this->data['days'] = json_encode($days);
            $this->data['days_sales_super_admin'] = json_encode($days_sales_super_admin);
            $this->data['days_sales_employer_portal'] = json_encode($days_sales_employer_portal);

            $this->data['total_sale_super_admin'] = $total_sale_super_admin;
            $this->data['total_sale_employer_portal'] = $total_sale_employer_portal;


            $this->data['page_title'] = 'Financial Reports - Monthly Sales';
            $this->render('manage_admin/financial_reports/monthly_sales', 'admin_master');
        } else {

        }
    }

    public function print_monthly_sales($year = null, $month = null)
    {
        

            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $start_unix_date = mktime(0, 0, 0, $month, 1, $year);
            $end_unix_date = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);

            $month_first_date = intval(date('d', $start_unix_date));
            $month_last_date = intval(date('d', $end_unix_date));

            $months_sale_super_admin = array();
            $months_sale_employer_portal = array();

            for ($day = $month_first_date; $day <= $month_last_date; $day++) {
                $start_unix_date = mktime(0, 0, 0, $month, $day, $year);
                $end_unix_date = mktime(23, 59, 59, $month, $day, $year);

                $start_date = date('Y-m-d H:i:s', $start_unix_date);
                $end_date = date('Y-m-d H:i:s', $end_unix_date);

                $months_sale_super_admin[$day] = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
                $months_sale_employer_portal[$day] = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');
            }

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            $this->data['months_sale_super_admin'] = $months_sale_super_admin;
            $this->data['months_sale_employer_portal'] = $months_sale_employer_portal;

            $this->data['months'] = $months;
            $this->data['month'] = intval($month);
            $this->data['year'] = intval($year);

            $this->data['month_start'] = $month_first_date;
            $this->data['month_end'] = $month_last_date;

            //Graph Data
            $total_sale_super_admin = 0;
            $total_sale_employer_portal = 0;


            $days = array();
            $days_sales_super_admin = array();
            for ($day = $month_first_date; $day <= $month_last_date; $day++) {
                $days[] = $day;
                $days_sales_super_admin[] = $months_sale_super_admin[$day];
                $days_sales_employer_portal[] = $months_sale_employer_portal[$day];

                $total_sale_super_admin = $total_sale_super_admin + $months_sale_super_admin[$day];
                $total_sale_employer_portal = $total_sale_employer_portal + $months_sale_employer_portal[$day];
            }

            $this->data['days'] = json_encode($days);
            $this->data['days_sales_super_admin'] = json_encode($days_sales_super_admin);
            $this->data['days_sales_employer_portal'] = json_encode($days_sales_employer_portal);

            $this->data['total_sale_super_admin'] = $total_sale_super_admin;
            $this->data['total_sale_employer_portal'] = $total_sale_employer_portal;


            $this->data['page_title'] = 'Financial Reports - Monthly Sales';
            $this->load->view('manage_admin/financial_reports/monthly_sales_print',$this->data);
        
    }

    public function daily_sales($year = null, $month = null, $day = null)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'daily_sales';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if ($this->form_validation->run() == false) {

            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);
            $day = ($day == null ? date('d') : $day);

            $start_unix_date = mktime(0, 0, 0, $month, $day, $year);
            $end_unix_date = mktime(23, 59, 59, $month, $day, $year);

            $start_date = date('Y-m-d H:i:s', $start_unix_date);
            $end_date = date('Y-m-d H:i:s', $end_unix_date);


            $receipts_super_admin = $this->financial_reports_model->get_receipts($start_date, $end_date, 'all', 'admin_invoice');
            $this->data['receipts_super_admin'] = $receipts_super_admin;

            $receipts_employer_portal = $this->financial_reports_model->get_receipts($start_date, $end_date, 'all', 'market_place');
            $this->data['receipts_employer_portal'] = $receipts_employer_portal;

            $this->data['month'] = intval($month);
            $this->data['year'] = intval($year);
            $this->data['day'] = intval($day);

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $this->data['page_title'] = 'Financial Reports - Daily Sales';
            $this->render('manage_admin/financial_reports/daily_sales', 'admin_master');
        } else {

        }
    }

    public function yearly_sales($year = null)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'yearly_sales';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($this->form_validation->run() == false) {

            $year = ($year == null ? date('Y') : $year);

            $yearly_sale_super_admin = array();
            $yearly_sale_employer_portal = array();

            $total_super_admin_sales = 0;
            $total_employer_portal_sales = 0;

            $chart_data_super_admin = array();
            $chart_data_employer_portal = array();

            for ($month = 1; $month <= 12; $month++) {
                $start_date_unix = mktime(0, 0, 0, $month, 1, $year);
                $end_date_unix = mktime(0, 0, 0, $month, intval(date('t', $start_date_unix)), $year);

                $start_date = date('Y-m-d H:i:s', $start_date_unix);
                $end_date = date('Y-m-d H:i:s', $end_date_unix);

                $month_super_admin = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
                $month_employer_portal = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');

                $total_super_admin_sales = $total_super_admin_sales + $month_super_admin;
                $total_employer_portal_sales = $total_employer_portal_sales + $month_employer_portal;

                $yearly_sale_super_admin[$month] = $month_super_admin;
                $yearly_sale_employer_portal[$month] = $month_employer_portal;

                $chart_data_super_admin[] = $month_super_admin;
                $chart_data_employer_portal[] = $month_employer_portal;
            }

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $this->data['year'] = $year;

            $this->data['yearly_sale_super_admin'] = $yearly_sale_super_admin;
            $this->data['yearly_sale_employer_portal'] = $yearly_sale_employer_portal;

            $this->data['total_super_admin_sales'] = $total_super_admin_sales;
            $this->data['total_employer_portal_sales'] = $total_employer_portal_sales;


            //Chart Data
            $chart_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['chart_months'] = json_encode($chart_months);

            $this->data['chart_data_super_admin'] = json_encode($chart_data_super_admin);
            $this->data['chart_data_employer_portal'] = json_encode($chart_data_employer_portal);


            $this->data['page_title'] = 'Financial Reports - Yearly Sales';
            $this->render('manage_admin/financial_reports/yearly_sales', 'admin_master');

        } else {

        }
    }

    public function print_yearly_sales($year = null)
    {
        $year = ($year == null ? date('Y') : $year);

        $yearly_sale_super_admin = array();
        $yearly_sale_employer_portal = array();

        $total_super_admin_sales = 0;
        $total_employer_portal_sales = 0;

        $chart_data_super_admin = array();
        $chart_data_employer_portal = array();

        for ($month = 1; $month <= 12; $month++) {
            $start_date_unix = mktime(0, 0, 0, $month, 1, $year);
            $end_date_unix = mktime(0, 0, 0, $month, intval(date('t', $start_date_unix)), $year);

            $start_date = date('Y-m-d H:i:s', $start_date_unix);
            $end_date = date('Y-m-d H:i:s', $end_date_unix);

            $month_super_admin = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
            $month_employer_portal = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');

            $total_super_admin_sales = $total_super_admin_sales + $month_super_admin;
            $total_employer_portal_sales = $total_employer_portal_sales + $month_employer_portal;

            $yearly_sale_super_admin[$month] = $month_super_admin;
            $yearly_sale_employer_portal[$month] = $month_employer_portal;

            $chart_data_super_admin[] = $month_super_admin;
            $chart_data_employer_portal[] = $month_employer_portal;
        }

        $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->data['months'] = $months;

        $this->data['year'] = $year;

        $this->data['yearly_sale_super_admin'] = $yearly_sale_super_admin;
        $this->data['yearly_sale_employer_portal'] = $yearly_sale_employer_portal;

        $this->data['total_super_admin_sales'] = $total_super_admin_sales;
        $this->data['total_employer_portal_sales'] = $total_employer_portal_sales;


        //Chart Data
        $chart_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->data['chart_months'] = json_encode($chart_months);

        $this->data['chart_data_super_admin'] = json_encode($chart_data_super_admin);
        $this->data['chart_data_employer_portal'] = json_encode($chart_data_employer_portal);


        $this->data['page_title'] = 'Financial Reports - Yearly Sales';
        $this->load->view('manage_admin/financial_reports/yearly_sales_print',$this->data);

    }

    public function yearly_sales_comparison($from_year = null, $to_year = null)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'yearly_sales_comparison';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $super_admin_sales = array();
        $employer_portal_sales = array();

        $total_super_admin = 0;
        $total_employer_portal = 0;

        $chart_years = array();
        $chart_super_admin_sales = array();
        $chart_employer_portal_sales = array();

        if ($this->form_validation->run() == false) {
            $from_year = $from_year == null ? 2016 : $from_year;
            $to_year = $to_year == null ? date('Y') : $to_year;

            for ($year = $from_year; $year <= $to_year; $year++) {
                $start_date_unix = mktime(0, 0, 0, 1, 1, $year);
                $end_date_unix = mktime(23, 59, 59, 12, 31, $year);

                $start_date = date('Y-m-d H:i:s', $start_date_unix);
                $end_date = date('Y-m-d H:i:s', $end_date_unix);

                $super_admin_sale = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
                $employer_portal_sale = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');

                $super_admin_sales[$year] = $super_admin_sale;
                $employer_portal_sales[$year] = $employer_portal_sale;

                $chart_years[] = $year;
                $chart_super_admin_sales[] = $super_admin_sale;
                $chart_employer_portal_sales[] = $employer_portal_sale;

                $total_super_admin = $total_super_admin + $super_admin_sale;
                $total_employer_portal = $total_employer_portal + $employer_portal_sale;
            }

            $this->data['super_admin_sales'] = $super_admin_sales;
            $this->data['employer_portal_sales'] = $employer_portal_sales;

            $this->data['from_year'] = $from_year;
            $this->data['to_year'] = $to_year;

            $this->data['total_super_admin_sales'] = $total_super_admin;
            $this->data['total_employer_portal_sales'] = $total_employer_portal;


            //Chart Data
            $this->data['chart_years'] = json_encode($chart_years);
            $this->data['chart_super_admin_sales'] = json_encode($chart_super_admin_sales);
            $this->data['chart_employer_portal_sales'] = json_encode($chart_employer_portal_sales);

            $this->data['page_title'] = 'Financial Reports - Yearly Sales Comparison';
            $this->render('manage_admin/financial_reports/yearly_sales_comparison', 'admin_master');
        } else {

        }
    }

    public function print_yearly_sales_comparison($from_year = null, $to_year = null)
    {
        
            $from_year = $from_year == null ? 2016 : $from_year;
            $to_year = $to_year == null ? date('Y') : $to_year;

            for ($year = $from_year; $year <= $to_year; $year++) {
                $start_date_unix = mktime(0, 0, 0, 1, 1, $year);
                $end_date_unix = mktime(23, 59, 59, 12, 31, $year);

                $start_date = date('Y-m-d H:i:s', $start_date_unix);
                $end_date = date('Y-m-d H:i:s', $end_date_unix);

                $super_admin_sale = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'admin_invoice');
                $employer_portal_sale = $this->financial_reports_model->get_total_sales($start_date, $end_date, 'all', 'market_place');

                $super_admin_sales[$year] = $super_admin_sale;
                $employer_portal_sales[$year] = $employer_portal_sale;

                $chart_years[] = $year;
                $chart_super_admin_sales[] = $super_admin_sale;
                $chart_employer_portal_sales[] = $employer_portal_sale;

                $total_super_admin = $total_super_admin + $super_admin_sale;
                $total_employer_portal = $total_employer_portal + $employer_portal_sale;
            }

            $this->data['super_admin_sales'] = $super_admin_sales;
            $this->data['employer_portal_sales'] = $employer_portal_sales;

            $this->data['from_year'] = $from_year;
            $this->data['to_year'] = $to_year;

            $this->data['total_super_admin_sales'] = $total_super_admin;
            $this->data['total_employer_portal_sales'] = $total_employer_portal;


            //Chart Data
            $this->data['chart_years'] = json_encode($chart_years);
            $this->data['chart_super_admin_sales'] = json_encode($chart_super_admin_sales);
            $this->data['chart_employer_portal_sales'] = json_encode($chart_employer_portal_sales);

            $this->data['page_title'] = 'Financial Reports - Yearly Sales Comparison';
            $this->load->view('manage_admin/financial_reports/yearly_sales_comparison_print',$this->data);
        
    }

    public function monthly_marketplace_products_usage($vendor = 'all', $year = null, $month = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_marketplace_products_usage';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if($this->form_validation->run() == false){
            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $from_date_unix = mktime(0,0,0, $month, 1, $year);
            $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

            $from_date = date('Y-m-d H:i:s', $from_date_unix);
            $to_date = date('Y-m-d H:i:s', $to_date_unix);


            $indeed_products_sids = [1, 21];
            $ziprecruiter_products_sids = [2];
            $automotosocial_products_sids = [3, 4, 22];
            $jobs2careers_products_sids = [5];
            $juju_products_sids = [6];
            $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
            $sparkhire_products_sids = [12, 13, 14];

            $products_sids = array();
            switch($vendor){
                case 'indeed':
                    $products_sids = $indeed_products_sids;
                    break;
                case 'ziprecruiter':
                    $products_sids = $ziprecruiter_products_sids;
                    break;
                case 'automotosocial':
                    $products_sids = $automotosocial_products_sids;
                    break;
                case 'jobs2careers':
                    $products_sids = $jobs2careers_products_sids;
                    break;
                case 'juju':
                    $products_sids = $juju_products_sids;
                    break;
                case 'accuratebackground':
                    $products_sids = $accuratebackground_products_sids;
                    break;
                case 'sparkhire':
                    $products_sids = $sparkhire_products_sids;
                    break;
                default:
                    $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                    break;
            }


            $product_usage = array();

            switch($vendor){
                case 'indeed':
                case 'ziprecruiter':
                case 'automotosocial':
                case 'jobs2careers':
                case 'juju':
                case 'sparkhire':
                    if(!empty($products_sids)){
                        foreach($products_sids as $product_sid){
                            $prod_data = $this->financial_reports_model->get_job_board_product_usage($product_sid, $from_date, $to_date);
                            $product_usage = array_merge($product_usage, $prod_data);
                        }
                    }
                    break;
                case 'accuratebackground':
                    if(!empty($products_sids)){
                        foreach($products_sids as $product_sid){
                            $prod_data = $this->financial_reports_model->get_accurate_background_product_usage($product_sid, $from_date, $to_date);
                            $product_usage = array_merge($product_usage, $prod_data);
                        }
                    }
                    break;
                default:
                    if(!empty($products_sids)){
                        foreach($products_sids as $product_sid){
                            $prod_data = $this->financial_reports_model->get_job_board_product_usage($product_sid, $from_date, $to_date);
                            $product_usage = array_merge($product_usage, $prod_data);

                            $prod_data = $this->financial_reports_model->get_accurate_background_product_usage($product_sid, $from_date, $to_date);
                            $product_usage = array_merge($product_usage, $prod_data);
                        }
                    }
                    break;
            }


            $total_sale = 0;
            $total_cost = 0;
            $total_profit = 0;

            $excluded_companies = get_company_sids_excluded_from_reporting();


            foreach($product_usage as $key => $product){
                $unset_executed = false;

                if($product['company_sid'] <= 0 || in_array($product['company_sid'], $excluded_companies))
                {
                    unset($product_usage[$key]);
                    $unset_executed = true;
                }

                if($product['product_cost_price'] == 0 || $product['product_cost_price'] == null){
                    $product['product_cost_price'] = $product['product_price'];
                }

                $profit = $product['product_price'] - $product['product_cost_price'];

                if($unset_executed == false) {
                    $product_usage[$key]['profit'] = $profit;

                    $total_sale = $total_sale + $product['product_price'];
                    $total_cost = $total_cost + $product['product_cost_price'];
                    $total_profit = $total_profit + $profit;

                }
            }

            usort($product_usage, function ($item1, $item2) {
                if ($item2['usage_date'] == $item1['usage_date']) return 0;
                return $item2['usage_date'] < $item1['usage_date'] ? -1 : 1;
            });


            $this->data['product_usage'] = $product_usage;
            $this->data['total_sale'] = $total_sale;
            $this->data['total_cost'] = $total_cost;
            $this->data['total_profit'] = $total_profit;

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $vendors = array();
            $vendors['indeed'] = 'Indeed.com';
            $vendors['ziprecruiter'] = 'ZipRecruiter.com';
            $vendors['automotosocial'] = 'AutomotoSocial.com';
            $vendors['jobs2careers'] = 'Jobs2Careers.com';
            $vendors['juju'] = 'JuJu.com';
            $vendors['sparkhire'] = 'SparkHire.com';
            $vendors['accuratebackground'] = 'AccurateBackground.com';

            $this->data['vendors'] = $vendors;

            $this->data['vendor'] = $vendor;
            $this->data['month'] = $month;
            $this->data['year'] = $year;


            $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Products Usage History';
            $this->render('manage_admin/financial_reports/monthly_marketplace_products_usage', 'admin_master');

        } else {

        }
    }

    public function print_monthly_marketplace_products_usage($vendor = 'all', $year = null, $month = null){
        
        $year = ($year == null ? date('Y') : $year);
        $month = ($month == null ? date('m') : $month);

        $from_date_unix = mktime(0,0,0, $month, 1, $year);
        $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

        $from_date = date('Y-m-d H:i:s', $from_date_unix);
        $to_date = date('Y-m-d H:i:s', $to_date_unix);


        $indeed_products_sids = [1, 21];
        $ziprecruiter_products_sids = [2];
        $automotosocial_products_sids = [3, 4, 22];
        $jobs2careers_products_sids = [5];
        $juju_products_sids = [6];
        $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
        $sparkhire_products_sids = [12, 13, 14];

        $products_sids = array();
        switch($vendor){
            case 'indeed':
                $products_sids = $indeed_products_sids;
                break;
            case 'ziprecruiter':
                $products_sids = $ziprecruiter_products_sids;
                break;
            case 'automotosocial':
                $products_sids = $automotosocial_products_sids;
                break;
            case 'jobs2careers':
                $products_sids = $jobs2careers_products_sids;
                break;
            case 'juju':
                $products_sids = $juju_products_sids;
                break;
            case 'accuratebackground':
                $products_sids = $accuratebackground_products_sids;
                break;
            case 'sparkhire':
                $products_sids = $sparkhire_products_sids;
                break;
            default:
                $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                break;
        }


        $product_usage = array();

        switch($vendor){
            case 'indeed':
            case 'ziprecruiter':
            case 'automotosocial':
            case 'jobs2careers':
            case 'juju':
            case 'sparkhire':
                if(!empty($products_sids)){
                    foreach($products_sids as $product_sid){
                        $prod_data = $this->financial_reports_model->get_job_board_product_usage($product_sid, $from_date, $to_date);
                        $product_usage = array_merge($product_usage, $prod_data);
                    }
                }
                break;
            case 'accuratebackground':
                if(!empty($products_sids)){
                    foreach($products_sids as $product_sid){
                        $prod_data = $this->financial_reports_model->get_accurate_background_product_usage($product_sid, $from_date, $to_date);
                        $product_usage = array_merge($product_usage, $prod_data);
                    }
                }
                break;
            default:
                if(!empty($products_sids)){
                    foreach($products_sids as $product_sid){
                        $prod_data = $this->financial_reports_model->get_job_board_product_usage($product_sid, $from_date, $to_date);
                        $product_usage = array_merge($product_usage, $prod_data);

                        $prod_data = $this->financial_reports_model->get_accurate_background_product_usage($product_sid, $from_date, $to_date);
                        $product_usage = array_merge($product_usage, $prod_data);
                    }
                }
                break;
        }


        $total_sale = 0;
        $total_cost = 0;
        $total_profit = 0;

        $excluded_companies = get_company_sids_excluded_from_reporting();


        foreach($product_usage as $key => $product){
            $unset_executed = false;

            if($product['company_sid'] <= 0 || in_array($product['company_sid'], $excluded_companies))
            {
                unset($product_usage[$key]);
                $unset_executed = true;
            }

            if($product['product_cost_price'] == 0 || $product['product_cost_price'] == null){
                $product['product_cost_price'] = $product['product_price'];
            }

            $profit = $product['product_price'] - $product['product_cost_price'];

            if($unset_executed == false) {
                $product_usage[$key]['profit'] = $profit;

                $total_sale = $total_sale + $product['product_price'];
                $total_cost = $total_cost + $product['product_cost_price'];
                $total_profit = $total_profit + $profit;

            }
        }

        usort($product_usage, function ($item1, $item2) {
            if ($item2['usage_date'] == $item1['usage_date']) return 0;
            return $item2['usage_date'] < $item1['usage_date'] ? -1 : 1;
        });


        $this->data['product_usage'] = $product_usage;
        $this->data['total_sale'] = $total_sale;
        $this->data['total_cost'] = $total_cost;
        $this->data['total_profit'] = $total_profit;

        $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->data['months'] = $months;

        $vendors = array();
        $vendors['indeed'] = 'Indeed.com';
        $vendors['ziprecruiter'] = 'ZipRecruiter.com';
        $vendors['automotosocial'] = 'AutomotoSocial.com';
        $vendors['jobs2careers'] = 'Jobs2Careers.com';
        $vendors['juju'] = 'JuJu.com';
        $vendors['sparkhire'] = 'SparkHire.com';
        $vendors['accuratebackground'] = 'AccurateBackground.com';

        $this->data['vendors'] = $vendors;

        $this->data['vendor'] = $vendor;
        $this->data['month'] = $month;
        $this->data['year'] = $year;


        $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Products Usage History';
        $this->load->view('manage_admin/financial_reports/monthly_marketplace_products_usage_print',$this->data);

    }

    public function monthly_marketplace_products_sales($vendor = 'all', $year = null, $month = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_marketplace_products_sales';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if($this->form_validation->run() == false){
            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $from_date_unix = mktime(0,0,0, $month, 1, $year);
            $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

            $from_date = date('Y-m-d H:i:s', $from_date_unix);
            $to_date = date('Y-m-d H:i:s', $to_date_unix);


            $indeed_products_sids = [1, 21];
            $ziprecruiter_products_sids = [2];
            $automotosocial_products_sids = [3, 4, 22];
            $jobs2careers_products_sids = [5];
            $juju_products_sids = [6];
            $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
            $sparkhire_products_sids = [12, 13, 14];

            $products_sids = array();
            switch($vendor){
                case 'indeed':
                    $products_sids = $indeed_products_sids;
                    break;
                case 'ziprecruiter':
                    $products_sids = $ziprecruiter_products_sids;
                    break;
                case 'automotosocial':
                    $products_sids = $automotosocial_products_sids;
                    break;
                case 'jobs2careers':
                    $products_sids = $jobs2careers_products_sids;
                    break;
                case 'juju':
                    $products_sids = $juju_products_sids;
                    break;
                case 'accuratebackground':
                    $products_sids = $accuratebackground_products_sids;
                    break;
                case 'sparkhire':
                    $products_sids = $sparkhire_products_sids;
                    break;
                default:
                    $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                    break;
            }


            $products_sold = array();

            foreach($products_sids as $product_sid){
                $products = $this->financial_reports_model->get_invoices_by_products_sid($product_sid, $from_date, $to_date);

                $products_sold = array_merge($products_sold, $products);
            }




            usort($products_sold, function ($item1, $item2) {
                if ($item2['date'] == $item1['date']) return 0;
                return $item2['date'] < $item1['date'] ? -1 : 1;
            });

            $excluded_companies = get_company_sids_excluded_from_reporting();

            foreach($products_sold as $key => $products){
                if(in_array($products['company_sid'], $excluded_companies)){
                    unset($products[$key]);
                }
            }


            $this->data['products_sold'] = $products_sold;


            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $vendors = array();
            $vendors['indeed'] = 'Indeed.com';
            $vendors['ziprecruiter'] = 'ZipRecruiter.com';
            $vendors['automotosocial'] = 'AutomotoSocial.com';
            $vendors['jobs2careers'] = 'Jobs2Careers.com';
            $vendors['juju'] = 'JuJu.com';
            $vendors['sparkhire'] = 'SparkHire.com';
            $vendors['accuratebackground'] = 'AccurateBackground.com';

            $this->data['vendors'] = $vendors;

            $this->data['vendor'] = $vendor;
            $this->data['month'] = $month;
            $this->data['year'] = $year;


            $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Products Sales History';
            $this->render('manage_admin/financial_reports/monthly_marketplace_products_sales', 'admin_master');

        } else {

        }
    }

    public function print_monthly_marketplace_products_sales($vendor = 'all', $year = null, $month = null){
        
            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $from_date_unix = mktime(0,0,0, $month, 1, $year);
            $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

            $from_date = date('Y-m-d H:i:s', $from_date_unix);
            $to_date = date('Y-m-d H:i:s', $to_date_unix);


            $indeed_products_sids = [1, 21];
            $ziprecruiter_products_sids = [2];
            $automotosocial_products_sids = [3, 4, 22];
            $jobs2careers_products_sids = [5];
            $juju_products_sids = [6];
            $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
            $sparkhire_products_sids = [12, 13, 14];

            $products_sids = array();
            switch($vendor){
                case 'indeed':
                    $products_sids = $indeed_products_sids;
                    break;
                case 'ziprecruiter':
                    $products_sids = $ziprecruiter_products_sids;
                    break;
                case 'automotosocial':
                    $products_sids = $automotosocial_products_sids;
                    break;
                case 'jobs2careers':
                    $products_sids = $jobs2careers_products_sids;
                    break;
                case 'juju':
                    $products_sids = $juju_products_sids;
                    break;
                case 'accuratebackground':
                    $products_sids = $accuratebackground_products_sids;
                    break;
                case 'sparkhire':
                    $products_sids = $sparkhire_products_sids;
                    break;
                default:
                    $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                    break;
            }


            $products_sold = array();

            foreach($products_sids as $product_sid){
                $products = $this->financial_reports_model->get_invoices_by_products_sid($product_sid, $from_date, $to_date);

                $products_sold = array_merge($products_sold, $products);
            }




            usort($products_sold, function ($item1, $item2) {
                if ($item2['date'] == $item1['date']) return 0;
                return $item2['date'] < $item1['date'] ? -1 : 1;
            });

            $excluded_companies = get_company_sids_excluded_from_reporting();

            foreach($products_sold as $key => $products){
                if(in_array($products['company_sid'], $excluded_companies)){
                    unset($products[$key]);
                }
            }


            $this->data['products_sold'] = $products_sold;


            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $vendors = array();
            $vendors['indeed'] = 'Indeed.com';
            $vendors['ziprecruiter'] = 'ZipRecruiter.com';
            $vendors['automotosocial'] = 'AutomotoSocial.com';
            $vendors['jobs2careers'] = 'Jobs2Careers.com';
            $vendors['juju'] = 'JuJu.com';
            $vendors['sparkhire'] = 'SparkHire.com';
            $vendors['accuratebackground'] = 'AccurateBackground.com';

            $this->data['vendors'] = $vendors;

            $this->data['vendor'] = $vendor;
            $this->data['month'] = $month;
            $this->data['year'] = $year;


            $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Products Sales History';
            $this->load->view('manage_admin/financial_reports/monthly_marketplace_products_sales_print',$this->data);
    }

    public function monthly_marketplace_product_statistics($vendor = 'all', $year = null, $month = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_marketplace_product_statistics';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if($this->form_validation->run() == false){
            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $from_date_unix = mktime(0,0,0, $month, 1, $year);
            $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

            $from_date = date('Y-m-d H:i:s', $from_date_unix);
            $to_date = date('Y-m-d H:i:s', $to_date_unix);


            $indeed_products_sids = [1, 21];
            $ziprecruiter_products_sids = [2];
            $automotosocial_products_sids = [3, 4, 22];
            $jobs2careers_products_sids = [5];
            $juju_products_sids = [6];
            $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
            $sparkhire_products_sids = [12, 13, 14];

            $products_sids = array();
            switch($vendor){
                case 'indeed':
                    $products_sids = $indeed_products_sids;
                    break;
                case 'ziprecruiter':
                    $products_sids = $ziprecruiter_products_sids;
                    break;
                case 'automotosocial':
                    $products_sids = $automotosocial_products_sids;
                    break;
                case 'jobs2careers':
                    $products_sids = $jobs2careers_products_sids;
                    break;
                case 'juju':
                    $products_sids = $juju_products_sids;
                    break;
                case 'accuratebackground':
                    $products_sids = $accuratebackground_products_sids;
                    break;
                case 'sparkhire':
                    $products_sids = $sparkhire_products_sids;
                    break;
                default:
                    $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                    break;
            }



            $all_invoices = array();

            if(!empty($products_sids)){
                foreach($products_sids as $product_sid){
                    $invoices = $this->financial_reports_model->get_invoices_by_products_sid($product_sid, $from_date, $to_date);

                    $all_invoices = array_merge($all_invoices, $invoices);
                }
            }


            usort($all_invoices, function ($item1, $item2) {
                if ($item2['date'] == $item1['date']) return 0;
                return $item2['date'] < $item1['date'] ? -1 : 1;
            });

            $this->data['all_invoices'] = $all_invoices;

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;

            $vendors = array();
            $vendors['indeed'] = 'Indeed.com';
            $vendors['ziprecruiter'] = 'ZipRecruiter.com';
            $vendors['automotosocial'] = 'AutomotoSocial.com';
            $vendors['jobs2careers'] = 'Jobs2Careers.com';
            $vendors['juju'] = 'JuJu.com';
            $vendors['sparkhire'] = 'SparkHire.com';
            $vendors['accuratebackground'] = 'AccurateBackground.com';

            $this->data['vendors'] = $vendors;

            $this->data['vendor'] = $vendor;
            $this->data['month'] = $month;
            $this->data['year'] = $year;


            $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Product Statistics';
            $this->render('manage_admin/financial_reports/monthly_marketplace_product_statistics', 'admin_master');

        } else {

        }
    }

    public function print_monthly_marketplace_product_statistics($vendor = 'all', $year = null, $month = null){
       
        $year = ($year == null ? date('Y') : $year);
        $month = ($month == null ? date('m') : $month);

        $from_date_unix = mktime(0,0,0, $month, 1, $year);
        $to_date_unix = mktime(23, 59, 59, $month, date('t', $from_date_unix), $year);

        $from_date = date('Y-m-d H:i:s', $from_date_unix);
        $to_date = date('Y-m-d H:i:s', $to_date_unix);


        $indeed_products_sids = [1, 21];
        $ziprecruiter_products_sids = [2];
        $automotosocial_products_sids = [3, 4, 22];
        $jobs2careers_products_sids = [5];
        $juju_products_sids = [6];
        $accuratebackground_products_sids = [7, 8, 9, 10, 11, 16, 17, 18, 19];
        $sparkhire_products_sids = [12, 13, 14];

        $products_sids = array();
        switch($vendor){
            case 'indeed':
                $products_sids = $indeed_products_sids;
                break;
            case 'ziprecruiter':
                $products_sids = $ziprecruiter_products_sids;
                break;
            case 'automotosocial':
                $products_sids = $automotosocial_products_sids;
                break;
            case 'jobs2careers':
                $products_sids = $jobs2careers_products_sids;
                break;
            case 'juju':
                $products_sids = $juju_products_sids;
                break;
            case 'accuratebackground':
                $products_sids = $accuratebackground_products_sids;
                break;
            case 'sparkhire':
                $products_sids = $sparkhire_products_sids;
                break;
            default:
                $products_sids = array_merge($indeed_products_sids, $ziprecruiter_products_sids, $automotosocial_products_sids, $jobs2careers_products_sids, $juju_products_sids, $accuratebackground_products_sids, $sparkhire_products_sids);
                break;
        }



        $all_invoices = array();

        if(!empty($products_sids)){
            foreach($products_sids as $product_sid){
                $invoices = $this->financial_reports_model->get_invoices_by_products_sid($product_sid, $from_date, $to_date);

                $all_invoices = array_merge($all_invoices, $invoices);
            }
        }


        usort($all_invoices, function ($item1, $item2) {
            if ($item2['date'] == $item1['date']) return 0;
            return $item2['date'] < $item1['date'] ? -1 : 1;
        });

        $this->data['all_invoices'] = $all_invoices;

        $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->data['months'] = $months;

        $vendors = array();
        $vendors['indeed'] = 'Indeed.com';
        $vendors['ziprecruiter'] = 'ZipRecruiter.com';
        $vendors['automotosocial'] = 'AutomotoSocial.com';
        $vendors['jobs2careers'] = 'Jobs2Careers.com';
        $vendors['juju'] = 'JuJu.com';
        $vendors['sparkhire'] = 'SparkHire.com';
        $vendors['accuratebackground'] = 'AccurateBackground.com';

        $this->data['vendors'] = $vendors;

        $this->data['vendor'] = $vendor;
        $this->data['month'] = $month;
        $this->data['year'] = $year;


        $this->data['page_title'] = 'Financial Reports - Monthly Marketplace Product Statistics';
        $this->load->view('manage_admin/financial_reports/monthly_marketplace_product_statistics_print',$this->data);


        
    }


    public function monthly_profit_report($year = null, $month = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_profit_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if ($this->form_validation->run() == false) {

            $excluded_companies = get_company_sids_excluded_from_reporting();

            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $start_unix_date = mktime(0, 0, 0, $month, 1, $year);
            $end_unix_date = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);

            $month_first_date = date('y-m-d H:i:s', $start_unix_date);
            $month_last_date = date('y-m-d H:i:s', $end_unix_date);

            $months_sale_super_admin = array();
            $months_sale_employer_portal = array();

            $super_admin_invoices = $this->financial_reports_model->get_invoices('super_admin', $month_first_date, $month_last_date);
            $employer_portal_invoices = $this->financial_reports_model->get_invoices('employer_portal', $month_first_date, $month_last_date);


            $sa_grand_total_sale = 0;
            $sa_grand_total_cost = 0;
            $sa_grand_total_profit = 0;
            $sa_grand_total_discounted_profit = 0;
            $sa_grand_total_paypal_fee = 0;
            $sa_grand_total_profit_after_fee = 0;


            $ep_grand_total_sale = 0;
            $ep_grand_total_cost = 0;
            $ep_grand_total_profit = 0;
            $ep_grand_total_discounted_profit = 0;
            $ep_grand_total_paypal_fee = 0;
            $ep_grand_total_profit_after_fee = 0;





            foreach($super_admin_invoices as $key => $invoice){
                if(in_array($invoice['company_sid'], $excluded_companies) ){
                    unset($super_admin_invoices[$key]);
                } else {

                    /*
                    if($invoice['is_discounted'] == 1){
                        $amount = $invoice['total_after_discount'];
                    } else {
                        $amount = $invoice['value'];
                    }
                    */
                    $amount = $invoice['total_after_discount'];

                    $paypal_fee_one = $amount * ( 2.9 / 100 );
                    $paypal_fee_two = 0.30;

                    if($amount > 0 && $invoice['payment_method'] == 'credit-card'){
                        $paypal_fee = $paypal_fee_one + $paypal_fee_two;
                    } else {
                        $paypal_fee = 0;
                    }

                    $super_admin_invoices[$key]['paypal_fee'] = $paypal_fee;

                    $profit = $invoice['total_actual_profit'];


                    $invoice_cost = $invoice['total_cost'];

                    $profit_after_paypal_fee = $profit - $paypal_fee;
                    $super_admin_invoices[$key]['total_profit_after_paypal_fee'] = $profit_after_paypal_fee;

                    //Grand Totals
                    $sa_grand_total_sale = $sa_grand_total_sale + $amount;
                    $sa_grand_total_cost = $sa_grand_total_cost + $invoice_cost;
                    $sa_grand_total_profit = $sa_grand_total_profit + $profit;
                    $sa_grand_total_paypal_fee = $sa_grand_total_paypal_fee + $paypal_fee;

                    $sa_grand_total_profit_after_fee  = $sa_grand_total_profit_after_fee + $profit_after_paypal_fee;

                }
            }

            $this->data['sa_grand_total_sale'] = $sa_grand_total_sale;
            $this->data['sa_grand_total_cost'] = $sa_grand_total_cost;
            $this->data['sa_grand_total_profit'] = $sa_grand_total_profit;
            $this->data['sa_grand_total_paypal_fee'] = $sa_grand_total_paypal_fee;

            $this->data['sa_grand_total_profit_after_fee'] = $sa_grand_total_profit_after_fee;



            foreach($employer_portal_invoices as $key => $invoice){
                if(in_array($invoice['company_sid'], $excluded_companies) ){
                    unset($employer_portal_invoices[$key]);
                } else {

                    $amount = $invoice['total'];


                    $paypal_fee_one = $amount * ( 2.9 / 100 );
                    $paypal_fee_two = 0.30;

                    if($amount > 0 && $invoice['payment_method'] == 'Paypal') {
                        $paypal_fee = $paypal_fee_one + $paypal_fee_two;
                    } else {
                        $paypal_fee = 0;
                    }

                    $employer_portal_invoices[$key]['paypal_fee'] = $paypal_fee;




                    $invoice_cost = $invoice['total_cost'];
                    $invoice_discount = $invoice['total_discount_percentage'];

                    $profit = $invoice['total_actual_profit'];



                    $profit_after_paypal_fee = $profit - $paypal_fee;
                    $employer_portal_invoices[$key]['total_profit_after_paypal_fee'] = $profit_after_paypal_fee;

                    //Grand Totals
                    $ep_grand_total_sale = $ep_grand_total_sale + $amount;
                    $ep_grand_total_cost = $ep_grand_total_cost + $invoice_cost;
                    $ep_grand_total_profit = $ep_grand_total_profit + $profit;
                    $ep_grand_total_paypal_fee = $ep_grand_total_paypal_fee + $paypal_fee;

                    $ep_grand_total_profit_after_fee  = $ep_grand_total_profit_after_fee + $profit_after_paypal_fee;
                }
            }



            $this->data['ep_grand_total_sale'] = $ep_grand_total_sale;
            $this->data['ep_grand_total_cost'] = $ep_grand_total_cost;
            $this->data['ep_grand_total_profit'] = $ep_grand_total_profit;
            $this->data['ep_grand_total_paypal_fee'] = $ep_grand_total_paypal_fee;

            $this->data['ep_grand_total_profit_after_fee'] = $ep_grand_total_profit_after_fee;



            $this->data['super_admin_invoices'] = $super_admin_invoices;
            $this->data['employer_portal_invoices'] = $employer_portal_invoices;

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            $this->data['months_sale_super_admin'] = $months_sale_super_admin;
            $this->data['months_sale_employer_portal'] = $months_sale_employer_portal;

            $this->data['months'] = $months;
            $this->data['month'] = intval($month);
            $this->data['year'] = intval($year);


            $this->data['page_title'] = 'Financial Reports - Monthly Profit Report';
            $this->render('manage_admin/financial_reports/monthly_profit_report', 'admin_master');
        } else {

        }
    }

    public function monthly_unpaid_invoices($year = null, $month = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'monthly_profit_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        if ($this->form_validation->run() == false) {

            $excluded_companies = get_company_sids_excluded_from_reporting();

            $year = ($year == null ? date('Y') : $year);
            $month = ($month == null ? date('m') : $month);

            $start_unix_date = mktime(0, 0, 0, $month, 1, $year);
            $end_unix_date = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);

            $month_first_date = date('y-m-d H:i:s', $start_unix_date);
            $month_last_date = date('y-m-d H:i:s', $end_unix_date);


            $super_admin_invoices = $this->financial_reports_model->get_invoices('super_admin', $month_first_date, $month_last_date, 'unpaid');
            $employer_portal_invoices = $this->financial_reports_model->get_invoices('employer_portal', $month_first_date, $month_last_date, 'unpaid');


            $sa_grand_total_sale = 0;
            $sa_grand_total_cost = 0;
            $sa_grand_total_profit = 0;
            $sa_grand_total_discounted_profit = 0;
            $sa_grand_total_paypal_fee = 0;
            $sa_grand_total_profit_after_fee = 0;


            $ep_grand_total_sale = 0;
            $ep_grand_total_cost = 0;
            $ep_grand_total_profit = 0;
            $ep_grand_total_discounted_profit = 0;
            $ep_grand_total_paypal_fee = 0;
            $ep_grand_total_profit_after_fee = 0;



            foreach($super_admin_invoices as $key => $invoice){
                if(in_array($invoice['company_sid'], $excluded_companies) ){
                    unset($super_admin_invoices[$key]);
                } else {

                    /*
                    if($invoice['is_discounted'] == 1){
                        $amount = $invoice['total_after_discount'];
                    } else {
                        $amount = $invoice['value'];
                    }
                    */

                    $amount = $invoice['total_after_discount'];

                    $paypal_fee_one = $amount * ( 2.9 / 100 );
                    $paypal_fee_two = 0.30;

                    if($amount > 0 && $invoice['payment_method'] == 'credit-card'){
                        $paypal_fee = $paypal_fee_one + $paypal_fee_two;
                    } else {
                        $paypal_fee = 0;
                    }

                    $super_admin_invoices[$key]['paypal_fee'] = $paypal_fee;

                    $profit = $invoice['total_actual_profit'];


                    $invoice_cost = $invoice['total_cost'];

                    $profit_after_paypal_fee = $profit - $paypal_fee;
                    $super_admin_invoices[$key]['total_profit_after_paypal_fee'] = $profit_after_paypal_fee;

                    //Grand Totals
                    $sa_grand_total_sale = $sa_grand_total_sale + $amount;
                    $sa_grand_total_cost = $sa_grand_total_cost + $invoice_cost;
                    $sa_grand_total_profit = $sa_grand_total_profit + $profit;
                    $sa_grand_total_paypal_fee = $sa_grand_total_paypal_fee + $paypal_fee;

                    $sa_grand_total_profit_after_fee  = $sa_grand_total_profit_after_fee + $profit_after_paypal_fee;

                }
            }




            foreach($employer_portal_invoices as $key => $invoice){
                if(in_array($invoice['company_sid'], $excluded_companies) ){
                    unset($employer_portal_invoices[$key]);
                } else {

                    $amount = $invoice['total'];


                    $paypal_fee_one = $amount * ( 2.9 / 100 );
                    $paypal_fee_two = 0.30;

                    if($amount > 0 && $invoice['payment_method'] == 'Paypal') {
                        $paypal_fee = $paypal_fee_one + $paypal_fee_two;
                    } else {
                        $paypal_fee = 0;
                    }

                    $employer_portal_invoices[$key]['paypal_fee'] = $paypal_fee;




                    $invoice_cost = $invoice['total_cost'];
                    $invoice_discount = $invoice['total_discount_percentage'];

                    $profit = $invoice['total_actual_profit'];



                    $profit_after_paypal_fee = $profit - $paypal_fee;
                    $employer_portal_invoices[$key]['total_profit_after_paypal_fee'] = $profit_after_paypal_fee;

                    //Grand Totals
                    $ep_grand_total_sale = $ep_grand_total_sale + $amount;
                    $ep_grand_total_cost = $ep_grand_total_cost + $invoice_cost;
                    $ep_grand_total_profit = $ep_grand_total_profit + $profit;
                    $ep_grand_total_paypal_fee = $ep_grand_total_paypal_fee + $paypal_fee;

                    $ep_grand_total_profit_after_fee  = $ep_grand_total_profit_after_fee + $profit_after_paypal_fee;
                }
            }









            $this->data['invoices_super_admin'] = $super_admin_invoices;
            $this->data['invoices_employer_portal'] = $employer_portal_invoices;

            $months = [0, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $this->data['months'] = $months;


            $this->data['month'] = intval($month);
            $this->data['year'] = intval($year);

            $this->data['page_title'] = 'Financial Reports - Monthly Unpaid Invoices';
            $this->render('manage_admin/financial_reports/monthly_unpaid_invoices', 'admin_master');
        } else {

        }
    }

    public function sms_service_report($company = 'all', $start_date = null, $end_date = null){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/financial_reports';
        $function_name = 'sms_service_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if($this->form_validation->run() == false){
            $from_date = '';
            $to_date = '';
            $grand_total = '';
            //
            $current_date = date('Y-m-d H:i:s');
            //
            if ((empty($start_date) && empty($end_date)) || ($start_date == 'all' && $end_date == 'all')) {
                // First day of the current month.
                $from_date = date('Y-m-01', strtotime($current_date));
                // Last day of the current month.
                $to_date = date('Y-m-t', strtotime($current_date));
            } else if ($start_date == 'all' && ($end_date != 'all' || !empty($end_date))) {
                $from_date = date('Y-m-01', strtotime($end_date));
                //
                $to_date = date('Y-m-d', strtotime($end_date));

            } else if (($start_date != 'all' || !empty($start_date)) && $end_date == 'all') {
                // 
                $from_date = date('Y-m-d', strtotime($start_date));
                //
                $to_date = date('Y-m-t', strtotime($current_date));
            } else {
                // 
                $from_date = date('Y-m-d', strtotime($start_date));
                //
                $to_date = date('Y-m-d', strtotime($end_date));
            }
            
            $sms_data = $this->financial_reports_model->get_sms_data($company, $from_date, $to_date);

            $data_to_show = array();

            if(!empty($sms_data)) {
                $total_amount = 0;
                foreach ($sms_data as $key => $sms) {
                    $company_name = getCompanyNameBySid($sms['company_id']);
                    //
                    $receiver_name = '';
                    $user_type = '';
                    //
                    if ($sms['module_slug'] == 'ats') {
                        $receiver_name = get_applicant_name($sms['receiver_user_id']);
                        $user_type = 'Applicant';
                    } else {
                        $receiver_name = getUserNameBySID($sms['receiver_user_id']);
                        $user_type = 'Employee';
                    }
                    //
                    $sender_name = getUserNameBySID($sms['sender_user_id']);
                    //
                    $data_to_show[$key]['company_name'] = $company_name;
                    $data_to_show[$key]['receiver_name'] = $receiver_name;
                    $data_to_show[$key]['sender_name'] = $sender_name;
                    $data_to_show[$key]['message'] = $sms['message_body'];
                    $data_to_show[$key]['sender_phone_number'] = $sms['sender_phone_number'];
                    $data_to_show[$key]['receiver_phone_number'] = $sms['receiver_phone_number'];
                    $data_to_show[$key]['user_type'] = $sms['receiver_phone_number'];

                    $data_to_show[$key]['sent'] = $sms['is_sent'] == 1 ? 'Sent' : 'Not Sent';
                    $data_to_show[$key]['read'] = $sms['is_read'] == 1 ? 'Delivered' : 'Sent';
                    $data_to_show[$key]['date'] = date_format(new DateTime($sms['created_at']), 'M d Y h:m a');
                    $data_to_show[$key]['amount'] = $sms['charged_amount'];

                    $total_amount = $total_amount + $sms['charged_amount'];
                }

                $grand_total =$total_amount;
            }
            //
            $companies = $this->financial_reports_model->get_all_companies();

            $this->data['sms_data'] = $data_to_show;
            $this->data['grand_total'] = $grand_total;
            $this->data['companies'] = $companies;


            $this->data['page_title'] = 'SMS Service Reports';
            $this->render('manage_admin/financial_reports/sms_service_report', 'admin_master');

        } else {

        }
    }
}