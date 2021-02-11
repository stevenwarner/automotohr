<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_documents extends Public_Controller {
    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->load->model('hr_document_model');
            require_once(APPPATH . 'libraries/aws/aws.php');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function index() {
        if($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1)
            redirect('hr_documents_management');
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if($this->form_validation->run() == false) {
                $data['title'] = 'Admin HR Documents';
                $data['page'] = 'all';
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $data['dummyOfferLetter'] = $this->hr_document_model->getCompanyOfferLetters(0); //getting data of dummy offer letter
//                echo $this->db->last_query();
                if(!empty($data['dummyOfferLetter'])) {
                    $data['dummyOfferLetter'] = $data['dummyOfferLetter'][0];
                }

                $data['offerLetters'] = $this->hr_document_model->getCompanyOfferLetters($company_sid); //getting all offer letters of this company
                $data['documents'] = $this->hr_document_model->getAllActiveDocuments($company_sid);
                $data['allDocCount'] = count($data['documents']);
                $data['archiveDocCount'] = count($this->hr_document_model->getAllArchivedDocuments($company_sid));
                $data['docs'] = $this->hr_document_model->getAllEmployeesWithPendingAction($company_sid, 'document'); //getting all users which still have pending documents to Review Starts.
                $this->load->model('my_hr_document_model');
                $employeesInArray = array('');
                $i = 0;

                foreach ($data['docs'] as $key => $value) {
                    $result = $this->my_hr_document_model->removeVerificationKey($value['sid']);
                    
                    if ($result == "false") {
                        if (!in_array($value['receiver_sid'], $employeesInArray)) {
                            $employeesInArray[$i] = $value['receiver_sid'];
                            $i++;
                        }
                    }
                }

                $sections = $this->hr_document_model->get_hr_documents_section_records(1); //Get Editors Data
                $data['sections'] = $sections;
                $data['employees'] = $this->hr_document_model->getEmployeesDetails($employeesInArray);
//                echo '<pre>'; print_r($data); exit;
                $this->load->view('main/header', $data); //getting all users which still have pending documents to Review Ends
                $this->load->view('manage_employer/hr-document-list');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }//else end for session check fail
    }

    public function employee_document($employee_id = NULL) {
        if ($employee_id != NULL) {
            if ($this->session->has_userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
                $data['title'] = 'HR Documents';
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $data['docs'] = $this->hr_document_model->getEmployeePendingActionDocument($company_sid, 'document', $employee_id); //getting all users which still have pending documents to Review Starts.
                $data['documents']  = $data['docs'];
//                echo '<pre>'; print_r($data['docs']); echo '</pre>';
                
//                $this->load->model('my_hr_document_model');
//                $data['documents'] = array();
//                $i = 0;
//
//                foreach ($data['docs'] as $key => $value) {
//                    $result = $this->my_hr_document_model->removeVerificationKey($value['userDocumentSid']);
//                    
//                    if ($result == "false") {
//                        $data['documents'][$i] = $value;
//                        $i++;
//                    }
//                }

                $this->load->model('dashboard_model');
                $data['userDetail'] = $this->dashboard_model->getEmployerDetail($employee_id);
                $data['offerLetters'] = 0;

//                echo "<pre>";
//                print_r($data);
//                exit;
                //getting all users which still have pending documents to Review Ends
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/pending-hr-document');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('login'), "refresh");
            }//else end for session check fail
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please select an Employee to review documents');
            redirect(base_url('hr_documents'));
        }//else end for session check fail
    }

    public function add_hr_document() {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

            $data['title'] = 'Add HR Document';
            $data['heading'] = 'Upload a New Document';
            $data['page'] = 'add';
            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_id = $data['session']['employer_detail']['sid'];
            //form data valdation starts
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('document_name', 'Document name', 'required|callback_document_name');
            //form data valdation ends

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/hr-documents');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(null, true);
                $formpost['employer_sid'] = $employer_id;
                $formpost['company_sid'] = $company_sid;
                $formpost['document_original_name'] = $formpost['document_name'];
                $formpost['archive'] = 1;
                //uploading image to AWS
                if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                    $file = explode(".", $_FILES["document"]["name"]);
                    $document = $formpost['document_name'] . '_' . generateRandomString(10);
                    $document = clean($document);
                    $document = $document . '.' . end($file);
                    $formpost['document_name'] = $document;
                    $formpost['document_type'] = end($file);
                    
                    if($_FILES['document']['size'] == 0){
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect(base_url('hr_documents'));
                    }
                    
                    $aws = new AwsSdk();
                    $aws->putToBucket($document, $_FILES["document"]["tmp_name"], AWS_S3_BUCKET_NAME);
                }

                $documentId = $this->hr_document_model->saveHrDocument($formpost);
                //Start=>> checking if the document is to be sent to all employers
                $from = $data["session"]["company_detail"]["email"];
                $subject = 'HR Document';
                $fromName = $data["session"]["company_detail"]["CompanyName"];
                $message_hf = (message_header_footer($company_sid, $fromName));
                $dataToSave['company_sid'] = $company_sid;
                $dataToSave['sent_on'] = date('Y-m-d H:i:s');
                $dataToSave['sender_sid'] = $employer_id;
                $dataToSave['document_sid'] = $documentId;

                if ($formpost['to_all_employees']) {
                    $this->load->model('employee_model');
                    $employees = $this->hr_document_model->getAllEmployeesByCompanyId($company_sid, $employer_id);
                    
                    foreach ($employees as $employee) {
                        $dataToSave['receiver_sid'] = $employee['sid'];
                        $docToUserObj = $this->employee_model->check_random_string_exits($dataToSave['receiver_sid'], NULL);
                        
                        if ($docToUserObj->num_rows() > 0) {
                            $res = $docToUserObj->result_array();
                            $dataToSave['verification_key'] = $res[0]['verification_key'];
                        } else {
                            $dataToSave['verification_key'] = generateRandomString(70);
                        }

                        $verification_key = '';
                        //$emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION);
                        $emailTemplateData = $this->hr_document_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION, $company_sid);
                        $emailTemplateBody = $emailTemplateData['text'];
                        $emailTemplateBody = str_replace('{{username}}', ucwords($employee['first_name'] . ' ' . $employee['last_name']), $emailTemplateBody);
                        $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
                        $emailTemplateBody = str_replace('{{verification_key}}', $dataToSave['verification_key'], $emailTemplateBody);
                        $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
                        replace_magic_quotes($emailTemplateBody);
                        $from = $emailTemplateData['from_email'];
                        $to = $employee['email'];
                        $subject = $emailTemplateData['subject'];
                        $from_name = $emailTemplateData['from_name'];


                        /*
                        $body = $message_hf['header']
                                . '<h2 style="width:100%; margin:10px 0;">Dear ' . $employee['first_name'] . ' ' . $employee['last_name'] . ',</h2>'
                                . 'Your Company HR Administrator has sent you some important HR Documents. Please see the attachments.'
                                . '<br>To manage all of your documents Please go to <a href="' . base_url('received_documents') . '/' . $dataToSave['verification_key'] . '">This Link</a>.'
                                . '<br>Or <a href="' . base_url('login') . '">Login</a> to your '.STORE_NAME.' account.'
                                . '<br>Go to your <b>'.STORE_NAME.' dashboard</b>-><b>My HR Documents</b>.'
                                . $message_hf['footer'];*/

                        $this->employee_model->saveUserDocument('document', $dataToSave);
                        /*
                         sendMailWithAttachment($from, $employee['email'], $subject, $body, $fromName, $_FILES["document"]);
                        */

                        if (base_url() == STAGING_SERVER_URL) {
                            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $to,
                                'message' => $emailTemplateBody,
                            );
                            save_email_log_common($emailData);
                        } else {
                            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $to,
                                'message' => $emailTemplateBody,
                            );
                            save_email_log_common($emailData);
                            sendMailWithAttachment($from, $to, $subject, $emailTemplateBody, $from_name, $_FILES["document"]);
                        }
                    }
                    $message = '<b>Success:</b> HR document uploaded successfully and mailed to all Employees';
                } else {
                    $message = '<b>Success:</b> HR document uploaded successfully';
                }
                //End=>> checking if the document is to be sent to all employers
                $this->session->set_flashdata('message', $message);
                redirect(base_url('hr_documents'));
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_hr_document($document_id = NULL) {
        if ($document_id != NULL) {
            if ($this->session->has_userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

                $data['title'] = 'Edit HR Document';
                $data['heading'] = 'Edit Uploaded Document';
                $data['page'] = 'edit';

                $company_sid = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $data['document'] = $this->hr_document_model->get_document_detail($company_sid, $document_id);
                $data['document'] = $data['document'][0];

                $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                $this->form_validation->set_message('is_unique', '%s already exists, Please enter a unique name');

                if (isset($_POST) && $data['document']['document_original_name'] != $this->input->post('document_name')) {
                    $this->form_validation->set_rules('document_name', 'Document name', 'required|callback_document_name');
                } else {
                    $this->form_validation->set_rules('document_name', 'Document name', 'required');
                }
                //form data valdation ends
                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/hr-documents');
                    $this->load->view('main/footer');
                } else {
                    $formpost = $this->input->post(null, true);

                    foreach ($formpost as $key => $value) {
                        if ($key != 'old_file_name' && $key != 'old_file_type') {
                            $dataToUpdate[$key] = $value;
                        }
                    }

                    $dataToUpdate['employer_sid'] = $employer_id;
                    $dataToUpdate['company_sid'] = $company_sid;
                    $dataToUpdate['document_original_name'] = $formpost['document_name'];
                    //uploading image to AWS
                    if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                        $file = explode(".", $_FILES["document"]["name"]);
                        $document = $formpost['document_name'] . '_' . generateRandomString(10);
                        $document = clean($document);
                        $document = $document . '.' . end($file);
                        $dataToUpdate['document_name'] = $document;
                        $dataToUpdate['document_type'] = end($file);
                        
                        if($_FILES['document']['size'] == 0){
                            $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                            redirect(base_url('hr_documents'));
                        }
                        
                        $aws = new AwsSdk();
                        $aws->putToBucket($document, $_FILES["document"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    } else {
                        $dataToUpdate['document_name'] = $formpost['old_file_name'];
                        $dataToUpdate['document_type'] = $formpost['old_file_type'];
                    }

                    $this->hr_document_model->updateHrDocument($document_id, $dataToUpdate);
                    //Start=>> checking if the document is to be sent to all employers
                    $from = $data["session"]["company_detail"]["email"];
                    $subject = 'HR Document';
                    $fromName = $data["session"]["company_detail"]["CompanyName"];
                    $message_hf = (message_header_footer($company_sid, $fromName));

                    $dataToSave['company_sid'] = $company_sid;
                    $dataToSave['sender_sid'] = $employer_id;
                    $dataToSave['sent_on'] = date('Y-m-d H:i:s');
                    $dataToSave['document_sid'] = $document_id;

                    if ($formpost['to_all_employees'] && (isset($_FILES['document']) && $_FILES['document']['name'] != '')) {
                        $this->load->model('employee_model');
                        $employees = $this->hr_document_model->getAllEmployeesByCompanyId($company_sid, $employer_id);

                        foreach ($employees as $employee) {
                            $dataToSave['receiver_sid'] = $employee['sid'];
                            $body = $message_hf['header']
                                . '<h2 style="width:100%; margin:10px 0;">Dear ' . $employee['first_name'] . ' ' . $employee['last_name'] . ',</h2>'
                                . 'Your Admin have sent you some HR Documents, Please see the Attachments.'
                                . $message_hf['footer'];
                            $this->employee_model->saveUserDocument('document', $dataToSave);
                            sendMailWithAttachment($from, $employee['email'], $subject, $body, $fromName, $_FILES["document"]);
                        }
                        $message = '<b>Success:</b> HR document updated successfully and mailed to all Employees';
                    } else {
                        $message = '<b>Success:</b> HR document updated successfully';
                    }
                    //End=>> checking if the document is to be sent to all employers
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('hr_documents'));
                }
            } else {//if end for session check success
                redirect(base_url('login'));
            }
        } else {//if end for session check success
            $this->session->set_flashdata('message', '<b>Error:</b> Please select a HR document to edit!');
            redirect(base_url('hr_documents'));
        }
    }

    public function archived_document() {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
            $data['title'] = 'Archived HR Documents';
            $data['page'] = 'archive';
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];

            $data['documents'] = $this->hr_document_model->getAllArchivedDocuments($company_sid);
            $data['archiveDocCount'] = count($data['documents']);
            $data['allDocCount'] = count($this->hr_document_model->getAllActiveDocuments($company_sid));
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/hr-document-list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function document_tasks() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            
            if ($formpost['type'] == 'onboarding') {
                $updatedData['onboarding'] = $formpost['value'];
            } else if ($formpost['type'] == 'action') {
                $updatedData['action_required'] = $formpost['value'];
            } else if ($formpost['type'] == 'archive') {
                $updatedData['archive'] = $formpost['value'];
                $this->session->set_flashdata('message', '<b>Success:</b> HR document ' . $formpost['action'] . 'd successfully');
            }
            
            $document_id = $formpost['sid'];
            $this->hr_document_model->updateDocument($document_id, $updatedData);
        }
    }

    public function save_offer_letter() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            $data['session'] = $this->session->userdata('logged_in');
            $formpost['company_sid'] = $data['session']['company_detail']['sid'];
            $this->hr_document_model->saveOfferLetter($formpost);
            $this->session->set_flashdata('message', '<b>Success:</b> Offer letter saved successfully!');
            redirect(base_url('hr_documents'));
        } else {
            redirect(base_url('hr_documents'));
        }
    }

    public function update_offer_letter() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            $sid = $formpost['offer_letter_id'];
            $dataToupdate['letter_name'] = $formpost['letter_name'];
            $dataToupdate['letter_body'] = $formpost['letter_body'];
            $this->hr_document_model->updateOfferLetter($sid, $dataToupdate);
            $this->session->set_flashdata('message', '<b>Success:</b> Offer letter updated successfully!');
            
            if ($formpost['fromPage'] == 'send offer letter') {
                redirect(base_url('send_offer_letter_documents') . "/" . $formpost['user_id']);
            } elseif ($formpost['fromPage'] == 'hr document') {
                redirect(base_url('hr_documents'));
            }
        } else {
            redirect(base_url('hr_documents'));
        }
    }

    public function delete_offer_letter() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $sid = $this->input->post('sid');
            $this->hr_document_model->deleteOfferLetter($sid);
            $this->session->set_flashdata('message', '<b>Success:</b> Offer letter deleted successfully!');
            redirect(base_url('hr_documents'));
        } else {
            redirect(base_url('hr_documents'));
        }
    }

    public function send_document_reminder() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $userDocumentSid = $this->input->post('user_document_sid');
            $userData = $this->hr_document_model->getUserDocument($userDocumentSid);
            $userData = $userData[0];
            $this->load->model('dashboard_model');
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $company_data = $this->dashboard_model->get_company_detail($company_sid);
            $companyname = $company_data['CompanyName'];
            $message_hf = (message_header_footer($company_sid, $companyname));
            //$emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION);
            $emailTemplateData = $this->hr_document_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION, $company_sid);
            $emailTemplateBody = $emailTemplateData['text'];
            $emailTemplateBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{verification_key}}', $userData['ver_key'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
            replace_magic_quotes($emailTemplateBody);
            $from = $emailTemplateData['from_email'];
            $to = $userData['email'];
            $subject = $emailTemplateData['subject'];
            $from_name = $emailTemplateData['from_name'];

            if (base_url() == STAGING_SERVER_URL) {
                $emailData = array('date' => date('Y-m-d H:i:s'),
                                    'subject' => $subject,
                                    'email' => $to,
                                    'message' => $emailTemplateBody);
                save_email_log_common($emailData);
            } else {
                $emailData = array('date' => date('Y-m-d H:i:s'),
                                    'subject' => $subject,
                                    'email' => $to,
                                    'message' => $emailTemplateBody);
                save_email_log_common($emailData);
                sendMail($from, $to, $subject, $emailTemplateBody, $from_name);
            }

            $emailLog['subject'] = $subject;
            $emailLog['email'] = $to;
            $emailLog['message'] = $emailTemplateBody;
            $emailLog['date'] = date('Y-m-d H:i:s');
            $emailLog['admin'] = 'admin';
            $emailLog['status'] = 'Delivered';
            save_email_log_common($emailLog);
            $this->session->set_flashdata('message', '<b>Success:</b> Document reminder sent successfully!');
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please try again!');
        }
    }

    public function document_name() {
        if ($this->session->userdata('logged_in')) {
            if ($this->input->post('document_name')) {
                $document_name = $this->input->post('document_name');
                $data['session'] = $this->session->userdata('logged_in');
                $companyId = $data["session"]["company_detail"]["sid"];
                $result = $this->hr_document_model->uniqueDocumentName($document_name, $companyId);
                if ($result > 0) {
                    $this->form_validation->set_message('document_name', $document_name . ' already exists in HR documents!');
                    return false;
                } else {
                    return TRUE;
                }
            }
        }
    }
    
    function assign_employee_documents_to_new_hr_document() {
        if ($this->session->userdata('logged_in')) {
            $unassigned_documents = $this->hr_document_model->get_unassigned_documents();
            $document_details = array();
            $assigned_documents = array();
            
            foreach($unassigned_documents as $key=>$v) {
                $sid = $v['sid'];
                $document_sid = $v['document_sid'];
                
                if(!array_key_exists($document_sid, $document_details)) {
                    $document_details[$document_sid] = $this->hr_document_model->get_document_details($document_sid);
                }

                $document_assignment_data = array();
                $document_assignment_data['company_sid'] = $v['company_sid'];
                $document_assignment_data['assigned_date'] = $v['sent_on'];
                $document_assignment_data['assigned_by'] = $v['sender_sid'];
                $document_assignment_data['user_type'] = 'employee';
                $document_assignment_data['user_sid'] = $v['receiver_sid'];
                $document_assignment_data['document_type'] = 'uploaded';
                $document_assignment_data['document_title'] = $document_details[$document_sid]['document_title'];
                $document_assignment_data['document_description'] = $document_details[$document_sid]['document_description'];
                $document_assignment_data['document_original_name'] = $document_details[$document_sid]['uploaded_document_original_name'];
                $document_assignment_data['document_extension'] = $document_details[$document_sid]['uploaded_document_extension'];
                $document_assignment_data['document_s3_name'] = $document_details[$document_sid]['uploaded_document_s3_name'];
                $document_assignment_data['document_sid'] = $document_details[$document_sid]['sid'];
                $document_assignment_data['acknowledged'] = $v['acknowledged'];
                $document_assignment_data['acknowledged_date'] = $v['acknowledged_date'];
                $document_assignment_data['downloaded'] = $v['downloaded'];
                $document_assignment_data['downloaded_date'] = $v['downloaded_date'];
                $document_assignment_data['uploaded'] = $v['uploaded'];
                $document_assignment_data['uploaded_date'] = $v['uploaded_date'];
                $document_assignment_data['uploaded_file'] = $v['uploaded_file'];
                $document_assignment_data['status'] = '1';
                $document_assignment_data['is_pending'] = '1';
                
                $documents_assigned_sid = $this->hr_document_model->assign_document_details($document_assignment_data);
                $this->hr_document_model->update_hr_user_document($sid, $documents_assigned_sid);
                
                $assigned_documents[] = array('hr_user_document' => $sid, 'documents_assigned_sid' => $documents_assigned_sid);
            }
            
        }
        
        echo 'Total Transferred: '.count($assigned_documents);
        echo '<pre>'; print_r($assigned_documents); echo '</pre>';
        
        //SELECT company_sid, acknowledged, downloaded, uploaded, uploaded_file, offer_letter_name, offer_letter_body, sent_on, documents_assigned_sid FROM `hr_user_document` WHERE document_type = 'offerletter' ORDER BY `document_type` ASC
    }

    function switch_admin_hr_to_new_documents() {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $documents = $this->hr_documents_management_model->get_all_hr_documents();
            echo 'I am In: <pre>'; print_r($documents); echo '</pre>';
            foreach ($documents as $key => $v) {
                $data = array();
                $data['company_sid'] = $v['company_sid'];
                $data['employer_sid'] = $v['employer_sid'];
                $data['document_title'] = $v['document_original_name'];
                $data['document_description'] = $v['document_description'];
                $data['document_type'] = 'uploaded';
                $data['uploaded_document_original_name'] = $v['document_original_name'];
                $data['uploaded_document_extension'] = $v['document_type'];
                $data['uploaded_document_s3_name'] = $v['document_name'];
                $data['unique_key'] = generateRandomString(32);
                $data['date_created'] = $v['date_uploaded'];
                $data['onboarding'] = $v['onboarding'];
                $data['action_required'] = $v['action_required'];
                $data['acknowledgment_required'] = $v['action_required'];
                $data['download_required'] = $v['action_required'];
                $data['signature_required'] = $v['action_required'];
                $data['to_all_employees'] = 0;
                
                if($v['date_uploaded'] == '' || $v['date_uploaded'] == NULL) {
                    $data['date_created'] = NULL;
                } else {
                    $data['date_created'] = $v['date_uploaded'];
                }
                
                if ($v['archive'] == 1) {
                    $data['archive'] = 0;
                } else {
                    $data['archive'] = 1;
                }
                
                $data['video_required'] = 0;
                $data['video_source'] = NULL;
                $data['video_url'] = NULL;
                $data['copied_doc_sid'] = $v['sid'];
                $data['sort_order'] = $v['sid'];
                $documents_management_sid = $this->hr_documents_management_model->insert_document_record($data);
                
                $data_for_hr = array();
                $data_for_hr['documents_management_sid'] = $documents_management_sid;
                $this->hr_documents_management_model->update_hr_document($v['sid'], $data_for_hr);
                
            }
        }
    }
}