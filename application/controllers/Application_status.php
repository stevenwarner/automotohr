<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Application_status extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Application_status_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'application_status');
            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Applicant Status Bar Module";
            $this->replace_status_text();
            $data['application_status'] = $this->Application_status_model->get_status_by_company($company_sid);
            $config = array(); // add codeigniter validation to all the status fields
            $data['company_status_right'] = $this->Application_status_model->check_company_status_right($company_sid);

            foreach ($data['application_status'] as $status) {
                $field = array(
                    'field' => $status['css_class'],
                    'label' => 'Status',
//                    'rules' => 'xss_clean|trim|required|alpha_numeric_spaces'
                );
                $order_field = array(
                    'field' => 'order_' . $status['css_class'],
                    'label' => 'Order',
                    'rules' => 'xss_clean|trim|required|numeric|min_length[1]|max_length[50]'
                );

                $config[] = $field;
                $config[] = $order_field;
            }

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $additional_status_bar = $this->Application_status_model->check_status_for_additional_status_bar($company_sid);
           
            $data['additional_status_bar'] = $additional_status_bar;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('application_status/index_new');
                $this->load->view('main/footer');
                
            } else {
                $custom_status_array = array();
                $formpost = $this->input->post(NULL, TRUE);;

                foreach ($formpost as $key => $status) {
                    if (strpos($key, 'custom_') != FALSE || strpos($key, 'custom_') === 0) {
                        $custom_status_array[$key] = $status;
                        unset($formpost[$key]);
                    }
                }
                if (isset($custom_status_array['additional_custom_count']) && $custom_status_array['additional_custom_count'] > 0) {
                    foreach ($custom_status_array['custom_status_name_'] as $key => $status_name) {
                        $insert_array = array();
                        $insert_array['name'] = $status_name;
                        $insert_array['css_class'] = str_replace(' ', '_', $status_name) . '_' . generateRandomString(8);
                        $insert_array['text_css_class'] = 'text_' . $insert_array['css_class'];
                        $insert_array['status_order'] = $custom_status_array['custom_sort_order_'][$key];
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['status_type'] = 'custom';
                        $insert_array['bar_bgcolor'] = $custom_status_array['custom_color_'][$key];
                        $this->Application_status_model->insert_applicant_status($insert_array);
                    }
                }

                $this->Application_status_model->update_company_status($formpost, $company_sid);
                $data['application_status'] = $this->Application_status_model->get_status_by_company($company_sid);
                $this->session->set_flashdata('message', '<strong>Success: </strong> Status updated!');
                redirect('application_status', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /*
     * function replace_status_text code runs for each company;
     * Company sid check cannot be removed because we have only status text to match to get status sid
     */

    public function replace_status_text() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $do_update = $this->Application_status_model->check_company_status($company_sid); // insert records if not already present

            if ($do_update == true) {
                $statuses = $this->Application_status_model->get_status_by_company($company_sid); // get all statuses for this company
                // update status_sid in both these tables for this particular company               
                //**** for portal_job_applications table *****//
                $this->db->where('company_sid', $company_sid);
                $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

                foreach ($applications as $application) {
                    $application_status = $application['status'];
                    $insert_sid = 0;

                    foreach ($statuses as $status) {
                        if (strtolower($status['name']) == strtolower($application_status)) {
                            $insert_sid = $status['sid'];
                        }
                    }

                    if ($insert_sid != 0) {
                        $this->db->where('sid', $application['sid']);
                        $this->db->update('portal_applicant_jobs_list', array('status_sid' => $insert_sid));
                    }
                }
                //**** for portal_job_applications table *****//                
                //**** for portal_manual_candidates table *****//
                $this->db->where('employer_sid', $company_sid);
                $applications = $this->db->get('portal_manual_candidates')->result_array();

                foreach ($applications as $application) {
                    $application_status = $application['status'];
                    $insert_sid = 0;

                    foreach ($statuses as $status) {
                        if (strtolower($status['name']) == strtolower($application_status)) {
                            $insert_sid = $status['sid'];
                        }
                    }

                    if ($insert_sid != 0) {
                        $this->db->where('sid', $application['sid']);
                        $this->db->update('portal_manual_candidates', array('status_sid' => $insert_sid));
                    }
                }
                //**** for portal_manual_candidates table *****//
            }
        }
    }

    public function delete_custom_status() {
        $id = $this->input->post('id');
        $this->Application_status_model->delete_status($id);
        print_r('success');
    }

    //
    function handler(){
        $sid = $this->session->userdata('logged_in')['company_detail']['sid'];

        $post = $this->input->post(NULL)['data'];
        //
        foreach($post as $status){
            //
            if(substr($status['id'],0,1) == 0){
                $ins = [
                    'name' => $status['status'],
                    'bar_bgcolor' => $status['color'],
                    'status_order' => $status['order'],
                    'status_type' => 'custom',
                    'css_class' => str_replace(' ', '_', $status['name']) . '_' . generateRandomString(8),
                    'company_sid' => $sid
                ];
                $ins['text_css_class'] = 'text_'.$ins['css_class'];
                $this->db
                ->insert('application_status', $ins);
            } else{
                $this->db
                ->where('sid', $status['id'])
                ->update('application_status', [
                    'name' => $status['status'],
                    'bar_bgcolor' => $status['color'],
                    'status_order' => $status['order']
                ]);
            }
        }
    }

}
