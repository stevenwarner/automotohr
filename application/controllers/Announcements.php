<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Announcements extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('announcement_model');
        $this->load->library("pagination");
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('announcements')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $employer_detail  = $data['session']['employer_detail'];
            $data['employee'] = $employer_detail;
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'announcements');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id  = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Announcements Management";
            $data['events_count'] = $this->announcement_model->get_all_events_count($company_id);
            $data['events'] = $this->announcement_model->get_all_events($company_id);
            $access_level           = $employer_detail['access_level'];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = false;
            if(!check_company_ems_status($company_id)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }
//            if(strtolower($access_level) == 'employee') {
//                $this->load->view('onboarding/on_boarding_header', $data);
//                $this->load->view('announcements/events_list_ems');
//                $this->load->view('onboarding/on_boarding_footer');
//            }else{
            $this->load->view('main/header', $data);
            $this->load->view('announcements/events_list');
            $this->load->view('main/footer');
//            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_announcements() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $employer_detail  = $data['session']['employer_detail'];
            $data['employee'] = $employer_detail;
            $data['employer'] = $employer_detail;
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'support_tickets');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id  = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Announcements";
            $data['events_count'] = $this->announcement_model->get_all_events_count($company_id, $security_sid);
            $data['events'] = $this->announcement_model->get_all_events($company_id, $security_sid);
            $access_level  = $employer_detail['access_level'];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            if(!check_company_ems_status($company_id)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

//            if(strtolower($access_level) == 'employee') {
//                $this->load->view('onboarding/on_boarding_header', $data);
//                $this->load->view('announcements/events_list_ems');
//                $this->load->view('onboarding/on_boarding_footer');
//            }else{

                $this->load->view('main/header', $data);
                $this->load->view('announcements/events_list_old');
                $this->load->view('main/footer');
//            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit($event_id) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'edit_announcements');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $CompanyName = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'Edit Announcement';
            $event = $this->announcement_model->get_event_by_id($event_id);
            $data['event'] = $event;
            $all_employees = $this->announcement_model->get_all_employees($company_id);
            $data['all_emp'] = $all_employees;
            $employer_detail  = $data['session']['employer_detail'];
            $data['employee'] = $employer_detail;
            $access_level     = $employer_detail['access_level'];
            $previously_sent_notifications = ($event[0]['announcement_for'] != NULL && $event[0]['announcement_for'] != 0) ? explode(',',$event[0]['announcement_for']) : 0;
            if(!check_company_ems_status($company_id)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }
            $config = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'xss_clean|trim|required'
                )
            );
            
            $data['load_view'] = false;
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $related_documented = $this->announcement_model->fetch_related_documents($event_id);
            $data['related_documented'] = $related_documented;
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('announcements/add_event');
                $this->load->view('main/footer');
            } else {
                //
                $uploadedFiles = array();
                //
                $formpost = $this->input->post(NULL, TRUE);
                //
                foreach ($related_documented as $k => $v) $uploadedFiles[] = array( 'file' => AWS_S3_BUCKET_URL.$v['document_code'], 'name' => $v['document_code']);
                
//                echo '<pre>';
//                print_r($formpost);
//                die();
                $insert_array = array(); // for insertion into tickets table
                $insert_array['title'] = $formpost['title'];
                $insert_array['type']  = $formpost['type'];
                $insert_array['message']  = html_entity_decode($this->input->post('message', false));
                $insert_array['status']  = $formpost['status'];
                $insert_array['company_sid'] = $company_id;
                $insert_array['created_by'] = $employer_id;
                $insert_array['display_start_date'] = empty($formpost['display_start_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['display_start_date'])->format('Y-m-d 00:00:00');
                $insert_array['display_end_date']  = empty($formpost['display_end_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['display_end_date'])->format('Y-m-d 23:59:59');
                $insert_array['new_hire_joining_date'] = empty($formpost['new_hire_joining_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['new_hire_joining_date'])->format('Y-m-d 00:00:00');
                $insert_array['event_start_date'] = empty($formpost['event_start_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['event_start_date'])->format('Y-m-d 00:00:00');
                $insert_array['event_end_date'] = empty($formpost['event_end_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['event_end_date'])->format('Y-m-d 23:59:59');
                $insert_array['announcement_for'] = isset($formpost['announcement_for']) && !in_array(0,$formpost['announcement_for']) ? implode(',',$formpost['announcement_for']) : 0;
                $insert_array['new_hire_name'] = ($formpost['type'] == 'New Hire') ? $formpost['new_hire_name'] : '';
                $insert_array['new_hire_bio'] = $formpost['new_hire_bio'];
                $insert_array['new_hire_job_position'] = $formpost['new_hire_job_position'];
                $insert_array['created_date'] = date('Y-m-d H:i:s');
                $insert_array['department_team_sid'] = empty($formpost['dep-team']) ? NULL : $formpost['dep-team'];
                $video_url = $this->input->post('video_url');
                $video_source = $insert_array['section_video_source'] = $this->input->post('video_source');
                $insert_array['section_image_status'] = $this->input->post('banner_status');
                $insert_array['section_video_status'] = $this->input->post('video_status');
                $image = upload_file_to_aws('section_image', $company_id, 'announcement_section_image', '');
                $section_image = '';

                if ($image != 'error') {
                    $section_image = $image;
                }

                $remove_flag = false;

                if(!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                    $random = generateRandomString(5); 
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id.'/'.$random.'_'.$target_file_name); 
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir.$company_id;
                    
                    if (!file_exists($filename)){
                        mkdir($filename);
                    }
                
                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file '. basename( $_FILES["video_upload"]["name"]). ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('announcements', 'refresh');
                    }
                    
                    $insert_array['section_video'] = $file_name; 
                    $remove_flag = true;             
                } elseif (!empty($video_url)) {
                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);
                        if (isset($url_prams['v'])) {
                            $video_url = $url_prams['v'];
                        } else {
                            $video_url = '';
                        }
                    } elseif ($video_source == 'vimeo') {
                        $video_url = (int)substr(parse_url($video_url, PHP_URL_PATH), 1);
                    }
                    $insert_array['section_video'] = $video_url;
                    $remove_flag = true;
                } else {
                    if($video_source == 'upload_video') {
                        $insert_array['section_video'] = $formpost['old_upload_video'];
                    } else {
                        $insert_array['section_video'] = '';
                    } 
                    $remove_flag = false;
                }

                if ($section_image != '') {
                    $insert_array['section_image'] = $section_image;
                }  

                if ($remove_flag == true){
                    $video_source_name = $this->announcement_model->get_announcement_video_source($event_id);
                    $previous_source = $video_source_name[0]['section_video_source'];

                    if ($previous_source == 'upload_video') {
                        $video_url = 'assets/uploaded_videos/' . $video_source_name[0]['section_video'];
                        unlink($video_url);
                    }
                }

                $this->announcement_model->update_event($insert_array,$event_id);

                $department_team_sid = empty($formpost['dep-team']) ? NULL : $formpost['dep-team'];
                if( !empty($_FILES) && isset($_FILES['document_upload'])) {
                    foreach($_FILES['document_upload']['name'] as $key => $document){
                        $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));

                        if($_FILES['document_upload']['size'][$key] > 0){
                            if($ext == "mp4" || $ext == "m4a" || $ext == "m4v" || $ext == "f4v" || $ext == "f4a" || $ext == "m4b" || $ext == "m4r" || $ext == "f4b" || $ext == "mov" || $ext == 'mp3'){

                                $random = generateRandomString(5);
                                $company_id = $data['session']['company_detail']['sid'];
                                $target_file_name = basename($_FILES["document_upload"]["name"][$key]);
                                $file_name = strtolower($company_id.'/'.$random.'_'.$target_file_name);
                                $target_dir = "assets/uploaded_videos/";
                                $target_file = $target_dir . $file_name;
                                $filename = $target_dir.$company_id;

                                if (!file_exists($filename)){
                                    mkdir($filename);
                                }
                                if(!move_uploaded_file($_FILES["document_upload"]["tmp_name"][$key], $target_file)){
                                    $file_name = '';
                                }
                            } else{
                                if($_SERVER['HTTP_HOST']=='localhost'){
                                    // $aws_file_name = '0003-d_6-1542874444-39O.jpg';
                                    // $aws_file_name = '0057-testing_uploaded_doc-58-AAH.docx';
                                    $aws_doc = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                                } else {
                                    $aws_doc = upload_file_to_aws('document_upload', $company_id, 'announcement_documents', time(), AWS_S3_BUCKET_NAME, $key);
                                }
                                $file_name = '';

                                if ($aws_doc != 'error') {
                                    $file_name = $aws_doc;
                                }
                            }

                            if($file_name != ''){
                                $announcement_document = array();
                                $announcement_document['document_name'] = $_FILES['document_upload']['name'][$key];
                                $announcement_document['document_code'] = $file_name;
                                $announcement_document['document_type'] = $ext;
                                $announcement_document['added_date'] = date('Y-m-d H:i:s');
                                $announcement_document['announcement_sid'] = $event_id;
                                $this->announcement_model->add_announcement_document($announcement_document);
                                //
                                //
                                $uploadedFiles[] = array( 'file' => AWS_S3_BUCKET_URL.$file_name, 'name' => $file_name);
                            }
                        }
                    }
                }
                $documented_emp = array();

                if($this->input->post('send_email') == 'yes'){

                    $message_hf = message_header_footer($company_id, $CompanyName);

                    $company_sms_notification_status = get_company_sms_status($this, $company_id);

                    //, 
                    $toEmployees = explode(',', $insert_array['announcement_for']);
                    //
                    foreach($all_employees as $employee){
                        //
                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                        if(!$this->HRDMM->isActiveUser($employee['sid'])){
                            continue;
                        }
                        //
                        if($toEmployees[0] != 0 && !in_array($employee['sid'], $toEmployees)) continue;

                        $replacement_array = array();
                        $replacement_array['event_start_date'] = (empty($formpost['event_start_date']) && $formpost['type'] != 'New Hire') ? '' : reset_datetime(array('datetime' => $formpost['event_start_date'], '_this' => $this), true);
                        $replacement_array['employer_name'] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                        $replacement_array['contact-name'] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                        $replacement_array['company_name'] = ucwords($CompanyName);
                        $replacement_array['title'] = $formpost['title'];
                        $replacement_array['firstname'] = $employee['first_name'];
                        $replacement_array['lastname'] = $employee['last_name'];
                        $replacement_array['first_name'] = $employee['first_name'];
                        $replacement_array['last_name'] = $employee['last_name'];
                        $replacement_array['event_body'] = $formpost['message'];
                        $replacement_array['announcement_link'] = base_url('announcements/view').'/'.$event_id;
                        $f = '';
                        if(count($uploadedFiles)){
                            $f = '<p>Below are the attachments:</p>';
                            foreach($uploadedFiles as $filo){
                                $f .= '<a href="'.($filo['file']).'">'.($filo['name']).'</a> <br />';
                            }
                        }
                        $replacement_array['attachments'] = $f;
                        //Check if email and sms is sent to this employee previously or not
                        if(is_array($previously_sent_notifications) && !in_array($employee, $previously_sent_notifications)){
                            //SMS Start
                            if($company_sms_notification_status){
                                $notify_by = get_employee_sms_status($this, $employee['sid']);
                                $sms_notify = 0 ;
                                if(strpos($notify_by['notified_by'],'sms') !== false){
                                    $contact_no = $notify_by['PhoneNumber'];
                                    $sms_notify = 1;
                                }
                                if($sms_notify){
                                    $this->load->library('Twilioapp');
                                    // Send SMS
                                    $sms_template = get_company_sms_template($this,$company_id,'new_announcement_notification');
                                    $replacement_sms_array = array(); //Send Announcement Notification to employee.
                                    $replacement_sms_array['applicant_name'] = $employee['first_name']. ' '. $employee['last_name'];
                                    $replacement_sms_array['contact_name'] = ucwords(strtolower($employee['first_name']. ' '. $employee['last_name']));
                                    $replacement_sms_array['company_name'] = ucwords(strtolower($CompanyName));
                                    $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_sms_array);
                                    sendSMS(
                                        $contact_no,
                                        $sms_body,
                                        trim(ucwords(strtolower($employee['username']))),
                                        $employee['email'],
                                        $this,
                                        $sms_notify,
                                        $company_id
                                    );
                                }
                            }
                            $emailTemplateData = get_email_template(EDIT_ANNOUNCEMENT_NOTIFICATION);
                            $emailTemplateBody = $emailTemplateData['text'];
                            $emailTemplateSubject = $emailTemplateData['subject'];
                            $emailTemplateFromName = $emailTemplateData['from_name'];

                            if (!empty($replacement_array)) {
                                foreach ($replacement_array as $key => $value) {
                                    $emailTemplateBody = str_replace('{{' . $key . '}}', ucwords($value), $emailTemplateBody);
                                    $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                    $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                }
                            }


                            $from = $emailTemplateData['from_email'];
                            $subject = $emailTemplateSubject;
                            $from_name = $emailTemplateData['from_name'];

                            if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
                                $from_name = $replacement_array['company_name'];
                            }

                            $body = $message_hf['header']
                                . $emailTemplateBody
                                . $message_hf['footer'];

                            $this->log_and_send_email_with_attachment($from, $employee['email'], $subject, $body, $from_name, $uploadedFiles);
                            
                        }else{
                            //Send Edited Email
                            $replacement_array['event_start_date'] = (empty($formpost['event_start_date']) && $formpost['type'] != 'New Hire') ? '' : reset_datetime(array('datetime' => $formpost['event_start_date'], '_this' => $this), true);
                            $replacement_array['event_end_date'] = (empty($formpost['event_end_date']) && $formpost['type'] != 'New Hire') ? '' : reset_datetime(array('datetime' => $formpost['event_end_date'], '_this' => $this), true);
                            $replacement_array['new_hire_date'] = (empty($formpost['new_hire_joining_date']) && $formpost['type'] != 'New Hire') ? '' : reset_datetime(array('datetime' => $formpost['new_hire_joining_date'], '_this' => $this), true);
                            $replacement_array['message'] = empty($formpost['message']) ? '' : $formpost['message'];
                            $replacement_array['new_hire_name'] = ($formpost['type'] == 'New Hire') ? $formpost['new_hire_name'] : '';
                            $replacement_array['new_hire_position'] = (!empty($formpost['new_hire_job_position']) && $formpost['type'] == 'New Hire') ? $formpost['new_hire_job_position'] : '';
                            $replacement_array['new_hire_bio'] = (!empty($formpost['new_hire_bio']) && $formpost['type'] == 'New Hire') ? $formpost['new_hire_bio'] : '';
                            //SMS Start
                            if($company_sms_notification_status){
                                $notify_by = get_employee_sms_status($this, $employee['sid']);
                                $sms_notify = 0 ;
                                if(strpos($notify_by['notified_by'],'sms') !== false){
                                    $contact_no = $notify_by['PhoneNumber'];
                                    $sms_notify = 1;
                                }
                                if($sms_notify){
                                    $this->load->library('Twilioapp');
                                    // Send SMS
                                    $sms_template = get_company_sms_template($this,$company_id,'edit_announcement_notification');
                                    $replacement_sms_array = array(); //Send Announcement Notification to employee.
                                    $replacement_sms_array['applicant_name'] = $employee['first_name']. ' '. $employee['last_name'];
                                    $replacement_sms_array['contact_name'] = ucwords(strtolower($employee['first_name']. ' '. $employee['last_name']));
                                    $replacement_sms_array['company_name'] = ucwords(strtolower($CompanyName));
                                    $replacement_sms_array['title'] = $formpost['title'];
                                    $replacement_sms_array['event_start_date'] = (empty($formpost['event_start_date']) && $formpost['type'] != 'New Hire') ? '' : ('Start Time: '.reset_datetime(array('datetime' => $formpost['event_start_date'], '_this' => $this), true));
                                    $replacement_sms_array['event_end_date'] = (empty($formpost['event_end_date']) && $formpost['type'] != 'New Hire') ? '' : ('End Time: '.reset_datetime(array('datetime' => $formpost['event_end_date'], '_this' => $this), true));
                                    $replacement_sms_array['new_hire_name'] = ($formpost['type'] == 'New Hire') ? ('New Hire Name: '.$formpost['new_hire_name']) : '';
                                    $replacement_sms_array['new_hire_position'] = (!empty($formpost['new_hire_job_position']) && $formpost['type'] == 'New Hire') ? ('New Hire Position: '.$formpost['new_hire_job_position']) : '';
                                    $replacement_sms_array['new_hire_date'] = (empty($formpost['new_hire_joining_date']) && $formpost['type'] != 'New Hire') ? '' : ('Joining Date: '.reset_datetime(array('datetime' => $formpost['new_hire_joining_date'], '_this' => $this), true));

                                    $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_sms_array);
                                    sendSMS(
                                        $contact_no,
                                        $sms_body,
                                        trim(ucwords(strtolower($employee['username']))),
                                        $employee['email'],
                                        $this,
                                        $sms_notify,
                                        $company_id
                                    );
                                }
                            }
                            $emailTemplateData = get_email_template(EDIT_ANNOUNCEMENT_NOTIFICATION);
                            $emailTemplateBody = $emailTemplateData['text'];
                            $emailTemplateSubject = $emailTemplateData['subject'];
                            $emailTemplateFromName = $emailTemplateData['from_name'];

                            if (!empty($replacement_array)) {
                                foreach ($replacement_array as $key => $value) {
                                    $emailTemplateBody = str_replace('{{' . $key . '}}', ucwords($value), $emailTemplateBody);
                                    $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                    $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                }
                            }
                            $from = $emailTemplateData['from_email'];
                            $subject = $emailTemplateSubject;
                            $from_name = $emailTemplateData['from_name'];

                            if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
                                $from_name = $replacement_array['company_name'];
                            }

                            $body = $message_hf['header']
                                . $emailTemplateBody
                                . $message_hf['footer'];
                            
                            $this->log_and_send_email_with_attachment($from, $employee['email'], $subject, $body, $from_name, $uploadedFiles);
                            
                        }
                    }
                }

                //Send Emails Through System Notifications Email - End
                $this->session->set_flashdata('message', '<b>Success:</b> Event Updated Successfully');
                redirect("announcements");           
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view($event_id) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'support_tickets');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'Announcement';
            $data['event'] = $this->announcement_model->get_event_by_id($event_id);
            $data['all_emp'] = $this->announcement_model->get_all_employees($company_id);
            $employer_detail  = $data['session']['employer_detail'];
            $data['employee'] = $employer_detail;
            $data['employer'] = $employer_detail;
            $access_level     = $employer_detail['access_level'];

            if(!check_company_ems_status($company_id)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            $validate_video_status = $this->announcement_model->validate_affiliate_video_status($event_id);

            if (!empty($validate_video_status[0]['section_video_source']) && $validate_video_status[0]['section_video_status'] == 1){
                
                $selected_source = $validate_video_status[0]['section_video_source'];
                $data['video_url'] = $validate_video_status[0]['section_video'];
                $data['validate_flag'] = true;
                $data['video_source'] = $validate_video_status[0]['section_video_source'];

            } else {
                $data['validate_flag'] = false;
            }

            if ($validate_video_status[0]['section_image_status'] == 1) {
                $data['validate_image_flag'] = true;
            } else {
                $data['validate_image_flag'] = false;
            }

            $department_team_sid = $data['event'][0]['department_team_sid'];
            $documented_emp = array();
            $related_documented = array();
            $related_documented = $this->announcement_model->fetch_related_documents($event_id);
            
            $data['related_documented'] = $related_documented;
            $this->announcement_model->update_event(array('is_pending' => 0),$event_id);
            $this->load->view('main/header', $data);
            $this->load->view('announcements/view_event_old');
            $this->load->view('main/footer');

        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'make_announcements');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id  = $data['session']['company_detail']['sid'];
            $CompanyName = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'Make Announcement';

            $config = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'xss_clean|trim|required'
                )
            );
            if(!check_company_ems_status($company_id)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $all_employees = $this->announcement_model->get_all_employees($company_id);
            $data['all_emp'] = $all_employees;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('announcements/add_event');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $insert_array = array(); // for insertion into tickets table
                $insert_array['title'] = $formpost['title'];
                $insert_array['type']  = $formpost['type'];
                $insert_array['message']  = html_entity_decode($this->input->post('message', false));
                $insert_array['status']  = $formpost['status'];
                $insert_array['company_sid'] = $company_id;
                $insert_array['created_by'] = $employer_id;
                $insert_array['display_start_date'] = empty($formpost['display_start_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['display_start_date'])->format('Y-m-d 00:00:00');
                $insert_array['display_end_date']  = empty($formpost['display_end_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['display_end_date'])->format('Y-m-d 23:59:59');
                $insert_array['new_hire_joining_date'] = empty($formpost['new_hire_joining_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['new_hire_joining_date'])->format('Y-m-d 00:00:00');
                $insert_array['event_start_date'] = empty($formpost['event_start_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['event_start_date'])->format('Y-m-d 00:00:00');
                $insert_array['event_end_date'] = empty($formpost['event_end_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['event_end_date'])->format('Y-m-d 23:59:59');
                $insert_array['announcement_for'] = in_array(0,$formpost['announcement_for']) ? 0 : implode(',',$formpost['announcement_for']);
                $insert_array['new_hire_name'] = $formpost['type'] == 'New Hire' ? $formpost['new_hire_name'] : '';
                $insert_array['new_hire_bio'] = $formpost['new_hire_bio'];
                $insert_array['new_hire_job_position'] = $formpost['new_hire_job_position'];
                $insert_array['created_date'] = date('Y-m-d H:i:s');
                $insert_array['department_team_sid'] = empty($formpost['dep-team']) ? NULL : $formpost['dep-team'];

                $video_url = $this->input->post('video_url');
                $video_source = $insert_array['section_video_source'] = $this->input->post('video_source');
                $insert_array['section_image_status'] = $this->input->post('banner_status');
                $insert_array['section_video_status'] = $this->input->post('video_status');

                $image = upload_file_to_aws('section_image', $company_id, 'announcement_section_image', '');
                $section_image = '';

                if ($image != 'error') {
                    $section_image = $image;
                }

                if(!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {

                    $random = generateRandomString(5); 
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id.'/'.$random.'_'.$target_file_name); 
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir.$company_id;
                    
                    if (!file_exists($filename)){
                        mkdir($filename);
                    }
                
                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file '. basename( $_FILES["video_upload"]["name"]). ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('announcements', 'refresh');
                    }
                    
                    $insert_array['section_video'] = $file_name;              
                } elseif (!empty($video_url)) {
                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);
                        if (isset($url_prams['v'])) {
                            $video_url = $url_prams['v'];
                        } else {
                            $video_url = '';
                        }
                    } elseif ($video_source == 'vimeo') {
                        $video_url = (int)substr(parse_url($video_url, PHP_URL_PATH), 1);
                    }
                    $insert_array['section_video'] = $video_url;
                } else {
                    $insert_array['section_video'] = '';
                }

                if ($section_image != '') {
                    $insert_array['section_image'] = $section_image;
                }
                $insert_id = $this->announcement_model->add_event($insert_array);

                $uploadedFiles = array();
                //Add files for department teams
                $department_team_sid = empty($formpost['dep-team']) ? NULL : $formpost['dep-team'];
                if(!empty($_FILES) && isset($_FILES['document_upload'])) {
                    foreach($_FILES['document_upload']['name'] as $key => $document){
                        $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));

                        if($_FILES['document_upload']['size'][$key] > 0){
                            if($ext == "mp4" || $ext == "m4a" || $ext == "m4v" || $ext == "f4v" || $ext == "f4a" || $ext == "m4b" || $ext == "m4r" || $ext == "f4b" || $ext == "mov" || $ext == 'mp3'){

                                $random = generateRandomString(5);
                                $company_id = $data['session']['company_detail']['sid'];
                                $target_file_name = basename($_FILES["document_upload"]["name"][$key]);
                                $file_name = strtolower($company_id.'/'.$random.'_'.$target_file_name);
                                $target_dir = "assets/uploaded_videos/";
                                $target_file = $target_dir . $file_name;
                                $filename = $target_dir.$company_id;

                                if (!file_exists($filename)){
                                    mkdir($filename);
                                }
                                if(!move_uploaded_file($_FILES["document_upload"]["tmp_name"][$key], $target_file)){
                                    $file_name = '';
                                }
                            } else{
                                if($_SERVER['HTTP_HOST']=='localhost'){
                                    // $aws_file_name = '0003-d_6-1542874444-39O.jpg';
                                    // $aws_file_name = '0057-testing_uploaded_doc-58-AAH.docx';
                                    $aws_doc = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                                } else {
                                    $aws_doc = upload_file_to_aws('document_upload', $company_id, 'announcement_documents', time(), AWS_S3_BUCKET_NAME, $key);
                                }
                                $file_name = '';

                                if ($aws_doc != 'error') {
                                    $file_name = $aws_doc;
                                }
                            }

                            if($file_name != ''){
                                $announcement_document = array();
                                $announcement_document['document_name'] = $_FILES['document_upload']['name'][$key];
                                $announcement_document['document_code'] = $file_name;
                                $announcement_document['document_type'] = $ext;
                                $announcement_document['added_date'] = date('Y-m-d H:i:s');
                                $announcement_document['announcement_sid'] = $insert_id;
                                $this->announcement_model->add_announcement_document($announcement_document);

                                //
                                $uploadedFiles[] = array( 'file' => AWS_S3_BUCKET_URL.$file_name, 'name' => $file_name);
                            }
                        }
                    }
                }

                $documented_emp = array();

                if($this->input->post('send_email') == 'yes'){
                    $company_sms_notification_status = get_company_sms_status($this, $company_id);
                    $message_hf = message_header_footer($company_id, $CompanyName);
                    //, 
                    $toEmployees = explode(',', $insert_array['announcement_for']);
                    //
                    foreach($all_employees as $employee){
                        //
                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                        if(!$this->HRDMM->isActiveUser($employee['sid'])){
                            continue;
                        }
                        //
                        if($toEmployees[0] != 0 && !in_array($employee['sid'], $toEmployees)) continue;

                        $replacement_array = array();
                        $replacement_array['event_start_date'] = (empty($formpost['display_start_date'])) ? '' : reset_datetime(array('datetime' => $formpost['display_start_date'], '_this' => $this), true);
                        $replacement_array['employer_name'] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                        $replacement_array['contact-name'] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                        $replacement_array['company_name'] = ucwords($CompanyName);
                        $replacement_array['title'] = $formpost['title'];
                        $replacement_array['firstname'] = $employee['first_name'];
                        $replacement_array['lastname'] = $employee['last_name'];
                        $replacement_array['first_name'] = $employee['first_name'];
                        $replacement_array['last_name'] = $employee['last_name'];
                        $replacement_array['event_body'] = $formpost['message'];
                        $replacement_array['announcement_link'] = base_url('announcements/view').'/'.$insert_id;
                        $f = '';
                        if(count($uploadedFiles)){
                            $f = '<p>Below are the attachments:</p>';
                            foreach($uploadedFiles as $filo){
                                $f .= '<a href="'.($filo['file']).'">'.($filo['name']).'</a> <br />';
                            }
                        }
                        $replacement_array['attachments'] = $f;

                        //SMS Start
                        if($company_sms_notification_status){
                            $notify_by = get_employee_sms_status($this, $employee['sid']);
                            $sms_notify = 0 ;
                            if(strpos($notify_by['notified_by'],'sms') !== false){
                                $contact_no = $notify_by['PhoneNumber'];
                                $sms_notify = 1;
                            }
                            if($sms_notify){
                                $this->load->library('Twilioapp');
                                // Send SMS
                                $sms_template = get_company_sms_template($this,$company_id,'new_announcement_notification');
                                $replacement_sms_array = array(); //Send Announcement Notification to employee.
                                $replacement_sms_array['applicant_name'] = $employee['first_name']. ' '. $employee['last_name'];
                                $replacement_sms_array['contact_name'] = ucwords(strtolower($employee['first_name']. ' '. $employee['last_name']));
                                $replacement_sms_array['company_name'] = ucwords(strtolower($CompanyName));
                                $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_sms_array);
                                sendSMS(
                                    $contact_no,
                                    $sms_body,
                                    trim(ucwords(strtolower($employee['username']))),
                                    $employee['email'],
                                    $this,
                                    $sms_notify,
                                    $company_id
                                );
                            }
                        }

                        $emailTemplateData = get_email_template(NEW_ANNOUNCEMENT_NOTIFICATION);
                        $emailTemplateBody = $emailTemplateData['text'];
                        $emailTemplateSubject = $emailTemplateData['subject'];
                        $emailTemplateFromName = $emailTemplateData['from_name'];

                        if (!empty($replacement_array)) {
                            foreach ($replacement_array as $key => $value) {
                                $emailTemplateBody = str_replace('{{' . $key . '}}', ucwords($value), $emailTemplateBody);
                                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                            }
                        }


                        $from = $emailTemplateData['from_email'];
                        $subject = $emailTemplateSubject;
                        $from_name = $emailTemplateData['from_name'];

                        if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
                            $from_name = $replacement_array['company_name'];
                        }

                        $body = $message_hf['header']
                            . $emailTemplateBody
                            . $message_hf['footer'];

                        
                        
                        $this->log_and_send_email_with_attachment($from, $employee['email'], $subject, $body, $from_name, $uploadedFiles);       
                    }
                }
                
                $this->session->set_flashdata('message', '<b>Success:</b> New Event added successfully');
                redirect("announcements");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function log_and_send_email_with_attachment($from, $to, $subject, $body, $senderName, $files = array()) {
        $emailData = array( 'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $senderName);
        save_email_log_common($emailData);
        // if (base_url() != STAGING_SERVER_URL) {
            
            sendMailWithAttachment($from, $to, $subject, $body, $senderName, sizeof($files) ? $files : $_FILES['document_upload'], REPLY_TO, false);
        // }
    }

    public function change_status(){
        $action = $this->input->post('action');
        $id = $this->input->post('id');
        if($action == 'disable'){
            $update = array('status' => 0);
        } else{
            $update = array('status' => 1);
        }

        $this->announcement_model->update_event($update,$id);
        echo 'success';
    }

    public function ajax_handler(){
        if($this->input->is_ajax_request()){
            $data['session'] = $this->session->userdata('logged_in');
            $company_id  = $data['session']['company_detail']['sid'];
            $departments = $this->announcement_model->get_all_departments_team($company_id); //Fetch All Departments
            $resultant_array = array();
            if(sizeof($departments)> 0){
                foreach($departments as $department){
                    $resultant_array[$department['dept_name']][] = array('sid' => $department['sid'], 'name' => $department['team_name']);
                }
            }
            echo json_encode($resultant_array);
        }
    }

    public function delete_record_ajax(){
        if($this->input->is_ajax_request()){
            $file_id = $this->input->post('id');

            $this->announcement_model->delete_file($file_id);
            echo 'Deleted';
        }
    }
}