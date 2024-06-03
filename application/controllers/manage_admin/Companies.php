<?php defined('BASEPATH') or exit('No direct script access allowed');

class Companies extends Admin_Controller
{
    private $indeedProductIds;
    private $ziprecruiterProductIds;
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('users_model');
        $this->load->model('portal_email_templates_model');
        $this->load->model('manage_admin/company_model');
        $this->load->model('manage_admin/remarket_model');
        $this->load->model('manage_admin/admin_invoices_model');
        $this->load->model('manage_admin/Job_listing_templates_model');
        $this->load->model('manage_admin/invoice_model');
        $this->load->model('manage_admin/company_billing_contacts_model');
        $this->load->model('manage_admin/maintenance_mode_model');
        $this->load->model('manage_admin/marketing_agencies_model');
        $this->load->model('manage_admin/Incident_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        require_once(APPPATH . 'libraries/xmlapi.php');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->indeedProductIds = array(1, 21);
        $this->ziprecruiterProductIds = array(2);
    }

    public function index()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if (isset($_POST['execute']) && $_POST['execute'] == 'multiple_action') {
            $form_type = $_POST['type'];
            $form_action = $_POST['action'];
            $form_rows = $_POST['checkit'];

            if (in_array('full_access', $security_details) || in_array('show_company_multiple_actions', $security_details)) {
                $this->company_model->perform_multiple_action($form_type, $form_action, $form_rows);
            }
        }
        //--------------------Search section Start---------------//
        $search = array();
        $contact_name = $this->uri->segment(4) != NULL ? trim(urldecode($this->uri->segment(4))) : 'all';
        $company_name = $this->uri->segment(5) != NULL ? trim(urldecode($this->uri->segment(5))) : 'all';
        $company_type = $this->uri->segment(6) != NULL ? trim(urldecode($this->uri->segment(6))) : 1;
        $company_status = $this->uri->segment(7) != NULL ? trim(urldecode($this->uri->segment(7))) : 'all';
        $start_date = $this->uri->segment(8) != NULL ? trim(urldecode($this->uri->segment(8))) : 'all';
        $end_date = $this->uri->segment(9) != NULL ? trim(urldecode($this->uri->segment(9))) : 'all';
        $page = $this->uri->segment(10) != NULL ? trim($this->uri->segment(10)) : 1;

        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_applied = '01-01-1970 00:00:00';
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_applied = date('Y-m-d 23:59:59');
        }

        $this->data['total'] = $this->company_model->total_companies_date($contact_name, $company_name, $company_type, $company_status, $start_date_applied, $end_date_applied);
        $this->data['flag'] = false;

        if ($contact_name != 'all' || $company_name != 'all' || $company_type != 1 || $company_status != 'all' || $start_date != 'all' || $end_date != 'all' || $page != 1) {
            $this->data['flag'] = true;
        }

        //--------------------Search section End---------------//
        //Pagination
        $config = array();
        $config['total_rows'] = $this->data['total'];
        $config['base_url'] = base_url() . "manage_admin/companies" . '/search_company/' . urlencode($contact_name) . '/' . urlencode($company_name) . '/' . $company_type . '/' . $company_status . '/' . urlencode($start_date) . '/' . urlencode($end_date);
        $config['per_page'] = 50;
        $config['uri_segment'] = 10;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = round($choice);
        $config['use_page_numbers'] = true;
        //pagination style
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(10)) ? $this->uri->segment(10) : 1;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $config["per_page"];
        }

        $this->data['links'] = $this->pagination->create_links();
        $this->data['page_title'] = 'Manage Companies';

        if ($company_status == 'all') {
            $this->data['companies'] = $this->company_model->get_all_companies_date($contact_name, $company_name, $company_type, 'all', $start_date_applied, $end_date_applied, $config['per_page'], $my_offset);
        } elseif ($company_status == 1) {
            $this->data['companies'] = $this->company_model->get_all_companies_date($contact_name, $company_name, $company_type, $company_status, $start_date_applied, $end_date_applied, $config['per_page'], $my_offset);
        } else {
            $this->data['companies'] = $this->company_model->get_all_companies_date($contact_name, $company_name, $company_type, $company_status, $start_date_applied, $end_date_applied, $config['per_page'], $my_offset);
        }

        $this->render('manage_admin/company/listings_view', 'admin_master');
    }

    public function edit_company($sid = NULL)
    {
        $redirect_url                                                           = 'manage_admin';
        $function_name                                                          = 'edit_company';
        $admin_id                                                               = $this->ion_auth->user()->row()->id;
        $security_details                                                       = db_get_admin_access_level_details($admin_id);
        $this->data['security_details']                                         = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $sid                                                                    = $this->input->post('sid') ? $this->input->post('sid') : $sid;
        $groups                                                                 = $this->Job_listing_templates_model->GetAllActiveGroups(); //Get All Groups
        $groupOptions                                                           = array(); //Create Array for Dropdown Start
        $groupOptions['0']                                                      = 'No Job Listing Template Assigned';

        foreach ($groups as $group) { //Create Array for Dropdown End
            $groupOptions[$group['sid']]                                        = $group['name']; //Groups for View
        }

        $this->data['groupOptions']                                             = $groupOptions;
        $payment_type                                                           = array();
        $payment_type['Credit Card']                                            = 'Credit Card';
        $payment_type['Check']                                                  = 'Check';
        $this->data['payment_type']                                             = $payment_type;
        $past_due                                                               = array();
        $past_due['Yes']                                                        = 'Yes';
        $past_due['No']                                                         = 'No';
        $this->data['past_due']                                                 = $past_due;
        $data_countries                                                         = $this->company_model->get_active_countries(); //get all active `countries`

        foreach ($data_countries as $value) { //get all active `states`
            $data_states[$value['sid']]                                         = $this->company_model->get_active_states($value['sid']);
        }

        $this->data['industry_categories']                                      = $this->company_model->get_industry_categories();
        $this->data['active_countries']                                         = $data_countries;
        $this->data['active_states']                                            = $data_states;
        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $this->data['states']                                                   = $data_states_encode;
        $select                                                                 = 'purchased';  //getting company enterprise theme status
        $is_purchased                                                           = $this->company_model->get_company_theme_detail($sid, $select);

        if ($is_purchased) {
            $this->data['is_purchased']                                         = 'checked';
        } else {
            $this->data['is_purchased']                                         = '';
        }

        $select                                                                 = 'purchased,expiry_date';
        $fb_api_data                                                            = $this->company_model->get_company_facebook_api_detail($sid, $select);
        $this->data['facebook_api_date']                                        = $fb_api_data['expiry_date'];

        if ($fb_api_data['expiry_date'] != NULL) {
            $fb_db_date                                                     = explode(' ', $fb_api_data['expiry_date']);
            $fb_db_date                                                     = explode('-', $fb_db_date[0]);
            $this->data['facebook_api_date']                                = $fb_db_date[1] . '-' . $fb_db_date[2] . '-' . $fb_db_date[0];
        }

        $is_purchased_facebook_api                                              = $fb_api_data['purchased'];

        if ($is_purchased_facebook_api) {
            $this->data['is_purchased_facebook_api']                            = 'checked';
        } else {
            $this->data['is_purchased_facebook_api']                            = '';
        }

        $company_detail                                                         = $this->company_model->get_details($sid, 'company'); // get company detailes
        $parent_sid                                                             = 0;

        if ($company_detail) {
            $this->data['data']                                                 = $company_detail[0];
            $parent_sid                                                         = $company_detail[0]['parent_sid'];

            if ($parent_sid == 0) {
                $portal_detail                                                  = $this->company_model->get_details($sid, 'portal'); // get company detailes
                $this->data['portal_detail']                                    = $portal_detail[0];
            }
        } else {
            $this->session->set_flashdata('message', 'Company does not exists!');
            redirect('manage_admin/companies', 'refresh');
        }

        $this->data['parent_sid']                                               = $parent_sid;
        $zones                                                                  = array();
        $zones['Pacific/Honolulu']                                              = 'Hawaii-Aleutian Standard Time (HAST)';
        $zones['US/Aleutian']                                                   = 'Hawaii-Aleutian with Daylight Savings Time (HADT)';
        $zones['Etc/GMT+9']                                                     = 'Alaska Standard Time (AKST)';
        $zones['America/Anchorage']                                             = 'Alaska with Daylight Savings Time (AKDT)';
        $zones['America/Dawson_Creek']                                          = 'Pacific Standard Time (PST)';
        $zones['PST8PDT']                                                       = 'Pacific with Daylight Savings Time (PDT)';
        $zones['MST']                                                           = 'Mountain Standard Time (MST)';
        $zones['MST7MDT']                                                       = 'Mountain with Daylight Savings Time (MDT)';
        $zones['Canada/Saskatchewan']                                           = 'Central Standard Time (CST)';
        $zones['CST6CDT']                                                       = 'Central with Daylight Savings Time (CDT)';
        $zones['EST']                                                           = 'Eastern Standard Time (EST)';
        $zones['EST5EDT']                                                       = 'Eastern with Daylight Savings Time (EDT)';
        $zones['America/Puerto_Rico']                                           = 'Atlantic Standard Time (AST)';
        $zones['America/Halifax']                                               = 'Atlantic with Daylight Savings Time (ADT)';
        $this->data['timezones']                                                = $zones;

        $this->data['page_title']                                               = 'Edit Company ';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('ContactName', 'Contact Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('PhoneNumber', 'Primary Number', 'trim|xss_clean');
        $this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
        $this->form_validation->set_rules('WebSite', 'Website', 'trim|xss_clean|valid_url');
        $this->form_validation->set_rules('accounts_contact_person', 'Accounts Contact Person', 'trim|xss_clean|alpha_numeric_spaces');
        $this->form_validation->set_rules('accounts_contact_number', 'Accounts Contact Number', 'trim|xss_clean');
        $this->form_validation->set_rules('full_billing_address', 'Full Billing Address', 'trim|xss_clean|alpha_numeric_spaces');
        $this->form_validation->set_rules('marketing_agency_sid', 'Marketing Agency', 'trim|xss_clean');
        $this->form_validation->set_rules('is_paid', 'Paid Company', 'trim|xss_clean');
        $this->form_validation->set_rules('job_category_industries_sid', 'Job Category Industry', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $this->load->helper('form');
            $marketing_agencies = $this->company_model->get_all_marketing_agencies();
            $this->data['marketing_agencies'] = $marketing_agencies;
            $this->render('manage_admin/company/edit_company');
        } else {
            $sid = $this->input->post('sid');
            $action = $this->input->post('submit');
            $has_job_approval_rights = 0;
            $is_paid = 0;

            if (IS_TIMEZONE_ACTIVE) {
                // Load dashboard model
                $this->load->model('dashboard_model');
                if ($this->input->post('company_timezone', true)) {
                    $this->dashboard_model->set_new_timezone_in_old_calendar_events_by_company_id($sid,  $this->input->post('company_timezone', true));
                }
                //
                if ($parent_sid == 0) {
                    $company_timezone = $this->input->post('company_timezone', true);
                    $this->company_model->update_company_timezone($sid, $company_timezone);
                }
            }

            if ($this->input->post('has_job_approval_rights') != null) {
                $has_job_approval_rights = $this->input->post('has_job_approval_rights');
            }

            if ($this->input->post('is_paid') != null) {
                $is_paid = $this->input->post('is_paid');
            }


            $data = array(
                'CompanyName' => $this->input->post('CompanyName'),
                'ContactName' => $this->input->post('ContactName'),
                'number_of_rooftops' => $this->input->post('number_of_rooftops'),
                'Location_Country' => $this->input->post('Location_Country'),
                'Location_State' => $this->input->post('Location_State'),
                'Location_City' => $this->input->post('Location_City'),
                'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                'Location_Address' => $this->input->post('Location_Address'),
                'PhoneNumber' => $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('PhoneNumber'),
                'CompanyDescription' => $this->input->post('CompanyDescription'),
                'job_listing_template_group' => $this->input->post('job_listing_template_group'),
                //'discount_amount' => $this->input->post('discount_amount'),
                //'discount_type' => $this->input->post('discount_type'),
                'has_job_approval_rights' => $has_job_approval_rights,
                'is_paid' => $is_paid,
                'WebSite' => $this->input->post('WebSite'),
                'accounts_contact_person' => $this->input->post('accounts_contact_person'),
                'accounts_contact_number' => $this->input->post('accounts_contact_number'),
                'full_billing_address' => $this->input->post('full_billing_address'),
                'marketing_agency_sid' => ($this->input->post('marketing_agency_sid') > 0) ? $this->input->post('marketing_agency_sid') : 0,
                'job_category_industries_sid' => ($this->input->post('job_category_industries_sid') > 0) ? $this->input->post('job_category_industries_sid') : 0,
                'payment_type' => $this->input->post('payment_type'),
                'past_due' => $this->input->post('past_due'),
                'user_shift_minutes' => $this->input->post('shift_mins'),
                'user_shift_hours' => $this->input->post('shift_hours'),
                'job_titles_template_group' => $this->input->post('job_titles_template_group'),
                'company_status' => $this->input->post('company_status'),

            );
            //
            if (IS_TIMEZONE_ACTIVE) $data['timezone'] = $this->input->post('company_timezone', true);

            $company_sid = $sid; //Add Company Portal Templates Information - Start
            $company_name = $this->input->post('CompanyName');
            $company_email = TO_EMAIL_DEV;

            if ($company_sid > 0) {
                $this->portal_email_templates_model->check_default_tables($company_sid, $company_email, $company_name);
            } else {
                mail(TO_EMAIL_DEV, 'company id not found', 'company id not found while adding a new company and creating email templates.');
            }


            $this->company_model->update_user($sid, $data, 'Company'); //Add Company Portal Templates Information - End
            $company_details = $data;
            $company_details['sid'] = $sid;
            // $this->company_model->sync_company_details_to_remarket($company_details);

            // if the company is on payroll
            if (isCompanyLinkedWithGusto($company_sid)) {
                // load the model
                $this->load->model('v1/payroll_model');
                //
                if (!$this->db->where('company_sid', $company_sid)->count_all_results('gusto_companies_locations')) {
                    // get the company details
                    $response = $this->payroll_model->checkAndPushCompanyLocationToGusto($company_sid);
                    //
                    if ($response['errors']) {
                        $this->session->set_flashdata('message', "Company is updated sucessfully. " . implode('<br />', $response['errors']));
                    } else {
                        $this->payroll_model->handleInitialEmployeeOnboard($company_sid);
                    }
                } else {
                    // // update the location on Gusto
                    // // get the company details
                    // $response = $this->payroll_model->updateCompanyLocationToGusto($company_sid);
                    // //
                    // if ($response['errors']) {
                    //     $this->session->set_flashdata('message', "Company is updated sucessfully. " . implode('<br />', $response['errors']));
                    // }
                }
            }

            if ($action == 'Save') {

                redirect('manage_admin/companies/manage_company/' . $sid, 'refresh');
            } else {
                redirect('manage_admin/companies/edit_company/' . $sid, 'refresh');
            }
        }
    }

    public function send_company_activation_email()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'edit_company';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $action = $this->input->post("action");
        $company_sid = $this->input->post("sid");

        if ($action == 'email') { //getting all users of the company.
            $employers = db_get_company_users($company_sid);
            $companyDetail = $this->company_model->get_details($company_sid, 'company');

            if ($companyDetail[0]['activation_key'] == NULL) {
                $this->load->model('dashboard_model');
                $activation_key = random_key(24);
                $updatedData = array('activation_key' => $activation_key);
                $this->dashboard_model->update_user($company_sid, $updatedData);
            } else {
                $activation_key = $companyDetail[0]['activation_key'];
            }

            foreach ($employers as $employer) {
                if ($employer['access_level'] == 'Admin') { //getting email template
                    $emailTemplateData = get_email_template(REACTIVE_EXPIRED_ACCOUNT);
                    // $emailTemplateBody = convert_email_template($emailTemplateData['text'], $employer["sid"]);
                    // $emailTemplateBody = str_replace('{{activation_key}}', $activation_key, $emailTemplateBody);
                    //
                    $replacement_array = array();
                    $replacement_array = $this->company_model->get_user_data($employer['sid']);
                    $replacement_array['activation_key'] = $activation_key;
                    $emailTemplateBody = convert_email_template($emailTemplateData['text'], $replacement_array);
                    //
                    $from = $emailTemplateData['from_email'];
                    $to = $employer['email'];
                    $subject = $emailTemplateData['subject'];
                    $from_name = $emailTemplateData['from_name'];

                    $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;

                    sendMail($from, $to, $subject, $body, $from_name);
                    //saving email to logs
                    $emailData = array(
                        'date' => date('Y-m-d H:i:s'),
                        'subject' => $subject,
                        'email' => $to,
                        'message' => $body,
                        'username' => $employer['sid'],
                    );
                    save_email_log_common($emailData);
                }
            }
        }
    }

    public function add_company()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/companies';
        $function_name = 'add_new_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean|is_unique[users.CompanyName]');
        $this->form_validation->set_rules('ContactName', 'Contact Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
        $this->form_validation->set_rules('WebSite', 'Website', 'trim|xss_clean|valid_url');
        $this->form_validation->set_rules('accounts_contact_person', 'Accounts Contact Person', 'trim|xss_clean|alpha_numeric_spaces');
        $this->form_validation->set_rules('accounts_contact_number', 'Accounts Contact Number', 'trim|xss_clean');
        $this->form_validation->set_rules('full_billing_address', 'Full Billing Address', 'trim|xss_clean|alpha_numeric_spaces');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim|xss_clean|is_unique[users.username]|callback_check_username');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|callback_check_email');
        $this->form_validation->set_rules('alternative_email', 'Alternative Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('job_title', 'Job Title', 'trim|xss_clean');
        $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim|xss_clean');
        $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim|xss_clean');
        $this->form_validation->set_rules('marketing_agency', 'Cell Number', 'trim|xss_clean');
        $this->form_validation->set_rules('job_category_industries_sid', 'Industry Category', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $data_countries = $this->company_model->get_active_countries(); //get all active `countries`

            foreach ($data_countries as $value) { //get all active `states`
                $data_states[$value['sid']] = $this->company_model->get_active_states($value['sid']);
            }

            $marketing_agencies = $this->company_model->get_all_marketing_agencies();
            $this->data['marketing_agencies'] = $marketing_agencies;
            $this->data['industry_categories'] = $this->company_model->get_industry_categories();
            $this->data['active_countries'] = $data_countries;
            $this->data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $this->data['states'] = $data_states_encode;
            $this->data['data']['Location_Country'] = 227;
            $this->data['data']['Location_State'] = 1;
            $security_access_levels = $this->company_model->get_security_access_levels();
            $this->data['security_access_levels'] = $security_access_levels;
            $this->render('manage_admin/company/add_company');
        } else { //Company Information
            $company_data = array();
            $company_username = clean(strtolower($this->input->post('CompanyName')));
            $company_data['username'] = $company_username . '-' . generateRandomString(6);
            $company_data['active'] = 0;
            $company_data['ip_address'] = getUserIP() . ' - ' . $_SERVER['HTTP_USER_AGENT'];
            $company_data['registration_date'] = date('Y-m-d H:i:s');
            $company_data['password'] = do_hash("admin125", 'md5');
            $company_data['api_key'] = random_key();
            $company_data['CompanyName'] = $this->input->post('CompanyName');
            $company_data['ContactName'] = $this->input->post('ContactName');
            $company_data['Location_Country'] = $this->input->post('Location_Country');
            $company_data['Location_State'] = $this->input->post('Location_State');
            $company_data['Location_City'] = $this->input->post('Location_City');
            $company_data['Location_ZipCode'] = $this->input->post('Location_ZipCode');
            $company_data['Location_Address'] = $this->input->post('Location_Address');
            $company_data['PhoneNumber'] = $this->input->post('PhoneNumber');
            $company_data['CompanyDescription'] = $this->input->post('CompanyDescription');
            $company_data['WebSite'] = $this->input->post('WebSite');
            $company_data['accounts_contact_person'] = $this->input->post('accounts_contact_person');
            $company_data['accounts_contact_number'] = $this->input->post('accounts_contact_number');
            $company_data['full_billing_address'] = $this->input->post('full_billing_address');
            $company_data['marketing_agency_sid'] = ($this->input->post('marketing_agency_sid') > 0) ? $this->input->post('marketing_agency_sid') : 0;
            $company_data['job_category_industries_sid'] = ($this->input->post('job_category_industries_sid') > 0) ? $this->input->post('job_category_industries_sid') : 0;
            $company_data['number_of_rooftops'] = $this->input->post('number_of_rooftops');

            //Employer Admin Information
            $registration_date = $this->input->post('registration_date');

            if ($registration_date != NULL) {
                $registration_date = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d H:i:s');
            } else {
                $registration_date = date('Y-m-d H:i:s');
            }

            $action = $this->input->post('action');
            $salt = generateRandomString(48);
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $access_level = $this->input->post('security_access_level');
            $username = $this->input->post('user_name');
            $email = $this->input->post('email');

            $employer_data = array();
            $employer_data['key'] = encode_string($this->input->post('password'));
            $employer_data['active'] = 0;
            $employer_data['registration_date'] = $registration_date;
            $employer_data['access_level'] = 'Admin';
            $employer_data['first_name'] = $first_name;
            $employer_data['last_name'] = $last_name;
            $employer_data['username'] = $username;
            //            $employer_data['password'] = do_hash($this->input->post('password'), 'md5');
            $employer_data['email'] = $email;
            $employer_data['alternative_email'] = $this->input->post('alternative_email');
            $employer_data['job_title'] = $this->input->post('job_title');
            $employer_data['access_level'] = $this->input->post('security_access_level');
            $employer_data['direct_business_number'] = $this->input->post('direct_business_number');
            $employer_data['cell_number'] = $this->input->post('cell_number');
            $employer_data['is_primary_admin'] = 1;
            $employer_data['salt'] = $salt;
            //Portal Information
            $employer_portal_data = array();
            $employer_portal_data['sub_domain'] = $company_username . "." . STORE_DOMAIN;
            $employer_portal_data['status'] = 0;
            $employer_portal_data['eeo_form_status'] = 0;
            $employer_portal_data['full_employment_app_print'] = 1;

            // Reset phone
            $company_data['PhoneNumber'] = $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $company_data['PhoneNumber'];
            $employer_data['cell_number'] = $this->input->post('txt_cellnumber') ? $this->input->post('txt_cellnumber') : $employer_data['cell_number'];


            $company_data['PhoneNumber'] = str_replace('(___) ___-____', '', $company_data['PhoneNumber']);
            $employer_data['cell_number'] = str_replace('(___) ___-____', '', $employer_data['cell_number']);

            $result = $this->users_model->insert($company_data, $employer_data, $employer_portal_data);

            if (!empty($result)) {
                // load the fillable document library
                $this->load->model("v1/Fillable_documents_model", "fillable_documents_model");
                $this
                    ->fillable_documents_model
                    ->checkAndAddFillableDocuments(
                        $result['company_id']
                    );
                // Also pushes the location to onboarding configuration
                $this->users_model->fixOnboardingAddress($result['company_id'], 0);
                //making sub domain
                //Add Company Portal Templates Information - Start
                $company_sid = $result['company_id'];
                $company_name = $this->input->post('CompanyName');
                $company_email = $this->input->post('email');

                if ($company_sid > 0) {
                    $this->portal_email_templates_model->check_default_tables($company_sid, $company_email, $company_name);
                    $this->company_model->insert_job_visibility_record_for_non_applicants($company_sid);
                } else {
                    mail(TO_EMAIL_DEV, 'company id not found', 'company id not found while adding a new company and creating email templates.');
                }
                //
                if (!isDevServer()) {
                    //Add Company Portal Templates Information - End
                    $auth_details = $this->company_model->fetch_details(THEME_AUTH);
                    $auth_user = $auth_details['auth_user'];
                    $auth_pass = $auth_details['auth_pass'];
                    $server = STORE_DOMAIN;
                    $json_client = new xmlapi($server);
                    $json_client->set_output('json');
                    $json_client->set_port(2083);
                    $json_client->password_auth($auth_user, $auth_pass);

                    $args = array(
                        'dir' => 'public_html/manage_portal/',
                        'rootdomain' => STORE_DOMAIN,
                        'domain' => $company_username
                    );

                    if ($_SERVER['SERVER_NAME'] != 'localhost') {
                        $result = $json_client->api2_query($auth_user, 'SubDomain', 'addsubdomain', $args);
                        sendMail(FROM_EMAIL_NOTIFICATIONS, 'mubashir.saleemi123@gmail.com', 'New Api Result', $result);
                    }
                }

                if ($action == 'sendemail') {
                    $replacement_array = array();
                    $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
                    $replacement_array['access_level'] = ucwords($access_level);
                    $replacement_array['company_name'] = $company_name;
                    $replacement_array['username'] = $username;
                    $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/login" target="_blank">Login page</a>';
                    $replacement_array['firstname'] = $first_name;
                    $replacement_array['lastname'] = $last_name;
                    $replacement_array['first_name'] = $first_name;
                    $replacement_array['last_name'] = $last_name;
                    $replacement_array['email'] = $email;
                    $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';
                    log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array);
                }

                redirect('manage_admin/companies', 'refresh');
            }
        }
    }

    //    public function remove_admin_company($sid, $admin_id)
    //    {
    //        if($sid == 0 || $sid == NULL)
    //        {
    //            $this->session->set_flashdata('message', 'Company for administrator does not exist');
    //            redirect('manage_admin/companies/executive_administrators');
    //        }
    //
    //        $this->company_model->remove_admin_company($sid);
    //
    //        $this->session->set_flashdata('message', 'Company removed successfully.');
    //        redirect('manage_admin/companies/executive_admin/manage_executive_administrators/'.$admin_id);
    //    }

    public function executive_admin_company_remove_ajax()
    {
        $sid = $this->input->post('sid');
        $logged_in_sid = $this->input->post('logged_in_sid');

        if ($sid == NULL || $sid == 0) {
            $result = false;
        } else {
            $result = $this->company_model->executive_admin_company_remove($sid, $logged_in_sid);
        }

        echo json_encode($result);
    }

    public function executive_admin_delete_ajax()
    {
        $administrator_sid = $this->input->post('administrator_sid');

        if ($administrator_sid == NULL || $administrator_sid == 0) {
            $result = false;
        } else {
            $this->company_model->executive_admin_user_delete($administrator_sid);
            //
            $result = $this->company_model->executive_admin_delete($administrator_sid);
        }

        echo json_encode($result);
    }

    public function executive_admin_activation_ajax()
    {
        $administrator_sid = $this->input->post('administrator_sid');

        if ($administrator_sid == NULL || $administrator_sid == 0) {
            $result = false;
        } else {
            $result = $this->company_model->executive_admin_activation($administrator_sid);
        }

        echo json_encode($result);
    }

    /*public function add_admin_company($admin_id)
    {
        if ($admin_id == 0 || $admin_id == NULL) {
            $this->session->set_flashdata('message', 'Administrator does not exist');
            redirect('manage_admin/companies/executive_administrators');
        }

        $this->data['page_title'] = 'Add New Admin Company';
        $this->data['admin_id'] = $admin_id;
        $this->data['companies'] = $this->company_model->get_all_companies();

        if (isset($_POST['submit'])) {
            $companies = $this->input->post('companies');
            $companies_names = $this->input->post('company_name');
            $companies_webs = $this->input->post('company_website');

            foreach ($companies as $company) {
                $data = array(
                    'executive_admin_sid' => $admin_id,
                    'company_sid' => $company,
                    'company_name' => $companies_names[$company],
                    'company_website' => $companies_webs[$company]
                );

                $this->company_model->add_company($data, $company);
            }

            $this->session->set_flashdata('message', 'New companies added to administrator.');
            redirect('manage_admin/companies/manage_executive_administrators/' . $admin_id);
        }

        $this->render('manage_admin/company/executive_admin/admin_add_company');
    }*/

    public function executive_administrators($name = null, $email = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'executive_administrators';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $name = $name == null ? 'all' : str_replace('_', ' ', urldecode($name));
        $email = $email == null ? 'all' : urldecode($email);
        $this->data['administrators'] = $this->company_model->get_executive_administrators($name, $email);

        $this->data['page_title'] = 'Manage Company Executive Admins';
        $this->form_validation->set_rules('executive_admin_sid', 'executive_admin_sid', 'required|xss_clean|trim');
        $this->data['flag'] = true;

        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/company/executive_admin/admin_listing');
        } else {

            //
            if ($this->input->post('checkit')) {
                $selectedadmins = $this->input->post('checkit');
                $action = $this->input->post('action');

                $msg = $action == 1 ? 'Marked as Admin Plus' : ' Unmarked as Admin Plus';



                if (!empty($selectedadmins)) {
                    foreach ($selectedadmins as $adminsid) {
                        $logged_in_sids = $this->company_model->get_executive_user_logged_in_sids($adminsid);

                        if (!empty($logged_in_sids)) {
                            $this->company_model->set_executive_access_level_plus($logged_in_sids, $action);
                        }
                    }
                }
                $this->session->set_flashdata('message', 'Employee are sucessfully ' . $msg);

                redirect('manage_admin/companies/executive_administrators', 'refresh');
            }


            $executive_admin_sid = $this->input->post('executive_admin_sid');
            $executive_admin_info = $this->company_model->get_administrator($executive_admin_sid);
            $session_array = array();

            if (!empty($executive_admin_info)) {
                $status = $executive_admin_info[0]['active']; // check the status whether the account is active or inactive

                if ($status == 1) {
                    $session_array['status'] = 'active';
                    $session_array['executive_user'] = $executive_admin_info[0];
                } else {
                    $session_array['status'] = 'inactive';
                }

                $this->session->set_userdata('executive_loggedin', $session_array);
                echo 'session_created';
                exit;
            } else {
                echo 'session_not_created';
                exit;
            }
        }
    }

    public function manage_executive_administrators($exec_admin_id)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'manage_executive_admins';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($exec_admin_id == 0 || $exec_admin_id == NULL) {
            $this->session->set_flashdata('message', 'Administrator does not exist');
            redirect('manage_admin/companies/executive_administrators');
        }

        $standard_companies = array();
        $corporate_companies = array();
        $access_companies = array();
        $this->data['page_title'] = 'Executive Administrator Details';
        $this->data['executive_admin_sid'] = $exec_admin_id;
        $exec_admin_details = $this->company_model->get_administrator($exec_admin_id);

        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        if ($this->form_validation->run() == false) {
            $this->data['administrator'] = $exec_admin_details[0];
            $has_access_to_companies = $this->company_model->get_admin_companies($exec_admin_id);

            if (!empty($has_access_to_companies)) {
                foreach ($has_access_to_companies as $access_company) {
                    $access_companies[] = $access_company['company_sid'];
                }
            }

            $all_companies = $this->company_model->get_all_executive_admin_companies();

            foreach ($all_companies as $company_data) {
                if ($company_data['career_page_type'] == 'standard_career_site') {
                    $standard_companies[] = $company_data;
                } else {
                    $corporate_companies[] = $company_data;
                }
            }


            $this->data['standard_companies'] = $standard_companies;
            $this->data['corporate_companies'] = $corporate_companies;
            $this->data['access_companies'] = $access_companies;
            $this->data['exec_admin_id'] = $exec_admin_id;

            // print_r($standard_companies);
            // die();

            $this->render('manage_admin/company/executive_admin/manage_executive_administrator');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'enable_company_access':

                    $company_sid = $this->input->post('company_sid');
                    $executive_admin_sid = $this->input->post('executive_admin_sid');
                    $company_name = $this->company_model->get_company_name($company_sid);
                    $admin_details = $this->company_model->get_administrator($executive_admin_sid);
                    if (!empty($admin_details)) {
                        $data = array(
                            'executive_admin_sid' => $executive_admin_sid,
                            'company_sid' => $company_sid,
                            'company_name' => $company_name,
                            'company_website' => db_get_sub_domain($company_sid)
                        );
                    }

                    $this->company_model->add_company($data, $company_sid);
                    $this->company_model->apply_e_signature($executive_admin_sid);
                    echo 'success';
                    break;
                case 'disable_company_access':
                    $company_sid = $this->input->post('company_sid');
                    $executive_admin_sid = $this->input->post('executive_admin_sid');
                    $executive_admin_company_details = $this->company_model->get_executive_admin_company_details($executive_admin_sid, $company_sid);

                    if (!empty($executive_admin_company_details)) {
                        $logged_in_sid = $executive_admin_company_details['logged_in_sid'];
                        $admin_company_sid = $executive_admin_company_details['sid'];
                        $result = $this->company_model->executive_admin_company_remove($admin_company_sid, $logged_in_sid);
                    }

                    echo 'success';
                    break;
                case 'mark_admin_plus':

                    $company_sid = $this->input->post('company_sid');
                    $executive_admin_sid = $this->input->post('executive_admin_sid');
                    $this->company_model->set_executive_access_level_plus_single_company($executive_admin_sid, $company_sid, 'mark_admin_plus');
                    echo 'success';
                    break;

                case 'unmark_admin_plus':

                    $company_sid = $this->input->post('company_sid');
                    $executive_admin_sid = $this->input->post('executive_admin_sid');
                    $this->company_model->set_executive_access_level_plus_single_company($executive_admin_sid, $company_sid, 'unmark_admin_plus');
                    echo 'success';
                    break;

                case 'configure_corporate_company_access':
                    $executive_admin_sid = $this->input->post('executive_admin_sid');
                    $company_sid = $this->input->post('company_sid');
                    $corporate_username = $this->input->post('corporate_username');
                    $corporate_password = $this->input->post('corporate_password');
                    $has_corporate_company_access = $this->input->post('has_corporate_company_access');
                    $executive_admin_already_registered = $this->input->post('executive_admin_already_registered');
                    $company_name = $this->company_model->get_company_name($company_sid);

                    if (!empty($admin_details)) {
                        $data = array(
                            'executive_admin_sid' => $executive_admin_sid,
                            'company_sid' => $company_sid,
                            'company_name' => $company_name,
                            'company_website' => db_get_sub_domain($company_sid)
                        );
                    }

                    if (intval($has_corporate_company_access) == 1) {
                        if (intval($executive_admin_already_registered) == 1) {
                            if ($corporate_password != '') {
                                $this->company_model->add_company($data, $company_sid, $corporate_username, $corporate_password, 'corporate_career_site');
                            } else {
                                $this->company_model->add_company($data, $company_sid, $corporate_username, null, 'corporate_career_site');
                            }
                        } else {
                            $this->company_model->add_company($data, $company_sid, $corporate_username, $corporate_password, 'corporate_career_site');
                        }
                    } else {
                        $executive_admin_company_details = $this->company_model->get_executive_admin_company_details($executive_admin_sid, $company_sid);

                        if (!empty($executive_admin_company_details)) {
                            $logged_in_sid = $executive_admin_company_details['logged_in_sid'];
                            $admin_company_sid = $executive_admin_company_details['sid'];

                            $result = $this->company_model->executive_admin_company_remove($admin_company_sid, $logged_in_sid);
                        }
                    }

                    $this->session->set_flashdata('message', '<strong>Success: </strong>Corporate Company Access Configuration Updates');
                    redirect('manage_admin/companies/manage_executive_administrators/' . $exec_admin_id, 'refresh');
                    break;
            }

            if ($this->input->post('is_ajax_request') == 1) {
                exit;
            }
        }
    }

    public function edit_executive_administrator($exec_admin_id)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'manage_executive_admins';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($exec_admin_id == 0 || $exec_admin_id == NULL) {
            $this->session->set_flashdata('message', 'Administrator does not exist');
            redirect('manage_admin/companies/executive_administrators');
        }

        $this->data['page_title'] = 'Edit Executive Administrator';
        $admin = $this->company_model->get_administrator($exec_admin_id);
        $this->data['administrator'] = $admin[0];
        $this->data['cancel_url'] = base_url('manage_admin/companies/manage_executive_administrators/' . $exec_admin_id);
        $previous_automotive_group_sid = $admin[0]['automotive_group_sid'];
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');

        if ($admin[0]["username"] != $this->input->post('username')) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|is_unique[executive_users.username]');
        } else {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
        $this->form_validation->set_rules('alternative_email', 'Alternative Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('job_title', 'Job Title', 'trim|xss_clean');
        $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim|xss_clean');
        $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'New Password', 'trim|xss_clean');
        //$this->form_validation->set_rules('automotive_group_sid', 'Automotive Group', 'required|trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            //            $automotive_groups = $this->company_model->get_automotive_groups();
            $this->data['automotive_groups'] = array();
            $this->data['access_levels'] = $this->company_model->get_security_access_levels();
            $this->render('manage_admin/company/executive_admin/admin_edit');
        } else { // create array for updation in database

            $admin_data = array();
            $admin_data['username'] = $this->input->post('username');
            $admin_data['email'] = $this->input->post('email');
            $admin_data['alternative_email'] = $this->input->post('alternative_email');
            $admin_data['first_name'] = $this->input->post('first_name');
            $admin_data['last_name'] = $this->input->post('last_name');
            $admin_data['direct_business_number'] = $this->input->post('direct_business_number');
            $admin_data['job_title'] = $this->input->post('job_title');
            $admin_data['cell_number'] = $this->input->post('cell_number');
            $admin_data['gender'] = $this->input->post('gender');
            $admin_data['access_level'] = $this->input->post('access_level');
            $admin_data['ip_address'] = getUserIP();

            if (IS_PTO_ENABLED == 1) {
                $admin_data['user_shift_hours'] = $this->input->post('shift_hours', true);
                $admin_data['user_shift_minutes'] = $this->input->post('shift_mins', true);
            }

            if (IS_NOTIFICATION_ENABLED == 1) {
                if (!sizeof($this->input->post('notified_by', true))) $admin_data['notified_by'] = 'email';
                else $admin_data['notified_by'] = implode(',', $this->input->post('notified_by', true));
            }

            if ($this->input->post('txt_phonenumber', true)) $admin_data['cell_number'] = $this->input->post('txt_phonenumber', true);
            else $admin_data['cell_number'] = '';
            //$automotive_group_sid = $this->input->post('automotive_group_sid');
            //            if (!empty($automotive_group_sid)) {
            //                $admin_data['automotive_group_sid'] = $automotive_group_sid;
            //            }
            $profile_picture = $this->upload_file_to_aws('profile_picture', $exec_admin_id, 'profile_picture');

            if ($this->input->post('active') == '1') {
                $admin_data['active'] = 1;
            } else {
                $admin_data['active'] = 0;
            }

            if ($this->input->post('password') != '' && $this->input->post('password') != NULL) {
                $admin_data['password'] = do_hash($this->input->post('password'), 'md5');
            }

            if ($profile_picture != 'error') {
                $admin_data['profile_picture'] = $profile_picture;
            } else {
                $admin_data['profile_picture'] = $admin[0]['profile_picture'];
            }

            if (IS_TIMEZONE_ACTIVE) {
                // Added on: 25-06-2019
                $new_timezone = $this->input->post('timezone', true);
                if ($new_timezone != '') $admin_data['timezone'] = $new_timezone;
            }

            $this->company_model->edit_admin($exec_admin_id, $admin_data);

            //            if($previous_automotive_group_sid != $automotive_group_sid){
            //                $executive_member_companies = $this->company_model->get_executive_admin_companies($exec_admin_id);
            //                //$executive_admin_company_details = $this->company_model->get_executive_admin_company_details($exec_admin_id, $previous_automotive_group_sid);
            //                if (!empty($executive_member_companies)) {
            //                    foreach($executive_member_companies as $company) {
            //
            //                        $logged_in_sid = $company['logged_in_sid'];
            //                        $admin_company_sid = $company['sid'];
            //
            //                        $result = $this->company_model->executive_admin_company_remove($admin_company_sid, $logged_in_sid);
            //                    }
            //                }
            //            }

            $this->session->set_flashdata('message', 'Administrator updated successfully');
            redirect('manage_admin/companies/manage_executive_administrators/' . $exec_admin_id);
        }
    }

    public function add_executive_administrator()
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'manage_executive_admins';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Add Executive Administrator';
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|is_unique[executive_users.username]');
        //        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|is_unique[executive_users.email]');
        $this->form_validation->set_rules('alternative_email', 'Alternative Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('job_title', 'Job Title', 'trim|xss_clean');
        $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim|xss_clean');
        $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim|xss_clean');
        //$this->form_validation->set_rules('automotive_group_sid', 'Automotive Group', 'required|trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            //            $automotive_groups = $this->company_model->get_automotive_groups();
            $this->data['automotive_groups'] = array();
            $this->data['access_levels'] = $this->company_model->get_security_access_levels();
            $this->render('manage_admin/company/executive_admin/add_executive_admin');
        } else { //Employer Admin Information
            $employer_data = array();
            $employer_data['first_name'] = $this->input->post('first_name');
            $employer_data['last_name'] = $this->input->post('last_name');
            $employer_data['username'] = $this->input->post('username');
            //            $employer_data['password'] = do_hash($this->input->post('password'), 'md5');
            $employer_data['salt'] = generateRandomString(48);
            $employer_data['email'] = $this->input->post('email');
            $employer_data['alternative_email'] = $this->input->post('alternative_email');
            $employer_data['job_title'] = $this->input->post('job_title');
            $employer_data['direct_business_number'] = $this->input->post('direct_business_number');
            $employer_data['cell_number'] = $this->input->post('cell_number');
            $employer_data['created_on'] = date('Y-m-d H:i:s');
            $employer_data['access_level'] = $this->input->post('access_level');
            $employer_data['active'] = 1;
            $employer_data['ip_address'] = getUserIP();
            $send_link = $this->input->post('send');
            //$automotive_group_sid = $this->input->post('automotive_group_sid');

            //            if (!empty($automotive_group_sid)) {
            //                $employer_data['automotive_group_sid'] = $automotive_group_sid;
            //            }

            if (IS_PTO_ENABLED == 1) {
                $employer_data['user_shift_hours'] = $this->input->post('shift_hours', true);
                $employer_data['user_shift_minutes'] = $this->input->post('shift_mins', true);
            }

            if (IS_NOTIFICATION_ENABLED == 1) {
                if (!sizeof($this->input->post('notified_by', true))) $employer_data['notified_by'] = 'email';
                else $employer_data['notified_by'] = implode(',', $this->input->post('notified_by', true));
            }

            if ($this->input->post('txt_phonenumber', true)) $employer_data['cell_number'] = $this->input->post('txt_phonenumber', true);
            else $employer_data['cell_number'] = '';


            if (IS_TIMEZONE_ACTIVE) {
                // Added on: 25-06-2019
                $new_timezone = $this->input->post('timezone', true);
                if ($new_timezone != '') $employer_data['timezone'] = $new_timezone;
            }

            $result = $this->company_model->add_exec_admin($employer_data);
            $profile_picture = $this->upload_file_to_aws('profile_picture', $result, 'profile_picture');

            if ($profile_picture != 'error') {
                $pictures = array('profile_picture' => $profile_picture);
                $this->company_model->edit_admin($result, $pictures);
            } else {
                $pictures = NULL;
            }

            $this->session->set_flashdata('message', 'New Executive Administrator added successfully');

            if ($send_link && $result) {
                $salt = $this->company_model->get_administrator($result);
                $replacement_array['firstname'] = $this->input->post('first_name');
                $replacement_array['lastname'] = $this->input->post('last_name');
                $replacement_array['username'] = $this->input->post('username');
                $replacement_array['create_password_link'] = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url() . "executive_admin/generate-password/" . $salt[0]['salt'] . '">Create Your Password</a>';
                log_and_send_templated_email(NEW_EXECUTIVE_ADMIN_INFO, $this->input->post('email'), $replacement_array);
            }

            redirect('manage_admin/companies/executive_administrators');
        }
    }

    public function manage_packages($company_sid = null)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($company_sid != null) { //Get All Platform Packages
            $packages = $this->company_model->get_all_platform_packages();
            $admin_sid = $this->ion_auth->user()->row()->id;
            $this->form_validation->set_rules('package', 'Package', 'required');
            $this->form_validation->set_rules('number_of_rooftops', 'Number of Rooftops', 'required');
            $company_info = $this->company_model->get_company_details($company_sid);
            $this->data['company_info'] = $company_info;
            $this->data['company_sid'] = $company_sid;
            $this->data['current_admin'] = $admin_sid;
            $this->data['packages'] = $packages;

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/manage_packages');
            } else {
                $company_sid = $this->input->post('company_sid');
                $created_by = $this->input->post('created_by');
                $package_id = $this->input->post('package');
                $number_of_rooftops = $this->input->post('number_of_rooftops');
                $custom_price = $this->input->post('custom_price');
                $cost_price = $this->input->post('cost_price');

                $id_to_rooftops = array();
                $id_to_rooftops[$package_id] = $number_of_rooftops;
                $id_to_quantity = array();
                $id_to_quantity[$package_id] = 1;

                //Create Admin Invoice
                $admin_invoice_sid = $this->admin_invoices_model->Save_invoice($created_by, $company_sid, array($package_id), $id_to_rooftops, $id_to_quantity, null, $custom_price, $cost_price);
                //Create Commission Invoice
                //                echo $package_id.'<br>';
                //                echo $custom_price.'<br>';
                //                echo $cost_price.'<br>';
                //                die();
                $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($created_by, $company_sid, array($package_id), $id_to_rooftops, $id_to_quantity, 'manual', 'super_admin', $custom_price, $cost_price);
                $secondary_invoice = 0;

                if (isset($commission_invoice_sid['secondary'])) {
                    $secondary_invoice = $commission_invoice_sid['secondary'];
                }

                //Update Commission Invoice Sid in Admin Invoices Table
                $this->admin_invoices_model->update_commission_invoice_sid($admin_invoice_sid, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
                //Update Admin Invoice Sid in Commission Invoices Table
                $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $admin_invoice_sid, $secondary_invoice);
                //Re Calculate Commission
                $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

                if (isset($commission_invoice_sid['secondary'])) {
                    //Re Calculate Commission
                    $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
                }
                $this->session->set_flashdata('message', 'Invoice Successfully Generated!');
                redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
            }
        } else {
            redirect('manage_admin/companies/', 'refresh');
        }
    }

    public function generate_commisson_invoice($company_sid = null, $admin_invoice_sid = null)
    {
        if ($company_sid != null) {
            $created_by = $this->ion_auth->user()->row()->id;
            $invoice_details = $this->admin_invoices_model->Get_admin_invoice($admin_invoice_sid, true);
            $my_packages = $invoice_details['items'];
            $package_id = array();
            $id_to_rooftops = array();
            $id_to_quantity = array();
            $number_of_rooftops = 1;

            foreach ($my_packages as $my_key => $my_value) {
                $product_id = $my_value['item_sid'];
                $number_of_rooftops = $my_value['number_of_rooftops'];
                $quantity = $my_value['quantity'];
                $id_to_rooftops[$product_id] = $number_of_rooftops;
                $id_to_quantity[$product_id] = $quantity;
                $package_id[] = $product_id;
            }

            $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($created_by, $company_sid, $package_id, $id_to_rooftops, $id_to_quantity);
            $secondary_invoice = 0;

            if (isset($commission_invoice_sid['secondary'])) {
                $secondary_invoice = $commission_invoice_sid['secondary'];
            }
            //Update Primary Commission Invoice Sid in Admin Invoices Table
            $this->admin_invoices_model->update_commission_invoice_sid($admin_invoice_sid, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
            //Update Admin Invoice Sid in Commission Invoices Table
            $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $admin_invoice_sid, $secondary_invoice);
            //Re Calculate Primary Commission
            $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

            if (isset($commission_invoice_sid['secondary'])) {
                //Re Calculate Commission
                $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
            }
            $this->session->set_flashdata('message', 'Commission Invoice Successfully Generated!');
            redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
        } else {
            redirect('manage_admin/companies/', 'refresh');
        }
    }

    public function manage_addons($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) { //Get All Platform Packages
            $packages = $this->company_model->get_all_platform_packages('facebook-api');
            $packages_dev_fee = $this->company_model->get_all_platform_packages('development-fee');
            foreach ($packages_dev_fee as $key => $package) {
                if ($package['name'] != 'Development Fee') {
                    unset($packages_dev_fee[$key]);
                }
            }

            $packages = array_merge($packages, $packages_dev_fee);
            $admin_sid = $this->ion_auth->user()->row()->id;
            $this->form_validation->set_rules('packages[]', 'Addons', 'required');
            $this->data['company_sid'] = $company_sid;
            $this->data['current_admin'] = $admin_sid;
            $this->data['packages'] = $packages;

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/manage_addons');
            } else {
                $company_sid = $this->input->post('company_sid');
                $created_by = $this->input->post('created_by');
                $package_ids = $this->input->post('packages');
                $number_of_rooftops = $this->input->post('number_of_rooftops');

                $id_to_rooftops = array();
                $id_to_quantity = array();

                foreach ($package_ids as $package_id) {
                    $id_to_rooftops[$package_id] = $number_of_rooftops;
                    $id_to_quantity[$package_id] = 1;
                }

                $admin_invoice_sid = $this->admin_invoices_model->Save_invoice($created_by, $company_sid, $package_ids, $id_to_rooftops, $id_to_quantity);
                //Create Commission Invoice
                $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($created_by, $company_sid, $package_ids, $id_to_rooftops, $id_to_quantity);
                $secondary_invoice = 0;

                if (isset($commission_invoice_sid['secondary'])) {
                    $secondary_invoice = $commission_invoice_sid['secondary'];
                }
                //Update Commission Invoice Sid in Admin Invoices Table
                $this->admin_invoices_model->update_commission_invoice_sid($admin_invoice_sid, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
                //Update Admin Invoice Sid in Commission Invoices Table
                $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $admin_invoice_sid, $secondary_invoice);
                //Re Calculate Commission
                $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

                if (isset($commission_invoice_sid['secondary'])) {
                    //Re Calculate Commission
                    $this->marketing_agencies_model->recalculate_commission($secondary_invoice);
                }

                $this->session->set_flashdata('message', 'Invoice Successfully Generated!');
                redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
            }
        } else {
            redirect('manage_admin/companies/', 'refresh');
        }
    }

    function check_username($username)
    {
        $result = $this->users_model->check_user($username);
        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_username', 'Username already exist.');
            return false;
        }
    }

    function check_email($email)
    {
        $result = $this->users_model->check_email($email);
        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_email', 'Email already in use.');
            return false;
        }
    }

    function ajax_responder()
    {
        if ($_POST) {
            if (isset($_POST['perform_action'])) {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'set_company_status':
                        $company_sid = $_POST['company_sid'];
                        $company_status = $_POST['company_status'];
                        $this->company_model->set_company_active_status($company_sid, $company_status);
                        echo 'success';
                        break;

                    case 'send_executive_admin_login_email':
                        $sid = $this->input->post('sid');
                        $admin_details = $this->company_model->get_administrator($sid);

                        if (!empty($admin_details)) {
                            $exec_admin = $admin_details[0];
                            $salt = $exec_admin['salt'];
                            $email = $exec_admin['email'];

                            if ($salt == NULL || $salt == '') {
                                $salt = generateRandomString(48);
                                $data = array('salt' => $salt);
                                $this->company_model->update_excetive_admin($sid, $data);
                            }

                            $replacement_array['firstname'] = $exec_admin['first_name'];
                            $replacement_array['lastname'] = $exec_admin['last_name'];
                            $replacement_array['username'] = $exec_admin['username'];
                            $replacement_array['create_password_link'] = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url() . "executive_admin/generate-password/" . $salt . '">Create Your Password</a>';
                            log_and_send_templated_email(NEW_EXECUTIVE_ADMIN_INFO, $email, $replacement_array);
                            echo 'success';
                        } else {
                            echo 'error';
                        }
                        break;

                    case 'mark_training_completed':
                        $company_sid = $this->input->post('company_sid');
                        $key = $this->input->post('key');
                        if ($key == '1') {
                            $key = 1;
                        } else {
                            $key = 0;
                        }
                        $update_data = array('training_session_status' => $key);
                        $this->company_model->update_user_status($company_sid, $update_data);
                        echo 'success';
                        break;
                }
            }
        }
    }

    function manage_company($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage Company Dashboard';
            $company_info = $this->company_model->get_company_details($company_sid);

            $company_info['incidents'] = checkIfAppIsEnabled('incidents');

            if (sizeof($company_info) < 1) {
                $this->session->set_flashdata('message', 'Company not found!');
                redirect('manage_admin/companies/', 'refresh');
            }

            $this->db->where('company_id', $company_sid);
            $info_flag = $this->db->get('contact_info_for_company')->num_rows();
            $this->data['info_flag'] = $info_flag;
            $company_admin = $this->admin_invoices_model->Get_company_admin_information($company_sid);
            $company_admin_invoices = $this->admin_invoices_model->Get_all_admin_invoices(1, 10000, $company_sid);
            $company_portal_status = $this->maintenance_mode_model->get_employer_portal_record($company_sid);
            $company_portal_invoices = $this->invoice_model->get_all_invoices($company_sid);
            $company_documents_status = $this->company_model->get_documents_status($company_sid);
            $company_trial_period_detail = $this->company_model->get_company_trial_period_detail($company_sid);
            $company_billing_contacts = $this->company_billing_contacts_model->get_all_billing_contacts($company_sid);
            $company_portal_email_templates = $this->portal_email_templates_model->getallemailtemplates($company_sid);
            $job_fair_page_status = $this->company_model->get_job_fair_status($company_sid);
            $this->data['job_fair_page_status'] = $job_fair_page_status;
            $this->form_validation->set_rules('company_sid', 'company_sid', 'required');

            if ($this->form_validation->run() == false) {

                if (!empty($company_admin)) {
                    $company_admin = $company_admin[0];
                }

                $show_trial_period_button = false;
                $trial_button_text = '';
                $company_recurring_payment = get_recurring_payment_detail($company_sid);
                $is_company_expired = false;
                $is_company_registered_forever = false;
                $is_company_set_for_recurring_payment = false;
                $is_company_on_trial_period = false;

                if (strtotime(date('Y/m/d H:i:s')) > strtotime(str_replace('-', '/', $company_info['expiry_date']))) {
                    $is_company_expired = true;
                }

                if ($company_info['expiry_date'] == null) {
                    $is_company_registered_forever = true;
                }

                if (!empty($company_recurring_payment)) {
                    $is_company_set_for_recurring_payment = true;
                }

                if (!empty($company_trial_period_detail)) {
                    if ($company_trial_period_detail['status'] == 'enabled') {
                        $is_company_on_trial_period = true;
                    }
                } else {
                    $is_company_on_trial_period = true;
                }

                if ($is_company_on_trial_period || $company_info['account_package_sid'] == 0) {
                    $show_trial_period_button = true;
                    $trial_button_text = 'Update Trial Period';
                } else {
                    if ($is_company_expired || $is_company_registered_forever) {
                        if ($is_company_set_for_recurring_payment) {
                            $show_trial_period_button = false;
                        } else {
                            $show_trial_period_button = true;

                            if (!empty($company_trial_period_detail)) {
                                $trial_button_text = 'Update Trial Period';
                            } else {
                                $trial_button_text = 'Activate Trial Period';
                            }
                        }
                    } else {
                        $show_trial_period_button = false;
                    }
                }

                if (isset($company_info['job_category_industries_sid']) && !empty($company_info['job_category_industries_sid'])) {
                    $job_category_industry = $this->company_model->get_industry_category($company_info['job_category_industries_sid']);
                    $this->data['job_category_industry'] = $job_category_industry[0];
                }

                $this->data['company_trial_period_detail'] = $company_trial_period_detail;
                $this->data['company_portal_invoices'] = $company_portal_invoices;
                $this->data['company_admin_invoices'] = $company_admin_invoices;
                $this->data['company_admin'] = $company_admin;
                $this->data['company_info'] = $company_info;
                $this->data['company_portal_status'] = $company_portal_status;
                $this->data['customize_career_site_status'] = $this->company_model->get_customize_career_site_data($company_sid)['status'];
                $this->data['remarket_company_settings_status'] = $this->remarket_model->get_remarket_company_settings($company_sid)['status'];
                $this->data['company_sid'] = $company_sid;
                $this->data['company_documents_status'] = $company_documents_status;
                $this->data['show_trial_period_button'] = $show_trial_period_button;
                $this->data['trial_button_text'] = $trial_button_text;
                $this->data['company_billing_contacts'] = $company_billing_contacts;
                $this->data['company_portal_email_templates'] = $company_portal_email_templates;
                $this->data['automotive_groups'] = $this->company_model->get_groups_by_company($company_sid);
                //            $this->data['company_card'] = $company_card;


                //
                $this->data['CompanyIndeedDetails'] = $this->company_model->GetCompanyIndeedDetails($company_sid);

                // Get dynamic modules
                $this->data['dynamicModules'] = $this->company_model->getDynamicModulesByCompany($company_sid);
                $this->data['configured_companies'] = $this->company_model->get_reassign_configured_companies($company_sid);
                $this->render('manage_admin/company/manage_company');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'set_company_status':
                        $company_sid = $this->input->post('company_sid');
                        $company_status = $this->input->post('company_status');
                        $this->company_model->set_company_active_status($company_sid, $company_status);
                        $this->session->set_flashdata('message', 'Successfully Updated Company Status!');
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'set_company_portal_status':
                        echo '<pre>';
                        print_r($_POST);
                        exit;
                        break;
                    case 'set_administrator_status':
                        $company_sid = $this->input->post('company_sid');
                        $company_status = $this->input->post('company_admin_status');
                        $company_user_sid = $this->input->post('user_sid');
                        $this->company_model->set_company_user_active_status($company_sid, $company_user_sid, $company_status);
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'set_career_site_powered_by':
                        $company_sid = $this->input->post('company_sid');
                        $footer_powered_by_logo = $this->input->post('footer_powered_by_logo');
                        $this->company_model->set_company_powered_by_status($company_sid, $footer_powered_by_logo);
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'set_header_video_overlay_status':
                        $company_sid = $this->input->post('company_sid');
                        $header_video_overlay_status = $this->input->post('header_video_overlay_status');
                        $this->company_model->set_header_video_overlay_status($company_sid, $header_video_overlay_status);
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'set_eeo_footer_text_status':
                        $company_sid = $this->input->post('company_sid');
                        $eeo_footer_text_status = $this->input->post('eeo_footer_text_status');
                        $this->company_model->set_eeo_footer_text_status($company_sid, $eeo_footer_text_status);
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                        // Set SMS module check
                    case 'set_company_sms_status':
                        $company_sid = $this->input->post('company_sid');
                        $this->company_model->set_sms_module_status($company_sid, $this->input->post('sms_module_status', TRUE));
                        if ($this->input->post('sms_module_status', TRUE) == 1) {
                            // $response = $this->sms_buy_process($company_sid);
                            $this->session->set_flashdata('message', "You have successfully activated the SMS module.");
                        }
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');

                        break;
                        // Set Phone Regex module check
                    case 'set_company_phone_pattern_status':
                        $company_sid = $this->input->post('company_sid', true);
                        $this->company_model->set_phone_pattern_module($company_sid, $this->input->post('phone_pattern_module', TRUE));
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');

                        break;
                    case 'set_bulk_email_status':
                        $company_sid = $this->input->post('company_sid');
                        $bulk_email_status = $this->input->post('bulk_email_status');
                        $this->company_model->set_bulk_email_status($company_sid, $bulk_email_status);
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    function company_brands($company_sid = NULL)
    {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        } else {
            $this->data['page_title'] = 'Oem, Independent, Vendors';
            $this->data['company_sid'] = $company_sid;
            $this->data['brands'] = $this->company_model->get_brands_by_company($company_sid);
            $this->render('manage_admin/company/company_brands');
        }
    }

    function activate_trial_period($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $company_info = $this->company_model->get_company_details($company_sid);
            $this->data['company_sid'] = $company_sid;
            $this->data['company_info'] = $company_info;
            $trial_data = $this->company_model->get_trial_period_details($company_sid);

            if (empty($trial_data)) {
                $this->data['page_title'] = 'Activate Trial Period';
                $this->data['submit_button_text'] = 'Activate Trial Period';
            } else {
                $this->data['page_title'] = 'Update Trial Period';
                $this->data['submit_button_text'] = 'Update Trial Period';
            }

            if ($_POST) {
                $perform_action = $this->input->post('perform_action');

                if ($perform_action == 'end_trial') {
                    $company_sid = $this->input->post('company_sid');
                    $trial_sid = $this->input->post('sid');
                    $this->company_model->end_trial_period($company_sid, $trial_sid);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Trial Period Manually Ended!');
                    redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                }
            }

            $this->data['trial_data'] = $trial_data; //Activate Trial Period
            $this->form_validation->set_rules('number_of_days', 'number_of_days', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/activate_trial_period');
            } else {
                $company_sid = $this->input->post('company_sid');
                $number_of_days = $this->input->post('number_of_days');
                $enable_facebook_api = $this->input->post('enable_facebook_api');
                $enable_deluxe_theme = $this->input->post('enable_deluxe_theme');
                $this->company_model->activate_trial_period($company_sid, $number_of_days, $enable_facebook_api, $enable_deluxe_theme);
                redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }
    }

    function list_company_notes($note_type = null, $company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($note_type != null && $company_sid != null) {
            $company_notes = $this->company_model->get_admin_company_notes($company_sid, $note_type);
            $this->data['company_notes'] = $company_notes;
            $this->data['company_sid'] = $company_sid;
            $this->render('manage_admin/company/list_company_notes');
        } else {
            redirect('manage_admin/companies');
        }
    }

    function add_edit_company_note($company_sid = null, $note_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $admin_id = $this->ion_auth->user()->row()->id;
        $note_data = array();

        if ($note_sid > 0) {
            $note_data = $this->company_model->get_admin_company_note($company_sid, $note_sid);
        }

        $this->form_validation->set_rules('note', 'Note', 'required|trim|xss_clean');
        $this->form_validation->set_rules('note_type', 'Note Type', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->data['company_sid'] = $company_sid;
            $this->data['note_data'] = $note_data;
            $this->render('manage_admin/company/add_edit_note');
        } else {
            $note_type = $this->input->post('note_type');
            $note_text = $this->input->post('note');
            $this->company_model->save_admin_company_note($note_sid, $company_sid, $admin_id, $note_type, $note_text);
            $this->session->set_flashdata('message ', '<b>Success:</b> Note Successfully Saved.');
            redirect('manage_admin/companies/list_company_notes/' . $note_type . '/' . $company_sid);
        }
    }

    function delete_admin_company_note()
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $admin_id = $this->ion_auth->user()->row()->id;
        $this->form_validation->set_rules('note_sid', 'note sid', 'required|xss_clean|trim');
        if ($this->form_validation->run() == false) {
            // no need for if else condition in this fucntion
        } else {
            $note_sid = $this->input->post('note_sid');
            $note_type = $this->input->post('note_type');
            $note_text = html_entity_decode($this->input->post('note_text'));
            $company_sid = $this->input->post('company_sid');
            $this->company_model->delete_admin_company_note($company_sid, $note_sid, $note_type, $note_text, $admin_id);
            redirect('manage_admin/companies/list_company_notes/' . $note_type . '/' . $company_sid);
        }
    }

    function manage_maintenance_mode($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->form_validation->set_rules('page_title', 'Title', 'required|trim');
        $this->form_validation->set_rules('page_content', 'Content', 'required|trim');
        $this->form_validation->set_rules('company_sid', 'Company', 'required|trim');
        $this->form_validation->set_rules('portal_sid', 'Portal', 'required|trim');

        if ($this->form_validation->run() == false) {
            $portal_info = $this->maintenance_mode_model->get_employer_portal_record($company_sid);
            $company_info = $this->maintenance_mode_model->get_company_info($company_sid);
            $maintenance_mode_detail = array();
            $employer_portal_sid = $portal_info['sid'];
            //Get Maintenance Mode Record
            $maintenance_mode_detail = $this->maintenance_mode_model->get_maintenance_mode_record($company_sid, $employer_portal_sid);

            if (empty($maintenance_mode_detail)) { //If Not found create new record
                $this->maintenance_mode_model->insert_default_maintenance_mode_record($company_sid, $employer_portal_sid);
            }

            //Re Get Maintenance Mode Record
            $maintenance_mode_detail = $this->maintenance_mode_model->get_maintenance_mode_record($company_sid, $employer_portal_sid);

            if ($portal_info['maintenance_mode'] == 1) {
                $maintenance_mode_enabled_default = true;
                $maintenance_mode_disabled_default = false;
            } else {
                $maintenance_mode_enabled_default = false;
                $maintenance_mode_disabled_default = true;
            }

            $this->data['maintenance_mode_enabled_default'] = $maintenance_mode_enabled_default;
            $this->data['maintenance_mode_disabled_default'] = $maintenance_mode_disabled_default;
            $this->data['company_sid'] = $company_sid;
            $this->data['company_info'] = $company_info;
            $this->data['portal_sid'] = $employer_portal_sid;
            $this->data['maintenance_mode_detail'] = $maintenance_mode_detail;
            $this->data['page_title'] = 'Manage Maintenance Mode';
            $this->render('manage_admin/company/manage_maintenance_mode');
        } else {
            //Get Data from post
            $page_title = $this->input->post('page_title');
            $page_content = $this->input->post('page_content');
            $maintenance_mode_status = $this->input->post('maintenance_mode_status');
            $company_sid = $this->input->post('company_sid');
            $portal_sid = $this->input->post('portal_sid');
            //Update Maintenance Mode Status
            $this->maintenance_mode_model->update_maintenance_mode_status($company_sid, $portal_sid, $maintenance_mode_status);
            //Update Maintenance Mode Page Content
            $page_content = htmlentities($page_content);
            $data_to_update = array();
            $data_to_update['page_title'] = $page_title;
            $data_to_update['page_content'] = $page_content;
            $this->maintenance_mode_model->update_maintenance_mode_page_content($company_sid, $portal_sid, $data_to_update);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Maintenance Mode Successfully updated.');
            redirect('manage_admin/companies/manage_maintenance_mode/' . $company_sid, 'location');
        }
    }

    //    public function manage_products() {
    //        $admin_id = $this->session->userdata('user_id');
    //        $security_details = db_get_admin_access_level_details($admin_id);
    //        $this->data['security_details'] = $security_details;
    //        check_access_permissions($security_details, 'manage_admin', 'private_messages'); // Param2: Redirect URL, Param3: Function Name
    //
    //        $this->data['page_title'] = 'Manage Company Products';
    //        $this->data['groups'] = $this->ion_auth->groups()->result();
    //        //$admin_id = $this->ion_auth->user()->row()->id;
    //
    //        $db_data = $this->message_model->get_admin_messages(1);
    //        $this->data['messages'] = $db_data->result_array();
    //        //unread
    //        $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
    //        //total
    //        $this->data['total'] = $this->message_model->get_messages_total_inbox(1);
    //
    //        $this->data['page'] = 'inbox';
    //        $this->render('manage_admin/private_messages/listing_view');
    //    }

    function job_fairs_recruitment($company_sid = null)
    {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }

        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('content', 'Content', 'required|trim|xss_clean');
        $this->form_validation->set_rules('picture_or_video', 'Show image or video', 'trim|xss_clean|required');
        $this->data['company_sid'] = $company_sid;
        $company_name = $this->company_model->get_company_name($company_sid);

        if ($company_name == '') { // company might not exists, need to verify

        }

        $this->data['company_name'] = $company_name;
        $job_fair_data = $this->company_model->get_job_fair_data($company_sid);

        if (empty($job_fair_data)) {
            $job_fair_page_status = $this->company_model->get_job_fair_status($company_sid);
            $job_fair_data = $this->company_model->get_job_fair_data($company_sid);
        }

        $this->data['job_fair_data'] = $job_fair_data[0];
        $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('content', 'Content', 'required|trim|xss_clean');
        $this->form_validation->set_rules('picture_or_video', 'Show image or video', 'trim|xss_clean|required');

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Job Fairs Recruitment';
            $this->render('manage_admin/company/job_fair_recruitment');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $title = $formpost['title'];
            $content = $formpost['content'];
            $video_id = $formpost['video_id'];
            $status = $formpost['status'];
            $picture_or_video = $formpost['picture_or_video'];

            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['name'] != '') {
                $result = put_file_on_aws('banner_image');
            }

            if (isset($video_id) && $video_id != '' && $video_id != NULL) {
                $video_id = substr($video_id, strpos($video_id, '=') + 1, strlen($video_id));
            }

            $insert_array = array();
            $insert_array['company_sid'] = $company_sid;
            $insert_array['title'] = $title;
            $insert_array['content'] = $content;
            $insert_array['video_id'] = $video_id;
            $insert_array['picture_or_video'] = $picture_or_video;
            $insert_array['status'] = $status;

            if (isset($result) && $result != 'error' && $result != '') {
                $insert_array['banner_image'] = $result;
            }

            $this->company_model->save_job_fair($insert_array);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Job Fairs page updated successfully.');
            redirect('manage_admin/companies/job_fairs_recruitment/' . $company_sid, 'location');
        }
    }

    function footer_logo($company_sid = null)
    {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }

        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('footer_status', 'Type', 'required|trim|xss_clean');
        $this->form_validation->set_rules('footer_type', 'Status', 'required|trim|xss_clean');
        $this->form_validation->set_rules('copyright_company_status', 'Status', 'required|trim|xss_clean');

        $this->data['company_sid'] = $company_sid;
        $company_name = $this->company_model->get_company_name($company_sid);

        $this->data['company_name'] = $company_name;
        $footer_logo_data = $this->company_model->get_footer_logo_data($company_sid);

        $this->data['footer_logo_data'] = $footer_logo_data[0];

        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Footer Logo Setting';
            $this->render('manage_admin/company/footer_logo');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $company_sid = $formpost['company_sid'];
            $status = $formpost['footer_status'];
            $type = $formpost['footer_type'];
            $copyright_status = $formpost['copyright_company_status'];

            $dataToUpdate = array();
            $dataToUpdate['footer_powered_by_logo'] = $status;
            $dataToUpdate['footer_logo_type'] = $type;
            $dataToUpdate['copyright_company_status'] = $copyright_status;

            if ($type == 'text') {

                $text = $formpost['logo_text'];
                $dataToUpdate['footer_logo_text'] = $text;
            } else if ($type == 'logo') {

                if (isset($_FILES['logo_upload']) && $_FILES['logo_upload']['name'] != '') {
                    $result = put_file_on_aws('logo_upload');
                }

                if (isset($result) && $result != 'error' && $result != '') {
                    $dataToUpdate['footer_logo_image'] = $result;
                }
            }

            if ($copyright_status == 1) {

                $company_name = $formpost['company_name'];
                $dataToUpdate['copyright_company_name'] = $company_name;
            }



            $this->company_model->update_footer_logo($company_sid, $dataToUpdate);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Footer Logo updated successfully.');
            redirect('manage_admin/companies/footer_logo/' . $company_sid, 'location');
        }
    }

    function manage_contact_info($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage Contact Info';
            $this->data['company_sid'] = $company_sid;
            $contact_info = $this->company_model->get_contact_info($company_sid);

            if (sizeof($contact_info) == 0) {
                $contact_info[0]['exec_sales_phone_no'] = '';
                $contact_info[0]['exec_sales_email'] = '';
                $contact_info[0]['tech_support_phone_no'] = '';
                $contact_info[0]['tech_support_email'] = '';
            }

            $this->data['contact_info'] = $contact_info;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('SalesPhoneNumber', 'Sales Phone Number', 'required|trim|xss_clean');
            $this->form_validation->set_rules('SalesEmail', 'Sales Email', 'required|trim|xss_clean');
            $this->form_validation->set_rules('TechnicalSupportPhoneNumber', 'Technical Support Phone Number', 'required|trim|xss_clean');
            $this->form_validation->set_rules('TechnicalSupportEmail', 'Technical Support Email', 'required|trim|xss_clean');

            if ($this->form_validation->run() === TRUE) {
                $SalesPhoneNumber = $this->input->post('SalesPhoneNumber');
                $SalesEmail = $this->input->post('SalesEmail');
                $TechnicalSupportPhoneNumber = $this->input->post('TechnicalSupportPhoneNumber');
                $TechnicalSupportEmail = $this->input->post('TechnicalSupportEmail');
                $this->company_model->add_update_contact_info($company_sid, $SalesPhoneNumber, $SalesEmail, $TechnicalSupportPhoneNumber, $TechnicalSupportEmail);
                redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
            }

            $this->render('manage_admin/company/manage_contact_info');
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }


    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
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
            if ($_FILES[$file_input_id]['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                return 'error';
            }
            $aws = new AwsSdk();
            $aws->putToBucket($new_file_name, $_FILES[$file_input_id]["tmp_name"], $bucket_name);
            return $new_file_name;
        } else {
            return 'error';
        }
    }

    function additional_content_boxes($sid)
    {
        //        echo $sid; exit;
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $additional_boxes = $this->company_model->get_additional_content_boxes($sid);
        //        echo '<pre>';
        //        print_r($additional_boxes);
        //        exit;
        $this->data['page_title'] = 'Add Additional Content Boxes';
        $this->render('manage_admin/company/additional_content_boxes_view', 'admin_master');
    }

    function change_applicant_status()
    {
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if ($status) {
            $data = array('enable_applicant_status_bar' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('enable_applicant_status_bar' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_user_status($sid, $data);

        print_r(json_encode($return_data));
    }

    function ajax_change_status()
    {
        $sid = $this->input->post('sid');
        $status = $this->input->post('status');
        $db_field = $this->input->post('db_field');
        $data = array($db_field => $status);

        if ($sid > 0) {
            $this->company_model->update_user_status($sid, $data);
            //            $this->handleXmlJobsByCompanyId($sid, $status); // enable it once the optimized indeed and zip feeds are activated
        }
    }

    function change_resource_center()
    {
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if ($status) {
            $data = array('enable_resource_center' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('enable_resource_center' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_user_status($sid, $data);

        print_r(json_encode($return_data));
    }

    function change_ems_status()
    {
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if ($status) {
            $data = array('ems_status' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('ems_status' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_user_status($sid, $data);

        print_r(json_encode($return_data));
    }

    function change_comply_status()
    {
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if ($status) {
            $data = array('complynet_status' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('complynet_status' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_user_status($sid, $data);

        print_r(json_encode($return_data));
    }

    function change_captcha_status()
    {
        $sid = $this->input->post("sid", true);
        $data = array('enable_captcha' => (int)$this->input->post("status", true));
        //
        $is_updated = $this->company_model->update_employer_status($sid, $data);
        echo json_encode('done');
        exit(0);
    }

    function manage_incident_configuration($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'manage_incident_configuration';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage Incident Reporting Configurations';
            $this->data['company_sid'] = $company_sid;
            $incident_types = $this->Incident_report_model->get_incident_types_company_specific($company_sid);
            $this->data['incident_types'] = $incident_types;
            $company_name = $this->company_model->get_company_name($company_sid);
            $this->data['company_name'] = $company_name;
            $this->form_validation->set_rules('action', 'action', '|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/manage_incident_configuration');
            }
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    public function access_level_plus($company_sid = null)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage Access Level Plus';
            $this->data['company_sid'] = $company_sid;
            $all_employees = $this->company_model->get_company_employers($company_sid);
            $this->data['all_employees'] = $all_employees;
            $configured_employees = $this->company_model->get_configured_access_level_plus_employers($company_sid);
            $this->data['configured_employees'] = array();
            foreach ($configured_employees as $config) {
                $this->data['configured_employees'][] = $config['sid'];
            }
            $configured_employees = $this->data['configured_employees'];
            $this->form_validation->set_rules('action', 'action', '|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/access_level_plus');
            } else {
                $form = $this->input->post(NULL, true);
                // echo '<pre>';
                // print_r($form);
                // print_r($configured_employees);
                // die();
                $selected_emp = isset($form['checklist']) ? $form['checklist'] : array();
                $selected_pp = isset($form['pay-plan']) ? $form['pay-plan'] : array();
                $configured_pp = isset($form['config-pp']) ? json_decode($form['config-pp']) : array();
                $selected_DPO = isset($form['doc_preview']) ? $form['doc_preview'] : array(); // DPO Document Preview Only users
                //
                foreach ($selected_emp as $emp) {
                    if (!in_array($emp, $configured_employees)) {
                        $this->company_model->add_configured_access_level_plus_employers($emp);
                    }
                }
                foreach ($configured_employees as $emp) {
                    if (!in_array($emp, $selected_emp)) {
                        $this->company_model->delete_configured_access_level_plus_employers($emp);
                    }
                }

                foreach ($selected_pp as $emp) {
                    if (!in_array($emp, $configured_pp)) {
                        $this->company_model->update_configured_pay_plan_employers($emp, 1);
                    }
                }
                foreach ($configured_pp as $emp) {
                    if (!in_array($emp, $selected_pp)) {
                        $this->company_model->update_configured_pay_plan_employers($emp, 0);
                    }
                }

                $this->session->set_flashdata('message', 'Access Level Plus Updated');
                redirect('manage_admin/companies/access_level_plus/' . $company_sid, 'refresh');
            }
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    public function manage_complynet($company_sid = null)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage ComplyNet Credentials';
            $this->data['company_sid'] = $company_sid;
            $company_info = $this->company_model->get_company_details($company_sid);
            $all_employees = $this->company_model->get_company_employers($company_sid);
            $company_name = $this->company_model->get_company_name($company_sid);
            $this->data['company_name'] = $company_name;
            $this->data['all_employees'] = $all_employees;
            $this->data['company_info'] = $company_info;
            $this->form_validation->set_rules('action', 'action', '|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/company/manage_complynet');
            } else {
                $link = $this->input->post('complynet_link');
                $insert_data = array('complynet_dashboard_link' => $link);
                $this->company_model->update_user($company_sid, $insert_data, 'ComplyNet dashboard link');
                redirect('manage_admin/companies/manage_complynet/' . $company_sid, 'refresh');
            }
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    public function save_complynet_cred()
    {
        if ($this->input->is_ajax_request()) {
            $username = $this->input->post('user');
            $password = $this->input->post('key');
            $sid = $this->input->post('emp');
            $comply_cred = array('username' => $username, 'password' => $password);
            $insert_data = array('complynet_credentials' => serialize($comply_cred));
            $this->company_model->update_user($sid, $insert_data, 'Employee');
            echo 'updated';
        } else {
            echo 'Not Authentic';
        }
    }

    public function save_exec_complynet_cred()
    {
        if ($this->input->is_ajax_request()) {
            $action = $this->input->post('action');
            if ($action == 'credential') {
                $username = $this->input->post('user');
                $password = $this->input->post('key');
                $link = $this->input->post('link');
                $sid = $this->input->post('emp');
                $comply_cred = array('username' => $username, 'password' => $password);
                $insert_data = array(
                    'complynet_credentials' => serialize($comply_cred),
                    'complynet_dashboard_link' => $link
                );
                $this->company_model->update_excetive_admin($sid, $insert_data);
                echo $password . ' ' . $username . ' ' . $sid . '<pre>';
                print_r($insert_data);
            }
            echo 'updated';
        } else {
            echo 'Not Authentic';
        }
    }

    public function update_exec_comply_status()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->post('status');
            $id = $this->input->post('id');
            $insert_data = array('complynet_status' => !$status);
            $this->company_model->update_excetive_admin($id, $insert_data);
            echo $status;
        } else {
            echo 'Not Authentic';
        }
    }

    /**
     * Get phone number from twilio
     * Created on: 16-03-2021
     *
     * @param $zip Integer
     *
     * @return VOID
     */
    public function get_Phone_number_list($zip = null)
    {
        // Load twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        $send_array = array();
        if (!empty($zip) || $zip != '') {
            $send_array['areacode'] = $zip;
        } else {
            $send_array['areacode'] = 951;
        }
        // Fetch numbers list
        $number_list = $this
            ->twilio
            ->setMode('production')
            ->setCountryISO('US')
            ->availableLocalPhoneNumbers($send_array, 20);


        $res['Status'] = empty($number_list['Error']) ? true : false;
        $res['Response'] = empty($number_list['Error']) ? 'Number List Found.' : $number_list['Error'];
        $res['list'] = $number_list;
        //
        header('Content-Type: application/json');
        echo json_encode($res);
        exit(0);
    }

    /**
     * Purchase phone number from twilio
     * Created on: 17-03-2021
     *
     * @param $Phone_num Integer
     * @param $company_sid Integer
     *
     * @return VOID
     */
    public function purchase_phone_number()
    {

        // Load twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        $actual_phone_number = $_POST['actual_phone_number'];
        //
        $number_to_buy = $_POST['phone_number'];
        //
        $company_sid = $_POST['company_sid'];
        // Purchase phone number
        $resp = $this
            ->twilio
            ->setMode('production')
            ->setReservePhone($actual_phone_number)
            ->incomingPhoneNumbers();

        $response = array();

        if (!isset($resp['Error'])) {
            $PhoneSid = $resp['Sid'];

            $check_row = $this->company_model->check_company_row_exist($company_sid);

            if (!$check_row) {
                $this
                    ->company_model
                    ->save_company_phone_number(array(
                        'company_sid' => $company_sid,
                        'phone_sid' => $PhoneSid,
                        'phone_number' => $actual_phone_number,
                        'purchaser_type' => 'admin',
                        'purchaser_id' => $this->ion_auth->user()->row()->id
                    ));
            } else {
                $data_to_update = array();
                $data_to_update['phone_sid'] = $PhoneSid;
                $data_to_update['phone_number'] = $actual_phone_number;
                $data_to_update['purchaser_type'] = 'admin';
                $data_to_update['purchaser_id'] = $this->ion_auth->user()->row()->id;
                //
                $this
                    ->company_model
                    ->update_company_phone_number($company_sid, $data_to_update);
            }



            $response['Status'] = true;
            $response['Response'] = 'You have successfully purchased this number (' . $number_to_buy . ').';
        } else {
            $response['Status'] = false;
            $response['Response'] = $resp['Error'];
        }
        //
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }

    /**
     * Create meassage service for company
     * Created on: 17-03-2021
     *
     * @param $service_name String
     * @param $company_sid Integer
     *
     * @return VOID
     */
    public function create_message_service()
    {
        $response = array();
        // Load twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        $service_name = $_POST['service_name'];
        //
        if (strlen($service_name) > 11) {
            $response['Status'] = false;
            $response['Response'] = "Maximum 11 characters allowed.";
        } else {
            //
            $company_sid = $_POST['company_sid'];
            //
            $service_code = $_POST['service_code'];
            //
            $service_sid = $_POST['service_sid'];
            //
            if ($service_sid != 'no') {
                // $this->twilio->deleteMessageService($service_sid);
            }
            //
            $resp = $this
                ->twilio
                ->setMode('production')
                ->setAlfaSenderName($service_name)
                ->setMessageServiceCode(
                    array(
                        'company_id' => $company_sid
                    )
                )
                ->setMessageServicePhoneSid($service_code)
                ->createMessageService();

            if (!isset($resp['Error'])) {
                $data_to_update = array();
                $data_to_update['alfa_sender_sid'] = $resp['AlfaSid'];
                $data_to_update['message_service_sid'] = $resp['Sid'];
                $data_to_update['message_service_name'] = $resp['AlfaName'];
                $data_to_update['alfa_sender_name'] = $resp['AlfaName'];
                //
                $this
                    ->company_model
                    ->update_company_phone_number($company_sid, $data_to_update);

                $response['Status'] = true;
                $response['Response'] = 'You have successfully created message service.';
            } else {
                $response['Status'] = false;
                $response['Response'] = $resp['Error'];
            }
        }
        //
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }


    /**
     * Buy a new number for the company
     * Created on: 18-07-2019
     *
     * @param $company_sid Integer
     *
     * @return VOID
     */
    private function sms_buy_process($company_sid)
    {
        // Load twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        // For Sandbox mode
        if (SMS_MODE == 'sandbox') {
            if (IS_SANDBOX) {
                $is_record = $this
                    ->company_model
                    ->get_company_phone_by_sid($company_sid);
                if (!$is_record) {
                    $this
                        ->company_model
                        ->save_company_phone_number(array(
                            'company_sid' => $company_sid,
                            'phone_sid' => 'MG2798b7fc2bce2a1c7121f5aaf809c298',
                            'message_service_sid' => 'MG359e34ef1e42c763d3afc96c5ff28eaf',
                            'message_service_name' => 'Development',
                            'phone_number' => '+15005550006',
                            'purchaser_type' => 'admin',
                            'purchaser_id' => $this->ion_auth->user()->row()->id
                        ));
                }
                return true;
            }
        }
        // For development mode
        if (SMS_MODE == 'staging') {
            $number_list['Number'] = '+1 909 757 0288';
            $MessageServiceCode = 'PNcd41479c6145ecb0eab1dcdcf608360e';
        } else {
            // Check db for phone number
            $is_record = $this
                ->company_model
                ->get_company_phone_by_sid($company_sid);
            $h = fopen('step1.txt', 'w');
            fwrite($h, json_encode($is_record));
            fclose($h);
            //
            if ($is_record) return;
            // Buy a new number
            // Fetch numbers list
            $number_list = $this
                ->twilio
                ->setMode('production')
                ->setCountryISO('US')
                // ->availableLocalPhoneNumbers(array());
                ->availableLocalPhoneNumbers(array('areacode' => 951));
            // _e($number_list, true, true);
            // ->availablePhoneNumbers();
            // Check for error
            $h = fopen('step2.txt', 'w');
            fwrite($h, json_encode($number_list));
            fclose($h);
            if (isset($number_list['Error'])) return $number_list;
            if (!isset($number_list['FriendlyName'])) return $number_list;
            $number_to_buy = $number_list['FriendlyName'];
            //
            if ($number_to_buy == '') return $number_list;
            // Reserve numbers list
            $resp = $this
                ->twilio
                ->setReservePhone($number_to_buy)
                ->incomingPhoneNumbers();
            $h = fopen('step3.txt', 'w');
            fwrite($h, json_encode($resp));
            fclose($h);
            //
            if (isset($resp['Error'])) return $resp;
            // Let's create a messag service
            $MessageServiceCode = $resp['Sid'];
        }

        // Link number to MessageService
        $resp2 =
            $this
            ->twilio
            ->setMode('production')
            ->setAlfaSenderName($this->company_model->get_company_column($company_sid, 'CompanyName'))
            ->setMessageServiceCode(
                array(
                    'company_id' => $company_sid
                )
            )
            ->setMessageServicePhoneSid($MessageServiceCode)
            // _e($this->twilio->debug(), true);
            ->createMessageService();
        $h = fopen('step4.txt', 'w');
        fwrite($h, json_encode($resp2));
        fclose($h);
        // _e($resp2, true, true);
        // Check for errors
        if (isset($resp2['Error'])) return false;
        // Save number to db
        $this
            ->company_model
            ->save_company_phone_number(array(
                'company_sid' => $company_sid,
                'phone_sid' => $MessageServiceCode,
                'alfa_sender_sid' => $resp2['AlfaSid'],
                'alfa_sender_name' => $resp2['AlfaName'],
                'message_service_sid' => $resp2['Sid'],
                'message_service_name' => $resp2['MessageServiceCode'],
                'phone_number' => $number_list['Number'],
                'purchaser_type' => 'admin',
                'purchaser_id' => $this->ion_auth->user()->row()->id
            ));

        return true;
    }


    /**
     * Genrate XML jobs for company
     * Created on: 08-08-2019
     *
     * @param $companyId  Integer
     * @param $status     String
     *
     * @return VOID
     */
    function handleXmlJobsByCompanyId($companyId, $status)
    {
        // Load model
        $this->load->model('all_feed_model');
        $this->load->model('job_listings_visibility_model');
        // Flush all company xml jobs
        $this->all_feed_model->flushXmlJobsByCompanyId($companyId);
        // Check if company is active etc
        $companyDetails = $this->all_feed_model->checkCompanyStatusByCompanyId($companyId, $status);
        //
        if (!$companyDetails) return;
        //
        $jobs = $this->all_feed_model->jobsByCompanyId($companyId, $status === 1 ? true : false);
        $jobRows = '';
        foreach ($jobs as $job) {
            $formpost = array();
            if ($status == 1) {
                // Fetch PPJ Jobs
                $exp_date = date('Y-m-d', strtotime($job['ppj_activation_date'] . ' + ' . $job['ppj_expiry_days'] . ' days '));
                if (strtotime(date('Y-m-d')) > strtotime($exp_date)) continue;
            } else {
                // Fetch Organic Jobs
                if ($companyDetails['has_job_approval_rights'] ==  1) if ($job['approval_status'] != 'approved') continue;
            }
            $formpost['publish_date'] = $job['activation_date'];
            $result = $this->job_listings_visibility_model->getUidOfJob($companyId, $job['sid']);
            //
            if ($result === 0) $uid = $job['sid'];
            else {
                $uid = $result['uid'];
                $formpost['publish_date'] = $result['publish_date'];
            }
            //

            $formpost['Title'] = $job['Title'];
            $formpost['Salary'] = $job['Salary'];
            $formpost['SalaryType'] = $job['SalaryType'];
            $formpost['JobCategory'] = $job['JobCategory'];
            $formpost['Location_City'] = $job['Location_City'];
            $formpost['JobDescription'] = $job['JobDescription'];
            $formpost['Location_State'] = $job['Location_State'];
            $formpost['JobRequirements'] = $job['JobRequirements'];
            $formpost['Location_Country'] = $job['Location_Country'];
            $formpost['Location_ZipCode'] = $job['Location_ZipCode'];

            //
            $jobRow = xml_create_job(
                $uid,
                $companyId,
                $companyDetails['sub_domain'],
                $companyDetails['CompanyName'],
                $companyDetails['job_title_location'],
                $formpost,
                '',
                $this
            );

            //
            $insertDataArray = array(
                'job_sid' => $job['sid'],
                'company_sid' => $companyId,
                'is_indeed_job' => 1,
                'is_ziprecruiter_job' => 1,
                'job_content' => $jobRow
            );

            $this->job_listings_visibility_model->insertXmlJob($insertDataArray);

            if ($status == 0) {
                $indeed = $this->indeedOrganicDB($formpost, $uid, $companyId, $companyDetails, $job['sid']);
                $ziprec = $this->zipRecruiterOrganicDB($formpost, $uid, $companyId, $companyDetails, $job['sid']);
                if ($indeed === 0 && $ziprec === 0) {
                    $this->job_listings_visibility_model->updateXmlJob(array(
                        'is_indeed_job' => 1,
                        'is_ziprecruiter_job' => 1
                    ), array(
                        'job_sid' => $job['sid']
                    ));
                }
            }
        }
    }

    /**
     * Set, delete indeed job
     * Created on: 07-08-2019
     *
     * @param $formpost         Array
     * @param $uid              String
     * @param $companySid       Integer
     * @param $companyPortal    Array
     * @param $jobSid           Integer
     *
     * @return VOID
     */
    private function indeedOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid
    ) {
        // Check if product is active
        $is_product_purchased = $this->job_listings_visibility_model->isPurchasedJob($jobSid, $this->indeedProductIds);
        if ((int)$is_product_purchased === 0) {
            // Update xml job indeed check to 0
            $this->job_listings_visibility_model->updateXmlJob(
                array('is_indeed_job' => 0),
                array('job_sid' => $jobSid)
            );
            return 0;
        }
        // Check if job exists in database
        $xmlJobId = $this->job_listings_visibility_model->getXmlJobId($jobSid);
        // If not found then insert
        if (!$xmlJobId) {
            $insertDataArray = array('job_sid' => $jobSid, 'company_sid' => $companySid, 'is_indeed_job' => 1);
            $xmlJobId = $this->job_listings_visibility_model->insertXmlJob($insertDataArray);
        }
        //
        $job = xml_create_job(
            $uid,
            $companySid,
            $companyPortal['sub_domain'],
            $companyPortal['CompanyName'],
            $companyPortal['job_title_location'],
            $formpost,
            'indeed',
            $this
        );
        // Update
        $this->job_listings_visibility_model->updateXmlJob(array('company_sid' => $companySid, 'job_content' => $job, 'is_indeed_job' => 1, 'is_ziprecruiter_job' => 0), array('sid' => $xmlJobId));
        return 1;
    }

    /**
     * Set, delete Zip Recruiter job
     * Created on: 07-08-2019
     *
     * @param $formpost         Array
     * @param $uid              String
     * @param $companySid       Integer
     * @param $companyPortal    Array
     * @param $jobSid           Integer
     *
     * @return VOID
     */
    private function zipRecruiterOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid
    ) {
        // Check if product is active
        $is_product_purchased = $this->job_listings_visibility_model->isPurchasedJob($jobSid, $this->ziprecruiterProductIds);
        if ((int)$is_product_purchased === 0) {
            // Update xml job indeed check to 0
            $this->job_listings_visibility_model->updateXmlJob(
                array('is_ziprecruiter_job' => 0),
                array('job_sid' => $jobSid)
            );
            return 0;
        }
        // Check if job exists in database
        $xmlJobId = $this->job_listings_visibility_model->getXmlJobId($jobSid);
        // If not found then insert
        if (!$xmlJobId) {
            $insertDataArray = array('job_sid' => $jobSid, 'company_sid' => $companySid, 'is_ziprecruiter_job' => 1);
            $xmlJobId = $this->job_listings_visibility_model->insertXmlJob($insertDataArray);
        }
        //
        $job = xml_create_job(
            $uid,
            $companySid,
            $companyPortal['sub_domain'],
            $companyPortal['CompanyName'],
            $companyPortal['job_title_location'],
            $formpost,
            'ziprecruiter',
            $this
        );
        // Update
        $this->job_listings_visibility_model->updateXmlJob(array('company_sid' => $companySid, 'job_content' => $job, 'is_ziprecruiter_job' => 1, 'is_indeed_job' => 0), array('sid' => $xmlJobId));
        return 1;
    }

    /**
     * 
     */
    public function manage_sms($sid)
    {
        //
        $admin_id = $this->ion_auth->user()->row()->id;
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        //
        check_access_permissions($this->data['security_details'], 'manage_admin', 'edit_employers'); // Param2: Redirect URL, Param3: Function Name
        //
        $this->data['security_access_levels'] = $this->company_model->get_security_access_levels();
        //
        $this->data['sid'] = $sid;
        //
        $this->data['companyInfo'] = $this->company_model->get_company_details($sid);
        //
        $this->data['phoneNumber'] = $this->company_model->getPhoneNumber($sid);
        //
        if ($this->form_validation->run() === FALSE) {

            $this->load->helper('form');
            $this->render('manage_admin/company/manage_sms');
        }
    }

    function customize_career_site($company_sid = null)
    {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }

        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
        $this->form_validation->set_rules('menu', 'Menu', 'required|trim|xss_clean');
        $this->form_validation->set_rules('footer', 'Footer', 'required|trim|xss_clean');

        $this->data['company_sid'] = $company_sid;
        $company_name = $this->company_model->get_company_name($company_sid);

        $this->data['company_name'] = $company_name;
        $this->data['customize_career_site'] = $this->company_model->get_customize_career_site_data($company_sid);
        $career_site_pages = $this->company_model->get_career_site_pages($company_sid);
        $static_pages = [
            [
                'page_name' => 'home',
                'page_title' => 'Home'
            ],
            [
                'page_name' => 'company_website',
                'page_title' => 'Company Website'
            ],
            [
                'page_name' => 'contact_us',
                'page_title' => 'Contact Us'
            ],
        ];

        $this->data['career_site_pages'] = array_merge($static_pages, $career_site_pages);


        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Customize Career Site';
            $this->render('manage_admin/company/customize_career_site');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $company_sid = $formpost['company_sid'];
            $formpost['inactive_pages'] = json_encode(array_values(array_filter($formpost['inactive_pages'])));
            $this->company_model->update_customize_career_site($company_sid, $formpost);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Customize Career Site updated successfully.');
            redirect('manage_admin/companies/customize_career_site/' . $company_sid, 'location');
        }
    }

    function update_module_status()
    {
        $r = array();
        $r['Status'] = FALSE;
        $r['Response'] = 'Failed to update module';
        //
        $statusUpdated = $this->company_model->update_module_status();
        //
        if ($statusUpdated) {
            $r['Status'] = TRUE;
            $r['Response'] = 'Proceed';
            //
            $post = $this->input->post(NULL, TRUE);
            // For Gusto
            if ($post['Id'] == 7 && $post['Status'] == 0) {
                // Load Payroll Model
                $this->load->model('Payroll_model', 'pm');
                // Check if company exists on Gusto
                $exists = $this->pm->GetCompany($post['CompanyId'], 'sid');
                //
                if (empty($exists)) {
                    // Load Curl Helper
                    $this->load->helper('curl');
                    //
                    SendRequest(
                        base_url('create_partner_company'),
                        [
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array(
                                'center' => '2',
                                'sid' => $post['CompanyId']
                            ),
                            CURLOPT_HTTPHEADER => [
                                'X-Requested-With: XMLHttpRequest'
                            ]
                        ]
                    );
                }
            }

            if ($post['Id'] == 1 && $post['Status'] == 0) {
                $this->load->model('manage_admin/copy_policies_model');
                $this->copy_policies_model->checkAndAddDefaultPolicies($post['CompanyId']);
            }
        }
        //
        header('Content-Type: application/json');
        echo json_encode($r);
        exit(0);
    }


    public function timeoff_approvers(
        $companySid,
        $approver = null,
        $status = null
    ) {
        //
        $this->data['companySid'] = $companySid;
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name


        //--------------------Search section Start---------------//
        $approver = $approver != null ? urldecode($approver) : 'all';
        $status = $status != null ? urldecode($status) : 'all';
        //
        $this->data['approver'] = $approver;
        $this->data['status'] = $status;


        $this->data['total'] = $this->company_model->getCompanyApprovers(
            $companySid,
            $approver,
            $status,
            true
        );
        $this->data['flag'] = $approver != 'all' || $status != 'all' ? true : false;

        //--------------------Search section End---------------//
        //Pagination
        $config = array();
        $config['total_rows'] = $this->data['total'];
        $config['base_url'] = base_url() . "manage_admin/companies" . '/timeoff_approvers/' . $companySid . '/' . urlencode($approver) . '/' . urldecode($status);
        $config['per_page'] = 50;
        $config['uri_segment'] = 10;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = round($choice);
        $config['use_page_numbers'] = true;
        //pagination style
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(10)) ? $this->uri->segment(10) : 1;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $config["per_page"];
        }

        $this->data['links'] = $this->pagination->create_links();
        $this->data['page_title'] = 'Manage Company Time Off Approvers';

        $this->data['companies'] = $this->company_model->getCompanyApprovers(
            $companySid,
            $approver,
            $status,
            false,
            $config['per_page'],
            $my_offset
        );

        // Get company employee list
        $this->data['employees'] = $this->company_model->getCompanyActiveEmployees($companySid);

        $this->render('manage_admin/company/timeoff_approvers', 'admin_master');
    }

    public function edit_approver(
        $companySid,
        $approverSid
    ) {
        //
        $this->data['approverSid'] = $approverSid;
        $this->data['companySid'] = $companySid;
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Edit Time Off Approver';
        // Get approver
        $this->data['approver'] = $this->company_model->getSingleApprover($approverSid);

        if (!sizeof($this->data['approver'])) {
            redirect('manage_admin/companies/timeoff_approvers/' . ($companySid) . '', 'refresh');
            return;
        }

        // Get company employee list
        $this->data['employees'] = $this->company_model->getCompanyActiveEmployees($companySid);
        $this->data['departments'] = $this->company_model->getCompanyActiveDepartments($companySid);

        $this->render('manage_admin/company/edit_approver', 'admin_master');
    }

    public function add_approver(
        $companySid
    ) {
        //
        $this->data['companySid'] = $companySid;
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Add Time Off Approver';


        // Get company employee list
        $this->data['employees'] = $this->company_model->getCompanyActiveEmployees($companySid);
        $this->data['departments'] = $this->company_model->getCompanyActiveDepartments($companySid);

        $this->render('manage_admin/company/add_approver', 'admin_master');
    }

    //
    function activate_approver()
    {
        $res = array('Status' => false, 'Response' => 'Invalid request!');
        $post = $this->input->post(NULL, TRUE);
        header('Content-Type: application/json');
        if (!sizeof($post) || !isset($post['approverSid'])) {
            echo json_encode($res);
            exit(0);
        }

        //
        $this->company_model->changeApproverStatus($post['approverSid'], 0);
        $res['Status'] = true;
        $res['Response'] = 'Approver is activated.';
        echo json_encode($res);
        exit(0);
    }

    //
    function deactivate_approver()
    {
        $res = array('Status' => false, 'Response' => 'Invalid request!');
        $post = $this->input->post(NULL, TRUE);
        header('Content-Type: application/json');
        if (!sizeof($post) || !isset($post['approverSid'])) {
            echo json_encode($res);
            exit(0);
        }

        //
        $this->company_model->changeApproverStatus($post['approverSid'], 1);
        $res['Status'] = true;
        $res['Response'] = 'Approver is deactivated.';
        echo json_encode($res);
        exit(0);
    }

    //
    function edit_approver_process()
    {
        $res = array('Status' => false, 'Response' => 'Invalid request!');
        $post = $this->input->post(NULL, TRUE);
        //
        header('Content-Type: application/json');
        //
        if (!sizeof($post)) {
            echo json_encode($res);
            exit(0);
        }
        // Check if it already exists
        $exists = $this->company_model->checkApprover($post);
        if ($exists) {
            $res['Response'] = 'Approver already exists for the selected department.';
            echo json_encode($res);
            exit(0);
        }
        //
        $this->company_model->updateApprover($post);
        $res['Status'] = true;
        $res['Response'] = 'Approver is updated.';
        echo json_encode($res);
        exit(0);
    }

    //
    function add_approver_process()
    {
        $res = array('Status' => false, 'Response' => 'Invalid request!');
        $post = $this->input->post(NULL, TRUE);
        //
        header('Content-Type: application/json');
        //
        if (!sizeof($post)) {
            echo json_encode($res);
            exit(0);
        }
        // Check if it already exists
        $exists = $this->company_model->checkApprover($post);
        if ($exists) {
            $res['Response'] = 'Approver already exists for the selected department.';
            echo json_encode($res);
            exit(0);
        }
        //
        $this->company_model->addApprover($post);
        $res['Status'] = true;
        $res['Response'] = 'Approver is added.';
        echo json_encode($res);
        exit(0);
    }


    function update_company_email()
    {
        //
        $this->company_model->UpdateCompanyIndeed(
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['companyId']
        );

        echo 'success';
    }

    public function manage_payroll($company_sid)
    {
        //
        $this->load->helper('common_helper');
        //
        $redirect_url = 'manage_admin';
        $function_name = 'manage_payroll';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Company Payroll Module';
        //
        $this->load->model('Payroll_model', 'pm');
        $this->data['company_info'] = $this->pm->GetGustoCompanyData($company_sid);
        $this->data['companyPayrollStatus'] = $this->pm->GetCompanyPayrollStatus($company_sid);
        //
        $company_status = array();
        $onboarding_link = "";
        //
        if (!empty($this->data['company_info']['access_token'])) {
            //
            $this->load->helper('v1/payroll' . ($this->db->where([
                "company_sid" => $company_sid,
                "stage" => "production"
            ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
            //
            $company_status = GetCompanyStatus($this->data['company_info']);
            //
            $flow_info = CreateCompanyFlowLink($this->data['company_info']);
            //
            $onboarding_link = isset($flow_info['url']) ? $flow_info['url'] : '';
            //
            $this->data['PageScripts'] = [
                'js/app_helper',
                time() => 'gusto/js/company_onboard'
            ];
        }
        //
        $this->data['company_status'] = $company_status;
        $this->data['onboarding_link'] = $onboarding_link;
        //
        $this->render('payroll/manage_payroll');
    }


    function default_document_category_listing($company_sid = null)
    {
        //
        $this->data['page_title'] = "Document Default Categories";
        $this->data['company_sid'] = $company_sid;

        $default_categories = $this->company_model->get_company_all_default_categories($company_sid);
        $this->data['default_categories'] = $default_categories;

        //
        $this->render('manage_admin/documents/default_companies_categories');
    }




    function add_default_category($company_sid = null)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;

        $this->data['page_title'] = "Add a Default Category";
        $this->data['page_sub_title'] = "Add a New Category";
        $this->data['company_sid'] = $company_sid;
        //
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $this->data['default_category'] = array();
            //
            $this->render('manage_admin/documents/companies_default_category_form');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['company_sid']  = $company_sid;
            $insert_array['is_default']  = 1;
            $insert_array['name'] = $formpost['category_name'];
            $insert_array['status']  = $formpost['status'];
            $insert_array['description']  = $formpost['description'];
            $insert_array['sort_order']  = $formpost['sort_order'];
            $insert_array['created_by_sid']  = 0;
            $insert_array['created_date'] = date('Y-m-d H:i:s');
            //
            $this->load->model('manage_admin/documents_model');
            $check = $this->documents_model->check_unique_with_name(0, $formpost['category_name']);

            if ($check > 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b>Category name is already Exists!');
                redirect(base_url('manage_admin/companies/default_document_category_listing/' . $company_sid));
            }
            $this->documents_model->add_category($insert_array);
            redirect(base_url('manage_admin/companies/default_document_category_listing/' . $company_sid));
        }
    }


    function edit_default_category($sid)
    {
        $company_sid = $this->uri->segment('5');
        $this->load->model('manage_admin/documents_model');
        //
        $this->data['page_title'] = "Edit a Default Category";
        $this->data['page_sub_title'] = "Edit Category";
        //
        $default_category = $this->documents_model->get_category($sid);
        //
        if (empty($default_category)) {
            $this->session->set_flashdata('message', '<b>Warning:</b>Default category not found!');
            redirect(base_url('manage_admin/default_categories'));
        }
        //
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        //
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        //
        if ($this->form_validation->run() == FALSE) {
            //
            $this->data['category'] = $default_category;
            $this->data['company_sid'] = $company_sid;
            //
            $this->render('manage_admin/documents/companies_default_category_form');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $data_to_update = array();
            $data_to_update['name'] = $formpost['category_name'];
            $data_to_update['status']  = $formpost['status'];
            $data_to_update['description']  = $formpost['description'];
            $data_to_update['sort_order']  = $formpost['sort_order'];
            //
            $check = $this->documents_model->check_unique_with_name(0, $formpost['category_name'], $sid);
            //
            if ($check > 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b>Category name is already Exists!');
                redirect(base_url('manage_admin/companies/default_document_category_listing/' . $company_sid));
            }
            //
            $this->documents_model->update_category($sid, $data_to_update);
            //
            redirect(base_url('manage_admin/companies/default_document_category_listing/' . $company_sid));
        }
    }



    //
    function manage_company_help_box($company_sid = null)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $this->data['page_title'] = 'Manage Help Box Info';
            $this->data['company_sid'] = $company_sid;
            $contact_info = $this->company_model->get_helpbox_info($company_sid);

            if (sizeof($contact_info) == 0) {
                $contact_info[0]['box_title'] = '';
                $contact_info[0]['box_support_email'] = '';
                $contact_info[0]['box_support_phone_number'] = '';
                $contact_info[0]['box_status'] = '0';
                $contact_info[0]['button_text'] = '';
            }

            $this->data['contact_info'] = $contact_info;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('helpboxtitle', 'Title', 'required|trim|xss_clean');
            $this->form_validation->set_rules('helpButtonText', 'Button Text', 'required|trim|xss_clean');
            $this->form_validation->set_rules('helpboxemail', 'Email', 'required|trim|valid_email|xss_clean');
            $this->form_validation->set_rules('helpboxphonenumber', 'Phone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('helpboxstatus', 'Status', 'required|trim|xss_clean');

            if ($this->form_validation->run() === TRUE) {
                $helpboxTitle = $this->input->post('helpboxtitle');
                $helpboxEmail = $this->input->post('helpboxemail');
                $helpboxPhoneNumber = $this->input->post('helpboxphonenumber');
                $helpboxStatus = $this->input->post('helpboxstatus');
                $helpButtonText = $this->input->post('helpButtonText');

                $this->company_model->add_update_helpbox_info($company_sid, $helpboxTitle, $helpboxEmail, $helpboxPhoneNumber, $helpboxStatus, $helpButtonText);
                $this->session->set_flashdata('message', '<strong>Success:</strong> You have successfully updated the details.');
                redirect('manage_admin/companies/manage_company_help_box/' . $company_sid, 'refresh');
            }

            $this->render('manage_admin/company/manage_company_help_box');
        } else {
            redirect('manage_admin/companies', 'refresh');
        }
    }

    public function send_invoice_by_email()
    {
        //
        $post = $this->input->post(null, true);
        //
        $emails = get_notification_email_contacts($post['companyId'], 'billing_invoice');
        //
        if (!$emails) {
            return SendResponse(200, ['error' => 'No emails found in billing. Please add them first']);
        }
        //
        //
        $subject = 'A new Invoice Generated!';
        foreach ($emails as $email) {
            $message_body = '';
            $message_body .= '<p>' . 'Dear ' . ucwords($email['contact_name']) . '</p>';
            $message_body .= '<p>' . 'A new invoice has been automatically generated' . '</p>';
            $message_body .= '<p>' . 'Invoice Details are as Follows: ' . '</p>';
            $message_body .= generate_invoice_html($post['invoiceId']);
            $message_body .= '<p>Please, click on the following link to pay the invoice.</p>';
            $message_body .= getButtonForEmail([
                '{{url}}' => base_url('pay/invoice/' . $post['invoiceId']),
                '{{color}}' => '#003087',
                '{{text}}' => 'View & Pay Invoice',
            ]);
            //
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
            //
            log_and_sendEmail(FROM_EMAIL_ACCOUNTS, $email['email'], $subject, $message_body, STORE_NAME);
        }
        //
        return SendResponse(200, ['success' => 'Email sent!']);
    }


    public function secureDocumentByCompany(int $companyId)
    {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $documentTitle = $this->input->get('title', true);

        $this->load->model('assign_bulk_documents_model');

        $this->data['secure_documents'] = $this->assign_bulk_documents_model->getSecureDocuments($companyId, $documentTitle);

        $this->data['page_title'] = 'Company Secure Documents';
        $this->data['company_sid'] = $companyId;
        //
        $this->render('manage_admin/company/company_secure_documents');
    }



    //
    function change_incident_status()
    {
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if ($status) {
            $data = array('is_active' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('is_active' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_incident_status($sid, $data);

        print_r(json_encode($return_data));
    }
}
