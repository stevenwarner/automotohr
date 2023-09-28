<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Demo_model');
    }

    public function schedule_your_free_demo() {
        $client_source = $this->uri->segment(1);
        $data['title']                                                          = 'Schedule Your free demo';
        $data_countries                                                         = db_get_active_countries();
        
        if($this->session->userdata('logged_in')){
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details; 
        }
        
        foreach ($data_countries as $value) {
            $data_states[$value['sid']]                                         = db_get_active_states($value['sid']);
        }
        
        $data['active_countries']                                               = $data_countries;
        $data['active_states']                                                  = $data_states;
        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $data['states']                                                         = $data_states_encode;
        
        $this->form_validation->set_rules('name', 'Please provide name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Please provide valid email address ', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('phone_number', 'Please provide valid number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('company_name', 'Please provide your Company Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', 'Please provide your Title', 'trim|xss_clean');
        $this->form_validation->set_rules('company_size', 'Please provide your Company Size', 'trim|xss_clean');
     
        //  $this->form_validation->set_rules('newsletter_subscribe', 'Please select your choice', 'trim|xss_clean');  nisar
          $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_recaptcha[' . $this->input->post('g-recaptcha-response') . ']');
       
       
        /*if ($this->uri->segment(1) == 'demo') {
           $this->form_validation->set_rules('schedule_date', 'Please select schedule date', 'trim|required|xss_clean');
           $this->form_validation->set_rules('schedule_time', 'Please select schedule time', 'trim|required|xss_clean');                     
        } */
        
        $validate_video_status = $this->Demo_model->validate_affiliate_video_status(1);
      
        if (!empty($validate_video_status[0]['video_source']) && $validate_video_status[0]['status'] == 1 && $client_source == 'schedule_your_free_demo') {
            $selected_source = $validate_video_status[0]['video_source'];
            if (!empty($validate_video_status[0][$selected_source])) {
                if ($selected_source == 'youtube_video') {
                    $data['video_url'] = $validate_video_status[0][$selected_source];
                } elseif ($selected_source == 'vimeo_video') {
                    $data['video_url'] = $validate_video_status[0][$selected_source];
                } elseif ($selected_source == 'uploaded_video') {
                    $data['video_url'] = $validate_video_status[0][$selected_source];
                }
                $data['validate_flag'] = true;
                $data['video_source'] = $selected_source;
            } else {
                $data['validate_flag'] = false;
            }
            
        } else {
            $data['validate_flag'] = false;
        }

        $validate_body_video_status = $this->Demo_model->validate_affiliate_video_status(3);

        if (!empty($validate_body_video_status[0]['video_source']) && $validate_body_video_status[0]['status'] == 1 && $client_source == 'schedule_your_free_demo') {
            $selected_source = $validate_body_video_status[0]['video_source'];
            if (!empty($validate_body_video_status[0][$selected_source])) {
                if ($selected_source == 'youtube_video') {
                    $data['body_video_url'] = $validate_body_video_status[0][$selected_source];
                } elseif ($selected_source == 'vimeo_video') {
                    $data['body_video_url'] = $validate_body_video_status[0][$selected_source];
                } elseif ($selected_source == 'uploaded_video') {
                    $data['body_video_url'] = $validate_body_video_status[0][$selected_source];
                }
                $data['validate_body_flag'] = true;
                $data['body_video_source'] = $selected_source;
                $data['body_column_type'] = $validate_body_video_status[0]['column_type'];
                $data['body_title'] = $validate_body_video_status[0]['title'];
                $data['body_content'] = $validate_body_video_status[0]['content'];

            } else {
                $data['validate_body_flag'] = false;
            }
            
        } else {
            $data['validate_body_flag'] = false;
        }
        
        if ($this->form_validation->run() === FALSE) {
                $data['current_url'] = $this->uri->segment(1);
                $this->load->view('main/header',$data);
                $this->load->view('static-pages/schedule-your-free-demo-new',$data);
                $this->load->view('main/footer');
        } else {
            $first_name = $this->input->post('name');
            $email = $this->input->post('email');
            $phone_number = $this->input->post('phone_number');
            $company_name = $this->input->post('company_name');
            $job_role = $this->input->post('job_role');
            $company_size = $this->input->post('company_size');
            $newsletter_subscribe = 0;////$this->input->post('newsletter_subscribe');  Nisar
            $date_requested = date('Y-m-d H:i:s');
            
            
            if($client_source == 'demo'){
                $ppc = 1;
//                $date = $this->input->post('schedule_date');
//                $time = $this->input->post('schedule_time');
//                $schedule_demo = $date.' '.$time;
                $schedule_demo = NULL;
                $message = '';
            } elseif ($client_source == 'schedule_your_free_demo') {
               $ppc = 0;
               $schedule_demo = NULL;
               $message = $this->input->post('client_message');
            }
            

            $this->Demo_model->free_demo_new($first_name, $email, $phone_number, $company_name, $date_requested, $schedule_demo, $client_source, $ppc, $message, $company_size, $newsletter_subscribe, $job_role);
            $replacement_array['name'] = $first_name;
            $replacement_array['firstname'] = $first_name;
            $replacement_array['first_name'] = $first_name;
            $replacement_array['first-name'] = $first_name;
            $replacement_array['date_time'] = $schedule_demo;
            log_and_send_templated_email(DEMO_REQUEST_THANKYOU, $email, $replacement_array);

            if ($client_source == 'demo') {
                redirect('/thank_you', 'refresh');
            } elseif ($client_source == 'schedule_your_free_demo') {
                $this->session->set_flashdata('message', '<strong>Success: </strong> Schedule Successfully Saved');
                redirect('/', 'refresh');
            }
        }
    }

    public function recaptcha($str) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Les2Q0TAAAAAPpmnngcC7RdzvAq1CuAVLqic_ei';
        $url = $google_url . "?secret=" . $secret . "&response=" . $str;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        
        if ($res['success']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
            return $str;
        }
    }
    
    function check_already_applied() {
        if ($this->input->post('email')) {
            $email = $this->input->post('email');
            $result = $this->Demo_model->check_reffer_affiliater($email);
            if ($result > 0) {
                $this->form_validation->set_message('email', 'You already applied for demo, we will get back to you');
                $this->session->set_flashdata('message', '<strong>Warning: </strong> You already applied for demo, we will get back to you');
                echo json_encode(0);
            } else {
                echo json_encode(1);
            }
        }

    }

    function thank_you() {
        $data['title']                                                          = 'Schedule Your free demo';
        $data_countries                                                         = db_get_active_countries();
        
        if($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details; 
        } else {
            $home_page['header_video_flag'] = 0;
            $home_page['header_banner'] = 'aaaa';
            $home_page['header_sub_text'] = 'bbbb';
            $home_page['header_text'] = 'cccc';
            $data['home_page'] = $home_page;
        }

            $this->load->view('main/header',$data);
            $this->load->view('static-pages/thank_you');
            $this->load->view('main/footer');
    }

}
