<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Affiliates extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('affiliation_model');
    }

    public function index() {
        // die('pakistan');
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        $data['title'] = "Affiliates";
        $data['home_page'] = array( 'header_video_flag' => 0,
                                    'header_text' => 'Can we send you a check every month?',
                                    'header_sub_text' => 'Earn ongoing monthly income by joining the free AutomotoHR<br>Affiliate Program',
                                    'header_banner' => 'Screen Shot 2018-06-01 at 1_POHNneDrSC.png',
                                    'sign_up_btn' => 1);

        $validate_video_status = $this->affiliation_model->validate_affiliate_video_status(2);

        if (!empty($validate_video_status[0]['video_source']) && $validate_video_status[0]['status'] == 1) {
            
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

        $validate_body_video_status = $this->affiliation_model->validate_affiliate_video_status(4);

        if (!empty($validate_body_video_status[0]['video_source']) && $validate_body_video_status[0]['status'] == 1) {
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
        
        $this->load->view('main/header', $data);
        $this->load->view('affiliates/index');
        $this->load->view('main/footer');
    }

    public function affiliationform() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        $data['title'] = 'Affiliates';
        $data['home_page'] = array( 'header_video_flag' => 0,
                                    'header_text' => 'Earn Rewards',
                                    'header_sub_text' => 'Earn ongoing monthly income by joining the free AutomotoHR Affiliate Program',
                                    'header_banner' => 'Screen Shot 2018-06-01 at 1_POHNneDrSC.png',
                                    'sign_up_btn' => 0);

        $field = array( 'field' => 'firstname',
                        'label' => 'First Name',
                        'rules' => 'xss_clean|trim|required');
        
        $order_field = array(   'field' => 'lastname',
                                'label' => 'Last Name',
                                'rules' => 'xss_clean|trim|required');

        $countries = $this->affiliation_model->get_all_countries();
        $data['countries'] = $countries;
        $config[] = $field;
        $config[] = $field;
        $config[] = $order_field;

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);

        $validate_video_status = $this->affiliation_model->validate_affiliate_video_status(2);
      
        if (!empty($validate_video_status[0]['video_source']) && $validate_video_status[0]['status'] == 1) {
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

        $validate_body_video_status = $this->affiliation_model->validate_affiliate_video_status(4);

        if (!empty($validate_body_video_status[0]['video_source']) && $validate_body_video_status[0]['status'] == 1) {
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

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('affiliates/affiliate_form');
            $this->load->view('main/footer');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            //
            if(!isset($formpost['g-recaptcha-response']) || empty($formpost['g-recaptcha-response'])){
                $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                return redirect('can-we-send-you-a-check-every-month', 'refresh');
            }
            //
            $gr = verifyCaptcha($formpost['g-recaptcha-response']);
            //
            if(!$gr['success']){
                $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                return redirect('can-we-send-you-a-check-every-month', 'refresh');
            }
            //
            $insert_data = array();
            $already_applied = $this->affiliation_model->check_register_affiliater($formpost['email']);
            
            if (sizeof($already_applied) > 0) {
                $this->session->set_flashdata('message', '<strong>Error: </strong>Affiliate Request Have Already Been Sent!');
                redirect('can-we-send-you-a-check-every-month', 'refresh');
            }
            
            $insert_data['first_name'] = $formpost['firstname'];
            $insert_data['last_name'] = $formpost['lastname'];
            $insert_data['email'] = $formpost['email'];
            $insert_data['paypal_email'] = $formpost['paypal_email'];
            $insert_data['company'] = $formpost['company'];
            $insert_data['street'] = $formpost['street'];
            $insert_data['city'] = $formpost['city'];
            $insert_data['state'] = $formpost['state'];
            $insert_data['zip_code'] = $formpost['zip'];
            $insert_data['country'] = $formpost['country'];
            $insert_data['method_of_promotion'] = $formpost['MOP'];
            $insert_data['website'] = $formpost['website'];
            $insert_data['special_notes'] = $formpost['info'];
            $insert_data['email_list'] = $formpost['no_of_names'];
            $insert_data['contact_number'] = $formpost['contact_number'];
            $insert_data['request_date'] = date('Y-m-d H:i:s');
            $insert_data['ip_address'] = getUserIP();
            $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            
            if (isset($_FILES['w8_form']) && $_FILES['w8_form']['name'] != '') {
                $w8_form = upload_file_to_aws('w8_form', generateRandomString(4), 'w8_form');

                if($w8_form != 'error') {
                    $insert_data['w8_form'] = $w8_form;
                }
            } 
            
            if (isset($_FILES['w9_form']) && $_FILES['w9_form']['name'] != '') {
                $w9_form = upload_file_to_aws('w9_form', generateRandomString(4), 'w9_form');

                if($w9_form != 'error') {
                    $insert_data['w9_form'] = $w9_form;
                }
            }
            

            $this->affiliation_model->insert_affiliation_form($insert_data);

            $from = FROM_EMAIL_DEV;
            //$to = TO_EMAIL_STEVEN;
            $subject = "Affiliate Program - New Request";
            $fromName = ucwords($insert_data['first_name'] . ' ' . $insert_data['last_name']);
            $replyTo = $insert_data['email'];

            $body = EMAIL_HEADER
                . '<h2 style="width:100%; margin:0 0 20px 0;">Affiliate Program Request!</h2>'
                . '<br><b>Applicant Name: </b>' . $fromName
                . '<br><b>Applicant Email: </b>' . $insert_data['email']
                . '<br><b>Contact Number: </b>' . $insert_data['contact_number']
                . '<br><b>Country: </b>' . $insert_data['country']
                . '<br>Login To Your Admin Panel For More Details'
                . EMAIL_FOOTER;

//            echo '<pre>';
//            print_r($body);
//            die();
            //sendMail($from, $to, $subject, $body, $fromName, $replyTo);
            //Send Emails Through System Notifications Email - Start
            $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');

            if (!empty($system_notification_emails)) {
                foreach ($system_notification_emails as $system_notification_email) {
                    sendMail($from, $system_notification_email['email'], $subject, $body, $fromName, $replyTo);
                }
            }

            $this->session->set_flashdata('message', '<strong>Success: </strong>Affiliate Request Submitted Successfully!');
            redirect('can-we-send-you-a-check-every-month', 'refresh');
        }
    }
}