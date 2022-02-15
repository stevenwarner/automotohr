<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Accurate_background extends Admin_Controller {

    private $api_mode;

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/accurate_background_model');
        $this->load->helper('accurate_background_helper');
        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($username = 'all', $search_sid = 0, $order_by = 'all', $statuses = 'all', $product_type = 'all', $start_time = '', $end_time = '', $company_id = 'all', $page = 0) {
        $this->index_new($username, $search_sid, $order_by, $statuses, $product_type, $start_time, $end_time, $company_id, $page);
       //  // ** Check Security Permissions Checks - Start ** //
       //  $redirect_url = 'manage_admin';
       //  $function_name = 'accurate_background';

       //  $admin_id = $this->ion_auth->user()->row()->id;
       //  $security_details = db_get_admin_access_level_details($admin_id);
       //  $this->data['security_details'] = $security_details;
       //  check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
       //  // ** Check Security Permissions Checks - End ** //
       //  $baseUrl = base_url('manage/accurate_background/'.$username.'/'.$search_sid.'/'.$order_by.'/'.$statuses.'/'.$product_type.'/'.$start_time.'/'.$end_time.'/'.$company_id);
       //  $product_type = urldecode($product_type);

       //  if(!empty($statuses) && $statuses != 'all'){
       //      $statuses = explode(',',urldecode($statuses));
       //  } else {
       //      $statuses = array('all');
       //  }
       //  if(!empty($username) && $username != 'all'){
       //      $usertype = explode(' - ',urldecode($username));

       //      if(sizeof($usertype) > 0 && isset($usertype[1])){
       //          $usertype = $usertype[1];
       //      } else{
       //          $usertype = 'all';
       //      }
       //  } else{
       //      $usertype = 'all';
       //  }

       //  if (!empty($start_time) && $start_time != 'all') {
       //      $start_time = empty($start_time) ? null : DateTime::createFromFormat('m-d-Y', $start_time)->format('Y-m-d 00:00:00');
       //  } else {
       //      $start_time = date('Y-m-01 00:00:00');
       //  }

       //  if (!empty($end_time) && $end_time != 'all') {
       //      $end_time = empty($end_time) ? null : DateTime::createFromFormat('m-d-Y', $end_time)->format('Y-m-d 23:59:59');
       //  } else {
       //      $end_time = date('Y-m-t 23:59:59');
       //  }

       //  $this->data['page_title'] = 'Accurate Background';
       //  $this->data['checks_count'] = $this->accurate_background_model->get_all_accurate_background_checks_by_count($company_id, $usertype, $search_sid, $order_by, $product_type, $start_time, $end_time);

       //  /** pagination * */
       //  $records_per_page = 100;
       //  $my_offset = 0;

       //  if ($page > 1) {
       //      $my_offset = ($page - 1) * $records_per_page;
       //  }
       //  $uri_segment = 11;
       //  $config = array();
       //  $config['base_url'] = $baseUrl;
       //  $config['total_rows'] = $this->data['checks_count'];
       //  $config['per_page'] = $records_per_page;
       //  $config['uri_segment'] = $uri_segment;
       //  $choice = $config['total_rows'] / $config['per_page'];
       //  $config['num_links'] = ceil($choice);
       //  $config["use_page_numbers"] = true;
       //  $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
       //  $config['full_tag_close'] = '</ul></nav><!--pagination-->';
       //  $config['first_link'] = '&laquo; First';
       //  $config['first_tag_open'] = '<li class="prev page">';
       //  $config['first_tag_close'] = '</li>';
       //  $config['last_link'] = 'Last &raquo;';
       //  $config['last_tag_open'] = '<li class="next page">';
       //  $config['last_tag_close'] = '</li>';
       //  $config['next_link'] = '<i class="fa fa-angle-right"></i>';
       //  $config['next_tag_open'] = '<li class="next page">';
       //  $config['next_tag_close'] = '</li>';
       //  $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
       //  $config['prev_tag_open'] = '<li class="prev page">';
       //  $config['prev_tag_close'] = '</li>';
       //  $config['cur_tag_open'] = '<li class="active"><a href="">';
       //  $config['cur_tag_close'] = '</a></li>';
       //  $config['num_tag_open'] = '<li class="page">';
       //  $config['num_tag_close'] = '</li>';
       //  $this->pagination->initialize($config);
       //  $this->data['links'] = $this->pagination->create_links();
       //  $this->data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
       //  /** pagination end * */
       //  $this->data['checks'] = $this->accurate_background_model->get_all_accurate_background_checks($company_id, $usertype, $search_sid, $order_by, $product_type, $start_time, $end_time, $records_per_page, $my_offset);
       //  foreach ($this->data['checks'] as $key => $check) {
       //      if ($check['users_type'] == 'applicant') {
       //          $this->db->select('first_name,last_name');
       //          $this->db->where('sid', $check['users_sid']);
       //          $result = $this->db->get('portal_job_applications')->result_array();
       //      } elseif ($check['users_type'] == 'employee') {
       //          $this->db->select('first_name,last_name');
       //          $this->db->where('sid', $check['users_sid']);
       //          $result = $this->db->get('users')->result_array();
       //      }

       //      if(isset($check['order_response'])){
       //          $response = @unserialize($check['order_response']);
       //      }

       //      if (isset($response['orderStatus']['status'])) { //echo '<hr>i am IF';
       //          $status = $response['orderStatus']['status'];
       //          $this->data['checks'][$key]['status'] = $status;
       //      } else { //echo '<hr>i am ELSE';
       //          $this->data['checks'][$key]['status'] = 'Pending';
       //      }

       //      if (!empty($result)) {
       //          $this->data['checks'][$key]['user_first_name'] = $result[0]['first_name'] . ' ' . $result[0]['last_name'];
       //      } else {
       //          $this->data['checks'][$key]['user_first_name'] = 'Candidate Not Found';
       //      }
       //      if(!in_array('all',$statuses) && !in_array(strtolower($this->data['checks'][$key]['status']), $statuses)){
       //          unset($this->data['checks'][$key]);
       //          $this->data['checks_count']--;
       //      }
       //  }
       //  $this->data['to_records'] = $this->data['checks_count'] < $records_per_page ? $this->data['checks_count'] : $my_offset + $records_per_page;
       //  $this->data['companies'] = $this->accurate_background_model->get_all_companies();
       // // echo '<pre>';
       // // print_r($this->data['checks']);
       // // die();
       //  $this->render('manage_admin/accurate_background/listings_view', 'admin_master');
    }

    function get_order_status($package_id, $ab_order_sid) {
        $this->load->model('background_check_model');
        $order_details = $this->background_check_model->get_order_details($ab_order_sid);

        if (!empty($order_details)) {
            $package_id = $order_details['package_id'];
            $stored_order_response = $order_details['order_response'];

            if ($stored_order_response != '' && $stored_order_response != null) {
                $stored_order_response = unserialize($stored_order_response);
            } else {
                $stored_order_response = array();
            }

            mail('mubashir.saleemi123@gmail.com', 'AB Debug - Order Response not Found', print_r($stored_order_response, true));

            if (isset($stored_order_response['orderStatus'])) {
                $stored_status = strtolower($stored_order_response['orderStatus']['status']);
                $percentage_complete = intval($stored_order_response['orderStatus']['percentageComplete']);

                if ($percentage_complete < 100) {
                    $order_status = ab_get_order_status($package_id, AB_API_MODE);

                    if (!empty($order_status) || $order_status != '') {
                        $order_status = json_decode($order_status, true);

                        if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                            $status = strtolower($order_status['orderStatus']['status']);

                            if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                                $product_sid = $order_details['product_sid'];
                                $company_sid = $order_details['company_sid'];
                                $employer_sid = $order_details['employer_sid'];
                                $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                                $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                            }
                        }
                    } else {
                        $order_status = array();
                    }
                    $order_status = serialize($order_status);
                    $this->background_check_model->update_order_status($ab_order_sid, $order_status);
                }
            } else {
                $order_status = ab_get_order_status($package_id, AB_API_MODE);

                if (!empty($order_status) || $order_status != '') {
                    $order_status = json_decode($order_status, true);

                    if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                        $status = strtolower($order_status['orderStatus']['status']);

                        if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                            $product_sid = $order_details['product_sid'];
                            $company_sid = $order_details['company_sid'];
                            $employer_sid = $order_details['employer_sid'];
                            $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                            $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                        }
                    }
                } else {
                    $order_status = array();
                }

                $order_status = serialize($order_status);
                $this->background_check_model->update_order_status($ab_order_sid, $order_status);
            }
        } else {
            //mail('j.taylor.title@gmail.com', 'AB Debug - Order Response not Found', 'Debug');

            $order_status = ab_get_order_status($package_id, AB_API_MODE);

            if (!empty($order_status) || $order_status != '') {
                $order_status = json_decode($order_status, true);

                if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                    $status = strtolower($order_status['orderStatus']['status']);

                    if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                        $product_sid = $order_details['product_sid'];
                        $company_sid = $order_details['company_sid'];
                        $employer_sid = $order_details['employer_sid'];
                        $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                        $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                    }
                }
            } else {
                $order_status = array();
            }

            $order_status = serialize($order_status);
            $this->background_check_model->update_order_status($ab_order_sid, $order_status);
        }
    }

    public function order_status($order_sid = null) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'accurate_background';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Accurate Background - Order Details';

        $background_order = $this->accurate_background_model->get_accurate_background_order_details($order_sid);
        $this->data['background_order'] = $background_order;

        if (!empty($background_order)) {
            $company_sid = $background_order['company_sid'];

            $testing_companies = array(3, 31, 57);

            if (in_array($company_sid, $testing_companies)) {
                $this->api_mode = 'dev';
            } else {
                $this->api_mode = AB_API_MODE;
            }
        }

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            //do nothing
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'get_report':

                    $search_id = $this->input->post('search_id');

                    $report = ab_get_report($search_id, 'pdf', $this->api_mode); 

                    $report_data = json_decode($report, true);

                    if (!empty($report_data)) {
                        $pdf_report = base64_decode($report_data['resultReport']);
                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment; filename="accurate_background_report.pdf"');
                        echo $pdf_report;
                    }
                    break;
                case 'get_order_status':
                    $package_id = $this->input->post('package_id');
                    $ab_order_sid = $this->input->post('ab_order_sid');
                    $this->get_order_status($package_id, $ab_order_sid);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Order Status successfully fetched.');
                    break;
            }
        }


        $this->render('manage_admin/accurate_background/background_report_view', 'admin_master');
    }

    public function activation_orders() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'activation_orders';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Accurate Background Activation Requests';
        $this->data['activation_orders'] = $this->accurate_background_model->get_all_accurate_background_activation_orders();

        foreach ($this->data['activation_orders'] as $key => $check) {
            $this->db->select('CompanyName,background_check');
            $this->db->where('sid', $check['company_sid']);
            $result = $this->db->get('users')->result_array();
            $this->data['activation_orders'][$key]['CompanyName'] = $result[0]['CompanyName'];
            $this->data['activation_orders'][$key]['background_check'] = $result[0]['background_check'];
        }

        //echo '<pre>'; print_r($this->data['activation_orders']); exit;

        $this->render('manage_admin/accurate_background/activation_listings_view', 'admin_master');
    }

     public function manage_document($document_sid = null) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'manage_document';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); 
        // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $document_detail = $this->accurate_background_model->get_accurate_background_document_detail($document_sid);
        $company_name    = $this->accurate_background_model->get_company_name($document_detail['company_sid']);
        $this->data['page_title']       = 'Manage Document';
        $this->data['document_sid']     = $document_sid;
        $this->data['document_detail']  = $document_detail;
        $this->data['company_name']     = $company_name;
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->render('manage_admin/accurate_background/manage_document');

        } else {
            if (isset($_FILES['document']['name']) && !empty($_FILES['document']['name'])) {
              
                $company_sid = $document_detail['company_sid'];
                $employer_sid = $document_detail['employer_sid'];

                $history_data = array();
                $history_data = $document_detail;
                $history_data['accurate_document_sid'] = $document_sid;
                unset($history_data['sid']);
                $this->accurate_background_model->insert_accurate_background_document_history($history_data);
                
                $original_name = $_FILES['document']['name'];
                $upload_file_name = upload_file_to_aws('document', $company_sid, 'Accurate_background_agreement_for_'.$company_sid, 'by_super_admin_' . date('Ymd'));
                $data_to_update = array();
                $data_to_update['uploaded_document'] = $upload_file_name;
                $this->accurate_background_model->update_accurate_background_document($document_sid, $data_to_update);
                $this->session->set_flashdata('message', 'Accurate Background Document Upload Successfully!');
                redirect(base_url('manage_admin/accurate_background/activation_orders'));
            } 
        }
    }

    public function accurate_background_tasks() {
        if ($this->input->post() != null) {
            $action = $this->input->post('action');
            $company_sid = $sid = $this->input->post('sid');

            if ($action == 'activate') {
                $this->accurate_background_model->activate_accurate_background($company_sid);
                $this->session->set_flashdata('message', 'Accurate Background Check Activated Successfully!');
            } else if ($action == 'deactivate') {
                $this->accurate_background_model->deactivate_accurate_background($company_sid);
                $this->session->set_flashdata('message', 'Accurate Background Check Deactivated Successfully!');
            } else if ($action == 'request_document') {
                $employerDetail = $this->accurate_background_model->request_document($sid);
                $this->session->set_flashdata('message', 'Accurate Background Check Document Requested Successfully!');
                //sending mail to the company.
                //sending email to user
                
                $emailTemplateData = get_email_template(ACCURATE_BACKGROUND_DOCUMENT_REQUEST);
                $userData = $this->accurate_background_model->get_user_data($employerDetail['sid']);
                $emailTemplateBody = convert_email_template($emailTemplateData['text'], $userData);

                $from = $emailTemplateData['from_email'];
                $to = $employerDetail['email'];
                $subject = $emailTemplateData['subject'];
                $from_name = $emailTemplateData['from_name'];
                $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);


                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => $user_data['sid']);
                save_email_log_common($emailData);
            } else if ($action == 'cancel_request_document') {
                $this->accurate_background_model->cancel_request_document($sid);
                $this->session->set_flashdata('message', 'Accurate Background Check Document Requested Cancelled Successfully!');
            } else if ($action == 'send_to_AB') {
                $employerDetail = $this->accurate_background_model->request_document($sid);
                $this->session->set_flashdata('message', 'Accurate Background Activation Document Sent Successfully!');
                //sending mail to the company.
                //sending email to user
                $emailTemplateData = get_email_template(ACCURATE_BACKGROUND_ACTIVATION_DOCUMENT_SEND);
                $userData = $this->accurate_background_model->get_user_data($employerDetail['sid']);
                $emailTemplateBody = convert_email_template($emailTemplateData['text'], $userData);

                $from = $emailTemplateData['from_email'];
                $to = $employerDetail['email'];
                $subject = $emailTemplateData['subject'];
                $from_name = $emailTemplateData['from_name'];
                $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;

                $documentNameArray = explode(".", $employerDetail['uploaded_document']);
                $files[0]['document_name'] = $employerDetail['uploaded_document'];
                $files[0]['document_original_name'] = $documentNameArray[0];
                $files[0]['document_type'] = end($documentNameArray);

                sendMailWithStringAttachment($from, $to, $subject, $body, $from_name, $files);
                //sendMail($from, $to, $subject, $body, $from_name);
                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => $user_data['sid']
                );
                save_email_log_common($emailData);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b>  Please select a valid order.');
            redirect(base_url('manage_admin/accurate_background/activation_orders'));
        }
    }

    function get_applicants($query){
        // check if ajax request is not set
        if(!$this->input->is_ajax_request()) redirect('manage_admin/accurate_background', 'refresh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET') $this->response($return_array);
        $return_array['Redirect'] = FALSE;
        $query = urldecode($query);
        $this->response( $this->accurate_background_model->get_applicants_by_query($query) );
    }

    private function response($array){
        header('Content-Type: application/json');
        echo json_encode($array); exit(0);
    }

    function ajax_responder(){
        if(!$this->input->is_ajax_request()) redirect('manage_admin/accurate_background/', 'refresh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not POST
        if ($this->input->server('REQUEST_METHOD') != 'POST') $this->response($return_array);
        $return_array['Redirect'] = FALSE;
        $company_sid = $this->input->post('company_sid');
        $this->response( $this->accurate_background_model->get_employee_by_query($company_sid) );

    }

    /**
     * Accurate background check
     * Created on: 29-05-2019
     *
     * TODO
     * Pagination count is wrong due to 
     * status is set in a serialize array
     * which causing wrong count value
     *
     * @param $username String Optional
     * @param $search_sid Bool Optional
     * @param $order_by String Optional
     * @param $statuses String Optional
     * @param $product_type String Optional
     * @param $start_time String Optional
     * @param $end_time String Optional
     * @param $company_id String|Integer Optional
     * @param $page Integer Optional
     *
     * @return VOID
     * 
     */
    function index_new(
        $username = 'all',
        $search_sid = 0, 
        $order_by = 'all', 
        $statuses = 'all', 
        $product_type = 'all', 
        $start_time = '', 
        $end_time = '', 
        $company_id = 'all', 
        $page = 0) {

        $redirect_url = 'manage_admin';
        $function_name = 'accurate_background';
        
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        // For AJAX
        if($this->input->post('action')){
            //
            $product_type = urldecode($product_type);
            
            if(!empty($statuses) && $statuses != 'all') $statuses = explode(',',str_replace('draft', 'awaiting_candidate_input', urldecode($statuses)));
            else $statuses = array('all');

            $usertype = 'all';
            if(!empty($username) && $username != 'all'){
                $usertype = explode(' - ',urldecode($username));
                if(sizeof($usertype) > 0 && isset($usertype[1]))
                    $usertype = $usertype[1];
                else $usertype = 'all';
            }
                
            if (!empty($start_time) && $start_time != 'all')
                $start_time = empty($start_time) ? null : DateTime::createFromFormat('m-d-Y', $start_time)->format('Y-m-d');
            else $start_time = date('Y-m-01');

            if (!empty($end_time) && $end_time != 'all')
                $end_time = empty($end_time) ? null : DateTime::createFromFormat('m-d-Y', $end_time)->format('Y-m-d');
            else $end_time = date('Y-m-t');

            $to_date = $end_time;
            $company_sid = $company_id;
            $status  = $statuses;
            $from_date = $start_time;
            $order_id  = $order_by;
            $user_id   = $usertype;

            // Set defaults
            $resp = array('Status' => FALSE, 'Response' => 'Invalid request.');
            $limit = 100;
            $offset = $limit; 
            $inset = 0;
            $total_pages = 1;
            // Set page and total records
            $page = $this->input->post('page', true);
            $total_records = $this->input->post('total_records', true);
            $status_records = $this->input->post('status_records', true) ? 
            $this->input->post('status_records', true) : 
            array(
                'pending' => array(),
                'cancelled' => array(),
                'completed' => array(),
                'awaiting_candidate_input' => array()
            );
            $overwrite_status = false;
            $pass = true;
            $ids_array = array();
            if(!sizeof($status_records['pending']) &&
                !sizeof($status_records['cancelled']) &&
                !sizeof($status_records['completed']) &&
                !sizeof($status_records['awaiting_candidate_input'])
            ) $overwrite_status = true;

            // TODO
            if(!in_array('all', $status)){
                if(sizeof($ids_array)) $pass = false;
                else{
                    foreach ($status as $k0 => $v0) {
                        $ids_array = array_merge($ids_array, $status_records[$v0]);
                    }

                }
                // $status = $status[0];
                // else $ids_array = $status_records[$status];
            }else $status = array('all');


            // Export CSV
            if($this->input->post('pages', true)){

                $records = $this->accurate_background_model->
                get_all_accurate_background(
                    $company_sid,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
                    $user_id,
                    $order_id,
                    $inset,
                    $offset,
                    false,
                    $ids_array,
                    true
                );
                $file_name = '';
                $file_name .= str_replace( '-', '_',  $from_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $to_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $product_type );
                $file_name .= '_';
                $file_name .= $company_sid;
                $file_name .= '_';
                $file_name .=  implode('_', $status);
                $file_name = generate_csv($records, $file_name);

                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Overwrite'] = $overwrite_status;
                $resp['Data'] = $file_name;

                header('Content-Type: application/json');
                echo json_encode($resp);
                exit(0);
            }

              
            //
            if($pass){
                //
                if($total_records == 0){
                    // Fetch records count 
                    $result_arr = $this->accurate_background_model->
                    get_all_accurate_background(
                        $company_sid,
                        $product_type,
                        $status,
                        $from_date,
                        $to_date,
                        $user_id,
                        $order_id,
                        $inset,
                        $offset,
                        true,
                        $ids_array
                    );
                    //
                    $total_records = $resp['TotalRecords'] = $result_arr['TotalRecords'];

                    if($overwrite_status)
                        $status_records = $resp['StatusRecords'] = $result_arr['StatusArray'];

                    // if($status != 'all')
                    //     if(sizeof($status_records[$status])) $ids_array = $status_records[$status];

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
                $resp['Data'] = $this->accurate_background_model->
                get_all_accurate_background(
                    $company_sid,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
                    $user_id,
                    $order_id,
                    $inset,
                    $offset,
                    false,
                    $ids_array
                );
            }else{
                $resp['Data'] = '';
                $resp['TotalPages'] = 0;
                $resp['from_records'] = 0;
                $resp['to_records'] = 0;
            }

            //
            $resp['Status'] = TRUE;
            $resp['Response'] = 'Proceed.';
            $resp['Overwrite'] = $overwrite_status;

            header('Content-Type: application/json');
            echo json_encode($resp);
            exit(0);
        }

        $this->data['companies'] = $this->accurate_background_model->get_all_companies();
        $this->data['page_title'] = 'Accurate Background';
        $this->render('manage_admin/accurate_background/listings_view_ajax', 'admin_master');
    }

    /**
     * Download file
     * Created on: 279-05-2019
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


    /**
     * 
     */
    function RemoveBackgroundCheck(){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if(!$post){
            res(['MSG' => 'Invalid Request.']);
        }
        // Lets remove the record but keep a copy of it
        // for safety
        $oldRecord = $this->db
        ->where('sid', $post['id'])
        ->get('background_check_orders')
        ->row_array();
        //
        $this->db->insert('background_check_orders_history', $oldRecord);
        // //
        $insertId = $this->db->insert_id();
        // //
        if(!$insertId){
            res(['MSG' => "Failed to add record to history"]);
        }
        // Deduct one before removing the row
        $invoiceId = 
        $this->accurate_background_model->add_product_qty(
            $oldRecord['product_sid'],
            $oldRecord['company_sid']
        );
        // Add the invoice Id
        $this->db
        ->where('sid', $post['id'])
        ->update('background_check_orders_history', [
            'invoice_sid' => $invoiceId
        ]);
        // Remove record now
        $this->db
        ->where('sid', $post['id'])
        ->delete('background_check_orders');
        //
        res(['MSG' => "Success"]);
    }

    /**
     * 
     */
    function RevertBackgroundCheck(){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if(!$post){
            res(['MSG' => 'Invalid Request.']);
        }
        // Lets remove the record but keep a copy of it
        // for safety
        $historyRecord = $this->db
        ->where('sid', $post['id'])
        ->get('background_check_orders_history')
        ->row_array();
        //
        $invoiceId = $historyRecord['invoice_sid'];
        //
        unset($historyRecord['invoice_sid']);
        // Deduct one before removing the row
        $invoiceId = 
        $this->accurate_background_model->deduct_product_qty(
            $historyRecord['product_sid'],
            $historyRecord['company_sid'],
            $invoiceId
        );

        //
        if($invoiceId == -1){
            res(['MSG' => 'Nothing to substract.']);
        }
        //
        $this->db->insert('background_check_orders', $historyRecord);
        // Remove record now
        $this->db
        ->where('sid', $post['id'])
        ->delete('background_check_orders_history');
        //
        res(['MSG' => "Success"]);
    }
}


