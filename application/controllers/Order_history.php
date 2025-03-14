
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_history extends Public_Controller {
    public function __construct() {
            parent::__construct();
            $this->load->model('order_history_model');
            $this->load->library("pagination");
            $this->load->library('pdfgenerator');
    }

    public function index() {
        $this->index_new(); return;
        if ($this->session->has_userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $data['title']                                                      = 'Orders History';
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'order_history'); // Param2: Redirect URL, Param3: Function Name

            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $orders_total                                                       = $this->order_history_model->get_orders_total($company_sid);

            /** pagination * */
            $records_per_page                                                   = 10;
            $page                                                               = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset                                                          = 0;

            if ($page > 1) {
                $my_offset                                                      = ($page - 1) * $records_per_page;
            }

            $baseUrl                                                            = base_url('order_history');
            $uri_segment                                                        = 2;
            $config                                                             = array();
            $config['base_url']                                                 = $baseUrl;
            $config['total_rows']                                               = $orders_total;
            $config['per_page']                                                 = $records_per_page;
            $config['uri_segment']                                              = $uri_segment;
            $choice                                                             = $config['total_rows'] / $config['per_page'];
            $config['num_links']                                                = ceil($choice);
            $config['use_page_numbers']                                         = true;
            $config['full_tag_open']                                            = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close']                                           = '</ul></nav><!--pagination-->';
            $config['first_link']                                               = '&laquo; First';
            $config['first_tag_open']                                           = '<li class="prev page">';
            $config['first_tag_close']                                          = '</li>';
            $config['last_link']                                                = 'Last &raquo;';
            $config['last_tag_open']                                            = '<li class="next page">';
            $config['last_tag_close']                                           = '</li>';
            $config['next_link']                                                = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open']                                            = '<li class="next page">';
            $config['next_tag_close']                                           = '</li>';
            $config['prev_link']                                                = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open']                                            = '<li class="prev page">';
            $config['prev_tag_close']                                           = '</li>';
            $config['cur_tag_open']                                             = '<li class="active"><a href="">';
            $config['cur_tag_close']                                            = '</a></li>';
            $config['num_tag_open']                                             = '<li class="page">';
            $config['num_tag_close']                                            = '</li>';

            $this->pagination->initialize($config);
            $data['links']                                                      = $this->pagination->create_links();
            /** pagination end * */            
            $orders                                                             = $this->order_history_model->get_orders($company_sid, $records_per_page, $my_offset);

            foreach ($orders as $order_key => $order) {
                    $product_ids                                                = explode(',', $order['product_sid']);

                    foreach ($product_ids as $prodcut_id_key => $product_id) {
                        $product_detail                                         = $this->order_history_model->get_product_detail($product_id);
                        $product_ids[$prodcut_id_key]                           = array($product_id, $product_detail['name']);
                        $orders[$order_key]['product_details']                  = $product_ids;
                    }
            }

            //$data['invoices']                                                   = $this->order_history_model->get_orders($company_sid);
            $data['invoices']                                                   = $orders;
            $data['invoiceCount']                                               = count($data['invoices']);
            $this->load->model('dashboard_model');
            $data['products']                                                   = $this->dashboard_model->getPurchasedProducts($company_sid);
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/order_history');
            $this->load->view('main/footer');
        }//if end for session check success
        else {
            redirect(base_url('login'), "refresh");
        }//else end for session check fail
    }

    public function order_detail($order_id = NULL) {
        if ($order_id == NULL) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        }

        $data['title'] = 'Order Details';
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $employer_sid = $data["session"]["employer_detail"]["sid"];

        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'order_history'); // Param2: Redirect URL, Param3: Function Name

        $this->load->model('manage_admin/invoice_model');
        $invoiceData = $this->invoice_model->get_invoice_detail($order_id, $company_sid);

        $invoice_credit_notes = $this->order_history_model->get_invoice_credit_notes($order_id);
        $data['credit_notes'] = $invoice_credit_notes;

        $data['order_id'] = $order_id;
        $data['company_details'] = $data["session"]["company_detail"];

        if (empty($invoiceData)) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        } else {
            $this->load->model('manage_admin/products_model');
            $data["products"] = $this->products_model->get_products();
            $this->load->model('manage_admin/company_model');
            $data["employers"] = $this->company_model->get_employers();
            $data["invoiceData"] = $invoiceData;

            if ($data["invoiceData"]["date"] != NULL) {
                $date = $data["invoiceData"]["date"];
                $dateArray = explode("-", $date);
                $mydate = explode(" ", $dateArray[2]);
                $data["invoiceData"]["date"] = $dateArray[1] . "-" . $mydate[0] . "-" . $dateArray[0];
            }

            $data["invoiceData"]["product_sid"] = explode(',', $data["invoiceData"]["product_sid"]);
            $data["invoiceData"]["serialized_products"] = unserialize($data["invoiceData"]["serialized_items_info"]);
            $data["invoiceData"]["serialized_items_info"] = json_encode(unserialize($data["invoiceData"]["serialized_items_info"]));

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/order_detail_new');
            $this->load->view('main/footer');
        }
    }

    public function job_products_report() {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $data['title'] = "Job Products Report";
            
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'job_products_report');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $data['products_count'] = $this->order_history_model->get_job_products_count($company_sid);

            /** pagination * */
            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('job_products_report');
            $uri_segment = 2;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $data['products_count'];
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = ceil($choice);
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $data["links"] = $this->pagination->create_links();
            /** pagination end * */
            
            /** search code * */
            if (isset($_GET['submit'])) { 
                $search = array();
                $search_data = $this->input->get(NULL, true);
                $data["search"] = $search_data;
                $data["flag"] = false;
                foreach ($search_data as $key => $value) {
                    if ($key != 'start' && $key != 'end' && $key != 'sid' && $key != 'submit') {
                        if ($value != '') { // exclude these values from array
                            $search[$key] = $value;
                            $data["flag"] = true;
                        }
                    }
                }

                if (isset($search_data['start']) || isset($search_data['end'])) {
                    if (isset($search_data['start']) && $search_data['start'] != "") {
                        $start_date = explode('-', $search_data['start']);
                        $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                    } else {
                        $start_date = '01-01-1970 00:00:00';
                    }

                    if (isset($search_data['end']) && $search_data['end'] != "") {
                        $end_date = explode('-', $search_data['end']);
                        $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                    } else {
                        $end_date = date('Y-m-d H:i:s');
                    }

                    $between = "purchased_date between '" . $start_date . "' and '" . $end_date . "'";
                }
                $data['search'] = $search_data; 
                $data["flag"] = true;
            } else { 
                $search = '';
                $between = '';
                $data["flag"] = false;
            }
            
            /** search code end * */
            $data['active_products'] = $this->order_history_model->get_active_products();
            $data['active_jobs'] = $this->order_history_model->get_active_jobs($company_sid);
            $data['products'] = $this->order_history_model->get_job_products($company_sid, $records_per_page, $my_offset, $search, $between);
            $products = $this->order_history_model->get_job_products($company_sid, null, null, $search, $between);
            
            //** excel sheet file **//
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($products) && sizeof($products) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                   
                    fputcsv($output, array('Job ID', 'Job Title', 'Product Name', 'Advertised Date'));

                    foreach ($products as $product) {
                        $input = array();
                        $input['job_sid'] = $product['job_sid'];
                        $input['job_title'] = $product['job_title'];
                        $input['product_name'] = $product['product_name'];
                        $input['purchased_date'] = reset_datetime(array('datetime' => $product['purchased_date'], '_this' => $this));
                        fputcsv($output, $input);
                    }

                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            //** excel sheet file **//
            
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/job_products_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

//    public function insert_company_ids(){
//        $this->db->select('sid, employer_sid');
//        $records = $this->db->get('jobs_to_feed')->result_array();
//        
//        foreach($records as $record){
//            $sid = $record['sid'];
//            $employer_sid = $record['employer_sid'];
//            
//            $this->db->select('parent_sid');
//            $this->db->where('sid', $employer_sid);
//            $company = $this->db->get('users')->result_array();
//            $company_sid = $company[0]['parent_sid'];
//            
//            $this->db->where('sid', $sid);
//            $this->db->update('jobs_to_feed', array('company_sid'=> $company_sid));
//      
//        }
//    }


    public function get_pdf(){
        $html = $this->input->raw_input_stream;
        $this->pdfgenerator->generate($html,'pdf');
    }

    /**
     *
     *
     *
     *
     *
     */
    function index_new(
        $order_by = 'all', 
        $invoice_sid = 'all', 
        $status = 'all', 
        $payment_method = 'any', 
        $from_date = false, 
        $to_date = false
    ) {
        if (!$this->session->has_userdata('logged_in')) redirect(base_url('login'), "refresh");
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = $security_details= db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'my_settings', 'order_history'); // Param2: Redirect URL, Param3: Function Name
        $company_sid  = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        //
        // $orders_total = $this->order_history_model->get_orders_total($company_sid);



        // _e($orders_total, true, true);

        // $orders = $this->order_history_model->get_orders($company_sid, $records_per_page, $my_offset);

        // foreach ($orders as $order_key => $order) {
        //     $product_ids = explode(',', $order['product_sid']);

        //     foreach ($product_ids as $prodcut_id_key => $product_id) {
        //         $product_detail  = $this->order_history_model->get_product_detail($product_id);
        //         $product_ids[$prodcut_id_key] = array($product_id, $product_detail['name']);
        //         $orders[$order_key]['product_details'] = $product_ids;
        //     }
        // }


        // For AJAX
        if($this->input->post('action')){
            $order_by = urldecode($order_by);
            $invoice_sid = urldecode($invoice_sid);
            $status      = urldecode($status);
            $payment_method = urldecode($payment_method);
            $from_date      = urldecode($from_date);
            $to_date        = urldecode($to_date);

            // Set Search

            // Set start date
            if ($from_date != 'all' && $from_date !== FALSE)
                $from_date = empty($from_date) ? null : DateTime::createFromFormat('m-d-Y', $from_date)->format('Y-m-d');
            else $from_date = date('Y-m-d');

            // Set end date
            if ($to_date != 'all' && $to_date !== FALSE)
                $to_date = empty($to_date) ? null : DateTime::createFromFormat('m-d-Y', $to_date)->format('Y-m-d');
            else $to_date = date('Y-m-d');
            // Set defaults
            $resp = array('Status' => FALSE, 'Response' => 'Invalid request.');
            $limit = 100;
            $offset = $limit; 
            $inset = 0;
            $total_pages = 1;
            // Set page and total records
            $page = $this->input->post('page', true);
            $total_records = $this->input->post('total_records', true);

            // Export
            if($this->input->post('pages', true)){

                $records = $this->order_history_model->
                get_invoices_ajax(
                    $company_sid,
                    $from_date,
                    $to_date,
                    $order_by,
                    $invoice_sid,
                    $payment_method,
                    $status,
                    $inset,
                    $offset,
                    false,
                    true
                );
                $file_name = '';
                $file_name .= str_replace( '-', '_',  $from_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $to_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $payment_method );
                $file_name .= '_';
                $file_name .= $order_by;
                $file_name .= '_';
                $file_name .=  $status;
                
                $file_name = generate_csv(
                    $records, 
                    $file_name, 
                    array(
                        'Invoice #', 'Customer Name', 'Description', 'Date', 'Payment Method', 'Total', 'Status'
                    ), 
                    'invoice_orders'
                );

                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Data'] = $file_name;

                header('Content-Type: application/json');
                echo json_encode($resp);
                exit(0);
            }

            //
            //
            if($total_records == 0){
                // Fetch records count 
                $total_records = $resp['TotalRecords'] = $this->order_history_model->
                get_invoices_ajax(
                    $company_sid,
                    $from_date,
                    $to_date,
                    $order_by,
                    $invoice_sid,
                    $payment_method,
                    $status,
                    $inset,
                    $offset,
                    true
                );

                $resp['Limit'] = $limit;
            }else{
                $inset = $page == 1 ? 0 : ( ( $page - 1 ) * $limit);
                $offset = $inset * $page;
            }


            $resp['TotalPages'] = $total_pages = ceil($total_records / $limit);

            $resp['from_records'] = $inset == 0 ? 1 : $inset;
            $resp['to_records']   = $total_records < $limit ? 
            $total_records : ( $page == $total_pages ? $total_records : $inset + $limit);

            //
            $resp['Data'] = $this->order_history_model->
            get_invoices_ajax(
                $company_sid,
                $from_date,
                $to_date,
                $order_by,
                $invoice_sid,
                $payment_method,
                $status,
                $inset,
                $offset,
                false
            );

            //
            $resp['Status'] = TRUE;
            $resp['Response'] = 'Proceed.';

            header('Content-Type: application/json');
            echo json_encode($resp);
            exit(0);
        }

        $data['title'] = 'Orders History';
        // $data['invoices'] = $orders;
        // $data['invoiceCount'] = count($data['invoices']);
        $this->load->model('dashboard_model');
        $data['products'] = $this->dashboard_model->getPurchasedProducts($company_sid);
        $data['employees'] = $this->order_history_model->getCompanyAccounts($company_sid);
        // _e($data['products'], true, true);
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/order_history_ajax');
        $this->load->view('main/footer');
    }



    /**
     * Download file
     * Created on: 27-05-2019
     *
     * @param $type String (csv)
     * @param $file_name String
     *
     * @return VOID
     */
    function download($type, $file_name){
        $download_file = APPPATH . '../assets/'.$type.'/'.$file_name.'.'.$type;
        download_file($download_file);
        exit(0);
    }
}
