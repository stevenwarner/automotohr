<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Govt_user extends Public_Controller {

    private $resp = array();
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('govt_user_model');
        $this->load->model('manage_admin/company_billing_contacts_model');
        $this->load->model('form_wi9_model');
        $this->load->helper('email');
        //$this->load->model('application_tracking_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //require_once(APPPATH . 'libraries/aws/aws.php');
        //$this->load->library("pagination");

        $this->resp['Status'] = FALSE;
        $this->resp['Response'] = 'Invalid request';
    }

    public function index() {
        $this->indexNew();
        return;
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'company_address'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Govt User Details';
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;

            $data['govt_user'] = $this->govt_user_model->get_user_detail($company_id);
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/govt_users/govt_user_profile');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function create_update() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];

            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('note ', 'Note', 'trim|xss_clean');

            if ($this->form_validation->run() == FALSE){
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            }else{
                $msg = $this->govt_user_model->create_or_update_govt_account($company_id, $company_name);
                echo $msg;
            }
        } else {
            echo json_encode(['error'=>"<b>Error:</b> Your session is expired, login again"]);
        }
    }

    public function govt_user_login($en_company_id){
        if ($this->session->userdata('govt_user_login')) {
            redirect("govt_user/dashboard/", "location");
        }

        $company_id = $en_company_id;
        $company_id = str_replace('$slash$','/',$company_id);
        $company_id = $this->encryption->decrypt($company_id);

        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
        $company_Name = $this->company_billing_contacts_model->get_company_name($company_id);
        $data = message_header_footer($company_id, $company_Name);
        $data['en_company_id'] = $en_company_id;

        if ($this->form_validation->run() == FALSE){
            $data['title'] = $company_Name.' | Goverment Agent';
            $this->load->view('manage_employer/govt_users/header',$data);
            $this->load->view('manage_employer/govt_users/govt_login',$data);
            $this->load->view('manage_employer/govt_users/footer',$data);

        }else{
            $govt_user = $this->govt_user_model->get_user_exist($company_id);
            if($govt_user){
                $govt_user_data['en_company_id'] = $en_company_id;
                $govt_user_data['company_name'] = $company_Name;
                $govt_user_data['company_id'] = $govt_user['company_sid'];
                $govt_user_data['agency_name'] = $govt_user['agency_name'];
                $govt_user_data['agent_name'] = $govt_user['agent_name'];
                $govt_user_data['username'] = $govt_user['username'];
                $govt_user_data['email'] = $govt_user['email'];
                $govt_user_data['picture'] = $govt_user['picture'];
                $govt_user_data['employee_sids'] = $govt_user['employee_sids'];
                $govt_user_data['sid'] = $govt_user['sid'];
                $this->session->set_userdata(['govt_user_login' => $govt_user_data]);
                redirect("govt_user/dashboard/", "location");
            }else{
                $data['title'] = $company_Name.' | Goverment area';
                $this->session->set_flashdata('message', '<b>Error:</b> Wrong Username or Password');
                $this->load->view('manage_employer/govt_users/header',$data);
                $this->load->view('manage_employer/govt_users/govt_login',$data);
                $this->load->view('manage_employer/govt_users/footer',$data);
            }
        }
    }

    public function dashboard(){
        if ($this->session->userdata('govt_user_login')) {
            $company_login = $this->session->userdata('govt_user_login');
            $company_id = $company_login['company_id'];
            // $company_id = 5884;
            $company_Name = $company_login['company_name'];
            $data = message_header_footer($company_id, $company_Name);
            $data['en_company_id'] = $company_login['en_company_id'];
            $data['users'] = $this->govt_user_model->get_i9form_users($company_login);
            $data['govt_user_loggedin'] = true;
            $data['title'] = 'Form i-9';
            $this->load->view('manage_employer/govt_users/header',$data);
            $this->load->view('manage_employer/govt_users/govt_dashboard',$data);
            $this->load->view('manage_employer/govt_users/footer',$data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function logout(){
        if ($this->session->userdata('govt_user_login')) {
            $company_login = $this->session->userdata('govt_user_login');
            $en_company_id = $company_login['en_company_id'];
            $this->session->set_userdata(['govt_user_login' => false]);
            redirect("govt_login/".$en_company_id, "location");
        } else {
            redirect('login', "refresh");
        }
    }

    public function view_i9form($user_type,$employee_sid, $action = 'view'){
        if ($this->session->userdata('govt_user_login')) {

            $data['title'] = 'Form i-9';

            $previous_form = $this->govt_user_model->fetch_i9form($user_type, $employee_sid);
            $data['pre_form'] = $previous_form;
            $data['section_access'] = "complete_i9_pdf";
            //
            if($action == 'download'){
                $this->load->view('2022/federal_fillable/form_i9_download', $data);
            }elseif($action == 'print'){
                $this->load->view('2022/federal_fillable/form_i9_print', $data);
            }else{
                $this->load->view('2022/federal_fillable/form_i9_preview', $data);
            }
        } else {
            redirect('login', "refresh");
        }
    }

    /**
     * Download employess I9 forms with attachment for
     * a specific company
     *
     * @return JSON
     */
    function download_forms(){
        $formpost = $this->input->post(NULL);
        //
        $companyName = $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        $basePath = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $companyName)).'/';
        //
        if(!is_dir($basePath)) mkdir($basePath, 0777, true);
        // For selected I9 employees
        foreach ($formpost['ids'] as $k0 => $v0) {
            // Fetch Employee fullname
            $employee = $this->govt_user_model->getI9User($v0['userId']);
            // fetch all documents of employee
            $documentArray = $this->govt_user_model->getI9EmployeeDocuments(
                $v0['userId']
            );
            $folderPath = $basePath.strtolower(preg_replace('/\s+/', '_', $employee['full_name'])).'/';
            //
            if(!is_dir($folderPath)) mkdir($folderPath, 0777, true);
            //
            if(strtolower($v0['type']) == 'assigned'){
                $handler = fopen($folderPath.'i9.pdf', 'w');
                fwrite($handler, base64_decode(str_replace('data:application/pdf;base64,', '', $v0['pdf'])));
                fclose($handler);
            } else
                @file_put_contents($folderPath.'i9.pdf', @file_get_contents($v0['pdf']));
            //
            if(!sizeof($documentArray)) continue;
            //
            foreach ($documentArray as $k1 => $v1) downloadFileFromAWS($folderPath.$v1['document_name'], AWS_S3_BUCKET_URL.$v1['s3_filename']);
            // foreach ($documentArray as $k1 => $v1) @file_put_contents($folderPath.$v1['document_name'], @file_get_contents(AWS_S3_BUCKET_URL.$v1['s3_filename']));
        }

        $fileName = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $companyName)).'.zip';
        //
        $this->load->library('zip');
        // $this->zip->read_file($basePath, true);
        $this->zip->read_dir(rtrim($basePath,'/'), false);
        // $this->zip->add_dir($basePath);
        $this->zip->archive($fileName);
        // Delete Folders with files
        deleteFolderWithFiles($basePath);
        //
        header('Content-Type: application/json');
        echo json_encode(array('Status' => TRUE, 'Response' => 'Proceed.', 'Link' => strtolower(preg_replace('/\s+/', '_', $companyName)).'.zip'));
    }

    function view(){ $this->indexNew(); }
    //
    private function indexNew(){
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), 'refresh');
        $data['session'] = $this->session->userdata('logged_in');
        //
        $search = array();
        $search['agency'] = $this->uri->segment(3) ? $this->uri->segment(3) : 'all';
        $search['agent'] = $this->uri->segment(4) ? $this->uri->segment(4) : 'all';
        $search['status'] = $this->uri->segment(5) ? $this->uri->segment(5) : 'all';
        $search['startDate'] = $this->uri->segment(6) ? $this->uri->segment(6) : 'all';
        $search['endDate'] = $this->uri->segment(7) ? $this->uri->segment(7) : 'all';
        //
        $data['startDate'] = $search['startDate'] == 'all' ? '' : $search['startDate'];
        $data['endDate'] = $search['endDate'] == 'all' ? '' : $search['endDate'];
        //
        $data['search'] = $search;
        //
        $data['agencies'] = $this->govt_user_model->getAgencies($data['session']['company_detail']['sid']);
        $data['agents'] = $this->govt_user_model->getAgents($data['session']['company_detail']['sid']);
        $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'my_settings', 'dashboard'); // Param2: Redirect URL, Param3: Function Name
        $data['title'] = 'Goverment Agent Credentials';
        // Fetch employees
        $data['employees'] = $this->govt_user_model->getAllEmployees($data['session']['company_detail']['sid']);
        $data['records']  = $this->govt_user_model->getRecords( $data['session']['company_detail']['sid'], $search );
        //
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/govt_users/new/view');
        $this->load->view('main/footer');
    }
    //
    function add(){
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), 'refresh');
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'my_settings', 'dashboard'); // Param2: Redirect URL, Param3: Function Name
        $data['title'] = 'Goverment Agent Credentials';
        // Fetch employees
        $data['employees'] = $this->govt_user_model->getAllEmployees($data['session']['company_detail']['sid']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/govt_users/new/add');
        $this->load->view('main/footer');
    }
    //
    function edit($id){
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), 'refresh');
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'my_settings', 'dashboard'); // Param2: Redirect URL, Param3: Function Name
        $data['title'] = 'Goverment Agent Credentials';
        // Fetch employees
        $data['record'] = $this->govt_user_model->getAgent($data['session']['company_detail']['sid'], $id);
        if(!sizeof($data['record'])){
            $this->session->set_flashdata('error', 'You don\'t have access to the agent credentials.');
            redirect('govt_user/view');
        }
        $data['employees'] = $this->govt_user_model->getAllEmployees($data['session']['company_detail']['sid']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/govt_users/new/edit');
        $this->load->view('main/footer');
    }
    //
    function handler(){
        $session = $this->session->userdata('logged_in');
        $post = $this->input->post(NULL, TRUE);
        switch ($post['action']) {
            case 'add':
                // Check if same use exists and not expired yet
                $doExist = $this->govt_user_model->checkAgent( $post, $session );
                //
                if($doExist){
                    $this->resp['Expire'] = true;
                    $this->resp['Response'] = 'Credentials under this username already exists and not expired. Do you want to expire the old credentials and use these credentials?';
                    $this->res();
                }
                //
                $insertId = $this->govt_user_model->addAgent($post, $session);

                if(!$insertId){
                    $this->resp['Response'] = 'Something went wrong while adding agent credentials.';
                    $this->res();
                }

                // For email send
                if( $post['sendEmail'] == 1 ){
                    $post['sid'] = $insertId;
                    $this->sendEmail( $post, $session);
                }

                $this->resp['Data'] = $insertId;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Agent credentials are added.';
                $this->res();
            break;

            case 'add_expire':
                // Expire previous
                $this->govt_user_model->expireAgents( $post, $session );
                //
                $insertId = $this->govt_user_model->addAgent($post, $session);
                //
                if(!$insertId){
                    $this->resp['Response'] = 'Something went wrong while adding agent credentials.';
                    $this->res();
                }
                // For email send
                if( $post['sendEmail'] == 1 ){
                    $post['sid'] = $insertId;
                    $this->sendEmail( $post, $session);
                }
                //
                $this->resp['Data'] = $insertId;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Agent credentials are added.';
                $this->res();
            break;

            case 'edit':
                // Check if same use exists and not expired yet
                $doExist = $this->govt_user_model->checkAgent( $post, $session );
                //
                if($doExist){
                    $this->resp['Expire'] = true;
                    $this->resp['Response'] = 'Credentials under this username already exists.';
                    $this->res();
                }
                //
                $this->govt_user_model->editAgent($post, $session);

                // For email send
                if( $post['sendEmail'] == 1 ){
                    $this->sendEmail( $post, $session);
                }

                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Agent credentials are updated.';
                $this->res();
            break;

            case 'send_email':
                // Fetch credentials
                $creds = $this->govt_user_model->getAgent($session['company_detail']['sid'], $post['sid'] );
                $this->sendEmail($creds, $session);
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Credential has been sent.';
                $this->res();
            break;

            case 'expire_agent':
                $creds = $this->govt_user_model->expireAgentById( $post['sid'] );
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Agent is deactivated.';
                $this->res();
            break;

            case 'activate_agent':
                $creds = $this->govt_user_model->activateAgentById( $post['sid'] );
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Agent is activated.';
                $this->res();
            break;
        }

        $this->res();
    }
    //
    private function res(){
        header('Content-type: application/json');
        echo json_encode($this->resp);
        exit(0);
    }
    //
    private function sendEmail($post, $ses){
        $encrypted_company_id = str_replace('/', '$slash$', $this->encryption->encrypt( $ses['company_detail']['sid'] ) );
        // Fetch goverment email template
        $parent_sid = $ses['company_detail']['sid'];
        //
        $template = get_email_template(GOVERMENT_EMAIL_TEMPLATE_SID);
        //
        $replacement_array = array();
        $replacement_array['parent_sid'] = $parent_sid;
        $replacement_array['username'] = $post['username'];
        $replacement_array['password'] = $post['password'];
        $replacement_array['password_encrypt'] = "no";
        $replacement_array['login_button'] = '<a href="'.( base_url('govt_login/'.$encrypted_company_id)).'" style=" text-decoration: none; background-color: #81b431; color: #ffffff; padding: 10px ; margin: 10px; font-size: 16px; border-radius: 3px;">Click to login</a>';
        //
        $subject = convert_email_template($template['subject'], $replacement_array);
        $message = convert_email_template($template['text'], $replacement_array);
        //
        log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $post['email'], $subject, $message, $post['username']);
        $this->db->insert('govt_users_email_history', [
            'govt_user_id' => $post['sid'],
            'email' => $post['email'],
        ]);
    }
}
