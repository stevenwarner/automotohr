<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller {
  protected $data = array();
  function __construct() {
    parent::__construct();
    $this->data['page_title'] = 'automotoHR';
    $this->data['before_head'] = '';
    $this->data['before_body'] = '';
  }
 
  protected function render($the_view = NULL, $template = 'master') {
    if($template == 'json' || $this->input->is_ajax_request()) {
      header('Content-Type: application/json');
      echo json_encode($this->data);
    } else {
      $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view,$this->data, TRUE);
      $this->load->view('templates/'.$template.'_view', $this->data);
    }
  }
}
 
class Admin_Controller extends MY_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('ion_auth');
        
    if(!in_array('generate_password', $this->uri->segment_array()) && !in_array('receive_request', $this->uri->segment_array())){

        if ($this->ion_auth->logged_in()) {
            $current_user = $this->ion_auth->user()->row();

            if($current_user->active == 1) {
                $this->data['current_user'] = $current_user;
                $this->data['current_user_menu'] = '';

                if ($this->ion_auth->in_group('admin')) {
                    $this->data['current_user_menu'] = $this->load->view('templates/_parts/user_menu_admin_view.php', NULL, TRUE);
                }

                $this->data['page_title'] = 'Admin Panel - automotoHR';
                $header_notifications = get_admin_notifications();
                $header_notifications['pending_jobs_to_feed'] = count_pending_job_feed_requests();
                $header_notifications['affiliations'] = check_affiliations();
                $header_notifications['affiliations_link'] = sizeof($header_notifications['affiliations']) > 0 ? $header_notifications['affiliations'][0]['is_reffered'] == 0 ? 'manage_admin/affiliates' : 'manage_admin/referred_affiliates' : '';
                $header_notifications['unpaid_commissions_count'] = unpaid_commissions();
                $header_notifications['end_user_license_signed'] = end_user_license_signed();
                $header_notifications['client_refer_by_affiliate'] = client_refer_by_affiliate();
                $header_notifications['form_document_credit_card_authorization'] = form_document_credit_card_authorization();
                $header_notifications['form_affiliate_end_user_license_agreement'] = form_affiliate_end_user_license_agreement();
                $header_notifications['private_messages'] = fetch_private_message_notification();
                $header_notifications['sms_notifications'] = fetch_admin_sms_notifications($this);
                $header_notifications['profile_date_change'] = count(getProfileDataChange($this));
               // echo '<pre>';
               // print_r($header_notifications['private_messages']);
               // die();
                $this->data['header_notifications'] = $header_notifications;
            } else {
                $this->session->set_flashdata('message', 'Account is Inactive');
                redirect('manage_admin/user/login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'User Session Has Expired!');
            redirect('manage_admin/user/login', 'refresh');
        }
    }
  }
  
  protected function render($the_view = NULL, $template = 'admin_master') {
    parent::render($the_view, $template);
  }
}
 
class Public_Controller extends MY_Controller {
  function __construct() {
    parent::__construct();
    $data                                                                       = array();
    $login_check                                                                = $this->session->userdata('logged_in');
        if($login_check){ // User is already Logged IN // track User's activity Log
            $activity_data                                                      = array();
            $activity_data['company_sid']                                       = $login_check['company_detail']['sid'];
            $activity_data['employer_sid']                                      = $login_check['employer_detail']['sid'];
            $activity_data['company_name']                                      = $login_check['company_detail']['CompanyName'];
            $activity_data['employer_name']                                     = $login_check['employer_detail']['first_name'].' '.$login_check['employer_detail']['last_name'];
            $activity_data['employer_access_level']                             = $login_check['employer_detail']['access_level'];
            $activity_data['module']                                            = $this->router->fetch_class();
            $activity_data['action_performed']                                  = $this->router->fetch_method();
            $activity_data['action_year']                                       = date('Y');
            $activity_data['action_week']                                       = date('W');
            $activity_data['action_timestamp']                                  = date('Y-m-d H:i:s');
            $activity_data['action_status']                                     = '';
            $activity_data['action_url']                                        = current_url();
            $activity_data['employer_ip']                                       = getUserIp();
            $activity_data['user_agent']                                        = $_SERVER['HTTP_USER_AGENT'];

            if(isset($_SESSION['logged_in']['is_super']) && $_SESSION['logged_in']['is_super'] == 1){
                $this->db->insert('logged_in_activitiy_tracker_super', $activity_data);
            } else {
                $this->db->insert('logged_in_activitiy_tracker', $activity_data);
            }
        } else { // User is not Logged IN // make snapshot for users URL, after login send him to his actual URL
            $data['snapshot']['class']                                          = $this->router->fetch_class();
            $data['snapshot']['method']                                         = $this->router->fetch_method();
            $data['snapshot']['url']                                            = current_url();
        }
        
        $this->session->set_userdata('snapshot', $data);
  }
}