<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('Users_model');
    }
    public function index($keyword = null) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['page_title'] = 'Dashboard';
            $name = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $name['affiliate_users']['full_name'];
            $data['session'] = $name;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $this->load->view('main/header', $data);
            $this->load->view('dashboard/dashboard_view');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }
    
    public function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
    {
        require_once(APPPATH . 'libraries/aws/aws.php');
        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $last_index_of_dot = strrpos($_FILES[$file_input_id]["name"], '.') + 1;
            $file_ext = substr($_FILES[$file_input_id]["name"], $last_index_of_dot, strlen($_FILES[$file_input_id]["name"]) - $last_index_of_dot);
            $file_name = trim($document_name . '-' . $suffix);
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = strtolower($file_name);
            $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
            $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_ext;
            $aws = new AwsSdk();
            $aws->putToBucket($new_file_name, $_FILES[$file_input_id]["tmp_name"], $bucket_name);
            return $new_file_name;
        } else {
            return 'error';
        }
    }

    public function forgot_password() {      
        $config = array(
                        array(
                            'field' => 'email',
                            'label' => 'Email',
                            'rules' => 'trim|required|valid_email|xss_clean'
                        )
                    );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Forgot Password";
            $this->load->view('main/header', $data);
            $this->load->view('users/forgot_password');
            $this->load->view('main/footer');
        } else {
            $email = $this->input->post('email');
            $result = $this->Users_model->check_email($email);
            
            if ($result) { 
                $this->session->set_flashdata('message', 'Sorry! Account not found!');
            } else { 
                $username = $this->Users_model->get_user_name($email);
                
                if (!empty($username['username'])) {
                    $random_string = generateRandomString(12);
                    $this->Users_model->varification_key($email, $random_string); // update activation code for current user record in table                
                    $user_data = $this->Users_model->email_user_data($email);
                    $this->session->set_flashdata('message', 'Check Your Email and follow link to Reset Your password.');
                    $url = base_url() . 'dashboard/change_password/' . $user_data["username"] . '/' . $user_data["activation_code"];                
                    $emailTemplateBody = 'Dear ' . $user_data["full_name"] . ', <br>';
                    $emailTemplateBody = $emailTemplateBody . 'You can change your password by following the link below : ' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . 'Your username is : ' . $user_data["username"] . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '<a href="'.$url.'">Change Your Password</a><br><br>';
                    $from = TO_EMAIL_DEV; //$emailTemplateData['from_email'];
                    $to = $email;
                    $subject = 'Password Recovery'; //$emailTemplateData['subject'];
                    $from_name = ucwords(STORE_DOMAIN); //$emailTemplateData['from_name'];
                    
                    $body = EMAIL_HEADER
                            . $emailTemplateBody
                            . EMAIL_FOOTER;
                    sendMail($from, $to, $subject, $body, $from_name);

                    $emailData = array(
                                        'date' => date('Y-m-d H:i:s'),
                                        'subject' => $subject,
                                        'email' => $to,
                                        'message' => $body,
                                        'username' => $user_data['sid'],
                                    );
                    $this->Users_model->save_email_logs($emailData);
                } else {
                    $this->session->set_flashdata('message', 'Sorry! Account not found!');
                }
                
            }          
            redirect('dashboard/forgot_password');
        }
    }

    public function change_password($user = NULL, $key = NULL) {
        if ($this->session->userdata('logged_in')) { 
            redirect('dashboard', 'refresh');
        }
        
        $data['verification'] = $this->Users_model->varification_user_key($user, $key);
        
        if($data['verification'] == false){
            redirect('login');
        }
        
        $config = array(
                        array(
                            'field' => 'password',
                            'label' => 'Password',
                            'rules' => 'md5|trim|required|xss_clean'
                        ),
                        array(
                            'field' => 'retypepassword',
                            'label' => 'Re Enter Passsword',
                            'rules' => 'md5|trim|required|xss_clean'
                        )
                    );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if($this->form_validation->run() == FALSE) {
            $retrn = $this->Users_model->varification_user_key($user, $key);
            $data['page_title'] = "Change Password";
            $this->load->view('main/header', $data);
            $this->load->view('users/change_password');
            $this->load->view('main/footer');
        } else {            
            $password = $this->input->post('password');
            $re_password = $this->input->post('retypepassword');
            
            if($password == $re_password) {
                $this->Users_model->change_password($password, $user, $key);
                $this->Users_model->reset_key($user);
                $user_data = $this->Users_model->username_user_data($user);
                
                $from = TO_EMAIL_DEV; 
                $to = $user_data['email'];
                $subject = 'Password Changed Successfully'; 
                $from_name = ucwords(STORE_DOMAIN); 
                
                $emailTemplateBody = 'Dear ' . $user_data["full_name"] . ', <br>';
                $emailTemplateBody = $emailTemplateBody . 'Your Password has been successfully Updated.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please login using this : <a href="'.STORE_FULL_URL_SSL.'/login">Link</a><br><br>';
                $emailTemplateBody = $emailTemplateBody . 'We are glad you have chosen to be a part of '.ucwords(STORE_DOMAIN).'.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please visit us often.<br>';
                $emailTemplateBody = $emailTemplateBody . ucwords(STORE_DOMAIN).' is a dynamic environment, with many changes and updates happening every day.<br><br>';
                $emailTemplateBody = $emailTemplateBody . 'We are here to help you Succeed!!.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please Email or Call us any time with questions or comments.<br>';
                $emailTemplateBody = $emailTemplateBody . 'We would love to hear from you.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Thank You<br>';
                
                $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);

                $emailData = array(
                                    'date' => date('Y-m-d H:i:s'),
                                    'subject' => $subject,
                                    'email' => $to,
                                    'message' => $body,
                                    'username' => $user_data['sid'],
                                );
                $this->Users_model->save_email_logs($emailData);
            }
            $this->session->set_flashdata('message', 'Your Password has been changed Successfully!');
            redirect('login');
        }
    }
    
}