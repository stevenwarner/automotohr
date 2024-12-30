<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accurate_background extends Public_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('background_check_model');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($username = '', $search_sid = 0, $order_by = '', $statuses = '', $product_type = '', $start_time = '', $end_time = '', $page = 0) {
     
        if (!checkIfAppIsEnabled('backgroundchecksreport')) {
            $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
            redirect(base_url('dashboard'), "refresh");
        }

        $this->index_new($username, $search_sid, $order_by, $statuses, $product_type, $start_time, $end_time); return;
        if ($this->session->has_userdata('logged_in')) {
            $data['title'] = 'Accurate Background Orders';
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'accurate_background'); // Param2: Redirect URL, Param3: Function Name
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $is_check_active = $data['session']['company_detail']['background_check'];           
            $data['is_check_active'] = $is_check_active;
            $data['employer_id'] = $employer_sid;
            $data['company_accounts'] = $this->background_check_model->getCompanyAccounts($company_sid);
            $product_type = urldecode($product_type);

            if(!empty($statuses) && $statuses != 'all'){
                $statuses = explode(',',urldecode($statuses));
            } else {
                $statuses = array('all');
            }
            
            if(!empty($username) && $username != 'all'){
                $usertype = explode(' - ',urldecode($username));
                
                if(sizeof($usertype) > 0){
                    $usertype = $usertype[1];
                } else{
                    $usertype = 'all';
                }
            } else{
                $usertype = 'all';
            }
            
            if (!empty($start_time) && $start_time != 'all') {
                $start_time = empty($start_time) ? null : DateTime::createFromFormat('m-d-Y', $start_time)->format('Y-m-d 00:00:00');
            } else {
                $start_time = date('Y-m-01 00:00:00');
            }

            if (!empty($end_time) && $end_time != 'all') {
                $end_time = empty($end_time) ? null : DateTime::createFromFormat('m-d-Y', $end_time)->format('Y-m-d 23:59:59');
            } else {
                $end_time = date('Y-m-t 23:59:59');
            }
            
            if ($data['is_check_active'] == 1) {
                $data['checks_count'] = $this->background_check_model->get_all_accurate_background_checks_by_company_count($company_sid, $usertype, $search_sid, $order_by, $product_type, $start_time, $end_time);
                
                /** pagination * */
                $records_per_page = 1000; //- it is disabled for background checks - PAGINATION_RECORDS_PER_PAGE;
                $my_offset = 0;

                if ($page > 1) {
                    $my_offset = ($page - 1) * $records_per_page;
                }

                $baseUrl = base_url('accurate_background');
                $uri_segment = 8;
                $config = array();
                $config['base_url'] = $baseUrl;
                $config['total_rows'] = $data['checks_count'];
                $config['per_page'] = $records_per_page;
                $config['uri_segment'] = $uri_segment;
                $choice = $config['total_rows'] / $config['per_page'];
                $config['num_links'] = ceil($choice);
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
                $data['links'] = $this->pagination->create_links();
                /** pagination end * */
                $data['checks'] = $this->background_check_model->get_all_accurate_background_checks_by_company($company_sid, $usertype, $search_sid, $order_by, $product_type, $start_time, $end_time, $records_per_page, $my_offset);
                
                foreach ($data['checks'] as $key => $check) {
                    if ($check['users_type'] == 'applicant') {
                        $this->db->select('first_name,last_name');
                        $this->db->where('sid', $check['users_sid']);
                        $result = $this->db->get('portal_job_applications')->result_array();
                    } elseif ($check['users_type'] == 'employee') {
                        $this->db->select('first_name,last_name');
                        $this->db->where('sid', $check['users_sid']);
                        $result = $this->db->get('users')->result_array();
                    }

                    if(isset($check['order_response'])){
                        $response = @unserialize($check['order_response']);
                    }
                    
                    if (isset($response['orderStatus']['status'])) { //echo '<hr>i am IF';
                        $status = $response['orderStatus']['status'];
                        $data['checks'][$key]['status'] = $status;
                    } else { //echo '<hr>i am ELSE';
                        $data['checks'][$key]['status'] = 'Pending';
                    }
                    
                    if(!in_array('all',$statuses) && !in_array(strtolower($data['checks'][$key]['status']), $statuses)){
                        unset($data['checks'][$key]);
                        $data['checks_count']--;
                    } else{
                        $data['checks'][$key]['user_first_name'] = $result[0]['first_name'] . ' ' . $result[0]['last_name'];
                    }
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/accurate_background_orders');
            $this->load->view('main/footer');
        }//if end for session check success
        else {
            redirect(base_url('login'), "refresh");
        }//else end for session check fail
    }

    function get_applicants($query){
        // check if ajax request is not set
        if(!$this->input->is_ajax_request()) redirect('accurate_background', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
//        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $this->response( $this->background_check_model->get_applicants_by_query($company_id, $query) );
    }

    private function response($array){
        header('Content-Type: application/json');
        echo json_encode($array); exit(0);
    }


    // $username = '', $search_sid = 0, $order_by = '', $statuses = '', $product_type = '', $start_time = '', $end_time = '', $page = 0
    private function index_new(
        $username = false, 
        $users_sid = 0, 
        $order_by = false, 
        $status = false, 
        $product_type = false, 
        $from_date = false, 
        $to_date = false) {
        // ** Check Security Permissions Checks - Start ** //
        //
        if (!$this->session->has_userdata('logged_in')) redirect(base_url('login'), "refresh");
    
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'my_settings', 'accurate_background'); // Param2: Redirect URL, Param3: Function Name
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $is_check_active = $data['session']['company_detail']['background_check'];           
        $data['is_check_active'] = $is_check_active;
        $data['employer_id'] = $employer_sid;
        
        // ** Check Security Permissions Checks - End ** //

        // For AJAX
        if($this->input->post('action')){
            $product_type = urldecode($product_type);
            // Set Search
            // Set user type
            $users_type = 'all';
            if(!empty($username) && $username != 'all'){
                $users_type = explode(' - ',urldecode($username));
                $users_type = 'all';
                if(sizeof($users_type) > 0) $users_type = $users_type[1];
            }

            // Set product type
            if ($product_type != 'all' && $product_type !== FALSE)
                $product_type = $product_type == 'background_check' || $product_type == 'background-checks' ? 'background-checks' : 'drug-testing';
            else $product_type = 'all';

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

            if(!empty($status) && $status != 'all') $status = explode(',',str_replace('draft', 'awaiting_candidate_input', urldecode($status)));
            else $status = array('all');

            //
            if(!in_array('all', $status)){
                if(sizeof($ids_array)) $pass = false;
                else{
                    foreach ($status as $k0 => $v0) {
                        if(isset($status_records[$v0]))
                            $ids_array = array_merge($ids_array, $status_records[$v0]);
                    }

                }
                // $status = $status[0];
                // else $ids_array = $status_records[$status];
            }else $status = array('all');

            if(!in_array('all', $status) && !$overwrite_status){
                foreach ($status as $k0 => $v0) {
                    if(isset($status_records[$v0]))
                        if(!sizeof($status_records[$v0])) $pass = false;
                }
                if($pass === false)
                    $ids_array = $status_records[$status];
            }

            if($this->input->post('pages', true)){

                $records = $this->background_check_model->
                get_all_accurate_background(
                    $company_sid,
                    $users_type,
                    $users_sid,
                    $order_by,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
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
                $file_name .=  implode('_',$status);
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
                    $result_arr = $this->background_check_model->
                    get_all_accurate_background(
                        $company_sid,
                        $users_type,
                        $users_sid,
                        $order_by,
                        $product_type,
                        $status,
                        $from_date,
                        $to_date,
                        $inset,
                        $offset,
                        true,
                        $ids_array
                    );
                    //

                    $total_records = $resp['TotalRecords'] = $result_arr['TotalRecords'];

                    if($overwrite_status)
                        $status_records = $resp['StatusRecords'] = $result_arr['StatusArray'];

                    // if(!in_array('all', $status))
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
                $resp['Data'] = $this->background_check_model->
                get_all_accurate_background(
                    $company_sid,
                    $users_type,
                    $users_sid,
                    $order_by,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
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

        //
        $data['title'] = 'Accurate Background Report';
        // $this->data['companies'] = $this->accurate_background_report_model->get_all_companies();
        $data['company_accounts'] = $this->background_check_model->getCompanyAccounts($company_sid);
        
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/accurate_background_orders_ajax');
        $this->load->view('main/footer');
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
}
