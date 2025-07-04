<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_job_feeds_management extends Admin_Controller{

    private $indeedProductIds;
    private $ziprecruiterProductIds;

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library("pagination");

        $this->load->model('manage_admin/custom_job_feeds_management_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->indeedProductIds = array(1, 21);
        $this->ziprecruiterProductIds = array(2);
    }

    public function index()
    {

        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'job_feeds_management';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Custom Job Feeds Management';

        $this->data['default_feed_list'] = $this->custom_job_feeds_management_model->fetch_feeds_list(0,0);
        $this->data['default_feed_accept_url'] = $this->custom_job_feeds_management_model->fetch_feeds_list(0,1);
        $this->data['custom_feed_list']  = $this->custom_job_feeds_management_model->fetch_feeds_list(1,0);
        $this->data['custom_feed_accept_url']  = $this->custom_job_feeds_management_model->fetch_feeds_list(1,1);
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/custom_job_feeds_management/index');
        }
    }

    public function add_feed()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'add_feed';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Add Custom Job Feed';

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/custom_job_feeds_management/add_feed');
        } else {
            $perform_action = $this->input->post('perform_action');
//            echo '<pre>';
//            print_r($_POST);
//            die();
            switch ($perform_action) {
                case 'add':
                    $insert_data = array();
                    $insert_data['title'] = $this->input->post('title');
                    $insert_data['slug'] = strtolower(str_replace('','-',$this->input->post('title')));
                    $insert_data['description'] = $this->input->post('desc');
                    $insert_data['status'] = $this->input->post('status');
                    $insert_data['is_default'] = 1;
                    $insert_data['accept_url_flag'] = $this->input->post('accept_url');
                    $insert_data['type'] = $this->input->post('type');
                    $insert_data['url'] = base_url().'job_feeds/'.$insert_data['slug'];
                    $insert_data['creator_sid'] = $admin_id;
                    $insert_id = $this->custom_job_feeds_management_model->insert_custom_feed($insert_data);
                    // update the url in http_urls file
                    $this->update_http_url($insert_data['url']);
                    $redirect = $this->input->post('form-submit');

                    if($redirect == 'Save and Add More'){
                        redirect(base_url('manage_admin/custom_job_feeds_management/add_feed'), 'refresh');
                    }else{
                        redirect(base_url('manage_admin/custom_job_feeds_management'), 'refresh');
                    }
                break;
            }
        }
    }

    public function edit_job_feed($feed_id)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'add_feed';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Add Custom Job Feed';

        $edit_data = $this->custom_job_feeds_management_model->fetch_feed_by_id($feed_id);
//        echo '<pre>';
//        print_r($edit_data);die();
        if(!sizeof($edit_data)){
            $this->session->set_flashdata('message', '<b>Error:</b> Feed Not Found');
            redirect(base_url('manage_admin/custom_job_feeds_management'), 'refresh');
        }
        $this->data['edit_data'] = $edit_data[0];
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/custom_job_feeds_management/edit_feed');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'edit':
                    $update_data = array();
                    $update_data['title'] = $this->input->post('title');
                    $update_data['slug'] = strtolower(str_replace('','-',$this->input->post('title')));
                    $update_data['description'] = $this->input->post('desc');
                    $update_data['status'] = $this->input->post('status');
                    $update_data['accept_url_flag'] = $this->input->post('accept_url');
                    $update_data['type'] = $this->input->post('type');
                    $update_data['url'] = base_url().'job_feeds/'.$update_data['slug'];
                    $this->custom_job_feeds_management_model->update_custom_feed($feed_id, $update_data);
                    $this->update_http_url($update_data['url']);
                    $this->session->set_flashdata('message', '<b>Success:</b> Feed Updated Successfully');
                    redirect(base_url('manage_admin/custom_job_feeds_management'), 'refresh');
                break;
            }
        }
    }
    public function companies_listing($feed_sid){
        $this->data['feedName']=$this->custom_job_feeds_management_model->getFeedName($feed_sid)['title'];
        $company_data=$this->custom_job_feeds_management_model->get_companies_data($feed_sid);
        $this->data['company_listing']=$company_data;
        $this->data['feedSid']=$feed_sid;
        $this->render("manage_admin/custom_job_feeds_management/companies_view");
    }
    public function ajax_handler(){
        $sid = $this->input->post('id');
        $status = $this->input->post('status');
        $update_array = array('status' => $status);
        $this->custom_job_feeds_management_model->update_custom_feed($sid, $update_array);
        echo 'updated';
    }


    public function updatestatus(){
        $this->custom_job_feeds_management_model->updateFeed(
            array(
                'status' => $this->input->post('status') == 1 ? 0 : 1
            ), 
            $this->input->post('companySid'),
            $this->input->post('feedSid')
        );
        echo 'updated';
    }

    /**
     * Update custom feed URLs on file
     * @param string
     */
    private function update_http_url($url){
        $urlParts = parse_url($url);
        $url = (preg_replace('/^www\./', '', $urlParts['host'])).$urlParts['path'];
        $file_name = APPPATH.'../ahr_jsons/http_urls.json';
        //
        $file = fopen($file_name, 'r');
        // read data from file
        $file_data =  fread($file, filesize($file_name));
        $file_data = json_decode($file_data, true);
        // save data to file
        $file_data[strtolower($url)] = true;
        // open file to write
        $file = fopen($file_name, 'w');
        // write data in file
        fwrite($file, json_encode($file_data));
        // close file after saving data
        fclose($file);
    }

}