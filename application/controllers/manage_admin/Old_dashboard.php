<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Old_dashboard extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/dashboard_old');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $admin_id = $this->ion_auth->user()->row()->id;
        
        if ($admin_id !=1 ) {
            redirect('manage_admin', "location");
        }

        $this->data['page_title'] = 'AHR Dashboard';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $all_enquiries = $this->dashboard_old->get_cc_auth();
        $active_companies = $this->dashboard_old->get_all_active_company_name();
        $company_info = array();
        
        foreach($active_companies as $company_details){
            $company_sid = $company_details['sid'];
            $company_name = $company_details['CompanyName'];
            $company_info[$company_sid] = $company_name;
        }
        //echo $company_info[3];
        //exit;
        foreach($all_enquiries as $key => $value){
            $search_sid = $value['company_sid'];
            $all_enquiries[$key]['CompanyName'] = 'Not found!';
            if(isset($company_info[$search_sid])){
                $all_enquiries[$key]['CompanyName'] =  $company_info[$search_sid];
            }
        }
//        echo "<pre>";
//        print_r($all_enquiries);
//        exit;
        //foreach($all_enquiries)
        $this->data['all_enquiries'] = $all_enquiries;
        $this->render('manage_admin/free_demo/dashboard_view');
    }
    
   public function info($id = NULL) {
        if ($id == NULL) {
            $this->session->set_flashdata('message', 'No Record Found!');
            redirect('manage_admin/free_demo/dashboard_view', 'refresh');
        } else {
            $this->data['page_title'] = 'Card Details';
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $data = $this->dashboard_old->get_details($id);
            
            if (!empty($data)) {
                    if($data[0]['cc_type']!='') {
                        $this->data['cc_type'] = decode_string($data[0]['cc_type']);
                    } else {
                        $this->data['cc_type'] = 'not available';
                    }
                    if($data[0]['cc_holder_name']!='') {
                        $this->data['cc_holder_name'] = decode_string($data[0]['cc_holder_name']);
                    } else {
                        $this->data['cc_holder_name'] = 'not available';
                    }
                    if($data[0]['cc_number']!='') {
                        $this->data['cc_number'] = decode_string($data[0]['cc_number']);
                    } else {
                        $this->data['cc_number'] = 'not available';
                    }
                    if($data[0]['cc_expiration_month']!='') {
                        $this->data['cc_expiration_month'] = decode_string($data[0]['cc_expiration_month']);
                    } else {
                        $this->data['cc_expiration_month'] = 'not available';
                    }
                    if($data[0]['cc_expiration_year']!='') {
                        $this->data['cc_expiration_year'] = decode_string($data[0]['cc_expiration_year']);
                    } else {
                        $this->data['cc_expiration_year'] = 'not available';
                    }
            } else {
                $this->session->set_flashdata('message', 'No Record Found!');
                redirect('manage_admin/free_demo/dashboard_view', 'refresh');
            }
            
            $this->render('manage_admin/free_demo/detail_view');
        }
    }

}