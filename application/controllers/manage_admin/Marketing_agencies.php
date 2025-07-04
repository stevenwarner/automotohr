<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Marketing_agencies extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/marketing_agencies_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $redirect_url = 'manage_admin';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $this->form_validation->set_rules('sid', 'sid', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $marketing_agencies = $this->marketing_agencies_model->get_all_marketing_agencies();
            $this->data['marketing_agencies'] = $marketing_agencies;
            $this->data['page_title'] = 'Marketing Agencies';
            $this->render('manage_admin/marketing_agencies/index');
        } else {
            $perform_action = $this->input->post('perform_action');
            $sid = $this->input->post('sid');

            switch ($perform_action) {
                case 'delete_marketing_agency':
                    $data_to_update = array();
                    $data_to_update['is_deleted'] = 1;
                    $this->marketing_agencies_model->update_marketing_agency($sid,$data_to_update);
                    $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Deleted!');
                    redirect('manage_admin/marketing_agencies', 'refresh');
                    break;
                case 'deactivate_marketing_agency':
                    $this->marketing_agencies_model->set_status_of_marketing_agency($sid, 0);
                    $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Deactivated!');
                    redirect('manage_admin/marketing_agencies', 'refresh');
                    break;
                case 'activate_marketing_agency':
                    $this->marketing_agencies_model->set_status_of_marketing_agency($sid, 1);
                    $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Activated!');
                    redirect('manage_admin/marketing_agencies', 'refresh');
                    break;
            }
        }
    }

    public function add_marketing_agency() {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'add_edit_marketing_agency';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $all_agencies = $this->marketing_agencies_model->get_all_marketing_agencies();
        $this->data['all_agencies'] = $all_agencies;
        $this->form_validation->set_rules('full_name', 'Full Name', 'xss_clean|required|trim');
        $this->form_validation->set_rules('contact_number', 'Contact Number', 'xss_clean|trim');
        $this->form_validation->set_rules('address', 'Address', 'xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email|trim');
        $this->form_validation->set_rules('initial_commission_value', 'Initial Commission', 'xss_clean|required|trim|numeric');
        $this->form_validation->set_rules('initial_commission_type', 'Initial Commission Type', 'xss_clean|required|trim');
        $this->form_validation->set_rules('recurring_commission_value', 'Recurring Commission', 'xss_clean|required|trim|numeric');
        $this->form_validation->set_rules('recurring_commission_type', 'Recurring Commission Type', 'xss_clean|required|trim');
        $this->form_validation->set_rules('username', 'User Name', 'trim|is_unique[marketing_agencies.username]');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Add Marketing Agency';
            $this->render('manage_admin/marketing_agencies/add_marketing_agency');
        } else {
            $full_name = $this->input->post('full_name');
            $contact_number = $this->input->post('contact_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $paypal_email = $this->input->post('paypal_email');
            $zip_code = $this->input->post('zip_code');
            $company_name = $this->input->post('company_name');
            $website = $this->input->post('website');
            $method_of_promotion = $this->input->post('method_of_promotion');
            $list_of_emails = $this->input->post('list_of_emails');
            $notes = $this->input->post('notes');
            $initial_commission_value = $this->input->post('initial_commission_value');
            $initial_commission_type = $this->input->post('initial_commission_type');
            $recurring_commission_value = $this->input->post('recurring_commission_value');
            $recurring_commission_type = $this->input->post('recurring_commission_type');
            $secondary_initial_commission_value = $this->input->post('secondary_initial_commission_value');
            $secondary_initial_commission_type = $this->input->post('secondary_initial_commission_type');
            $secondary_recurring_commission_value = $this->input->post('secondary_recurring_commission_value');
            $secondary_recurring_commission_type = $this->input->post('secondary_recurring_commission_type');
            $referred = $this->input->post('referred');
            $username = $this->input->post('username');
            $status = 1;
            $created_by = $admin_id;
            $created_date = date('Y-m-d H:i:s');
            $data_to_insert = array();
            $data_to_insert['full_name'] = $full_name;
            $data_to_insert['contact_number'] = $contact_number;
            $data_to_insert['address'] = $address;
            $data_to_insert['email'] = $email;
            $data_to_insert['paypal_email'] = $paypal_email;
            $data_to_insert['zip_code'] = $zip_code;
            $data_to_insert['company_name'] = $company_name;
            $data_to_insert['website'] = $website;
            $data_to_insert['method_of_promotion'] = $method_of_promotion;
            $data_to_insert['list_of_emails'] = $list_of_emails;
            $data_to_insert['notes'] = $notes;
            $data_to_insert['secondary_initial_commission_value'] = $secondary_initial_commission_value;
            $data_to_insert['secondary_initial_commission_type'] = $secondary_initial_commission_type;
            $data_to_insert['secondary_recurring_commission_value'] = $secondary_recurring_commission_value;
            $data_to_insert['secondary_recurring_commission_type'] = $secondary_recurring_commission_type;
            $data_to_insert['initial_commission_value'] = $initial_commission_value;
            $data_to_insert['initial_commission_type'] = $initial_commission_type;
            $data_to_insert['recurring_commission_value'] = $recurring_commission_value;
            $data_to_insert['recurring_commission_type'] = $recurring_commission_type;
            $data_to_insert['status'] = $status;
            $data_to_insert['created_by'] = $created_by;
            $data_to_insert['created_date'] = $created_date;
            $data_to_insert['username'] = !empty($username) && $username != NULL ? $username : '';
            $data_to_insert['referred_by'] = $referred;
            $marketing_agency_sid = $this->marketing_agencies_model->insert_marketing_agency($data_to_insert);
           
            if(isset($_FILES['documents_and_forms']) && $_FILES['documents_and_forms']['name'] != '') {
                    $document_name = $_FILES['documents_and_forms']['name'];
                    $aws_document_name = upload_file_to_aws('documents_and_forms', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                    $document_insert_data['document_name'] = $document_name;
                    $document_insert_data['aws_document_name'] = $aws_document_name;
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
            }
            
            if(isset($_FILES['w9_form']) && $_FILES['w9_form']['name'] != '') {
                $document_name = $_FILES['w9_form']['name'];
                $aws_document_name = upload_file_to_aws('w9_form', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
                $document_insert_data = array();
                $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                $document_insert_data['document_name'] = $document_name;
                $document_insert_data['aws_document_name'] = $aws_document_name;
                $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
            }
            
            if(isset($_FILES['w8_form']) && $_FILES['w8_form']['name'] != '') {
                $document_name = $_FILES['w8_form']['name'];
                $aws_document_name = upload_file_to_aws('w8_form', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
                $document_insert_data = array();
                $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                $document_insert_data['document_name'] = $document_name;
                $document_insert_data['aws_document_name'] = $aws_document_name;
                $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
            }
                
            $this->session->set_flashdata('message', '<strong>Success :</strong> Marketing Agency Added!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }

    public function edit_marketing_agency($marketing_agency_sid = 0, $parent_sid = 0) {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'add_edit_marketing_agency';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $sub_account = false;
        
        if($parent_sid > 0) {
            $sub_account = true;
        }
        
        if ($marketing_agency_sid > 0) {
            $this->data['parent_sid'] = $parent_sid;
            
            if(!$sub_account) {
                $all_agencies = $this->marketing_agencies_model->get_all_marketing_agencies_except_own($marketing_agency_sid);
                $this->data['all_agencies'] = $all_agencies;
                $agreement_flag = $this->marketing_agencies_model->get_affiliate_record($marketing_agency_sid);
                $this->data['agreement_flag'] = $agreement_flag;
                $this->form_validation->set_rules('initial_commission_value', 'Initial Commission', 'xss_clean|required|trim|numeric');
                $this->form_validation->set_rules('initial_commission_type', 'Initial Commission Type', 'xss_clean|required|trim');
                $this->form_validation->set_rules('recurring_commission_value', 'Recurring Commission', 'xss_clean|required|trim|numeric');
                $this->form_validation->set_rules('recurring_commission_type', 'Recurring Commission Type', 'xss_clean|required|trim');
            }
            
            $this->form_validation->set_rules('full_name', 'Full Name', 'xss_clean|required|trim');
            $this->form_validation->set_rules('contact_number', 'Contact Number', 'xss_clean|trim');
            $this->form_validation->set_rules('address', 'Address', 'xss_clean|trim');
            $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email|trim');
            $this->form_validation->set_rules('documents_and_forms', 'Documents and Forms', 'xss_clean|trim');
            $this->form_validation->set_rules('username', 'User Name', 'xss_clean|trim');
            // documents_and_forms
            $market_documents_status = $this->marketing_agencies_model->get_documents_status($marketing_agency_sid);
            $this->data['market_documents_status'] = $market_documents_status;
            $marketing_agency = $this->marketing_agencies_model->get_single_marketing_agency($marketing_agency_sid);
            $marketing_agency_documents = $this->marketing_agencies_model->get_marketing_agency_documents($marketing_agency_sid);
            
            if ($this->form_validation->run() == false) {
                if (!empty($marketing_agency)) {
                    $this->data['marketing_agency'] = $marketing_agency;
                    $this->data['marketing_agency_documents'] = $marketing_agency_documents;
                    if(!$sub_account) {
                        $this->data['page_title'] = 'Edit Marketing Agency';
                        $this->render('manage_admin/marketing_agencies/add_marketing_agency');
                    } else {
                        $this->data['user_groups'] = $this->marketing_agencies_model->get_affiliate_groups();
                        $this->data['page_title'] = 'Edit Sub Account';
                        $this->render('manage_admin/marketing_agencies/marketing_agency_users');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency does not Exist!');
                    redirect('manage_admin/marketing_agencies', 'refresh');
                }
            } else {
                $full_name = $this->input->post('full_name');
                $contact_number = $this->input->post('contact_number');
                $address = $this->input->post('address');
                $email = $this->input->post('email');
                $paypal_email = $this->input->post('paypal_email');
                $zip_code = $this->input->post('zip_code');
                $company_name = $this->input->post('company_name');
                $website = $this->input->post('website');
                $method_of_promotion = $this->input->post('method_of_promotion');
                $list_of_emails = $this->input->post('list_of_emails');
                $notes = $this->input->post('notes');
                $initial_commission_value = $this->input->post('initial_commission_value');
                $initial_commission_type = $this->input->post('initial_commission_type');
                $recurring_commission_value = $this->input->post('recurring_commission_value');
                $recurring_commission_type = $this->input->post('recurring_commission_type');
                $secondary_initial_commission_value = $this->input->post('secondary_initial_commission_value');
                $secondary_initial_commission_type = $this->input->post('secondary_initial_commission_type');
                $secondary_recurring_commission_value = $this->input->post('secondary_recurring_commission_value');
                $secondary_recurring_commission_type = $this->input->post('secondary_recurring_commission_type');
                $referred = $this->input->post('referred');
                $username = $this->input->post('username');
                $access_level = $this->input->post('access_level');
                $status = 1;
                $modified_by = $admin_id;
                $modified_date = date('Y-m-d H:i:s');
                $data_to_update = array();
                $data_to_update['full_name'] = $full_name;
                $data_to_update['contact_number'] = $contact_number;
                $data_to_update['address'] = $address;
                $data_to_update['email'] = $email;
                $data_to_update['paypal_email'] = $paypal_email;
                $data_to_update['zip_code'] = $zip_code;
                $data_to_update['company_name'] = $company_name;
                $data_to_update['website'] = $website;
                $data_to_update['method_of_promotion'] = $method_of_promotion;
                $data_to_update['list_of_emails'] = $list_of_emails;
                $data_to_update['notes'] = $notes;
                $data_to_update['initial_commission_value'] = $initial_commission_value;
                $data_to_update['initial_commission_type'] = $initial_commission_type;
                $data_to_update['recurring_commission_value'] = $recurring_commission_value;
                $data_to_update['recurring_commission_type'] = $recurring_commission_type;
                $data_to_update['secondary_initial_commission_value'] = $secondary_initial_commission_value;
                $data_to_update['secondary_initial_commission_type'] = $secondary_initial_commission_type;
                $data_to_update['secondary_recurring_commission_value'] = $secondary_recurring_commission_value;
                $data_to_update['secondary_recurring_commission_type'] = $secondary_recurring_commission_type;
                $data_to_update['status'] = $status;
                $data_to_update['modified_by'] = $modified_by;
                $data_to_update['modified_date'] = $modified_date;
                $data_to_update['username'] = $username;
                $data_to_update['referred_by'] = $referred;
                $data_to_update['access_level'] = $access_level;
                $this->marketing_agencies_model->update_marketing_agency($marketing_agency_sid, $data_to_update);
                
                if(isset($_FILES['documents_and_forms']) && $_FILES['documents_and_forms']['name'] != '') {
                    $document_name = $_FILES['documents_and_forms']['name'];
                    $aws_document_name = upload_file_to_aws('documents_and_forms', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                    $document_insert_data['document_name'] = $document_name;
                    $document_insert_data['aws_document_name'] = $aws_document_name;
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
                }
                
                if(isset($_FILES['w9_form']) && $_FILES['w9_form']['name'] != '') {
                    $document_name = $_FILES['w9_form']['name'];
                    $aws_document_name = upload_file_to_aws('w9_form', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
//                    $aws_document_name = 'My_File.docx';
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                    $document_insert_data['document_name'] = $document_name;
                    $document_insert_data['aws_document_name'] = $aws_document_name;
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
                }
                
                if(isset($_FILES['w8_form']) && $_FILES['w8_form']['name'] != '') {
                    $document_name = $_FILES['w8_form']['name'];
                    $aws_document_name = upload_file_to_aws('w8_form', $marketing_agency_sid, $full_name, 'by_admin_' . date('Ymd'));
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                    $document_insert_data['document_name'] = $document_name;
                    $document_insert_data['aws_document_name'] = $aws_document_name;
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
                }
                
                $this->session->set_flashdata('message', '<strong>Success :</strong> Marketing Agengy updated successfully!');
                if(!$sub_account) {
                    redirect('manage_admin/marketing_agencies/', 'refresh');
                } else{
                    redirect('manage_admin/marketing_agencies/get_agency_users'.'/'.$parent_sid, 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency does not Exist!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }

    public function generate_prefill(){
        $this->load->model('manage_admin/documents_model');
        $market_sid = $this->input->post('market_sid');
        $verification_key = random_key(80);
        $this->documents_model->insert_affiliate_document_record($market_sid, $verification_key, 'generated');
        redirect(base_url('form_affiliate_end_user_license_agreement/'.$verification_key.'/pre_fill'),'refresh');
    }

    public function manage_commissions($marketing_agency_sid = 0, $startdate = "all", $enddate = "all", $agencies_name = "all", $payment_method = "all") {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        if ($marketing_agency_sid > 0) {
            $marketing_agency_info = $this->marketing_agencies_model->get_single_marketing_agency($marketing_agency_sid);

            if (!empty($marketing_agency_info)) {
                $this->data['marketing_agency_info'] = $marketing_agency_info;
                $marketing_agency_invoices_group = $this->marketing_agencies_model->get_commission_invoices_group($marketing_agency_sid);
                $agencies_name = urldecode($agencies_name);
                $payment_method = urldecode($payment_method);
                $startdate = urldecode($startdate);
                $enddate = urldecode($enddate);

                $agencies_name =  $agencies_name != 'all' ? (explode(',', $agencies_name)) : $agencies_name;

                if (isset($startdate) && $startdate != "" && $startdate != "all") {
                    $start_date = explode('-', $startdate);
                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                } else {
                    $start_date = '01-01-1970 00:00:00';
                }

                if (isset($enddate) && $enddate != "" && $enddate != "all") {
                    $end_date = explode('-', $enddate);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "commission_invoices.created between '" . $start_date . "' and '" . $end_date . "'";

                $search_result = $this->marketing_agencies_model->get_commissions_invoices($marketing_agency_sid, $agencies_name, $payment_method, $between);
                $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

                if ($this->form_validation->run() == false) {
                    $search_filters = array();
                    $search_filters['companies']= $agencies_name;
                    $search_filters['payment'] = $payment_method;
                    $search_filters['start'] = $startdate;
                    $search_filters['end'] = $enddate;
                    if ($startdate != "all" || $enddate != "all" || $agencies_name != "all" || $payment_method != "all") {
                        $this->data['search_filter_status'] = 1;
                    } else {
                        $this->data['search_filter_status'] = 0;
                    }
                    $this->data['marketing_agency_invoices_group'] = $search_result;
                    $this->data['search_filters'] = $search_filters;
                    $this->data['marketing_agencies_group'] = $marketing_agency_invoices_group;
                    $this->data['page_title'] = 'Manage Commissions';
                    $this->render('manage_admin/marketing_agencies/manage_commissions');
                } else  {
                    $search_companies = $this->input->post('company_name');
                    $payment_status = $this->input->post('payment_type');
                    $to_date = $this->input->post('date_to');
                    $from_date = $this->input->post('date_from');
                    $action = $this->input->post('action');
                    $companies_filter = isset($search_companies) && !empty($search_companies) ? $search_companies : NULL ;
                    $payment_filter = isset($payment_status) && !empty($payment_status) ? $payment_status : NULL ;
                    $start_filter = isset($from_date) && !empty($from_date) ? DateTime::createFromFormat('m-d-Y', $from_date)->format('Y-m-d 00:00:00') : NULL ;
                    $end_filter = isset($to_date) && !empty($to_date) ? DateTime::createFromFormat('m-d-Y', $to_date)->format('Y-m-d 00:00:00') : NULL ;

                    $rows = '';
                    $agency_name = $marketing_agency_info['full_name'];
                    $commission_invoice_for_csv = $this->marketing_agencies_model->csv_commission_invoices($marketing_agency_sid, $companies_filter, $payment_filter, $start_filter, $end_filter);

                    foreach($commission_invoice_for_csv as $key => $data){

                        foreach($data as $row){
                            $payable = '';
                            if($row['discount_percentage'] > 0 && $row['discount_amount']> 0 ) {
                                $payable = '$'. number_format($row['total_commission_after_discount'], 2);
                            } else {
                                $payable = '$'.number_format($row['commission_value'], 2);
                            }
                            $payable = str_replace(",","",$payable);
                            $rows .= $key . ',' .convert_date_to_frontend_format( $row['created'], true) . ',' . $row['invoice_number'] . ',' . $payable . ',' . $row['payment_status'] . PHP_EOL;
                        }
                    }

                    $header_row = 'Company Name,Date,Commission Invoice Number,Commission Payable,Payment Status';
                    $file_content = '';
                    $file_content .= $header_row . PHP_EOL;
                    $file_content .= $rows;
                    $file_size = 0;

                    if (function_exists('mb_strlen')) {
                        $file_size = mb_strlen($file_content, '8bit');
                    } else {
                        $file_size = strlen($file_content);
                    }

                    header('Pragma: public');     // required
                    header('Expires: 0');         // no cache
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Cache-Control: private', false);
                    header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                    header('Content-Disposition: attachment; filename="comission_' . $agency_name . '_' . date('Y_m_d_H:i:s') . '.csv"');  // Add the file name
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . $file_size); // provide file size
                    header('Connection: close');
                    echo $header_row . PHP_EOL;
                    echo $rows;
                    exit;
                    
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency Not Found!');
                redirect('manage_admin/marketing_agencies', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency Not Found!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }

    public function pdf_download($marketing_agency_sid = 0, $startdate = "all", $enddate = "all", $agencies_name = "all", $payment_method = "all") {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($marketing_agency_sid > 0) {
            $marketing_agency_info = $this->marketing_agencies_model->get_single_marketing_agency($marketing_agency_sid);

            if (!empty($marketing_agency_info)) {
                $this->data['marketing_agency_info'] = $marketing_agency_info;
                $agencies_name = urldecode($agencies_name);
                $payment_method = urldecode($payment_method);
                $startdate = urldecode($startdate);
                $enddate = urldecode($enddate);

                $agencies_name =  $agencies_name != 'all' ? (explode(',', $agencies_name)) : $agencies_name;

                if (isset($startdate) && $startdate != "" && $startdate != "all") {
                    $start_date = explode('-', $startdate);
                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                } else {
                    $start_date = '01-01-1970 00:00:00';
                }

                if (isset($enddate) && $enddate != "" && $enddate != "all") {
                    $end_date = explode('-', $enddate);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "commission_invoices.created between '" . $start_date . "' and '" . $end_date . "'";

                $search_result = $this->marketing_agencies_model->get_commissions_invoices($marketing_agency_sid, $agencies_name, $payment_method, $between);
                $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

                $this->data['marketing_agency_invoices_group'] = $search_result;
                $this->render('manage_admin/marketing_agencies/manage_commissions_pdf');
            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency Not Found!');
                redirect('manage_admin/marketing_agencies', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error :</strong> Marketing Agency Not Found!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }

    public function add_manual_commission($marketing_agency_sid = 0){
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'add_edit_marketing_agency';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('company', 'Company Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('invoice_number', 'Invoice Number', 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|xss_clean');
        $this->form_validation->set_rules('applied', 'Commission Applied', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $marketing_agency_info = $this->marketing_agencies_model->get_single_marketing_agency($marketing_agency_sid);
            $this->data['marketing_agency_info'] = $marketing_agency_info;
            $data_companies = $this->marketing_agencies_model->get_all_companies(); //get all active `countries`
            $this->data['companies'] = $data_companies;
            $this->data['marketing_agency_sid'] = $marketing_agency_sid;
            $this->render('manage_admin/marketing_agencies/add_manual_commission');
        } else {
            $company = $this->input->post('company');
            $invoice_number = $this->input->post('invoice_number');
            $date = $this->input->post('date');
            $commission_applied = $this->input->post('applied');

            if($date != NULL) {
                $date = DateTime::createFromFormat('m-d-Y', $date)->format('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $all_data = explode('/',$invoice_number);
            $company = explode('/',$company);
            $invoice_sid = $this->marketing_agencies_model->Insert_commission_invoice($admin_id, $company[0], $company[2], $company[1], $marketing_agency_sid, 'super_admin', 0,'manual',$all_data[0],$all_data[1],$date);
            $this->marketing_agencies_model->Update_commission_invoice_number($invoice_sid);
            $this->session->set_flashdata('message', '<strong>Success :</strong> Commission Added Successfully!');
            redirect('manage_admin/marketing_agencies/manage_commissions/'.$marketing_agency_sid, 'refresh');
        }
    }

    public function view_commission_details($commission_invoice_sid = 0) {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'add_edit_marketing_agency';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        
        if ($this->form_validation->run() == false) {
            if ($commission_invoice_sid > 0) {
                $commission_invoice = $this->marketing_agencies_model->get_commission_invoice($commission_invoice_sid);

                if (!empty($commission_invoice)) {
                    $this->data['commission_invoice'] = $commission_invoice;
                    $this->data['page_title'] = 'View Commission Invoice Details';
                    $this->render('manage_admin/marketing_agencies/view_commission_details');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> : Commission Invoice Not Found!');
                    redirect('manage_admin/marketing_agencies', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong> : Commission Invoice Not Found!');
                redirect('manage_admin/marketing_agencies', 'refresh');
            }
        } else { //Handle Form Post
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action){
                case 'recalculate_commission':
                    $commission_invoice_sid = $this->input->post('commission_invoice_sid');
                    $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Commission Recalculated!');
                    redirect('manage_admin/marketing_agencies/view_commission_details/' . $commission_invoice_sid);
                    break;
            }
        }
    }
    
    function payment_voucher($commission_sid = 0) {
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        if ($commission_sid > 0) {
            $payment_voucher = $this->marketing_agencies_model->get_payment_voucher($commission_sid);
            $payment_voucher_email_log = $this->marketing_agencies_model->get_email_log($commission_sid);
            $this->data['payment_voucher'] = $payment_voucher['0'];
            $voucher_details = $this->marketing_agencies_model->get_payment_voucher_details($payment_voucher['0']['sid']);
            $this->data['voucher_details'] = $voucher_details;
            $this->data['payment_voucher_email_log'] = $payment_voucher_email_log;
            $this->data['page_title'] = 'Payment Voucher Details';
            
            $this->form_validation->set_rules('to_name', 'To Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('to_email', 'To Email', 'trim|xss_clean|required|valid_email');
            //$this->form_validation->set_rules('email_body', 'Email Body', 'trim|xss_clean|required');
            
            if ($this->form_validation->run() == false) {
                $this->render('manage_admin/marketing_agencies/view_payment_voucher');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $to_name = $formpost['to_name'];
                $to_email = $formpost['to_email'];
                $voucher_details = $this->marketing_agencies_model->get_payment_voucher_details($formpost['voucher_sid']);
                $view_data = array();
                $view_data['voucher'] = $voucher_details;
                $view_data['agency'] = $voucher_details['agency'];
                $view_data['company'] = $voucher_details['company'];
                $view_data['commission_invoice'] = $voucher_details['commission_invoice'];
                $view_data['invoice'] = $voucher_details['invoice'];
                $view_data['invoice_origin'] = $voucher_details['invoice_origin'];
                $voucher_html = $this->load->view('manage_admin/marketing_agencies/payment_voucher_partial', $view_data, true);
                $replacement_array                                              = array();
                $replacement_array['to-name']                                   = ucwords($to_name);
                $replacement_array['paid-amount']                               = '$' . number_format($payment_voucher[0]['paid_amount'], 2);
                $replacement_array['marketing-agency-name']                     = $payment_voucher[0]['marketing_agency_name'];
                $replacement_array['payment-voucher']                           = $voucher_html;
                log_and_send_templated_email(PAYMENT_VOUCHER_EMAIL, $to_email, $replacement_array);                
                $this->marketing_agencies_model->record_payment_voucher_emails($to_name , $to_email, $payment_voucher[0]);
                $this->session->set_flashdata('message', '<strong>Success</strong>: Payment Voucher Email sent successfully!');   
                redirect('manage_admin/marketing_agencies/payment_voucher/'.$payment_voucher['0']['commission_invoice_sid'], 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong> : Payment voucher not found!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }
    
    function edit_payment_voucher($sid = 0){
        $redirect_url = 'manage_admin/marketing_agencies';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        if ($sid > 0) {
            $voucher_details = $this->marketing_agencies_model->payment_voucher_by_sid($sid);
            
            if(!empty($voucher_details)){
                $this->form_validation->set_rules('paid_amount', 'Payment Amount', 'trim|xss_clean|required');
                $this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|xss_clean');
                $this->form_validation->set_rules('payment_reference', 'Payment Reference', 'trim|xss_clean');
                $this->form_validation->set_rules('payment_description', 'Payment Description', 'trim|xss_clean');
                
                if ($this->form_validation->run() == false) {
                    $this->data['payment_voucher'] = $voucher_details['0'];
                    $this->data['payment_method'] = $voucher_details['0']['payment_method'];
                    $this->data['payment_reference'] = $voucher_details['0']['payment_reference'];
                    $this->data['payment_description'] = $voucher_details['0']['payment_description'];
                    $this->data['page_title'] = 'Edit Payment Voucher Details';
                    $this->render('manage_admin/marketing_agencies/edit_payment_voucher');
                } else {
                    $formpost = $this->input->post(NULL, TRUE);
                    $data_to_update = array();
                    $data_to_update = array('paid_amount' => $formpost['paid_amount'],
                                            'payment_method' => $formpost['payment_method'],
                                            'payment_reference' => $formpost['payment_reference'],
                                            'payment_description' => $formpost['payment_description']);
                    $this->marketing_agencies_model->update_payment_voucher($sid, $data_to_update);
                    $commission_invoice_sid = $voucher_details['0']['commission_invoice_sid'];
                    $this->marketing_agencies_model->update_commission_amount_in_commission_invoice($commission_invoice_sid, $formpost['paid_amount']);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>: Payment voucher updated successfully!');
                    redirect('manage_admin/marketing_agencies/payment_voucher/'.$commission_invoice_sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong>: Payment voucher not found!');
                redirect('manage_admin/marketing_agencies', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong>: Payment voucher not found!');
            redirect('manage_admin/marketing_agencies', 'refresh');
        }
    }

    public function ajax_responder() {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'mark_commission_invoice_as_commission_paid':
                $commission_invoice_sid = $this->input->post('commission_invoice_sid');
                $this->marketing_agencies_model->mark_commission_invoice_as_commission_paid($commission_invoice_sid);
                echo 'success';
            break;
            case 'mark_payment_voucher_as_paid':
                $sid = $this->input->post('sid');
                $commission_invoice_sid = $this->input->post('commission_invoice_sid');
                $this->marketing_agencies_model->payment_voucher_is_paid($sid, $commission_invoice_sid);
                echo 'success';
            break;
            case 'mark_payment_voucher_as_unpaid':
                $sid = $this->input->post('sid');
                $commission_invoice_sid = $this->input->post('commission_invoice_sid');
                $this->marketing_agencies_model->payment_voucher_is_paid($sid, $commission_invoice_sid, 'unpaid');
                echo 'success';
            break;
            case 'delete_inovice_and_voucher':
                $sid = $this->input->post('sid'); // delete invoice and vocher against this invoice. e.g. commission_invoices, commission_invoice_items, payment_voucher, payment_voucher_emails_log
                $this->marketing_agencies_model->delete_invoice_and_voucher($sid);
                echo 'success';
            break;
            case 'delete_voucher_data':
                $sid = $this->input->post('sid'); // delete vocher. e.g. payment_voucher, payment_voucher_emails_log
                $this->marketing_agencies_model->delete_voucher_data($sid);
            break;
            case 'fetch_invoices':
                $cid = $this->input->post('cid');
                $invoices = $this->marketing_agencies_model->fetch_voucher_data($cid);
                print_r(json_encode($invoices));
            break;
        }
    }
    
    function update_voucher_nos() {
        $this->marketing_agencies_model->update_payment_voucher_no();
    }

    public function send_login_request(){
        $username = $this->input->post('username');
        $flag = $this->input->post('flag');
        $id = $this->input->post('id');
        $marketing_agency = $this->marketing_agencies_model->get_single_marketing_agency($id);
        $verification_code = generateRandomString(24);
        
        if($flag == 'db'){
            $replacement_array['affiliate_name'] = $marketing_agency['full_name'];
            $replacement_array['username'] = $marketing_agency['username'];
            $replacement_array['create_password_link'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "affiliate_portal/generate-password/" . $verification_code . '">Create Your Password</a>';
            log_and_send_templated_email(NEW_AFFILIATE_PROGRAM_LOGIN_REQUEST, $marketing_agency['email'], $replacement_array);
            $this->marketing_agencies_model->update_marketing_agency($id,array('verification_key' => $verification_code));
            echo 'success';
        } else{
            $result_check = $this->marketing_agencies_model->check_username($username);
            
            if(sizeof($result_check)>0){
                echo 'exist';
            } else{
                $this->marketing_agencies_model->set_username($username,$id);
                $replacement_array['affiliate_name'] = $marketing_agency['full_name'];
                $replacement_array['username'] = $marketing_agency['username'];
                $replacement_array['create_password_link'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "affiliate_portal/generate-password/" . $verification_code . '">Create Your Password</a>';

                log_and_send_templated_email(NEW_AFFILIATE_PROGRAM_LOGIN_REQUEST, $marketing_agency['email'], $replacement_array);
                $this->marketing_agencies_model->update_marketing_agency($id,array('verification_key' => $verification_code));
                echo 'success';
            }
        }
    }

    function affiliate_login() {
        $redirect_url = 'manage_admin';
        $function_name = 'affiliate_login';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
//        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $action = $this->input->post('action');
        $affiliate_sid = $this->input->post('sid');
        
        if ($action == 'login') {
            $result = $this->marketing_agencies_model->affiliate_login($affiliate_sid);
   
            if ($result['status'] == 'active') {
                $this->session->set_userdata('affiliate_loggedin', $result);
                return 'true';
            } else {
                return 'false';
            }
        }
    }
    
    function get_agency_users($sid = 0) {
        $redirect_url = 'manage_admin';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        if($sid > 0) {
            $marketing_agency_users = $this->marketing_agencies_model->get_marketing_agency_users($sid);
            $parent_agency_details = $this->marketing_agencies_model->get_single_marketing_agency($sid);
//            echo '<pre>'; print_r($parent_agency_details); exit;
            $this->data['parent_agency_details'] = $parent_agency_details;
            $this->data['marketing_agency_users'] = $marketing_agency_users;
            $this->data['page_title'] = $parent_agency_details['full_name'].' - Sub Account Users';
            $this->form_validation->set_rules('perform_action', 'perform_action', 'trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->render('manage_admin/marketing_agencies/agency_users');
            } else {
                $perform_action = $this->input->post('perform_action');
                $agency_sid = $this->input->post('sid');

                switch ($perform_action) {
                    case 'delete_marketing_agency':
                        $data_to_update = array();
                        $data_to_update['is_deleted'] = 1;
                        $this->marketing_agencies_model->update_marketing_agency($agency_sid,$data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Sub Account User Deleted!');
                        redirect('manage_admin/marketing_agencies/get_agency_users/'.$sid, 'refresh');
                        break;
                    case 'deactivate_marketing_agency':
                        $this->marketing_agencies_model->set_status_of_marketing_agency($agency_sid, 0);
                        $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Sub Account User Deactivated!');
                        redirect('manage_admin/marketing_agencies/get_agency_users/'.$sid, 'refresh');
                        break;
                    case 'activate_marketing_agency':
                        $this->marketing_agencies_model->set_status_of_marketing_agency($agency_sid, 1);
                        $this->session->set_flashdata('message', '<strong>Success :</strong> Marketeting Agency Sub Account User Activated!');
                        redirect('manage_admin/marketing_agencies/get_agency_users/'.$sid, 'refresh');
                        break;
                }
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error :</strong> Marketeting Agency Not found!');
            redirect('manage_admin/marketing_agencies', 'refresh');  
        }
    }

    function add_agency_user($sid = 0){
        $redirect_url = 'manage_admin';
        $function_name = 'list_marketing_agencies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('full_name', 'Full Name', 'xss_clean|required|trim');
        $this->form_validation->set_rules('contact_number', 'Contact Number', 'xss_clean|trim');
        $this->form_validation->set_rules('address', 'Address', 'xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email|trim');
        $this->form_validation->set_rules('username', 'User Name', 'trim|is_unique[marketing_agencies.username]');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Add Sub Account User';
            $this->data['user_groups'] = $this->marketing_agencies_model->get_affiliate_groups();
            $this->render('manage_admin/marketing_agencies/add_agency_user');
        } else{
            $full_name = $this->input->post('full_name');
            $contact_number = $this->input->post('contact_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $zip_code = $this->input->post('zip_code');
            $website = $this->input->post('website');
            $notes = $this->input->post('notes');
            $username = $this->input->post('username');
            $access_level = $this->input->post('access_level');
            $status = 1;
            $created_by = $admin_id;
            $created_date = date('Y-m-d H:i:s');
            $data_to_insert = array();
            $data_to_insert['full_name'] = $full_name;
            $data_to_insert['contact_number'] = $contact_number;
            $data_to_insert['address'] = $address;
            $data_to_insert['email'] = $email;
            $data_to_insert['notes'] = $notes;
            $data_to_insert['status'] = $status;
            $data_to_insert['created_by'] = $created_by;
            $data_to_insert['created_date'] = $created_date;
            $data_to_insert['website'] = $website;
            $data_to_insert['zip_code'] = $zip_code;
            $data_to_insert['parent_sid'] = $sid;
            $data_to_insert['access_level'] = $access_level;
            $data_to_insert['username'] = !empty($username) && $username != NULL ? $username : '';
            $marketing_agency_sid = $this->marketing_agencies_model->insert_marketing_agency($data_to_insert);

            $this->session->set_flashdata('message', '<strong>Success :</strong> Sub Account User Added!');
            redirect('manage_admin/marketing_agencies/get_agency_users/'.$sid, 'refresh');
        }
    }
    
}