<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Background_check extends CI_Controller {
    private $api_mode;

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in')) {
            $this->load->helper('accurate_background');
            $this->load->model('background_check_model');
            //$this->load->model('application_tracking_model');
            $this->load->model('application_tracking_system_model');
            $this->load->model('dashboard_model');
            //$this->load->model('manage_admin/invoice_model');
            require_once(APPPATH . 'libraries/aws/aws.php');
            $this->form_validation->set_error_delimiters('<p class="error"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $session = $this->session->userdata('logged_in');
            $company_sid = $session['company_detail']['sid'];
            $testing_companies = array(3, 31, 57);

            if (in_array($company_sid, $testing_companies)) {
                $this->api_mode = 'dev';
            } else {
                $this->api_mode = AB_API_MODE;
            }
        } else {
            redirect(base_url('login'));
        }
    }

    public function index($type = NULL, $sid = NULL, $jobs_listing = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'background_check');

            if ($type != null && $sid > 0) {
                if ($type == 'employee') {
                    $data_function = employee_right_nav($sid);
                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data_function['security_details'] = $security_details;
                    check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data_function['title'] = 'Employee / Team Emergency Background Check';
                    $data_function["return_title_heading"] = "Employee Profile";
                    $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                    $reload_location = 'background_check/employee/' . $sid;
                    $data_function['cancel_url'] = 'employee_management';
                    $data_function["employer"] = $this->dashboard_model->get_company_detail($sid);
                    // getting applicant ratings - getting average rating of applicant
                    $data_function['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee');
                }

                if ($type == 'applicant') {
                    $data_function = applicant_right_nav($sid);
                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data_function['security_details'] = $security_details;
                    check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
                    //check if Applicant Exists?
                    $applicantQueryObject = $this->background_check_model->applicant_details($sid, $company_id);

                    if ($applicantQueryObject->num_rows() == 0) {
                        $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                        redirect('application_tracking_system/active/all/all/all/all');
                    }

                    $data_function["return_title_heading"] = "Applicant Profile";
                    $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' .$jobs_listing;
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data_function['title'] = 'Applicant Background Check';
                    $reload_location = 'background_check/applicant/' . $sid . '/' . $jobs_listing;
                    $data_function['cancel_url'] = 'applicant_profile/' . $sid . '/' .$jobs_listing;
                    $applicant_info = $this->dashboard_model->get_applicants_details($sid);

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($type)
                    );

                    $data_function['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                    $data_function['employer'] = $data_employer;
                }

                $full_access = false;
                if ($employer_access_level == 'Admin') {
                    $full_access = true;
                }

                $data_function['page'] = 'background_report';
                $data_function['employer_access_level'] = $employer_access_level;
                $data_function['full_access'] = $full_access;
                $data_function['left_navigation'] = $left_navigation;
                //Start==> Checking purchased and not purchased backgroung Check products//
                $this->load->model('dashboard_model');
                $product_type = 'background-checks';
                $data_function['purchasedProducts'] = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);
                //Update Order Status on Load - start
                $appliedProductsQueryObject = $this->background_check_model->getProductsAlreadyAppliedOn($sid, $type, $product_type);
                $appliedProductsArray = $appliedProductsQueryObject->result_array();

                foreach ($appliedProductsArray as $order) {
                    $ab_order_sid = $order['sid'];
                    $package_id = $order['package_id'];
                    if($order['product_brand'] != 'assurehire') $this->get_order_status($package_id, $ab_order_sid);
                }
                //Update Order Status on Load - end

                $appliedProductsQueryObject = $this->background_check_model->getProductsAlreadyAppliedOn($sid, $type, $product_type);
                $appliedProductsArray = $appliedProductsQueryObject->result_array();

                if (!empty($appliedProductsArray)) {
                    foreach ($appliedProductsArray as $key => $product) {
                        $order_status = $product['order_response'];
                        $package_response = $product['package_response'];

                        if ($order_status != '') {
                            $order_status = unserialize($order_status);
                        } else {
                            $order_status = array();
                        }

                        if ($package_response != '') {
                            $package_response = unserialize($package_response);
                        } else {
                            $package_response = array();
                        }

                        $appliedProductsArray[$key]['order_response'] = $order_status;
                        $appliedProductsArray[$key]['package_response'] = $package_response;
                    }
                }
// 149 + 892
                // get all applied products that are not refunded.
                $data_function['appliedProducts'] = $appliedProductsArray;

                $product_ids = NULL;
                if (!empty($data_function['purchasedProducts'])) {
                    foreach ($data_function['purchasedProducts'] as $myKey => $product) {
                        $product_ids[$myKey] = $product['product_sid'];
                        $product['appliedOn'] = 'false';
                        if ($appliedProductsQueryObject->num_rows() > 0) {
                            foreach ($appliedProductsQueryObject->result_array() as $appliedProduct) {

                                $package_response = unserialize($appliedProduct['package_response']);

                                if (isset($package_response['errors'])) {
                                    $product['appliedOn'] = 'false';
                                } else {
                                    if ($product['product_sid'] == $appliedProduct['product_sid'] && $appliedProduct['order_refunded'] == 0) {
                                        $product['appliedOn'] = 'true';
                                    }
                                }
                            }
                        }

                        $data_function['purchasedProducts'][$myKey] = $product;
                    }
                }
                //
                $data_function['deleted_bgchecks'] = $this->background_check_model->GetDeletedBGC($sid, $type, $product_type);
                //
                $data_function['appliedProducts'] = array_merge($data_function['appliedProducts'], $data_function['deleted_bgchecks']);
                $data_function['notPurchasedProducts'] = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                //End==> Checking purchased backgroung Check products//
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'place_background_check_order':
                        $this->form_validation->set_rules('productId', 'Product', 'required|trim|xss_clean');
                        break;
                    case 'get_order_status':
                        $this->form_validation->set_rules('package_id', 'Package Id', 'required|trim|xss_clean');
                        break;
                    case 'get_report';
                        $this->form_validation->set_rules('search_id', 'Search Id', 'required|trim|xss_clean');
                        break;
                }

                $data_function['market_place_url'] = 'market_place/background-checks';
                $data_function['questions_sent'] = $this->application_tracking_system_model->check_sent_video_questionnaires($sid, $company_id);
                $data_function['questions_answered'] = $this->application_tracking_system_model->check_answered_video_questionnaires($sid, $company_id);
                $data_function['job_list_sid'] = $jobs_listing;
                //form data valdation ends
                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('main/header', $data_function);
                    $this->load->view('manage_employer/background_check');
                    $this->load->view('main/footer');
                } else {
                    $perform_action = $this->input->post('perform_action');
                    switch ($perform_action) {
                        case 'place_background_check_order':
                            $formpost = array($this->input->post('productId'));
                            $dataToSave['employer_sid'] = $employer_sid;
                            $dataToSave['company_sid'] = $company_id;
                            $dataToSave['users_sid'] = $sid;
                            $dataToSave['users_type'] = $type;
                            $dataToSave['date_applied'] = date('Y-m-d H:i:s');
                            $productCounter = $this->dashboard_model->checkPurchasedProductQty($formpost, $company_id, $product_type);
                            //Start=>> checking that the applied products still have counter greater than 1?
                            foreach ($productCounter as $product) {
                                if ($product['remaining_qty'] <= 0) {
                                    $this->session->set_flashdata('message', '<b>Error:</b> Some error occured while processing your request, Please try again.');
                                    redirect($reload_location, 'refresh');
                                }
                            }
                            //End=>> checking that the applied products still have counter greater than 1?
                            //foreach ($formpost as $productId) {

                            $productId = $this->input->post('productId');
                            $res = $this->dashboard_model->productDetail($productId);

                            if ($this->api_mode == 'live') {
                                $package_code = $res[0]['package_code'];
                            } else {
                                $package_code = 3487;
                            }

                            //Placing BG check order
                            $package_response = $this->place_bg_check_order($package_code, $sid, $type, $jobs_listing, $res);
                            $package_response = json_decode($package_response, true);
                            // Error occuresd
                            if(json_last_error() !== JSON_ERROR_NONE){
                                $email_message = '<pre>' . print_r($this->input->post(), true) . print_r($package_response, true) . '</pre>';
                               // mail(TO_EMAIL_DEV, 'Accurate Background Check Order Error ' . date('Y-m-d H:i:s'), $email_message);
                                $this->session->set_flashdata('message', '<b>Error!:</b> Failed to place a background check.');
                                if ($type == 'applicant') {
                                    redirect('background_check/applicant/' . $sid . '/' .$jobs_listing, 'refresh');
                                } else {
                                    redirect('background_check/employee/' . $sid, 'refresh');
                                }
                                return;
                            }
                            $dataToSave['package_response'] = serialize($package_response);
                            $dataToSave['package_id'] = isset($package_response['packageId']) ? $package_response['packageId'] : $package_response['orderInfo']['packageId'];

                            if ($dataToSave['package_id'] == '' || $dataToSave['package_id'] == NULL) {
                                unset($dataToSave['package_id']);
                            }
                            //Checking BG check order Response
                            //$dataToSave['order_response'] = $order_response = $this->get_order_response($reload_location, $dataToSave['package_id']);
                            //$dataToSave['order_response'] = $result = ab_get_order_response($dataToSave['package_id']);
                            //$order_response = json_decode($result);
                            if(isset($package_response['orderStatus']['orderId'])) $dataToSave['external_id'] = $package_response['orderStatus']['orderId'];
                            $dataToSave['order_response_status'] = '';

                            if (isset($res)) {
                                $dataToSave['product_sid'] = $productId;
                                $dataToSave['product_price'] = $res[0]['price'];
                                $dataToSave['product_name'] = $res[0]['name'];
                                $dataToSave['product_type'] = $res[0]['product_type'];
                                $dataToSave['product_image'] = $res[0]['product_image'];
                            }

                            // die('ss');

                            //saving BG-Check data to table
                            $this->background_check_model->saveBackgroundOrder($dataToSave);

                            if (!isset($package_response['errors'])) {
                                //Deducting Quantity from Order-Product
                                $result = $this->dashboard_model->deduct_product_qty($productId, $company_id);
                                //}
                                $this->session->set_flashdata('message', '<b>Success:</b> Request placed for Background Check, you can check status after one Hour.');

                                if ($type == 'applicant') {
                                    redirect('background_check/applicant/' . $sid . '/' .$jobs_listing, 'refresh');
                                } else {
                                    redirect('background_check/employee/' . $sid, 'refresh');
                                }
                            } else if (isset($package_response['errors'])) {
                                if (isset($package_response['errors'][0])) {
                                    $error_code = $package_response['errors'][0]['code'];
                                    $message = $package_response['errors'][0]['message'];
                                } else {
                                    $error_code = 'AHR-ERR';
                                    $message = 'Could Not Place Background Check Order!';
                                }

                                //Send Mail to Dev
                                $post_data = $this->input->post();

                                $email_message = '<pre>' . print_r($post_data, true) . print_r($package_response, true) . '</pre>';
                                // mail(TO_EMAIL_DEV, 'Accurate Background Check Order Error ' . date('Y-m-d H:i:s'), $email_message);

                                $this->session->set_flashdata('message', '<b>Success:</b> ' . $message . ' ( ' . $error_code . ' ) ');

                                if ($type == 'applicant') {
                                    redirect('background_check/applicant/' . $sid . '/' .$jobs_listing, 'refresh');
                                } else {
                                    redirect('background_check/employee/' . $sid, 'refresh');
                                }
                            }
                            break;
                        case 'get_order_status':
                            $package_id = $this->input->post('package_id');
                            $ab_order_sid = $this->input->post('ab_order_sid');
                            $this->get_order_status($package_id, $ab_order_sid);
                            /* Comented on purpose
                              $order_details = $this->background_check_model->get_order_details($ab_order_sid);
                              $order_status = ab_get_order_status($package_id, $this->api_mode);

                              if (!empty($order_status) || $order_status != '') {
                              $order_status = json_decode($order_status, true);
                              //Check Order Status if Cancelled Refund Item
                              if(isset($order_status['orderStatus'])){
                              $status = strtolower($order_status['orderStatus']['status']);
                              if($status == 'cancelled' && $order_details['order_refunded'] == 0){
                              $product_sid = $order_details['product_sid'];
                              $company_sid = $order_details['company_sid'];
                              $employer_sid = $order_details['employer_sid'];
                              $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                              $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                              }
                              }
                              } else {
                              $order_status = array();
                              }

                              $order_status = serialize($order_status);
                              $this->background_check_model->update_order_status($ab_order_sid, $order_status);
                             */

                            $this->session->set_flashdata('message', '<strong>Success: </strong> Order Status successfully fetched.');
                            redirect('background_check/' . $type . '/' . $sid . '/' .$jobs_listing, 'refresh');
                            break;
                        case 'get_report':
                            $search_id = $this->input->post('search_id');
                            $report = ab_get_report($search_id, 'pdf', $this->api_mode);
                            $report_data = json_decode($report, true);

                            if (!empty($report_data)) {
                                $pdf_report = base64_decode($report_data['resultReport']);
                                header('Content-Type: application/pdf');
                                header('Content-Disposition: attachment; filename="report.pdf"');
                                echo $pdf_report;
                            }
                            break;
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No user selected!');
                redirect('application_tracking_system/active/all/all/all/all');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function background_report($reportId = NULL) {
        if ($reportId != NULL) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'background_check');

            $background_order = $this->background_check_model->getBackgroundOrderDetails($reportId);

            if (!empty($background_order)) {
                $background_order = $background_order[0];
            } else {// emergency contact does not exists.
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('dashboard'));
                }
            }

            $type = $background_order['users_type'];
            $sid = $background_order['users_sid'];

            if ($type == 'employee') {
                $data_function = employee_right_nav($sid);
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data_function['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Emergency Background Report';
                $reload_location = 'background_check/employee/' . $sid;
                $data_function['cancel_url'] = '';
                $data_function["employer"] = $this->dashboard_model->get_company_detail($sid);
            }

            if ($type == 'applicant') {
                //check if Applicant Exists?
                $data_function = applicant_right_nav($sid);
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data_function['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           
                $applicantQueryObject = $this->background_check_model->applicant_details($sid, $company_id);

                if ($applicantQueryObject->num_rows() == 0) {
                    $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                    redirect('application_tracking_system/active/all/all/all/all');
                }

                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Background Report';
                $reload_location = 'background_check/applicant/' . $sid;
                $data_function['cancel_url'] = 'applicant_profile/' . $sid;
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['profile_picture']
                );
                $data_function['employer'] = $data_employer;
            }

            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }
            $data_function['backgorund_order']['product_name'] = $background_order['product_name'];
            $data_function['employer_access_level'] = $employer_access_level;
            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            //Start =>>  Working for Background check response
            $order_response = $this->get_order_response($reload_location, $background_order['package_id']);
            $order_response_decode = json_decode($order_response);
            $updatedDataToSave['order_response_status'] = $data_function['backgorund_order']['order_response_status'] = $order_response_decode->orderStatus->status;
            $updatedDataToSave['order_response'] = $order_response;
            $data_function['backgorund_order']['order_response'] = $order_response_decode;
            $this->background_check_model->updateBackgroundCheckOrder($reportId, $updatedDataToSave);
            //End =>>  Working for Background check response
            $this->form_validation->set_rules('productId[]', 'Product', 'required');
            //form data valdation ends
            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/background_report');
                $this->load->view('main/footer');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Invalid path.');
            redirect(base_url('dashboard'));
        }
    }

    public function activate() {
        $data['session'] = $this->session->userdata('logged_in');

        $security_sid = $employer_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           

        $company_sid = $data["session"]["company_detail"]["sid"];
        $backGroundCheck = checkCompanyAccurateCheck($company_sid);
        $data['title'] = 'Background Check Activation';
        $application_Id = $_SESSION['applicant_id'];
        $application_Type = $_SESSION['applicant_type'];
//        $_SESSION['applicant_id'] = $_SESSION['applicant_type'] = NULL;

        $data['cancel_location'] = base_url($application_Type . '/' . $application_Id);
        $document_request = $this->background_check_model->getBackgroundDocument($company_sid);
        $data['flag'] = 1;

        if ($document_request == 0) {
            $data['flag'] = 0;
        }

        if (!$backGroundCheck) {
            $this->form_validation->set_rules('hidden', 'hidden', 'trim');
            if ($this->form_validation->run() === FALSE) { //Getting company data
                $data['companyDetail'] = $this->background_check_model->getCompanyDetail($company_sid);
                $stateDetails = db_get_state_name($data['companyDetail']['Location_State']);
                $data['companyDetail']['Location_State'] = $stateDetails['state_name'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/background_check_activate_new');
                $this->load->view('main/footer');
            } else {
                if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                    $file = explode(".", $_FILES["document"]["name"]);
                    $document = $file[0] . '_' . generateRandomString(10) . '.' . end($file);
                    if($_FILES['document']['size'] == 0){
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect(base_url($application_Type . '/' . $application_Id));
                    }
                    $aws = new AwsSdk();
                    $aws->putToBucket($document, $_FILES["document"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $backGroundData['uploaded_document'] = $document;
                    $backGroundData['company_sid'] = $company_sid;
                    $backGroundData['employer_sid'] = $employer_sid;
                    $backGroundData['date_applied'] = date('Y-m-d H:i:s');
                    $this->background_check_model->saveBackgroundCheck($backGroundData);
                    $body['company_sid'] = $company_sid;
                    $body['date_applied'] = date('Y-m-d H:i:s');
                    $body = serialize($body);
                    $new_body = 'Please review the attached file for more details';
                    $fromName = STORE_NAME;
                    //Send Emails Through System Notifications Email - Start
                    $system_notification_emails = get_system_notification_emails('accurate_background_emails');

                    if (!empty($system_notification_emails)) {
                        foreach ($system_notification_emails as $system_notification_email) {
                            sendMailWithAttachment(FROM_EMAIL_NOTIFICATIONS, $system_notification_email['email'], 'Background check activation for a company', $new_body, $fromName, $_FILES['document']);
                        }
                    }
                    //Send Emails Through System Notifications Email - End
                    //sendMailWithAttachment(FROM_EMAIL_NOTIFICATIONS, 'mubashir.saleemi123@gmail.com', 'Background check activation for a company', $body, $fromName, $_FILES["document"]);
                    //sendMail(FROM_EMAIL_NOTIFICATIONS, 'mubashir.saleemi123@gmail.com', 'Background check activation for a company', $body, 'Background check activation');
                    $this->session->set_flashdata('message', '<b>Success:</b> Your application to run Background Checks have been forwarded.');
                    redirect(base_url($application_Type . '/' . $application_Id));
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> Please try again.');
                    redirect(base_url($application_Type . '/' . $application_Id));
                }
            }
        } else {
            $this->session->set_flashdata('message', '<b>Success:</b> You are currently active for Background Checks.');
            redirect(base_url($application_Type . '/' . $application_Id));
        }
    }

    public function activate_old() {
        $data['session'] = $this->session->userdata('logged_in');

        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           

        $company_sid = $data["session"]["company_detail"]["sid"];
        $backGroundCheck = checkCompanyAccurateCheck($company_sid);
        $data['title'] = 'Background Check Activation';
        $application_Id = $_SESSION['applicant_id'];

        if (!$backGroundCheck) {
            $this->form_validation->set_rules('companyName', 'Company', 'trim|xss_clean|required');
            $this->form_validation->set_rules('typeOfBusiness', 'Type Of Business', 'trim|xss_clean|required');
            $this->form_validation->set_rules('taxId', 'Fed. Tax ID #', 'trim|xss_clean|required');
            $this->form_validation->set_rules('yearInBusiness', 'Year In Business', 'trim|xss_clean|required|numeric');
            $this->form_validation->set_rules('address', 'Street Address', 'trim|xss_clean|required');
            $this->form_validation->set_rules('city', 'City', 'trim|xss_clean|required');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean|required');
            $this->form_validation->set_rules('zip', 'ZIP code', 'trim|xss_clean|required|numeric');
            $this->form_validation->set_rules('telephone', 'Telephone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('fax', 'Fax Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('contactName', 'Contact Person', 'trim|xss_clean|required');
            $this->form_validation->set_rules('title', 'Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('contactTelephone', 'Contact Telephone', 'trim|xss_clean|required');
            $this->form_validation->set_rules('companyType', 'Company Type', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required');
            $this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) { //Getting company data
                $data['companyDetail'] = $this->background_check_model->getCompanyDetail($company_sid);
                $stateDetails = db_get_state_name($data['companyDetail']['Location_State']);
                $data['companyDetail']['Location_State'] = $stateDetails['state_name'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/background_check_activate');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                foreach ($formpost as $key => $value) {
                    if ($key != 'otherCompanyType') { // exclude these values from array
                        $backgroundCheckData[$key] = $value;
                    }
                }

                if ($formpost['companyType'] == 'Other') {
                    if ($formpost['otherCompanyType'] == NULL && empty($formpost['otherCompanyType'])) {
                        $backgroundCheckData['companyType'] == 'Other';
                    } else {
                        $backgroundCheckData['companyType'] == $formpost['otherCompanyType'];
                    }
                } else {
                    $backgroundCheckData['companyType'] == $formpost['companyType'];
                }

                $backGroundData['company_sid'] = $company_sid;
                $backGroundData['backgroundCheckDetails'] = serialize($backgroundCheckData);
                $backGroundData['date_applied'] = date('Y-m-d H:i:s');
                $this->background_check_model->saveBackgroundCheck($backGroundData);
                $body['company_sid'] = $company_sid;
                $body['backgroundCheckDetails'] = $backgroundCheckData;
                $body['date_applied'] = date('Y-m-d H:i:s');
                $body = serialize($body);
                //sendMail(FROM_EMAIL_NOTIFICATIONS, 'mubashir.saleemi123@gmail.com', 'Background check activation for a company', $body, 'Background check activation');
                $this->session->set_flashdata('message', '<b>Success:</b> Your application to run Background Checks have been forwarded.');
                redirect(base_url('applicant_profile/' . $application_Id));
            }
        } else {
            $this->session->set_flashdata('message', '<b>Success:</b> You are currently active for Background Checks.');
            redirect(base_url('applicant_profile/' . $application_Id));
        }
    }

    public function place_bg_check_order($package_code = NUll, $users_id = NULL, $users_type = NULL, $jobs_listing = NULL, $res = []) {
        
        if ($users_id != NULL && $users_id != NULL && $users_type != NULL) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'background_check'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           

            $company_sid = $data["session"]["company_detail"]["sid"];
            $portalDetails = $this->dashboard_model->get_portal_detail($company_sid);

            if ($users_type == 'employee') {
                $user_info = $this->dashboard_model->get_company_detail($users_id);
                if (!empty($user_info)) {
                    $full_employment_application = $user_info['full_employment_application'];

                    if ($full_employment_application != '') {
                        $full_employment_application = unserialize($full_employment_application);
                        $user_info['full_employment_application'] = $full_employment_application;
                    } else {
                        $user_info['full_employment_application'] = array();
                    }
                }
                $data_employer = $user_info;
            }

            if ($users_type == 'applicant') {
                $user_info = $this->dashboard_model->get_applicants_details($users_id);

                if (!empty($user_info)) {
                    $full_employment_application = $user_info['full_employment_application'];

                    if ($full_employment_application != '') {
                        $full_employment_application = unserialize($full_employment_application);
                        $user_info['full_employment_application'] = $full_employment_application;
                    } else {
                        $user_info['full_employment_application'] = array();
                    }
                }

                $data_employer = array(
                    'sid' => $user_info['sid'],
                    'first_name' => $user_info['first_name'],
                    'last_name' => $user_info['last_name'],
                    'email' => $user_info['email'],
                    'Location_Address' => $user_info['address'],
                    'Location_City' => $user_info['city'],
                    'Location_Country' => $user_info['country'],
                    'Location_State' => $user_info['state'],
                    'Location_ZipCode' => $user_info['zipcode'],
                    'PhoneNumber' => $user_info['phone_number'],
                    'full_employment_application' => $user_info['full_employment_application']
                );
            }
            //$applicantQueryObject = $this->background_check_model->applicant_details($user_id, $company_sid);
            if (isset($user_info) && !empty($user_info['full_employment_application'])) {
                //getting country and state details
                $stateCountry = db_get_state_name($data_employer['Location_State']);
                $company_name = $data["session"]["company_detail"]["CompanyName"];

                if (strlen($company_name) > 24) {
                    $company_name = substr($company_name, 0, 24);
                }

                $employer_name = $data["session"]["employer_detail"]["first_name"] . ' ' . $data["session"]["employer_detail"]["last_name"];
                if (strlen($employer_name) > 24) {
                    $employer_name = substr($employer_name, 0, 24);
                }

                $sub_domain = $portalDetails['sub_domain'];
                if (strlen($sub_domain) > 24) {
                    $sub_domain = substr($sub_domain, 0, 24);
                }

                $user_sid = $user_info['sid'];
                $user_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

                if (strlen($user_name) > 24) {
                    $user_name = substr($user_name, 0, 24);
                }

                $data = '{
                    "headerInfo": {
                        "orderMode": "two-pass",
                        "requestor": {
                            "email": ' . ($this->api_mode == 'live' ? '"' . FROM_EMAIL_STEVEN . '"' : '"integrations@accuratebackground.com"') . '
                        },
                        "originCode": "ats"
                    },
                    "orderInfo": {
                        "referenceCodes1": "' . $company_name . '",
                        "referenceCodes2": "' . $employer_name . '",
                        "referenceCodes3": "' . $sub_domain . '",
                        "referenceCodes4": "' . $user_sid . '",
                        "referenceCodes5": "' . $user_name . '",                            
                        "copyOfReport": {
                            "requestCopy": "' . FROM_EMAIL_NOTIFICATIONS . '",
                            "byEmail": 1
                        },
                        "position": {
                            "address": {
                                "countryCode": "' . $stateCountry['country_code'] . '",
                                "region": "' . $stateCountry['state_code'] . '",
                                "city": "' . $data_employer['Location_City'] . '",
                                "street": "' . $data_employer['Location_Address'] . '",
                                "postalCode": "' . $data_employer['Location_ZipCode'] . '"
                            }
                        },
                        "specialInstruction": "some optional text goes here"
                    },
                    "candidate": {
                        "name": {
                            "firstname": "' . $data_employer['first_name'] . '",
                            "lastname": "' . $data_employer['last_name'] . '",
                            "middlename": ""
                        },
                        "aka": [
                            {
                                "firstname": "",
                                "lastname": "",
                                "middlename": ""
                            },
                            {
                                "firstname": "",
                                "lastname": "",
                                "middlename": ""
                            },
                            {
                                "firstname": "",
                                "lastname": "",
                                "middlename": ""
                            }
                        ],
                        "dateOfBirth": "' . date('Y-m-d', strtotime($data_employer['full_employment_application']['TextBoxDOB'])) . '",
                        "ssn": "' . $data_employer['full_employment_application']['TextBoxSSN'] . '",
                        "governmentId": {
                            "countryCode": "",
                            "type": "",
                            "number": ""
                        },
                        "contact": {
                            "email": "' . $data_employer['email'] . '",
                            "phone": "' . $data_employer['PhoneNumber'] . '"
                        },
                        "address": {
                            "street": "' . $data_employer['Location_Address'] . '",
                            "street2": "' . $data_employer['Location_Address'] . '",
                            "city": "' . $data_employer['Location_City'] . '",
                            "region": "' . $stateCountry['state_code'] . '",
                            "country": "' . $stateCountry['country_code'] . '",
                            "postalCode": "' . $data_employer['Location_ZipCode'] . '"
                        }
                    },
                    "convicted": "",
                    "conviction": [
                        {
                            "convictionDate": "",
                            "description": "",
                            "location": {
                                "countryCode": "",
                                "region": "",
                                "region2": "",
                                "city": ""
                            }
                        }
                    ]
                }';
                //
                // mail(TO_EMAIL_DEV, 'Accurate Background Check Order ' . date('Y-m-d H:i:s'), $data);
                //
                $orderReference = generateRandomString(8);
                //
                if($res[0]['product_brand'] == 'assurehire'){
                    $data = '{
                        "orderReference": "'.($orderReference).'",
                        "packageId": "'.( $res[0]['package_code'] ).'",
                        "callback_url": "https://www.automotohr.com/assurehire/cb",
                        "requestor": {
                            "company": "' . $company_name . '",
                            "name": "' . $employer_name . '",
                            "email": "'.(ASSUREHIR_USR ).'",
                            "address": {
                                "countryCode": "' . $stateCountry['country_code'] . '",
                                "region": "' . $stateCountry['state_code'] . '",
                                "city": "' . $data_employer['Location_City'] . '",
                                "street": "' . $data_employer['Location_Address'] . '",
                                "postalCode": "' . $data_employer['Location_ZipCode'] . '"
                            }
                        },
                        "candidate": {
                            "name": {
                                "firstname": "' . $data_employer['first_name'] . '",
                                "lastname": "' . $data_employer['last_name'] . '",
                                "middlename": ""
                            },
                            "aka": [],
                            "dateOfBirth": "' . date('Y-m-d', strtotime($data_employer['full_employment_application']['TextBoxDOB'])) . '",
                            "ssn": "' . $data_employer['full_employment_application']['TextBoxSSN'] . '",
                            "governmentId": {
                                "countryCode": "",
                                "type": "",
                                "number": ""
                            },
                            "contact": {
                                "email": "' . $data_employer['email'] . '",
                                "phone": "' . $data_employer['PhoneNumber'] . '"
                            },
                            "address": {
                                "street": "' . $data_employer['Location_Address'] . '",
                                "street2": "' . $data_employer['Location_Address'] . '",
                                "city": "' . $data_employer['Location_City'] . '",
                                "region": "' . $stateCountry['state_code'] . '",
                                "country": "' . $stateCountry['country_code'] . '",
                                "postalCode": "' . $data_employer['Location_ZipCode'] . '"
                            }
                        },
                        "convicted": "",
                        "conviction": [
                            {
                                "convictionDate": "",
                                "description": "",
                                "location": {
                                    "countryCode": "",
                                    "region": "",
                                    "region2": "",
                                    "city": ""
                                }
                            }
                        ],
                        "specialInstruction": "some optional text goes here",
                        "copyOfReport": {
                            "email": "dev@automotohr.com"
                        }
                    }
                    ';
                    $this->load->helper('assurehire');
                    $result = placeOrderAH($data);
                }else{
                    //$url = 'https://validusuat.accuratebackground.com/order/create/AUTOHR/' . $package_code;
                    //$url = 'https://validusuat.accuratebackground.com/order/create/AMY/' . $package_code;
                    //$result = $this->CallAPI('POST', $url, $data);
                    $result = ab_post_order($package_code, $data, $this->api_mode);
                }
                //$result = '{"orderResult":"success","candidate":{"firstName":"Mohammad","lastName":"Muzammil","middleName":""},"orderInfo":{"referenceCode1":"Egenie Next Web Solutions","referenceCode2":"Haseeb Saif","referenceCode3":"intactwebsolutions.com","referenceCode4":"Your billing code 4","referenceCode5":"Your billing code 5","searchId":33095152,"packageId":"Y5811249"}}';
                return $result;
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Please Fill Full Employment Application in Order to process Background Check.');
                switch ($users_type) {
                    case 'applicant':
                        redirect('applicant_profile/' . $users_id.'/'.$jobs_listing, 'refresh');
                        break;
                    case 'employee':
                        redirect('employee_profile/' . $users_id, 'refresh');
                        break;
                }
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Select an Applicant to run a Background Check.');
            redirect(base_url('application_tracking_system/active/all/all/all/all'));
        }
    }

    function get_order_response($reload_location, $package_id) {
        if ($package_id != NULL) {
            /* $url = 'https://validusuat.accuratebackground.com/order/get/' . $package_id;
              $result = $this->CallAPI('GET', $url, false);
              //$result = '{"orderInfo":{"referenceCode1":"Egenie Next Web Solutions","referenceCode2":"Haseeb Saif","referenceCode3":"intactwebsolutions.com","referenceCode4":"Your billing code 4","referenceCode5":"Your billing code 5","searchId":"33095152","packageId":"Y5811249","packageTitle":"Basic Package","compCode":"AUTOHR","orderDate":"2016-02-11","requestor":{"email":"steven@automohr.com","name":{"firstname":"","lastname":""}},"positionLocation":"","candidate":{"name":{"firstname":"Mohammad","lastname":"Muzammil","middlename":""},"aka":[],"dateOfBirth":"","ssn":"","governmentId":{"countryCode":"","type":"","number":""},"contact":{"email":"mmuzammil@egenienext.com","phone":"03054581930"},"address":{"street":"","street2":"","city":"","region":"","country":"United States","postalCode":""}},"convicted":"False","convictionDetails":"","notes":"Search back 7 yrs. \r\nsome optional text goes here"},"orderStatus":{"status":"DRAFT","result":"N/A","percentageComplete":"0","completedDate":"","notes":"","summary":[]},"version":"1.0"}';
             */
            //$result = ab_get_order_response($package_id);
            //return $result;
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please provide a valid package id.');
            redirect(base_url($reload_location));
        }
    }

    public function drug_test($type = NULL, $sid = NULL, $jobs_listing = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'drug_test');

            if ($type != null && $sid > 0) {
                if ($type == 'employee') {
                    $data_function = employee_right_nav($sid);
                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data_function['security_details'] = $security_details;
                    check_access_permissions($security_details, 'dashboard', 'drug_test'); // Param1: security array, param2: redirect url, 3rd param: function name
                    $data_function["return_title_heading"] = "Employee Profile";
                    $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid ;
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data_function['title'] = 'Employee / Team Member Drug Test';
                    $reload_location = 'drug_test/employee/' . $sid ;
                    $data_function['cancel_url'] = 'employee_management';
                    $data_function["employer"] = $this->dashboard_model->get_company_detail($sid);
                    $data_function['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee'); //getting average rating of applicant
                }

                if ($type == 'applicant') {
                    $data_function = applicant_right_nav($sid);
                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data_function['security_details'] = $security_details;
                    check_access_permissions($security_details, 'dashboard', 'drug_test'); // Param1: security array, param2: redirect url, 3rd param: function name
                    //check if Applicant Exists?
                    $applicantQueryObject = $this->background_check_model->applicant_details($sid, $company_id);

                    if ($applicantQueryObject->num_rows() == 0) {
                        $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                        redirect('application_tracking_system/active/all/all/all/all');
                    }
                    //Get Applicant Details for Profile top view - Start
                    $data_function['applicant_notes'] = $this->application_tracking_system_model->getApplicantNotes($sid); //Getting Notes
                    $data_function['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                    //Get Applicant Details for Profile top view - End
                    $data_function["return_title_heading"] = "Applicant Profile";
                    $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' .$jobs_listing;
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data_function['title'] = 'Applicant Drug Test';
                    $reload_location = 'drug_test/applicant/' . $sid . '/' .$jobs_listing;
                    $data_function['cancel_url'] = 'applicant_profile/' . $sid . '/' .$jobs_listing;
                    $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                    $data_function['applicant_info'] = $applicant_info;
                    $data_function['job_list_sid'] = $jobs_listing;

                    //getting Company accurate backgroud check
                    $data_function['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);
                    //Outsourced HR Compliance and Onboarding check
                    $data_function['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($type)
                    );
                    $data_function['employer'] = $data_employer;
                }

                $full_access = false;
                $data_function['questions_sent'] = $this->application_tracking_system_model->check_sent_video_questionnaires($sid, $company_id);
                $data_function['questions_answered'] = $this->application_tracking_system_model->check_answered_video_questionnaires($sid, $company_id);

                if ($employer_access_level == 'Admin') {
                    $full_access = true;
                }

                $data_function['page'] = 'drug_report';
                $data_function['employer_access_level'] = $employer_access_level;
                $data_function['full_access'] = $full_access;
                $data_function['left_navigation'] = $left_navigation;
                //Start==> Checking purchased and not purchased backgroung Check products//
                $this->load->model('dashboard_model');
                $product_type = 'drug-testing';

                //Update Order Status on Load - start
                $appliedProductsQueryObject = $this->background_check_model->getProductsAlreadyAppliedOn($sid, $type, $product_type);
                $appliedProductsArray = $appliedProductsQueryObject->result_array();

                foreach ($appliedProductsArray as $order) {
                    $ab_order_sid = $order['sid'];
                    $package_id = $order['package_id'];
                    $this->get_order_status($package_id, $ab_order_sid);
                }

                //Update Order Status on Load - end
                $data_function['purchasedProducts'] = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);

                $appliedProductsQueryObject = $this->background_check_model->getProductsAlreadyAppliedOn($sid, $type, $product_type);
                $appliedProductsArray = $appliedProductsQueryObject->result_array();

                if (!empty($appliedProductsArray)) {
                    foreach ($appliedProductsArray as $key => $product) {
                        $order_status = $product['order_response'];
                        $package_response = $product['package_response'];

                        if ($order_status != '') {
                            $order_status = unserialize($order_status);
                        } else {
                            $order_status = array();
                        }

                        if ($package_response != '') {
                            $package_response = unserialize($package_response);
                        } else {
                            $package_response = array();
                        }

                        $appliedProductsArray[$key]['order_response'] = $order_status;
                        $appliedProductsArray[$key]['package_response'] = $package_response;
                    }
                }
// 149 + 892
                $data_function['appliedProducts'] = $appliedProductsArray;
                $product_ids = NULL;

                if (!empty($data_function['purchasedProducts'])) {
                    foreach ($data_function['purchasedProducts'] as $myKey => $product) {
                        $product_ids[$myKey] = $product['product_sid'];
                        $product['appliedOn'] = 'false';
                        if ($appliedProductsQueryObject->num_rows() > 0) {
                            foreach ($appliedProductsQueryObject->result_array() as $appliedProduct) {

                                $package_response = unserialize($appliedProduct['package_response']);

                                if(isset($package_response['errors'])){
                                    $product['appliedOn'] = 'false';
                                } else {
                                    if ($product['product_sid'] == $appliedProduct['product_sid'] && $appliedProduct['order_refunded'] == 0) {
                                        $product['appliedOn'] = 'true';
                                    }
                                }
                            }
                        }
                        $data_function['purchasedProducts'][$myKey] = $product;
                    }
                }

                $data_function['notPurchasedProducts'] = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                //End==> Checking purchased backgroung Check products//
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'place_background_check_order':
                        $this->form_validation->set_rules('productId', 'Product', 'required|trim|xss_clean');
                        break;
                    case 'get_order_status':
                        $this->form_validation->set_rules('package_id', 'Package Id', 'required|trim|xss_clean');
                        break;
                    case 'get_report';
                        $this->form_validation->set_rules('search_id', 'Search Id', 'required|trim|xss_clean');
                        break;
                }
                $data_function['job_list_sid'] = $jobs_listing;
                $data_function['market_place_url'] = 'market_place/drug-testing';
                //form data valdation ends
                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('main/header', $data_function);
                    $this->load->view('manage_employer/background_check');
                    $this->load->view('main/footer');
                } else {
                    $perform_action = $this->input->post('perform_action');
                    switch ($perform_action) {
                        case 'place_background_check_order':
                            $formpost = array($this->input->post('productId'));
                            $dataToSave['employer_sid'] = $employer_sid;
                            $dataToSave['company_sid'] = $company_id;
                            $dataToSave['users_sid'] = $sid;
                            $dataToSave['users_type'] = $type;
                            $dataToSave['date_applied'] = date('Y-m-d H:i:s');
                            $productCounter = $this->dashboard_model->checkPurchasedProductQty($formpost, $company_id, $product_type);
                            //Start=>> checking that the applied products still have counter greater than 1?
                            foreach ($productCounter as $product) {
                                if ($product['remaining_qty'] <= 0) {
                                    $this->session->set_flashdata('message', '<b>Error:</b> Some error occured while processing your request, Please try again.');
                                    redirect($reload_location, 'refresh');
                                }
                            }
                            //End=>> checking that the applied products still have counter greater than 1?
                            //foreach ($formpost as $productId) {

                            $productId = $this->input->post('productId');
                            $res = $this->dashboard_model->productDetail($productId);

                            if ($this->api_mode == 'live') {
                                $package_code = $res[0]['package_code'];
                            } else {
                                $package_code = 3487;
                            }
                            //Placing BG check order
                            $package_response = $this->place_bg_check_order($package_code, $sid, $type, $jobs_listing);
                            $package_response = json_decode($package_response, true);
                            // Error occuresd
                            if(json_last_error() !== JSON_ERROR_NONE){
                                $email_message = '<pre>' . print_r($this->input->post(), true) . print_r($package_response, true) . '</pre>';
                                // mail(TO_EMAIL_DEV, 'Accurate Background Check Order Error ' . date('Y-m-d H:i:s'), $email_message);
                                $this->session->set_flashdata('message', '<b>Error!:</b> Failed to place a drug test.');
                                if ($type == 'applicant') {
                                    redirect('drug_test/applicant/' . $sid, 'refresh');
                                } else {
                                    redirect('drug_test/employee/' . $sid, 'refresh');
                                }
                                return;
                            }
                            $dataToSave['package_response'] = serialize($package_response);
                            $dataToSave['package_id'] = $package_response['orderInfo']['packageId'];
                            //Checking BG check order Response
                            //$dataToSave['order_response'] = $order_response = $this->get_order_response($reload_location, $dataToSave['package_id']);
                            //$dataToSave['order_response'] = $result = ab_get_order_response($dataToSave['package_id']);
                            //$order_response = json_decode($result);
                            $dataToSave['order_response_status'] = '';

                            if (isset($res)) {
                                $dataToSave['product_sid'] = $productId;
                                $dataToSave['product_price'] = $res[0]['price'];
                                $dataToSave['product_name'] = $res[0]['name'];
                                $dataToSave['product_type'] = $res[0]['product_type'];
                                $dataToSave['product_image'] = $res[0]['product_image'];
                            }
                            //saving BG-Check data to table
                            $this->background_check_model->saveBackgroundOrder($dataToSave);
                            //Deducting Quantity from Order-Product
                            $result = $this->dashboard_model->deduct_product_qty($productId, $company_id);
                            //}
                            $this->session->set_flashdata('message', '<b>Success:</b> Request placed for Background Check, you can check status after one Hour.');

                            if ($type == 'applicant') {
                                redirect('drug_test/applicant/' . $sid, 'refresh');
                            } else {
                                redirect('drug_test/employee/' . $sid, 'refresh');
                            }

                            break;
                        case 'get_order_status':
                            $package_id = $this->input->post('package_id');
                            $ab_order_sid = $this->input->post('ab_order_sid');
                            $this->get_order_status($package_id, $ab_order_sid);
                            /* Commented on Purpose
                              $order_status = ab_get_order_status($package_id, $this->api_mode);

                              if (!empty($order_status) || $order_status != '') {
                              $order_status = json_decode($order_status, true);
                              } else {
                              $order_status = array();
                              }

                              $order_status = serialize($order_status);

                              $this->background_check_model->update_order_status($ab_order_sid, $order_status);

                             */

                            $this->session->set_flashdata('message', '<strong>Success: </strong> Order Status successfully fetched.');
                            redirect('drug_test/' . $type . '/' . $sid, 'refresh');
                            break;
                        case 'get_report':
                            $search_id = $this->input->post('search_id');
                            $report = ab_get_report($search_id, 'pdf', $this->api_mode);
                            $report_data = json_decode($report, true);

                            if (!empty($report_data)) {
                                $pdf_report = base64_decode($report_data['resultReport']);
                                header('Content-Type: application/pdf');
                                header('Content-Disposition: attachment; filename="report.pdf"');
                                echo $pdf_report;
                            }
                            break;
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No user selected!');
                redirect('application_tracking_system/active/all/all/all/all', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function drug_report($reportId = NULL) {
        if ($reportId != NULL) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $background_order = $this->background_check_model->getBackgroundOrderDetails($reportId);

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'drug_test');

            if (!empty($background_order)) {
                $background_order = $background_order[0];
            } else {// emergency contact does not exists.
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('dashboard'));
                }
            }

            $type = $background_order['users_type'];
            $sid = $background_order['users_sid'];

            if ($type == 'employee') {
                $data_function = employee_right_nav($sid);
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data_function['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'drug_test'); // Param1: security array, param2: redirect url, 3rd param: function name           
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Emergency Drug Test';
                $reload_location = 'drug_test/employee/' . $sid;
                $data_function['cancel_url'] = '';
                $data_function["employer"] = $this->dashboard_model->get_company_detail($sid);
            }

            if ($type == 'applicant') {
                $data_function = applicant_right_nav($sid);
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', 'drug_test'); // Param1: security array, param2: redirect url, 3rd param: function name           
                //check if Applicant Exists?
                $applicantQueryObject = $this->background_check_model->applicant_details($sid, $company_id);

                if ($applicantQueryObject->num_rows() == 0) {
                    $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                    redirect('application_tracking_system/active/all/all/all/all');
                }

                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Drug Test';
                $reload_location = 'drug_test/applicant/' . $sid;
                $data_function['cancel_url'] = 'applicant_profile/' . $sid;
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number']
                );

                $data_function['employer'] = $data_employer;
            }

            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }
            $data_function['backgorund_order']['product_name'] = $background_order['product_name'];
            $data_function['employer_access_level'] = $employer_access_level;
            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            //Start =>>  Working for Background check response
            $order_response = $this->get_order_response($reload_location, $background_order['package_id']);
            $order_response_decode = json_decode($order_response);
            $updatedDataToSave['order_response_status'] = $data_function['backgorund_order']['order_response_status'] = $order_response_decode->orderStatus->status;
            $updatedDataToSave['order_response'] = $order_response;
            $data_function['backgorund_order']['order_response'] = $order_response_decode;
            $this->background_check_model->updateBackgroundCheckOrder($reportId, $updatedDataToSave);
            //End =>>  Working for Background check response
            $this->form_validation->set_rules('productId[]', 'Product', 'required');
            //form data valdation ends
            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/background_report');
                $this->load->view('main/footer');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Invalid path.');
            redirect(base_url('dashboard'));
        }
    }

    public function list_account_packages() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            //check_access_permissions($security_details, 'dashboard', 'drug_test'); // Param1: security array, param2: redirect url, 3rd param: function name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $packages = ab_get_package_codes($this->api_mode);
            echo '<pre>';

            if ($packages != '') {
                print_r($packages);
            } else {
                echo 'request failed';
            }
            echo '</pre>';
            exit;
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //get order status new implementation 11-01-2017
    private function get_order_status($package_id, $ab_order_sid) {
        $order_details = $this->background_check_model->get_order_details($ab_order_sid);

        if (!empty($order_details)) {
            $package_id = $order_details['package_id'];
            $stored_order_response = $order_details['order_response'];

            if ($stored_order_response != '' && $stored_order_response != null) {
                $stored_order_response = unserialize($stored_order_response);
            } else {
                $stored_order_response = array();
            }

            //mail('j.taylor.title@gmail.com', 'AB Debug - Order Response not Found', print_r($stored_order_response, true));

            if (isset($stored_order_response['orderStatus'])) {
                $stored_status = strtolower($stored_order_response['orderStatus']['status']);
                $percentage_complete = intval($stored_order_response['orderStatus']['percentageComplete']);

                if ($percentage_complete < 100) {
                    $order_status = ab_get_order_status($package_id, $this->api_mode);

                    if (!empty($order_status) || $order_status != '') {
                        $order_status = json_decode($order_status, true);

                        if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                            $status = strtolower($order_status['orderStatus']['status']);

                            if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                                $product_sid = $order_details['product_sid'];
                                $company_sid = $order_details['company_sid'];
                                $employer_sid = $order_details['employer_sid'];
                                $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                                $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                            }
                        }
                    } else {
                        $order_status = array();
                    }
                    $order_status = serialize($order_status);
                    $this->background_check_model->update_order_status($ab_order_sid, $order_status);
                }
            } else {
                $order_status = ab_get_order_status($package_id, $this->api_mode);

                if (!empty($order_status) || $order_status != '') {
                    $order_status = json_decode($order_status, true);

                    if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                        $status = strtolower($order_status['orderStatus']['status']);

                        if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                            $product_sid = $order_details['product_sid'];
                            $company_sid = $order_details['company_sid'];
                            $employer_sid = $order_details['employer_sid'];
                            $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                            $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                        }
                    }
                } else {
                    $order_status = array();
                }

                $order_status = serialize($order_status);
                $this->background_check_model->update_order_status($ab_order_sid, $order_status);
            }
        } else {
            //mail('j.taylor.title@gmail.com', 'AB Debug - Order Response not Found', 'Debug');

            $order_status = ab_get_order_status($package_id, $this->api_mode);

            if (!empty($order_status) || $order_status != '') {
                $order_status = json_decode($order_status, true);

                if (isset($order_status['orderStatus'])) { //Check Order Status if Cancelled Refund Item
                    $status = strtolower($order_status['orderStatus']['status']);

                    if ($status == 'cancelled' && $order_details['order_refunded'] == 0) {
                        $product_sid = $order_details['product_sid'];
                        $company_sid = $order_details['company_sid'];
                        $employer_sid = $order_details['employer_sid'];
                        $this->background_check_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, 1);
                        $this->background_check_model->update_order_refund_status($ab_order_sid, 1);
                    }
                }
            } else {
                $order_status = array();
            }

            $order_status = serialize($order_status);
            $this->background_check_model->update_order_status($ab_order_sid, $order_status);
        }
    }

    public function test() {
        $packages = ab_get_package_codes($this->api_mode);

        echo '<pre>';
        print_r($packages);
        exit;
    }

}