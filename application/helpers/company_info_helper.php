<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_contact_info')) {

    function get_contact_info($company_sid)
    {
        $CI = &get_instance();

        if ($company_sid == NULL) {
            $session = $CI->session->userdata('logged_in');
            $company_sid = $session['company_detail']['sid'];
        }

        $CI->db->where('company_id', $company_sid);
        $result = $CI->db->get('contact_info_for_company')->result_array();
        return $result;
    }
}

if (!function_exists('get_employee_profile_info')) {
    function get_employee_profile_info($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name,last_name,email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, profile_picture, PhoneNumber');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->row_array();
    }
}

if (!function_exists('get_fillable_info')) {

    function get_fillable_info($form_name, $user_type, $user_sid)
    {
        $CI = &get_instance();

        //        $CI->db->select('user_consent');
        if ($form_name == 'w4') {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('employer_sid', $user_sid);
            $CI->db->where('status', 1);
            $CI->db->from('form_w4_original');

            $records_obj = $CI->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('user_sid', $user_sid);
            $CI->db->where('status', 1);
            $CI->db->from('applicant_w9form');

            $records_obj = $CI->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } else {
            $CI->db->where('user_type', $user_type);
            $CI->db->where('user_sid', $user_sid);
            $CI->db->where('status', 1);
            $CI->db->from('applicant_i9form');

            $records_obj = $CI->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }
        if (sizeof($records_arr) > 0) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('get_document_type')) {

    function get_document_type($document_sid)
    {
        $CI = &get_instance();
        $CI->db->select('document_description, acknowledgment_required, download_required, signature_required');
        $CI->db->where('sid', $document_sid);
        $CI->db->where('archive', 0);
        $CI->db->from('documents_assigned');
        $CI->db->limit(1);
        $assigned_document = $CI->db->get()->row_array();

        $is_magic_tag_exist = 0;
        $is_document_completed = 0;

        if (!empty($assigned_document['document_description'])) {

            $document_description = $assigned_document['document_description'];
            $document_body = replace_select_html_tag($document_description);
            $magic_codes = document_description_tags('all');

            if (str_replace($magic_codes, '', $document_body) != $document_body) {
                $is_magic_tag_exist = 1;
            }
        }

        if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist)) {
            return 'no';
        } else { // nothing is required so it is "No Action Required Document"
            return 'yes';
        }
    }
}

if (!function_exists('get_form_view')) {

    function get_form_view($form, $form_data)
    {
        $CI = &get_instance();
        if ($form == 'w4') {
            $form_values['pre_form'] = $form_data;
            $assign_on = date("Y-m-d", strtotime($form_data['sent_date']));
            $compare_date = date("Y-m-d", strtotime('2020-01-06'));

            if ($assign_on >= $compare_date) {
                //  $view = $CI->load->view('form_w4/form_w4_2020_pdf', $form_values, TRUE);
                $view = $CI->load->view('form_w4/form_w4_2023_pdf', $form_values, TRUE);
            } else {
                $view = $CI->load->view('form_w4/test_form_w4', $form_values, TRUE);
            }
        } else if ($form == 'w9') {
            $form_values['pre_form'] = $form_data;
            $form_values['pre_form']['dated'] = !empty($form_data['signature_timestamp']) ? DateTime::createFromFormat('Y-m-d H:i:s', $form_data['signature_timestamp'])->format('M d Y') : '';
            $view = $CI->load->view('form_w9/form_w9_pdf_popup', $form_values, TRUE);
        } else if ($form == 'i9') {
            $form_values['pre_form'] = $form_data;
            $form_values['action_button'] = "allowed";
            $form_values['section_access'] = "complete_pdf";
            $form_values['pre_form']['dated'] = !empty($form_data['signature_timestamp']) ? DateTime::createFromFormat('Y-m-d H:i:s', $form_data['signature_timestamp'])->format('M d Y') : '';
            //
            if (!empty($form_values["pre_form"]["section1_preparer_or_translator"]) && empty($form_values["pre_form"]["section1_preparer_json"])) {
                $form_values["pre_form"]["section1_preparer_json"] = copyPrepareI9Json($form_values["pre_form"]);
            }
            //
            if (!empty($form_values["pre_form"]["section3_emp_sign"]) && empty($form_values["pre_form"]["section3_authorized_json"])) {
                $form_values["pre_form"]["section3_authorized_json"] = copyAuthorizedI9Json($form_values["pre_form"]);
            }
            //
            if (!empty($form_values["pre_form"]["version"]) && $form_values["pre_form"]["version"] == "2023") {
                $view = $CI->load->view('2022/federal_fillable/form_i9_preview_new', $form_values, TRUE);
            } else {
                $view = $CI->load->view('2022/federal_fillable/form_i9_preview', $form_values, TRUE);
            }
            //
        } else if ($form == 'pw4') {
            $form_values['pre_form'] = $form_data;
            $view = $CI->load->view('form_w4/pending_form_w4', $form_values, TRUE);
        } else if ($form == 'pw9') {
            $form_values['pre_form'] = $form_data;
            $form_values['pre_form']['dated'] = !empty($form_data['signature_timestamp']) ? DateTime::createFromFormat('Y-m-d H:i:s', $form_data['signature_timestamp'])->format('M d Y') : '';
            $view = $CI->load->view('form_w9/form_w9_pdf_popup', $form_values, TRUE);
            $view = $CI->load->view('form_w9/pending_form_w9', $form_values, TRUE);
        }
        return $view;
    }
}

if (!function_exists('replace_tags_for_document')) {
    function replace_tags_for_document($company_sid, $user_sid = null, $user_type = null, $document_body, $document_sid = 0, $authorized_signature = 0, $signature_base64 = false, $forDownload = false, $autofill = 0)
    {
        $CI = &get_instance();

        //Get Company Info
        $CI->db->select('users.CompanyName');
        $CI->db->select('users.Location_Address');
        $CI->db->select('users.Location_Country');
        $CI->db->select('users.Location_State');
        $CI->db->select('users.Location_City');
        $CI->db->select('users.CompanyDescription');
        $CI->db->select('users.Location_ZipCode');
        $CI->db->select('users.PhoneNumber');

        $CI->db->select('states.state_name as state');

        $CI->db->select('portal_employer.sub_domain');
        $CI->db->select('portal_employer.domain_type');

        $CI->db->where('users.sid', $company_sid);
        $CI->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $CI->db->join('states', 'users.Location_State = states.sid', 'left');

        $record_obj = $CI->db->get('users');
        $company_info = $record_obj->result_array();
        $record_obj->free_result();

        $full_address = '';
        $career_site_url = '';

        if (!empty($company_info)) {
            $company_info = $company_info[0];

            $address = $company_info['Location_Address'];
            $city = $company_info['Location_City'];
            $state = $company_info['state'];
            $zipcode = $company_info['Location_ZipCode'];
            $country = $company_info['Location_Country'] == 227 ? 'United States' : 'Canada';

            $full_address = '';
            $full_address .= !empty($address) ? $address : '';
            $full_address .= !empty($city) ? ', ' . $city : '';
            $full_address .= !empty($state) ? ', ' . $state : '';
            $full_address .= !empty($zipcode) ? ', ' . $zipcode : '';
            $full_address .= !empty($country) ? ', ' . $country : '';

            $domain = $company_info['sub_domain'];
            $domain_type = $company_info['domain_type'];

            $career_site_url = '';
            if ($domain_type == 'addondomain') {
                $career_site_url = $domain;
            } else {
                $career_site_url = STORE_PROTOCOL . $domain;
            }
        } else {
            $company_info = array();
        }

        $user_info = array();
        //Get User Info
        if ($user_type == 'applicant' && $user_sid != null) {

            $CI->db->select('portal_job_applications.first_name');
            $CI->db->select('portal_job_applications.last_name');
            $CI->db->select('portal_job_applications.email');
            $CI->db->select('portal_job_applications.flat_rate_technician');
            $CI->db->select('portal_job_applications.hourly_technician');
            $CI->db->select('portal_job_applications.semi_monthly_salary');
            $CI->db->select('portal_job_applications.semi_monthly_draw');
            $CI->db->select('portal_job_applications.hourly_rate');

            $CI->db->select('portal_applicant_jobs_list.job_sid');
            $CI->db->select('portal_applicant_jobs_list.date_applied as application_date');
            $CI->db->select('portal_applicant_jobs_list.desired_job_title');
            $CI->db->select('portal_job_listings.Title as job_title');

            $CI->db->where('portal_job_applications.sid', $user_sid);

            $CI->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
            $CI->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

            $record_obj = $CI->db->get('portal_applicant_jobs_list');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $user_info = $record_arr[0];
            } else {
                $user_info = array();
            }
        } else if ($user_type == 'employee' && $user_sid != null) {
            $CI->db->select('username');
            $CI->db->select('first_name');
            $CI->db->select('last_name');
            $CI->db->select('job_title');
            $CI->db->select('email');
            $CI->db->select('job_title');
            $CI->db->select('hourly_rate');
            $CI->db->select('flat_rate_technician');
            $CI->db->select('hourly_technician');
            $CI->db->select('semi_monthly_salary');
            $CI->db->select('semi_monthly_draw');
            $CI->db->select('registration_date');

            $CI->db->where('sid', $user_sid);

            $record_obj = $CI->db->get('users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $user_info = $record_arr[0];
            } else {
                $user_info = array();
            }
        } else {
            $user_info = array();
        }

        $replacement_fields = array(
            'first_name',
            'last_name',
            'firstname',
            'lastname',
            'email',
            'company_name',
            'company_address',
            'company_phone',
            'career_site_url',
            'job_title',
            'desired_job_title',
            'registration_date',
            'application_date',
            'about_company',
            'signature',
            'inital',
            'sign_date',
            'text',
            'checkbox',
            'supervisor',
            'department',
            'last_day_of_work',
        );

        $date = date('M d Y');
        $username = '';
        $password = '';

        if ($user_type == 'applicant') {
            $username = 'Please contact with your manager';
            $password = 'Please contact with your manager';
        } else if ($user_type == 'employee') {
            $username = isset($user_info['username']) ? $user_info['username'] : '[' . $user_type . ' UserName]';
            $password = 'Please contact with your manager';
        }



        $my_return = $document_body;

        $user_type = $user_type != null ? ucwords($user_type) : 'Target User';

        $value = $date;
        $my_return = str_replace('{{date}}', $value, $my_return);

        $value = $username;
        $my_return = str_replace('{{username}}', $value, $my_return);

        $value = $password;
        $my_return = str_replace('{{password}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{first_name}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{last_name}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{first-name}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{last-name}}', $value, $my_return);

        $value = isset($user_info['first_name']) ? $user_info['first_name'] : '[' . $user_type . ' First Name]';
        $my_return = str_replace('{{firstname}}', $value, $my_return);

        $value = isset($user_info['last_name']) ? $user_info['last_name'] : '[' . $user_type . ' Last Name]';
        $my_return = str_replace('{{lastname}}', $value, $my_return);

        $value = isset($user_info['email']) ? $user_info['email'] : '[' . $user_type . ' Email]';
        $my_return = str_replace('{{email}}', $value, $my_return);

        $value = isset($user_info['job_title']) && !empty($user_info['job_title']) ? $user_info['job_title'] : 'No Job Title Found';
        $my_return = str_replace('{{job_title}}', $value, $my_return);

        $value = isset($user_info['desired_job_title']) ? $user_info['desired_job_title'] : '[' . $user_type . ' Desired Job]';
        $my_return = str_replace('{{desired_job_title}}', $value, $my_return);

        $value = isset($user_info['registration_date']) ? $user_info['registration_date'] : '[' . $user_type . ' Registration Date]';
        $my_return = str_replace('{{registration_date}}', $value, $my_return);

        $value = isset($user_info['application_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $user_info['application_date'])->format('m-d-Y h:i A') : '[' . $user_type . ' Application Date]';
        $my_return = str_replace('{{application_date}}', $value, $my_return);


        $value = isset($company_info['CompanyName']) ? $company_info['CompanyName'] : '[Company Name]';
        $my_return = str_replace('{{company_name}}', $value, $my_return);

        $value = isset($company_info['CompanyName']) ? $company_info['CompanyName'] : '[Company Name]';
        $my_return = str_replace('{{company-name}}', $value, $my_return);

        $value = !empty($full_address) ? $full_address : '[Company Address]';
        $my_return = str_replace('{{company_address}}', $value, $my_return);

        $value = !empty($full_address) ? $full_address : '[Company Address]';
        $my_return = str_replace('{{company-address}}', $value, $my_return);

        $value = isset($company_info['PhoneNumber']) ? $company_info['PhoneNumber'] : '[Company Phone]';
        $my_return = str_replace('{{company_phone}}', $value, $my_return);

        $value = isset($company_info['PhoneNumber']) ? $company_info['PhoneNumber'] : '[Company Phone]';
        $my_return = str_replace('{{company-phone}}', $value, $my_return);

        $value = !empty($career_site_url) ? $career_site_url : '[Career Site Url]';
        $my_return = str_replace('{{career_site_url}}', $value, $my_return);

        $value = !empty($career_site_url) ? $career_site_url : '[Career Site Url]';
        $my_return = str_replace('{{career-site-url}}', $value, $my_return);

        $value = isset($company_info['CompanyDescription']) ? $company_info['CompanyDescription'] : '[About Company]';
        $my_return = str_replace('{{about_company}}', $value, $my_return);

        $value = isset($user_info['hourly_rate']) && $user_info['hourly_rate'] != 0 ? '<b>Hourly Rate : ' . $user_info['hourly_rate'] . ' $</b>' : '<b>Hourly Rate : N/A</b>';
        $my_return = str_replace('{{hourly_rate}}', $value, $my_return);

        $value = isset($user_info['flat_rate_technician']) && $user_info['flat_rate_technician'] != 0 ? '<b>Flat Rate Technician : ' . $user_info['flat_rate_technician'] . ' $</b>' : '<b>Flat Rate Technician : N/A</b>';
        $my_return = str_replace('{{flat_rate_technician}}', $value, $my_return);

        $value = isset($user_info['hourly_technician']) && $user_info['hourly_technician'] != 0 ? '<b>Hourly Technician : ' . $user_info['hourly_technician'] . ' $</b>' : '<b>Hourly Technician : N/A</b>';
        $my_return = str_replace('{{hourly_technician}}', $value, $my_return);

        $value = isset($user_info['semi_monthly_salary']) && $user_info['semi_monthly_salary'] != 0 ? '<b>Semi Monthly Salary : ' . $user_info['semi_monthly_salary'] . ' $</b>' : '<b>Semi Monthly Salary : N/A</b>';
        $my_return = str_replace('{{semi_monthly_salary}}', $value, $my_return);

        $value = isset($user_info['semi_monthly_draw']) && $user_info['semi_monthly_draw'] != 0 ? '<b>Semi Monthly Draw : ' . $user_info['semi_monthly_draw'] . ' $</b>' : '<b>Semi Monthly Draw : N/A</b>';
        $my_return = str_replace('{{semi_monthly_draw}}', $value, $my_return);

        $short_textboxes = substr_count($my_return, '{{short_text}}');
        $long_textboxes = substr_count($my_return, '{{text}}');
        $checkboxes = substr_count($my_return, '{{checkbox}}');
        $textareas = substr_count($my_return, '{{text_area}}');

        // _e($my_return, true, true);
        // _e(substr_count($my_return, '<input type="checkbox" />'), true, true);

        $CI->db->select('form_input_data');
        $CI->db->where('sid', $document_sid);

        $record_obj = $CI->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $form_input_data = '';

        if (!empty($record_arr)) {
            $form_input_data = unserialize($record_arr[0]['form_input_data']);
            $form_input_data = (array) json_decode($form_input_data);
        }

        for ($stb = 0; $stb < $short_textboxes; $stb++) {
            $short_textbox_name = 'short_textbox_' . $stb;
            $short_textbox_value = !empty($form_input_data[$short_textbox_name]) && $autofill == 1 ? $form_input_data[$short_textbox_name] : '';
            // echo $short_textbox_value.'<br>';
            $short_textbox_id = 'short_textbox_' . $stb . '_id';
            $short_textbox = '<input type="text" data-type="text" maxlength="40" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee; padding: 0 5px;" class="short_textbox" name="' . $short_textbox_name . '" id="' . $short_textbox_id . '" value="' . $short_textbox_value . '" />';
            $my_return = preg_replace('/{{short_text}}/', $short_textbox, $my_return, 1);
        }

        for ($ltb = 0; $ltb < $long_textboxes; $ltb++) {
            $long_textbox_name = 'long_textbox_' . $ltb;
            $long_textbox_value = !empty($form_input_data[$long_textbox_name]) && $autofill == 1 ? $form_input_data[$long_textbox_name] : '';
            $long_textbox_id = 'long_textbox_' . $ltb . '_id';
            $long_textbox = '<input type="text" data-type="text" class="form-control input-grey long_textbox" name="' . $long_textbox_name . '" id="' . $long_textbox_id . '" value="' . $long_textbox_value . '"/>';
            $my_return = preg_replace('/{{text}}/', $long_textbox, $my_return, 1);
        }

        for ($cb = 0; $cb < $checkboxes; $cb++) {
            $checkbox_name = 'checkbox_' . $cb;
            $checkbox_value = !empty($form_input_data[$checkbox_name]) && $form_input_data[$checkbox_name] == 'yes' && $autofill == 1 ? 'checked="checked"' : '';
            $checkbox_id = 'checkbox_' . $cb . '_id';
            $checkbox = '<br><input type="checkbox" data-type="checkbox" class="user_checkbox input-grey" name="' . $checkbox_name . '" id="' . $checkbox_id . '" ' . $checkbox_value . '/>';
            $my_return = preg_replace('/{{checkbox}}/', $checkbox, $my_return, 1);
        }

        for ($ta = 0; $ta < $textareas; $ta++) {
            $textarea_name = 'textarea_' . $ta;
            $textarea_value = !empty($form_input_data[$textarea_name]) && $autofill == 1 ? $form_input_data[$textarea_name] : '';
            $textarea_id = 'textarea_' . $ta . '_id';
            $div_id = 'textarea_' . $ta . '_id_sec';
            $textarea = '<textarea data-type="textarea" style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="text_area" name="' . $textarea_name . '" id="' . $textarea_id . '">' . $textarea_value . '</textarea><div style="border: 1px dotted #777; padding:5px; display: none; background-color:#eee;" class="div-editable fillable_input_field" id="' . $div_id . '"  contenteditable="false"></div>';
            $my_return = preg_replace('/{{text_area}}/', $textarea, $my_return, 1);
        }

        // $value = '<br><input type="checkbox" class="user_checkbox input-grey" name="get_checkbox_condition"/>';
        // $my_return = str_replace('{{checkbox}}', $value, $my_return);

        // $value = '<input type="text" maxlength="40" value="" style="width: 300px; height: 34px; border: 1px solid #777; border-radius: 4px; background-color:#eee;" name="get_textbox_val">';
        // $my_return = str_replace('{{short_text}}', $value, $my_return);

        // $value = '<input type="text" class="form-control input-grey" value="" name="get_textbox_val">';
        // $my_return = str_replace('{{text}}', $value, $my_return);

        // $value = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px; background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text"  contenteditable="true" data-placeholder="Type Here"></div>';
        // $value = '<textarea style="border: 1px dotted #777; padding:5px; min-height: 145px; width:100%; background-color:#eee; resize: none;" class="" name="get_textarea_val"></textarea>';
        // $my_return = str_replace('{{text_area}}', $value, $my_return);

        //E_signature process
        $signature_data =  get_e_signature($company_sid, $user_sid, $user_type);
        // $signature_person_name = !empty($form_input_data['signature_person_name']) && $autofill == 1  ? $form_input_data['signature_person_name'] : '';
        $signature_person_name = $signature_data['first_name'] . " " . $signature_data['last_name'];

        $value = '<input type="text" id="signature_person_name" class="form-control input-grey js_signature_person_name" style="margin-top:16px; width: 50%;" name="signature_person_name" readonly value="' . $signature_person_name . '">';
        $my_return = str_replace('{{signature_print_name}}', $value, $my_return);

        if ($forDownload) {
            $signature_bas64_image = '_______________________';
        } else {
            if (!$signature_base64)
                $signature_bas64_image = '<a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
            else
                $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . ($signature_base64) . '"  id="draw_upload_img" />';
        }

        if ($authorized_signature == 1) {
            $authorized_signature = '<a class="btn btn-sm blue-button show_authorized_signature_popup" data-auth-signature="" href="javascript:;">Create Authorized E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="show_authorized_signature" />';
            $authorized_signature_date = '<a class="btn btn-sm blue-button get_authorized_sign_date" href="javascript:;">Authorized Sign Date</a><p id="target_authorized_signature_date"></p>';
        } else {
            $authorized_signature = '<p>Authorized Signature (<b>Not Signed</b>)</p>';
            $authorized_signature_date = '<p>Authorized Signature Date (<b>Not Entered</b>)</p>';;
        }


        // $authorized_base64 = get_authorized_base64_signature($company_sid, $document_sid);
        // if (!empty($authorized_base64)) {
        //     $authorized_signature = '<img style="max-height: '.SIGNATURE_MAX_HEIGHT.';" src="'.$authorized_base64.'">';
        // } else {
        //     $authorized_signature = '';
        // }



        $authorized_signature_name = '<input type="text" class="form-control" readonly style="background: #fff; margin-top:16px; width: 50%;">';
        $init_signature_bas64_image = '<a class="btn btn-sm blue-button get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
        $signature_timestamp = '<a class="btn btn-sm blue-button get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

        $my_return = str_replace('{{signature}}', $signature_bas64_image, $my_return);
        $my_return = str_replace('{{inital}}', $init_signature_bas64_image, $my_return);
        $my_return = str_replace('{{sign_date}}', $signature_timestamp, $my_return);
        $my_return = str_replace('{{authorized_signature}}', $authorized_signature, $my_return);
        $my_return = str_replace('{{authorized_signature_print_name}}', $authorized_signature_name, $my_return);
        $my_return = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $my_return);
        // Fillable documents
        $supervisor = "";
        $department = "";
        if (strtolower($user_type) == 'employee' && $user_sid != null) {
            // load the model
            $CI->load->model(
                "Hr_documents_management_model",
                "hr_documents_management_model"
            );
            // get the supervisor with department
            $response = $CI
                ->hr_documents_management_model
                ->getEmployeeSupervisorAndDepartment($user_sid);
            if ($response) {
                if ($response["supervisor"]) {
                    $supervisor = explode(",", $response["supervisor"]);
                    $supervisor = $supervisor[0];
                    $supervisor = getUserNameBySID($supervisor, false);
                    $supervisor = $supervisor[0]["first_name"].' '.$supervisor[0]["last_name"];
                }

                if ($response["name"]) {
                    $department = $response["name"];
                }
            }
        }
        // notice of separation
        $my_return = str_replace('{{employee_name}}', '<input type="text" class="form-control input-grey gray-background js_employee_name" name="employee_name" value="' . ($user_info["first_name"].' '.$user_info["last_name"]) . '" />', $my_return);

        $my_return = str_replace('{{employee_job_title}}', '<input type="text" class="form-control input-grey gray-background js_employee_job_title" name="employee_job_title" value="' . ($user_info["job_title"]) . '" />', $my_return);
        
        $my_return = str_replace('{{supervisor}}', '<input type="text" class="form-control input-grey gray-background js_supervisor" name="supervisor" value="' . ($supervisor) . '" />', $my_return);

        $my_return = str_replace('{{department}}', '<input type="text" class="form-control input-grey gray-background js_department" name="department" value="' . ($department) . '" />', $my_return);

        $my_return = str_replace('{{last_day_of_work}}', '<input type="text" class="jsDatePicker form-control input-grey gray-background js_last_work_date" name="last_work_date" readonly />', $my_return);

        $my_return = str_replace('{{reason_to_leave_company}}', '<textarea rows="5" class="form-control input-grey gray-background js_reason_to_leave_company" name="reason_to_leave_company"></textarea>', $my_return);

        $my_return = str_replace('{{forwarding_information}}', '<textarea rows="5" class="form-control input-grey gray-background js_forwarding_information" name="forwarding_information"></textarea>', $my_return);
        
        // notice of termination
        $my_return = str_replace('{{is_termination_voluntary}}', '<br /><input type="radio" name="is_termination_voluntary" class="js_is_termination_voluntary" value="yes"/> Yes<br /><input type="radio" name="is_termination_voluntary" class="js_is_termination_voluntary" value="no"/> No', $my_return);
        $my_return = str_replace('{{property_returned}}', '<br /><input type="radio" name="property_returned" class="js_property_returned" value="yes"/> Yes<br /><input type="radio" name="property_returned" class="js_property_returned" value="no"/> No', $my_return);
        $my_return = str_replace('{{reemploying}}', '<br /><input type="radio" name="reemploying" class="js_reemploying" value="yes"/> Yes<br /><input type="radio" name="reemploying" class="js_reemploying" value="no"/> No', $my_return);
        // oral employee counselling report form
        $my_return = str_replace('{{date_of_occurrence}}', '<input type="text" class="jsDatePicker form-control input-grey gray-background js_date_of_occurrence" name="date_of_occurrence" readonly />', $my_return);
        $my_return = str_replace('{{summary_of_violation}}', '<textarea rows="5" class="form-control input-grey gray-background js_summary_of_violation" name="summary_of_violation"></textarea>', $my_return);
        $my_return = str_replace('{{summary_of_corrective_plan}}', '<textarea rows="5" class="form-control input-grey gray-background js_summary_of_corrective_plan" name="summary_of_corrective_plan"></textarea>', $my_return);
        $my_return = str_replace('{{follow_up_dates}}', '<textarea rows="5" class="form-control input-grey gray-background js_follow_up_dates" name="follow_up_dates"></textarea>', $my_return);
        $my_return = str_replace('{{counselling_form_fields}}', $CI->load->view("v1/documents/fillable/partials/oral_employee_counselling_report_form_fields", [], true), $my_return);

        //
        $my_return = str_replace('{{employee_number}}', '<input type="text" class=" form-control input-grey gray-background js_employee_number" name="employee_number" />', $my_return);

        $my_return = str_replace('{{q1}}', '<textarea rows="5" class="form-control input-grey gray-background js_q1" name="q1"></textarea>', $my_return);
        

        $my_return = str_replace('{{q2}}', '<textarea rows="5" class="form-control input-grey gray-background js_q2" name="q2"></textarea>', $my_return);

        $my_return = str_replace('{{q3}}', '<textarea rows="5" class="form-control input-grey gray-background js_q3" name="q3"></textarea>', $my_return);

        $my_return = str_replace('{{q4}}', '<textarea rows="5" class="form-control input-grey gray-background js_q4" name="q4"></textarea>', $my_return);
        
        $my_return = str_replace('{{q5}}', '<textarea rows="5" class="form-control input-grey gray-background js_q5" name="q5"></textarea>', $my_return);


        return $my_return;
    }
}

if (!function_exists('log_and_send_templated_portal_email')) {

    function log_and_send_templated_portal_email($template_code, $company_sid, $to, $replacement_array = array(), $message_hf = array())
    {
        if (empty($to) || $to == NULL) return 0;
        $emailTemplateData = get_portal_email_template($company_sid, $template_code);

        if (!empty($emailTemplateData)) {
            $emailTemplateData = $emailTemplateData[0];

            $emailTemplateBody = $emailTemplateData['message_body'];
            $emailTemplateSubject = $emailTemplateData['subject'];
            $emailTemplateFromName = $emailTemplateData['from_name'];

            if (!empty($replacement_array)) {
                foreach ($replacement_array as $key => $value) {
                    $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
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

            if (!empty($message_hf)) {
                $body = $message_hf['header']
                    . $emailTemplateBody
                    . $message_hf['footer'];
            } else {
                $body = EMAIL_HEADER
                    . $emailTemplateBody
                    . EMAIL_FOOTER;
            }


            log_and_sendEmail($from, $to, $subject, $body, $from_name);
        }
    }
}

if (!function_exists('count_assigned_documents')) {
    function count_assigned_documents($users_type, $users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('user_type', $users_type);
        $CI->db->where('user_sid', $users_sid);
        $CI->db->from('documents_assignment');
        return $CI->db->count_all_results();
    }
}


if (!function_exists('count_onboarding_panel_records')) {
    function count_onboarding_panel_records($users_type, $users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('user_type', $users_type);
        $CI->db->where('user_sid', $users_sid);
        $CI->db->from('onboarding_applicants_configuration');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('count_offer_letter')) {
    function count_offer_letter($users_type, $users_sid)
    {
        $CI = &get_instance();
        $CI->db->select('user_consent');
        $CI->db->where('user_type', $users_type);
        $CI->db->where('user_sid', $users_sid);
        $CI->db->where('document_type', 'offer_letter');
        $CI->db->where('status', 1);
        $CI->db->from('documents_assigned');
        $record_obj = $CI->db->get();
        $record_arr = $record_obj->result_array();

        if (!empty($record_arr)) {
            $user_consent = $record_arr[0]['user_consent'];
            if ($user_consent == 0) {
                return 'sent';
            } else if ($user_consent == 1) {
                return 'sign';
            }
        } else {
            return 'not_sent';
        }
    }
}

if (!function_exists('my_print_r')) {
    function my_print_r($obj, $ip_address)
    {
        $ip = getUserIP();

        if ($ip == $ip_address) {
            echo '<pre>';
            print_r($obj);
            echo '</pre>';
        }
    }
}

if (!function_exists('my_echo')) {
    function my_echo($str, $ip_address)
    {
        $ip = getUserIP();
        if ($ip == $ip_address) {
            echo '<pre>';
            echo $str;
            echo '</pre>';
        }
    }
}

if (!function_exists('db_get_employee_profile')) {
    function db_get_employee_profile($emp_id)
    {
        $CI = &get_instance();
        $CI->db->select('first_name,last_name,email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag');
        $CI->db->where('sid', $emp_id);
        return $CI->db->get('users')->result_array();
    }
}

if (!function_exists('count_pending_job_feed_requests')) {
    function count_pending_job_feed_requests()
    {
        $CI = &get_instance();

        /*
        $CI->db->where('jobs_to_feed.activation_date', null);
        $CI->db->where('jobs_to_feed.expiry_date', null);
        $CI->db->where('jobs_to_feed.activation_status', 0);
        $CI->db->where('jobs_to_feed.refund_status', 0);
        */

        $CI->db->where('jobs_to_feed.read_status', 0);
        $CI->db->where('purchased_date >', '2018-06-01 00:00:00'); //Read Status Applied on this date

        $CI->db->from('jobs_to_feed');
        $count = $CI->db->count_all_results();

        return $count;
    }
}

if (!function_exists('count_eeoc_forms')) {
    function count_eeoc_forms($users_type, $users_sid)
    {
        $CI = &get_instance();

        $CI->db->select('sid');
        $CI->db->where('users_type', $users_type);
        $CI->db->where('application_sid', $users_sid);
        $CI->db->from('portal_eeo_form');

        return $CI->db->count_all_results();
    }
}

if (!function_exists('check_resource_permission')) {
    function check_resource_permission($company_sid)
    {
        $CI = &get_instance();

        $CI->db->select('enable_resource_center');
        $CI->db->where('sid', $company_sid);
        $CI->db->from('users');

        return $CI->db->get()->result_array()[0]['enable_resource_center'];
    }
}

if (!function_exists('get_timezones_array')) {
    function get_timezones_array()
    {
        $zones = array();

        $zone = array();
        $zone['name'] = 'HST – Hawaii Standard Time';
        $zone['zone'] = 'hst';
        $zone['utc_offset_h'] = -10;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'HDT – Hawaii-Aleutian Daylight Time';
        $zone['zone'] = 'hdt';
        $zone['utc_offset_h'] = -9;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'AKST – Alaska Standard Time';
        $zone['zone'] = 'akst';
        $zone['utc_offset_h'] = -9;
        $zone['utc_offset_n'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'AKDT – Alaska Daylight Time';
        $zone['zone'] = 'akdt';
        $zone['utc_offset_h'] = -8;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'PST – Pacific Standard Time / Pacific Time';
        $zone['zone'] = 'pst';
        $zone['utc_offset_h'] = -8;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'PDT – Pacific Daylight Time / Pacific Daylight Saving Time';
        $zone['zone'] = 'pdt';
        $zone['utc_offset_h'] = -7;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'MST – Mountain Standard Time / Mountain Time';
        $zone['zone'] = 'mst';
        $zone['utc_offset_h'] = -7;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'MDT – Mountain Daylight Time';
        $zone['zone'] = 'mst';
        $zone['utc_offset_h'] = -6;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'CST – Central Standard Time / Central Time';
        $zone['zone'] = 'cst';
        $zone['utc_offset_h'] = -6;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'CDT – Central Daylight Time / Central Daylight Saving Time';
        $zone['zone'] = 'cdt';
        $zone['utc_offset_h'] = -5;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'EST – Eastern Standard Time / Eastern Time';
        $zone['zone'] = 'est';
        $zone['utc_offset_h'] = -5;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'EDT – Eastern Daylight Time / Eastern Daylight Savings Time';
        $zone['zone'] = 'edt';
        $zone['utc_offset_h'] = -4;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'AST – Atlantic Standard Time / Atlantic Time';
        $zone['zone'] = 'ast';
        $zone['utc_offset_h'] = -4;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'ADT – Atlantic Daylight Time / Atlantic Daylight Saving Time';
        $zone['zone'] = 'adt';
        $zone['utc_offset_h'] = -3;
        $zone['utc_offset_m'] = 0;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'NST – Newfoundland Standard Time';
        $zone['zone'] = 'nst';
        $zone['utc_offset_h'] = -3;
        $zone['utc_offset_m'] = -30;
        $zones[] = $zone;

        $zone = array();
        $zone['name'] = 'NDT – Newfoundland Daylight Time';
        $zone['zone'] = 'ndt';
        $zone['utc_offset_h'] = -2;
        $zone['utc_offset_m'] = -30;
        $zones[] = $zone;

        return $zones;
    }
}


if (!function_exists('check_for_blue_panel_status')) {
    function check_blue_panel_status($check_access_level = false, $user_type = 'self')
    {
        $CI = &get_instance();

        if ($CI->session->userdata('logged_in')) {
            if (!isset($session)) {
                $session = $CI->session->userdata('logged_in');
            }
            $session = $CI->session->userdata('logged_in');
            $access_level = $session['employer_detail']['access_level'];
            $company_sid = $session['company_detail']['ems_status'];
            //            $company_sid = $session['employer_detail']['parent_sid'];

            $module_name = strtolower($CI->uri->segment(1));
            if ($user_type == 'self') {
                if ($check_access_level == true) {
                    return strtolower($access_level) == 'employee' && $company_sid;
                } else {
                    return (in_array($module_name, explode(',', BLUE_PANEL_MODULES)) &&
                        !in_array($module_name, explode(',', PUBLIC_MODULES))) &&
                        (ENABLE_BLUE_PANEL_FOR_ALL || $company_sid/*in_array($company_sid, explode(',', BLUE_PANEL_COMPANIES))*/);
                }
            } else if ($user_type == 'employee') {
                return false;
            } else if ($user_type == 'applicant') {
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('check_blue_panel_status_for_view')) {
    function check_blue_panel_status_for_view($check_access_level = false)
    {
        $CI = &get_instance();

        if ($CI->session->userdata('logged_in')) {
            if (!isset($session)) {
                $session = $CI->session->userdata('logged_in');
            }
            $session = $CI->session->userdata('logged_in');
            $access_level = $session['employer_detail']['access_level'];
            //$company_sid = $session['employer_detail']['parent_sid'];
            $company_sid = $session['company_detail']['ems_status'];

            $module_name = strtolower($CI->uri->segment(1));

            if ($check_access_level == true) {
                return strtolower($access_level) == 'employee' && $company_sid;
            } else {
                return (in_array($module_name, explode(',', BLUE_PANEL_MODULES)) &&
                    !in_array($module_name, explode(',', PUBLIC_MODULES))) &&
                    (ENABLE_BLUE_PANEL_FOR_ALL || $company_sid/*in_array($company_sid, explode(',', BLUE_PANEL_COMPANIES))*/);
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('check_affiliations')) {
    function check_affiliations()
    {
        $CI = &get_instance();
        $CI->db->select('is_reffered');
        $CI->db->where('status', 0);
        $CI->db->where('request_date >', date('Y-m-d 00:00:00'));
        $CI->db->where('request_date <', date('Y-m-d 23:59:59'));
        //        $CI->db->where('is_read', 0);
        $CI->db->from('affiliations');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('set_e_signature')) {

    function set_e_signature($form_post)
    { //print_r($form_post); die();
        $company_sid = $form_post['company_sid'];
        $user_type = $form_post['user_type'];
        $user_sid = $form_post['user_sid'];
        $ip_address = $form_post['ip_address'];
        $user_agent = $form_post['user_agent'];
        $first_name = $form_post['first_name'];
        $last_name = $form_post['last_name'];
        $email_address = $form_post['email_address'];
        $signature_timestamp = date('Y-m-d H:i:s');
        $signature = $form_post['signature'];
        $init_signature = $form_post['init_signature'];
        $user_consent = $form_post['user_consent'];
        $signature_hash = md5($signature);
        $active_signature = $form_post['active_signature'];
        $drawn_signature = $form_post['drawn_signature'];
        $drawn_init_signature = $form_post['drawn_init_signature'];

        $CI = &get_instance();
        $data_to_save = array();
        $data_to_save['user_type'] = $user_type;
        $data_to_save['user_sid'] = $user_sid;
        $data_to_save['company_sid'] = $company_sid;
        $data_to_save['ip_address'] = $ip_address;
        $data_to_save['user_agent'] = $user_agent;
        $data_to_save['first_name'] = $first_name;
        $data_to_save['last_name'] = $last_name;
        $data_to_save['email_address'] = $email_address;
        $data_to_save['signature_timestamp'] = $signature_timestamp;
        $data_to_save['signature'] = $signature;
        $data_to_save['init_signature'] = $init_signature;
        $data_to_save['signature_hash'] = $signature_hash;
        $data_to_save['user_consent'] = $user_consent == 1 ? 1 : 0;
        $data_to_save['is_active'] = 1;
        $data_to_save['signature_bas64_image'] = $drawn_signature;
        $data_to_save['init_signature_bas64_image'] = $drawn_init_signature;
        $data_to_save['active_signature'] = $active_signature;

        $CI->db->insert('e_signatures_data', $data_to_save);

        return $CI->db->insert_id();
    }
}

if (!function_exists('set_prepare_e_signature')) {

    function set_prepare_e_signature($form_post)
    { //print_r($form_post); die();
        $company_sid = $form_post['company_sid'];
        $user_type = $form_post['user_type'];
        $user_sid = $form_post['user_sid'];
        $form_i9_sid = $form_post['form_i9_sid'];
        $ip_address = $form_post['ip_address'];
        $user_agent = $form_post['user_agent'];
        $drawn_signature = $form_post['drawn_signature'];
        $drawn_init_signature = $form_post['drawn_init_signature'];

        $CI = &get_instance();
        $data_to_save = array();
        $data_to_save['section1_preparer_signature'] = $drawn_signature;
        $data_to_save['section1_preparer_signature_init'] = $drawn_init_signature;
        $data_to_save['section1_preparer_signature_ip_address'] = $ip_address;
        $data_to_save['section1_preparer_signature_user_agent'] = $user_agent;

        $CI->db->where('sid', $form_i9_sid);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('company_sid', $company_sid);
        $CI->db->update('applicant_i9form', $data_to_save);
    }
}

if (!function_exists('set_agent_e_signature')) {

    function set_agent_e_signature($form_post)
    {
        $sid = $form_post['affiliate_and_license_record_sid'];
        $marketing_sid = $form_post['marketing_agency_sid'];
        $ip_address = $form_post['marketing_agency_ip_address'];
        $user_agent = $form_post['marketing_agency_user_agent'];
        $email_address = $form_post['marketing_agency_email'];
        $signature_timestamp = date('Y-m-d H:i:s');
        $signature = $form_post['drawn_signature'];
        $initial_signature = $form_post['drawn_init_signature'];
        $destination = $form_post['destination'];

        $CI = &get_instance();
        $data_to_update = array();
        $data_to_update['signature_timestamp'] = $signature_timestamp;
        $data_to_update['signature_email_address'] = $email_address;
        $data_to_update['signature_bas64_image'] = $signature;
        $data_to_update['init_signature_bas64_image'] = $initial_signature;
        $data_to_update['signature_ip_address'] = $ip_address;
        $data_to_update['signature_user_agent'] = $user_agent;

        if ($destination == 'end_user') {
            $CI->db->where('sid', $sid);
            $CI->db->where('company_sid', $marketing_sid);
            $CI->db->update('form_document_eula', $data_to_update);
        } elseif ($destination == 'affiliate_end_user') {
            $CI->db->where('sid', $sid);
            $CI->db->where('marketing_agency_sid', $marketing_sid);
            $CI->db->update('form_affiliate_end_user_license_agreement', $data_to_update);
        } elseif ($destination == 'application_employment_form') {
            $CI->db->where('sid', $sid);
            $CI->db->where('employer_sid', $marketing_sid);
            $CI->db->update('portal_job_applications', $data_to_update);
        }
    }
}

if (!function_exists('get_e_signature')) {
    function get_e_signature($company_sid, $user_sid, $user_type)
    {
        $CI = &get_instance();

        $CI->db->select('*');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('is_active', 1);
        $CI->db->limit(1);
        $CI->db->from('e_signatures_data');

        $records_obj = $CI->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('get_preparer_e_signature')) {
    function get_preparer_e_signature($document_sid, $company_sid, $user_sid, $user_type)
    {
        $CI = &get_instance();

        $CI->db->select('section1_preparer_signature');
        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('sid', $document_sid);
        $CI->db->limit(1);
        $CI->db->from('applicant_i9form');

        $records_obj = $CI->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
}

if (!function_exists('unpaid_commissions')) {
    function unpaid_commissions()
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('commission_invoices.payment_status', 'unpaid');
        $CI->db->where('is_read', 0);
        $CI->db->where('invoice_status !=', 'deleted');
        $CI->db->where('invoice_status !=', 'archived');
        $CI->db->where('invoice_status !=', 'cancelled');
        $CI->db->from('commission_invoices');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('end_user_license_signed')) {
    function end_user_license_signed()
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('status', 'signed');
        $CI->db->where('client_signature_timestamp >', date('Y-m-d 00:00:00'));
        $CI->db->where('client_signature_timestamp <', date('Y-m-d 23:59:59'));
        $CI->db->from('form_document_eula');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('form_document_credit_card_authorization')) {
    function form_document_credit_card_authorization()
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('status', 'signed');
        $CI->db->where('client_signature_timestamp >', date('Y-m-d 00:00:00'));
        $CI->db->where('client_signature_timestamp <', date('Y-m-d 23:59:59'));
        $CI->db->from('form_document_credit_card_authorization');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('form_affiliate_end_user_license_agreement')) {
    function form_affiliate_end_user_license_agreement()
    {
        $CI = &get_instance();
        $CI->db->select('marketing_agency_sid');
        $CI->db->where('status', 'signed');
        $CI->db->where('client_signature_timestamp >', date('Y-m-d 00:00:00'));
        $CI->db->where('client_signature_timestamp <', date('Y-m-d 23:59:59'));
        $CI->db->from('form_affiliate_end_user_license_agreement');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('fetch_private_message_notification')) {
    function fetch_private_message_notification()
    {
        $CI = &get_instance();
        $CI->db->where('to_id', 1);
        $CI->db->where('to_type', 'admin');
        $CI->db->where('outbox', 0);
        $CI->db->where('status', 0);
        $CI->db->where('date >', date('Y-m-d 00:00:00'));
        $CI->db->where('date <', date('Y-m-d 23:59:59'));
        $CI->db->from('private_message');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('get_all_pending_incidents')) {
    function get_all_pending_incidents()
    {
        $CI = &get_instance();
        $CI->db->where('status', 'Pending');
        $CI->db->from('incident_reporting');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('client_refer_by_affiliate')) {
    function client_refer_by_affiliate()
    {
        $CI = &get_instance();
        $CI->db->where('date_requested >', date('Y-m-d 00:00:00'));
        $CI->db->where('date_requested <', date('Y-m-d 23:59:59'));
        $CI->db->from('client_refer_by_affiliate');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('regenerate_e_signature')) {
    function regenerate_e_signature($company_sid, $user_sid, $user_type, $data_to_update)
    {
        $CI = &get_instance();

        $CI->db->where('company_sid', $company_sid);
        $CI->db->where('user_sid', $user_sid);
        $CI->db->where('user_type', $user_type);
        $CI->db->where('is_active', 1);
        $CI->db->update('e_signatures_data', $data_to_update);
    }
}

if (!function_exists('common_get_job_applicants_count')) {
    function common_get_job_applicants_count($sid, $arch_status = null, $desiredJob = false, $companySid = false)
    {

        $CI = &get_instance();
        if ($desiredJob) {
            $CI->db->where('desired_job_title', $sid);
            $CI->db->where('company_sid', $companySid);
        } else $CI->db->where('job_sid', $sid);
        if ($arch_status !== null) {
            $CI->db->where('archived', $arch_status);
        }
        $CI->db->from('portal_applicant_jobs_list');
        return $CI->db->count_all_results();
    }
}

if (!function_exists('get_agent_e_signature')) {
    function get_agent_e_signature($marketing_agency_sid, $recode_sid, $user_type, $url)
    {
        $CI = &get_instance();

        if ($url == 'form_end_user_license_agreement') {
            $CI->db->select('signature_bas64_image, signature_timestamp');
            $CI->db->where('sid', $recode_sid);
            $CI->db->where('company_sid', $marketing_agency_sid);
            $CI->db->from('form_document_eula');
        } elseif ($url == 'form_affiliate_end_user_license_agreement') {
            $CI->db->select('signature_bas64_image, signature_timestamp');
            $CI->db->where('sid', $recode_sid);
            $CI->db->where('marketing_agency_sid', $marketing_agency_sid);
            $CI->db->from('form_affiliate_end_user_license_agreement');
        } elseif ($url == 'form_full_employment_application') {
            $CI->db->select('signature_bas64_image, signature_timestamp');
            $CI->db->where('sid', $recode_sid);
            $CI->db->where('employer_sid', $marketing_agency_sid);
            $CI->db->from('portal_job_applications');
        }

        $records_obj = $CI->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr[0]['signature_bas64_image'])) {
            return $records_arr[0];
        } else {
            $CI = &get_instance();

            $CI->db->select('*');
            $CI->db->where('company_sid', $marketing_agency_sid);
            $CI->db->where('user_sid', $recode_sid);
            $CI->db->where('user_type', $user_type);
            $CI->db->where('is_active', 1);
            $CI->db->limit(1);
            $CI->db->from('e_signatures_data');

            $records_obj = $CI->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                return $records_arr[0];
            } else {
                return array();
            }
        }
    }
}

if (!function_exists('get_interview_status')) {
    function get_interview_status($status_sid)
    {
        $CI = &get_instance();
        $CI->db->select('name, css_class, bar_bgcolor');
        $CI->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.status_sid = application_status.sid');
        $CI->db->where('portal_applicant_jobs_list.sid', $status_sid);
        $CI->db->from('application_status');
        $result = $CI->db->get()->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('get_interview_status_by_parent_id')) {
    function get_interview_status_by_parent_id($status_sid)
    {
        $CI = &get_instance();
        $CI->db->select('name, css_class, bar_bgcolor');
        $CI->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.status_sid = application_status.sid');
        $CI->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $status_sid);
        $CI->db->limit(1);
        $CI->db->from('application_status');
        $result = $CI->db->get()->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('get_default_interview_status')) {
    function get_default_interview_status($status_sid, $field_id)
    {
        $CI = &get_instance();
        $CI->db->select('status');
        $CI->db->where($field_id, $status_sid);
        $CI->db->from('portal_applicant_jobs_list');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = 'Not Contacted Yet';

        if (!empty($result)) {
            $return_data = $result[0]['status'];
        }

        return $return_data;
    }
}

if (!function_exists('get_company_logo_status')) {
    function get_company_logo_status($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('enable_company_logo');
        $CI->db->where('user_sid', $company_sid);
        $CI->db->from('portal_employer');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = '';

        if (!empty($result)) {
            $return_data = $result[0]['enable_company_logo'];
        }

        return $return_data;
    }
}

if (!function_exists('get_footer_copyright_data')) {
    function get_footer_copyright_data($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('copyright_company_status, copyright_company_name');
        $CI->db->where('user_sid', $company_sid);
        $CI->db->from('portal_employer');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = '';

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('get_footer_logo_data')) {
    function get_footer_logo_data($company_sid)
    {
        $CI = &get_instance();
        $CI->db->select('footer_powered_by_logo, footer_logo_type, footer_logo_text, footer_logo_image');
        $CI->db->where('user_sid', $company_sid);
        $CI->db->from('portal_employer');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = '';

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }
}

if (!function_exists('vimeo_video_data')) {
    function vimeo_video_data($id)
    {
        $ch = curl_init('http://vimeo.com/api/v2/video/' . $id . '.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $a = curl_exec($ch);
        $hash = unserialize($a);
        //to reutrn full data return hash[0] but at the moment we only require thumbnail image
        $data = $hash[0];
        $thumbnail_small = $data['thumbnail_small'];
        $thumbnail_medium = $data['thumbnail_medium'];
        $thumbnail_large = $data['thumbnail_large'];

        if ($thumbnail_medium != '') {
            $thumbnail_image = $thumbnail_medium;
        } else if ($thumbnail_small != '') {
            $thumbnail_image = $thumbnail_small;
        } else if ($thumbnail_large != '') {
            $thumbnail_image = $thumbnail_large;
        } else {
            $thumbnail_image = base_url('assets/images/video-play-icon.png');
        }

        return $thumbnail_image;
    }
}

if (!function_exists('getreferralusername')) {
    function getreferralusername($sid)
    {
        $CI = &get_instance();
        $CI->db->select('full_name');
        $CI->db->where('sid', $sid);
        $CI->db->from('marketing_agencies');
        $CI->db->limit(1);
        $result = $CI->db->get()->result_array();
        $return_data = '';

        if (!empty($result)) {
            $return_data = $result[0]['full_name'];
        }

        return $return_data;
    }
}

if (!function_exists('get_print_document_url')) {
    function get_print_document_url($request_type, $document_type, $document_sid)
    {
        $urls = [];
        if ($request_type == 'original') {
            if ($document_type == 'MS') {
                $CI = &get_instance();
                $CI->db->select('uploaded_document_s3_name, uploaded_document_original_name');
                $CI->db->where('sid', $document_sid);
                $CI->db->from('documents_management');
                $CI->db->limit(1);
                $result = $CI->db->get()->result_array();
                $upload_document = $result[0]['uploaded_document_s3_name'];
                $file_name = explode(".", $upload_document);
                $document_name = $file_name[0];
                $document_extension = $file_name[1];
                if ($document_extension == 'pdf') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                } else if ($document_extension == 'doc') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                } else if ($document_extension == 'docx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                } else if ($document_extension == 'ppt') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
                } else if ($document_extension == 'pptx') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
                } else if ($document_extension == 'xls') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
                } else if ($document_extension == 'xlsx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
                } else if ($document_extension == 'csv') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
                }

                $document_path = $result[0]['uploaded_document_s3_name'];
                $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);
            } else if ($document_type == 'DS') {
                $CI = &get_instance();
                $CI->db->select('document_s3_name, document_original_name');
                $CI->db->where('sid', $document_sid);
                $CI->db->from('documents_assigned');
                $CI->db->limit(1);
                $result = $CI->db->get()->result_array();
                $upload_document = $result[0]['document_s3_name'];
                $file_name = explode(".", $upload_document);
                $document_name = $file_name[0];
                $document_extension = $file_name[1];
                if ($document_extension == 'pdf') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                } else if ($document_extension == 'doc') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                } else if ($document_extension == 'docx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                } else if ($document_extension == 'xls') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
                } else if ($document_extension == 'xlsx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
                } else if ($document_extension == 'ppt') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
                } else if ($document_extension == 'pptx') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
                } else if ($document_extension == 'csv') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
                }
                $document_path = $result[0]['document_s3_name'];
                $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);
            } else if ($document_type == 'generated') {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid . '/download');
            } else if ($document_type == 'offer') {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/offer/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/offer/' . $document_sid . '/download');
            } else if ($document_type == 'hybrid_document') {
                $urls['print_url'] = base_url('hr_documents_management/print_download_hybird_document/original/print/both/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_download_hybird_document/original/print/both/' . $document_sid);
            }
            // End of Original Documents
        } else if ($request_type == 'assigned') {
            if ($document_type == 'MS') {
                $CI = &get_instance();
                $CI->db->select('document_s3_name, document_original_name');
                $CI->db->where('sid', $document_sid);
                $CI->db->from('documents_assigned');
                $CI->db->limit(1);
                $result = $CI->db->get()->result_array();
                $upload_document = $result[0]['document_s3_name'];
                $file_name = explode(".", $upload_document);
                $document_name = $file_name[0];
                $document_extension = $file_name[1];
                // $document_extension = $file_name[1];

                //
                $index = sizeof($file_name) - 1;
                $document_extension = $file_name[$index];
                unset($file_name[$index]);
                $document_name = implode('.', $file_name);
                //
                if ($document_extension == 'pdf') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                } else if ($document_extension == 'doc') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                } else if ($document_extension == 'docx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                } else if ($document_extension == 'xls') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
                } else if ($document_extension == 'xlsx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
                } else if ($document_extension == 'ppt') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
                } else if ($document_extension == 'pptx') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
                } else if ($document_extension == 'csv') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/assigned/generated/' . $document_sid);
                }

                $document_path = $result[0]['document_s3_name'];
                $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);
            } else if ($document_type == 'generated') {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/assigned/generated/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_generated_and_offer_later/assigned/generated/' . $document_sid . '/download');
            } else if ($document_type == 'offer') {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/assigned/offer/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_generated_and_offer_later/assigned/offer/' . $document_sid . '/download');
            } else if ($document_type == 'hybrid_document') {
                $urls['print_url'] = base_url('hr_documents_management/print_download_hybird_document/assigned/print/both/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_download_hybird_document/assigned/print/both/' . $document_sid);
            }
        } else if ($request_type == 'submitted') {
            if ($document_type == 'MS') {
                $CI = &get_instance();
                $CI->db->select('uploaded_file, document_original_name');
                $CI->db->where('sid', $document_sid);
                $CI->db->from('documents_assigned');
                $CI->db->limit(1);
                $result = $CI->db->get()->result_array();
                $upload_document = $result[0]['uploaded_file'];
                $file_name = explode(".", $upload_document);
                $document_name = $file_name[0];
                $document_extension = $file_name[1];
                if ($document_extension == 'pdf') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                } else if ($document_extension == 'doc') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                } else if ($document_extension == 'docx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                } else if ($document_extension == 'xls') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
                } else if ($document_extension == 'xlsx') {
                    $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
                } else if ($document_extension == 'ppt') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
                } else if ($document_extension == 'pptx') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $document_sid);
                } else if ($document_extension == 'csv') {
                    $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
                }

                $document_path = $result[0]['uploaded_file'];
                $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);
            } else if ($document_type == 'generated') {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $document_sid . '/download');
            } else if ($document_type == 'hybrid_document') {
                $urls['print_url'] = base_url('hr_documents_management/print_download_hybird_document/submitted/print/both/' . $document_sid);
                $urls['download_url'] = base_url('hr_documents_management/print_download_hybird_document/submitted/print/both/' . $document_sid);
            }
        } else if ($request_type == 'offer_letter') {
            $CI = &get_instance();
            $CI->db->select('uploaded_document_s3_name as uploaded_file, uploaded_document_original_name as document_original_name');
            $CI->db->where('sid', $document_sid);
            $CI->db->from('offer_letter');
            $CI->db->limit(1);
            $result = $CI->db->get()->result_array();
            $upload_document = $result[0]['uploaded_file'];
            $file_name = explode(".", $upload_document);
            $document_name = $file_name[0];
            $document_extension = $file_name[1];
            if ($document_extension == 'pdf') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
            } else if ($document_extension == 'doc') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
            } else if ($document_extension == 'docx') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
            } else if ($document_extension == 'xls') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
            } else if ($document_extension == 'xlsx') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
            } else if ($document_extension == 'ppt') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
            } else if ($document_extension == 'pptx') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
            } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $document_sid);
            } else if ($document_extension == 'csv') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
            }

            $document_path = $result[0]['uploaded_file'];
            $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);
        }

        return $urls;
    }
}

if (!function_exists('get_required_url')) {
    function get_required_url($document_s3_url)
    {
        $document_name        = pathinfo($document_s3_url)['filename'];
        $document_extension   = pathinfo($document_s3_url)['extension'];
        $print_url = '';
        $preview_url = '';
        $type = 'document';

        if (in_array($document_extension, ['pdf', 'csv', 'ppt', 'pptx'])) {
            $preview_url = "https://docs.google.com/gview?url=" . AWS_S3_BUCKET_URL . $document_s3_url . "&embedded=true";

            if ($document_extension == 'pdf') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
            } else if ($document_extension == 'csv') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
            } else if ($document_extension == 'ppt') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
            } else if ($document_extension == 'pptx') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
            }
        } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) {
            $preview_url = "https://view.officeapps.live.com/op/embed.aspx?src=" . AWS_S3_BUCKET_URL . $document_s3_url;

            if ($document_extension == 'doc') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
            } else if ($document_extension == 'docx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
            } else if ($document_extension == 'xls') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
            } else if ($document_extension == 'xlsx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
            }
        } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
            $type = 'image';
            $preview_url = AWS_S3_BUCKET_URL . $document_s3_url;
            $print_url = base_url('hr_documents_management/print_s3_image/' . $document_s3_url);
        }

        $download_url = base_url('hr_documents_management/download_upload_document/' . $document_s3_url);

        $data_to_return = array();
        $data_to_return['type'] = $type;
        $data_to_return['preview_url'] = $preview_url;
        $data_to_return['print_url'] = $print_url;
        $data_to_return['download_url'] = $download_url;
        return $data_to_return;
    }
}

if (!function_exists('get_employee_resume')) {
    function get_employee_resume($employee_sid)
    {
        $return_data = "not_found";
        if ($employee_sid > 0) {
            $CI = &get_instance();
            $CI->db->select('resume');
            $CI->db->where('sid', $employee_sid);
            $CI->db->limit(1);
            $CI->db->from('users');
            $result = $CI->db->get()->result_array();

            if (!empty($result[0]["resume"])) {
                $return_data = $result[0]["resume"];
            } else {
                $return_data = "not_found";
            }
        }

        return $return_data;
    }
}

if (!function_exists('get_authorized_base64_signature')) {
    function get_authorized_base64_signature($company_sid, $document_sid)
    {
        $return_data = array();
        if ($document_sid > 0) {
            $CI = &get_instance();
            $CI->db->select('signature_base64');
            $CI->db->where('company_sid', $company_sid);
            $CI->db->where('document_sid', $document_sid);
            $CI->db->where('status', 1);
            $CI->db->limit(1);
            $CI->db->from('documents_authorized_signature');
            $result = $CI->db->get()->result_array();

            if (!empty($result)) {
                $return_data = $result[0]["signature_base64"];
            }
        }

        return $return_data;
    }
}

function get_resume_lsq_date($company_sid, $user_type, $user_sid)
{

    $CI = &get_instance();
    $CI->db->select('requested_date');
    $CI->db->where('company_sid', $company_sid);
    $CI->db->where('user_type', $user_type);
    $CI->db->where('user_sid', $user_sid);
    $CI->db->where('request_status < ', 2);
    // $CI->db->where('is_respond', 1);
    $CI->db->order_by('sid', 'DESC');


    $record_obj = $CI->db->get('resume_request_logs');
    $record_arr = $record_obj->result_array();
    $record_obj->free_result();
    if (!empty($record_arr)) {
        return $record_arr[0]['requested_date'];
    } else {
        return null;
    }
}

if (!function_exists('get_witness_name_by_id')) {
    function get_witness_name_by_id($witness_sid)
    {
        $witness_name = '';

        $CI = &get_instance();
        $CI->db->select('witness_name');
        $CI->db->where('sid', $witness_sid);
        $CI->db->from('incident_related_witnesses');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            $witness_name = $result[0]['witness_name'];
        }

        return $witness_name;
    }
}

if (!function_exists('get_witness_email_by_id')) {
    function get_witness_email_by_id($witness_sid)
    {
        $witness_name = '';

        $CI = &get_instance();
        $CI->db->select('witness_email');
        $CI->db->where('sid', $witness_sid);
        $CI->db->from('incident_related_witnesses');
        $result = $CI->db->get()->result_array();

        if (!empty($result)) {
            $witness_name = $result[0]['witness_email'];
        }

        return $witness_name;
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS(
        $receiverPhoneNumber,
        $message,
        $userName,
        $userEmailAddress,
        $_this,
        $isSMSEnabled = null,
        $companySid = null
    ) {
        // Check if module is enabled and
        if ($isSMSEnabled === null && $companySid === null) {
            $ses = $_this->session->userdata('logged_in');
            $isSMSEnabled = $ses['company_detail']['sms_module_status'];
            //
            $companySid = $ses['company_detail']['sid'];
        }
        if ($isSMSEnabled == 0) return;
        // Check company phone sid
        $data = get_company_sms_phonenumber($companySid, $_this);
        //
        if ($data['phone_sid'] == '' || $data['phone_sid'] == null) return;
        //
        $senderPhoneNumber = $data['phone_number'];
        //
        $receiverPhoneNumber = preg_replace('/[^+0-9]/', '', trim($receiverPhoneNumber));
        if (strpos($receiverPhoneNumber, '+1') === false) {
            $receiverPhoneNumber = '+1' . $receiverPhoneNumber;
        }
        $isValidate = (int)phonenumber_validate($receiverPhoneNumber);
        //
        $insertArray = array();
        $insertArray['body'] = nl2br($message);
        $insertArray['user_name'] = $userName;
        $insertArray['user_email_address'] = $userEmailAddress;
        $insertArray['sender_phone_number'] = $senderPhoneNumber;
        $insertArray['receiver_phone_number'] = $receiverPhoneNumber != NULL ? $receiverPhoneNumber : 0;
        if ($isValidate === 0) $insertArray['note'] = "Receiver phone number is in-valid.";
        else {
            // Send SMS to reciever


            $_this
                ->twilioapp
                ->setReceiverPhone($receiverPhoneNumber);
            if (SMS_MODE === 'production') {
                $_this
                    ->twilioapp
                    ->setMessageServiceSID($data['message_service_sid'])
                    ->setSenderPhone($data['phone_number'], 'number');
            }
            $resp = $_this
                ->twilioapp
                ->setMode(SMS_MODE)
                ->setMessage($message)
                ->sendMessage();
            // Check & Handling Errors
            if (!is_array($resp)) $insertArray['note'] = 'System failed to send sms.';
            else if (isset($resp['Error'])) $insertArray['note'] = $resp['Error'];
            else {
                $insertArray['note'] = 'SMS sent.';
                $insertArray['is_sent'] = 1;
            }
        }
        //
        $_this->db->insert(
            'portal_sms_log',
            $insertArray
        );
    }
}

if (!function_exists('get_company_sms_status')) {
    function get_company_sms_status($_this, $company_sid)
    {
        if (in_array($company_sid, explode(',', TEST_COMPANIES))) {
            $_this->db->select('sms_module_status');
            $_this->db->where('sid', $company_sid);
            $sms_module = $_this->db->get('users')->result_array();
            return $sms_module[0]['sms_module_status'];
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_employee_sms_status')) {
    function get_employee_sms_status($_this, $employee_sid)
    {
        $_this->db->select('notified_by, PhoneNumber');
        $_this->db->where('sid', $employee_sid);
        $sms_module = $_this->db->get('users')->result_array();
        return $sms_module[0];
    }
}

if (!function_exists('get_company_sms_template')) {
    function get_company_sms_template($_this, $company_sid, $code)
    {
        $_this->db->select('sms_body');
        $_this->db->where('company_sid', $company_sid);
        $_this->db->where('template_code', $code);
        $sms_template = $_this->db->get('portal_sms_templates')->result_array();
        return $sms_template[0];
    }
}

if (!function_exists('replace_sms_body')) {
    function replace_sms_body($smsTemplateBody, $replacement_array)
    {
        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $smsTemplateBody = str_replace('{{' . $key . '}}', ucwords($value), $smsTemplateBody);
            }
        }
        return $smsTemplateBody;
    }
}

if (!function_exists('get_email_attachment')) {
    function get_email_attachment($incident_sid, $email_sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid, attachment_type, item_title, item_type, item_path');
        $CI->db->where('incident_sid', $incident_sid);
        $CI->db->where('email_sid', $email_sid);
        $CI->db->from('incident_email_attachments');
        $attachments = $CI->db->get()->result_array();
        $return_data = array();

        if (!empty($attachments)) {
            $return_data = $attachments;
        }

        return $return_data;
    }
}

if (!function_exists('is_manager_have_new_email')) {
    function is_manager_have_new_email($manager_sid, $incident_sid)
    {
        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('incident_reporting_id', $incident_sid);
        $CI->db->where('receiver_sid', $manager_sid);
        $CI->db->where('is_read', 0);
        $CI->db->from('incident_reporting_emails');
        $result = $CI->db->get()->result_array();
        $return_data = 0;

        if (!empty($result)) {
            $return_data = count($result);
        }

        return $return_data;
    }
}

if (!function_exists('is_user_have_unread_message')) {
    function is_user_have_unread_message($manager_sid, $user_sid, $incident_sid)
    {

        $CI = &get_instance();
        $CI->db->select('sid');
        $CI->db->where('incident_reporting_id', $incident_sid);
        $CI->db->where('receiver_sid', $manager_sid);
        if (filter_var($user_sid, FILTER_VALIDATE_EMAIL)) {
            $CI->db->where('manual_email', $user_sid);
        } else {
            $CI->db->where('sender_sid', $user_sid);
        }
        $CI->db->where('is_read', 0);
        $CI->db->from('incident_reporting_emails');
        $result = $CI->db->get()->result_array();
        $return_data = 0;

        if (!empty($result)) {
            $return_data = count($result);
        }

        return $return_data;
    }
}

if (!function_exists('domainParser')) {
    function domainParser($source, $referrer, $return = FALSE)
    {
        // Convert to lower case
        $source = trim(strtolower(urldecode($source)));
        $referrer = trim(strtolower(urldecode($referrer)));
        if ($source == '' && $referrer == '') return 'N/A';
        // Set return array
        $r = array(
            'Original' => array(
                'Source' => $source,
                'Referrer' => $referrer,
            ),
            'Source' => $source,
            'Referrer' => $referrer,
            'URL' => '',
            'ReferrerSource' => '',
            'Text' => ''
        );
        // Check if referrer is empty
        if ($referrer != '' && $referrer != null && $referrer != "null") {
            // Reset referrer URL
            // $referrer = preg_replace('/(http|https):\/\/(www.)?/', '', $referrer);
            // Check if AutomotoHR in the URL
            if (preg_match('/automotohr/', strtolower($referrer))) {
                $r['Source'] = 'https://www.automotohr.com';
                $r['ReferrerSource'] = 'AutomotoHR';
                return ($referrer);
            } else if (preg_match('/automotosocial/', strtolower($referrer))) {
                $r['Source'] = 'https://www.automotosocial.com';
                $r['ReferrerSource'] = 'AutomotoSocial';
            } else {
                if (strpos($referrer, '.') === FALSE) {
                    $r['ReferrerSource'] = ucfirst($referrer);
                } else {
                    $m = array();
                    $referrer = parse_url($referrer)['host'];
                    preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $referrer, $m);
                    $r['ReferrerSource'] = ucfirst(isset($m['domain']) ? explode('.', $m['domain'])[0] : preg_replace('#^(?:.+?\\.)+(.+?\\.(?:co\\.uk|com|net))#', '$1', $referrer));
                }
            }
            // Check for UTM
            if (strpos($referrer, "?utm_source") !== false) {
                $r['URL'] = ucfirst(explode('?', $referrer)[0]);
                $r['Referrer'] = ucfirst(explode('&', explode('?utm_source=', $referrer)[1])[0]);
                $r['ReferrerSource'] = explode('.', preg_replace('#^(?:.+?\\.)+(.+?\\.(?:co\\.uk|com|net))#', '$1', $r['Referrer']))[0];
            }
        } else {
            if (preg_match('/automotohr/', strtolower($source))) {
                $r['Source'] = 'https://www.automotohr.com';
                $r['ReferrerSource'] = 'AutomotoHR';
            } else if (preg_match('/automotosocial/', strtolower($source))) {
                $r['Source'] = 'https://www.automotosocial.com';
                $r['ReferrerSource'] = 'AutomotoSocial';
            } else {
                if (strpos($source, '.') === FALSE) {
                    $r['ReferrerSource'] = ucfirst($source);
                } else {
                    $source = isset(parse_url($source)['host']) ? parse_url($source)['host'] : $source;
                    preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $source, $m);
                    $r['ReferrerSource'] = ucfirst(isset($m['domain']) ? explode('.', $m['domain'])[0] : preg_replace('#^(?:.+?\\.)+(.+?\\.(?:co\\.uk|com|net))#', '$1', $source));
                }
            }
            // Check for UTM
            if (strpos($source, "utm_source") !== false) {
                $r['URL'] = ucfirst(explode('?', $source)[0]);
                $r['Referrer'] = ucfirst(explode('&', explode('?utm_source=', $source)[1])[0]);
                $r['ReferrerSource'] = explode('.', preg_replace('#^(?:.+?\\.)+(.+?\\.(?:co\\.uk|com|net))#', '$1', $r['Referrer']))[0];
            }
        }
        //
        $r['Text'] = '<b>Source:</b> ' . $r['Source'] . ($r['ReferrerSource'] == '' ? '' : '<br /><b>Origin Source: (' . ($r['ReferrerSource']) . ')<b>');
        // if($r['URL'] != ''){
        //     $r['Text'] = '<b>Site:</b> '.$r['Source'].($r['URL'] == '' ? '' : '<br />URL: '.( $r['URL'] ).'').($r['Referrer'] == '' ? '' : '<br /><b>Referrer: ('.( $r['Referrer'] ).')<b>');
        // } else{
        //     $r['Text'] = '<b>Site:</b> '.$r['Source'].($r['Referrer'] == '' ? '' : '<br /><b>Referrer: ('.( $r['Referrer'] ).')<b>');
        // }
        return $return ? $r : $r['Text'];
    }
}

function fetchEmployees($company_sid, $_this)
{
    $_this->db->select('sid, first_name, last_name, email');
    $_this->db->where('parent_sid', $company_sid);
    $_this->db->where('active', 1);
    $_this->db->order_by('first_name', 'ASC');
    $result = $_this->db->get('users')->result_array();
    return $result;
}

if (!function_exists('get_policy_item_info')) {
    function get_policy_item_info($info_slug = false)
    {
        $CI = &get_instance();

        $CI->db->select('info_content, slug');
        if ($info_slug) $CI->db->where('slug', $info_slug);
        $CI->db->from('timeoff_policy_icons_info');

        $records_obj = $CI->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            if ($info_slug) return $records_arr[0]['info_content'];
            //
            $r = [];
            foreach ($records_arr as $k => $v) $r[$v['slug']] = $v['info_content'];
            return $r;
        } else {
            return array();
        }
    }
}

if (!function_exists('get_pto_user_access')) {
    function get_pto_user_access($company_sid, $user_sid)
    {
        $user_status = get_this_user_status($user_sid);
        $is_access_level_plus = $user_status['access_level_plus'];
        $is_pay_roll = $user_status['pay_plan_flag'];
        $is_approver = is_approver($company_sid, $user_sid);

        $return_access_array = array();

        if ($is_access_level_plus == 1 || $is_pay_roll == 1) {

            $return_access_array['url'] = base_url('timeoff/requests');
            $return_access_array['dashboard'] = 1;
            $return_access_array['quick_link'] = 1;
            $return_access_array['create_time_off'] = 1;
            $return_access_array['time_off_request'] = 1;
            $return_access_array['time_off_balance'] = 1;
            $return_access_array['time_off_report'] = 1;
            $return_access_array['import_time_off'] = 1;
            $return_access_array['import_historical'] = 1;
            $return_access_array['export_time_off'] = 1;
            $return_access_array['time_off_setting'] = 1;
            $return_access_array['time_off_approver'] = 1;
            $return_access_array['company_holiday'] = 1;
            $return_access_array['time_off_type'] = 1;
            $return_access_array['time_off_policies'] = 1;
            $return_access_array['time_off_policy_overwrite'] = 1;
            $return_access_array['report'] = 1;
            $return_access_array['employee_profile'] = 1;
        } else if ($is_approver) {
            //
            $return_access_array['url'] = base_url('timeoff/requests');
            $return_access_array['dashboard'] = 1;
            $return_access_array['quick_link'] = 1;
            $return_access_array['create_time_off'] = 1;
            $return_access_array['time_off_request'] = 1;
            $return_access_array['time_off_balance'] = 1;
            $return_access_array['time_off_report'] = 1;
            $return_access_array['import_time_off'] = 0;
            $return_access_array['import_historical'] = 0;
            $return_access_array['export_time_off'] = 0;
            $return_access_array['time_off_setting'] = 0;
            $return_access_array['time_off_approver'] = 0;
            $return_access_array['company_holiday'] = 0;
            $return_access_array['time_off_type'] = 0;
            $return_access_array['time_off_policies'] = 0;
            $return_access_array['time_off_policy_overwrite'] = 0;
            $return_access_array['report'] = 0;
            $return_access_array['employee_profile'] = 0;
        } else {
            $return_access_array['url'] = base_url('dashboard');
            $return_access_array['dashboard'] = 0;
            $return_access_array['quick_link'] = 0;
            $return_access_array['create_time_off'] = 0;
            $return_access_array['time_off_request'] = 0;
            $return_access_array['time_off_balance'] = 0;
            $return_access_array['time_off_report'] = 0;
            $return_access_array['import_time_off'] = 0;
            $return_access_array['import_historical'] = 0;
            $return_access_array['export_time_off'] = 0;
            $return_access_array['time_off_setting'] = 0;
            $return_access_array['time_off_approver'] = 0;
            $return_access_array['company_holiday'] = 0;
            $return_access_array['time_off_type'] = 0;
            $return_access_array['time_off_policies'] = 0;
            $return_access_array['time_off_policy_overwrite'] = 0;
            $return_access_array['report'] = 0;
            $return_access_array['employee_profile'] = 0;
        }

        return $return_access_array;
    }
}

function get_this_user_status($user_sid)
{

    $CI = &get_instance();

    $CI->db->select('access_level_plus, pay_plan_flag');
    $CI->db->where('sid', $user_sid);
    $CI->db->from('users');

    $records_obj = $CI->db->get();
    $records_arr = $records_obj->result_array();
    $records_obj->free_result();

    if (!empty($records_arr)) {
        return $records_arr[0];
    } else {
        return array();
    }
}


function is_approver($companySid, $userSid)
{
    // Get approver all departments
    $CI = &get_instance();
    return
        $CI->db
        ->where('employee_sid', $userSid)
        ->where('is_archived', 0)
        ->count_all_results('timeoff_approvers');
}

function get_department_status($company_sid, $user_sid, $status_for)
{

    $CI = &get_instance();

    if ($status_for == 'supervisor') {
        $CI->db->select('sid');
        $CI->db->where('supervisor', $user_sid);
        $CI->db->where('is_deleted', 0);
        $CI->db->where('status', 1);
        $CI->db->where('company_sid', $company_sid);
        $CI->db->from('departments_management');
    } else {
        $CI->db->select('sid');
        $CI->db->where('team_lead', $user_sid);
        $CI->db->where('company_sid', $company_sid);
        $CI->db->from('departments_team_management');
        $CI->db->where('is_deleted', 0);
        $CI->db->where('status', 1);
    }

    $a = $CI->db->get();
    $b = $a->result_array();
    $a->free_result();

    //
    if (!$b || !count($b)) return 0;
    //
    $a = $CI->db
        ->select('
        users.sid,
        users.sid as user_id,
        users.first_name, 
        users.last_name, 
        users.access_level, 
        users.access_level_plus, 
        users.pay_plan_flag,
        users.job_title,
        users.employee_number,
        concat(users.first_name," ",users.last_name) as full_name,
        users.email,
        users.profile_picture as image,
        users.is_executive_admin
    ')
        ->where_in($status_for == 'supervisor' ? 'departments_employee_2_team.department_sid' : 'departments_employee_2_team.team_sid', array_column($b, 'sid'))
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid')
        ->order_by('concat(users.first_name, users.last_name)', 'ASC', false)
        ->get('departments_employee_2_team');
    //
    $b = $a->result_array();
    $a = $a->free_result();
    //
    if (!$b || !count($b)) return 0;
    //
    return $b;
}


//
if (!function_exists('broadcastAlert')) {
    function broadcastAlert(
        $templateCode,
        $alertSlug,
        $notificationSlug,
        $companySid,
        $companyName,
        $employeeFirstName,
        $employeeLastName,
        $employeeSid,
        $extra = [],
        $userType = 'employee'
    ) {
        // Get employers that need to be notified
        $employers = getNotificationContacts($companySid, $alertSlug);
        //
        if (!count($employers)) return;
        // Set the document type
        $dt = 'Document';
        $dti = 'a document';
        $action = '';
        $subject = '';
        $documentTitle = '';
        $employeeName = '';
        $link = 'hr_documents_management/documents_assignment/' . $userType . '/';
        //
        switch ($notificationSlug) {
            case 'driver_license':
                $dt = 'Driver\'s License';
                $subject = 'Driver\'s license details have changed';
                $link = 'drivers_license_info/' . $userType . '/';
                break;
            case 'occupational_license':
                $dt = 'Occupational License';
                $subject = 'Occupational license details have changed';
                $link = 'occupational_license_info/' . $userType . '/';
                break;
            case 'dependent_details':
                $dt = 'Dependent Details';
                $subject = 'Dependent details have changed';
                $link = 'dependants/' . $userType . '/';
                break;
            case 'emergency_contact':
                $dt = 'Emergency Contact';
                $subject = 'Emergency contact details have changed';
                $link = 'emergency_contacts/' . $userType . '/';
                break;
            case 'direct_deposit_information':
                $dt = 'Direct Deposit Information';
                $subject = 'Direct Deposit Information has changed';
                $link = 'direct_deposit/' . $userType . '/';
                break;
            case 'equipment_info':
                $dt = 'Equipment Information';
                $subject = 'Equipment Information has changed';
                $link = 'equipment_info/' . $userType . '/';
                break;
            case 'equipment_info_acknowledged':
                $dt = 'Equipment Information';
                $subject = 'Equipment Information Acknowledged';
                $action = 'Acknowledged';
                $link = 'equipment_info/' . $userType . '/';
                break;
            case 'i9_completed':
                $dt = 'I9 Form';
                $subject = 'I9 Form Completed';
                $action = 'Completed';
                break;
            case 'w9_completed':
                $dt = 'W9 Form';
                $subject = 'W9 Form Completed';
                $action = 'Completed';
                break;
            case 'w4_completed':
                $dt = 'W4 Form';
                $subject = 'W4 Form Completed';
                $action = 'Completed';
                break;
            case 'document_assigned':
                $dt = $extra['document_title'];
                $subject = 'Document assigned to ' . $extra['employee_name'] . '.';
                $action = 'Assigned';
                $employeeName = $extra['employee_name'];
                $documentTitle = $extra['document_title'];
                break;
            case 'document_completed':
                $dt = 'completed or changed the Document(s).';
                $subject = 'Document Completed.';
                $action = '';
                break;
        }
        //
        $list = '';
        //
        if (isset($extra['completedDocTitles'])) {
            //
            $list .= '<ul style="margin-left: 10px;">';
            //
            foreach ($extra['completedDocTitles'] as $t0) {
                $list .= '<li>' . ($t0) . '</li>';
            }
            //
            $list .= '</ul>';
        }
        //
        $hf = message_header_footer($companySid, $companyName);
        //
        if (!isset($_SESSION[$templateCode . '_SES'])) {
            // Get email template
            $_SESSION[$templateCode . '_SES'] = get_portal_email_template($companySid, $templateCode);
            //
            if (!count($_SESSION[$templateCode . '_SES'])) $_SESSION[$templateCode . '_SES'] = get_email_template($templateCode);
        }
        //
        $template = $_SESSION[$templateCode . '_SES'];
        //
        $fromName = str_replace('{{company_name}}', $companyName, $template['from_name']);
        $fromEmail = !empty($template['from_email']) ? $template['from_email'] : FROM_EMAIL_NOTIFICATIONS;
        $subject = str_replace('{{company_name}}', $companyName, ($subject == '' ? $template['subject'] : $subject));
        $content = $hf['header'] . $template['text'] . $hf['footer'];
        //
        $replaceArray = [
            '{{company_name}}' => $companyName,
            '{{first_name}}' => $employeeFirstName,
            '{{last_name}}' => $employeeLastName,
            '{{action}}' => $action,
            '{{list}}' => $list,
            '{{document}}' => $dt,
            '{{document_title}}' => $documentTitle,
            '{{employee_name}}' => $employeeName,
            '{{link}}' => '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url() . '/' . $link . $employeeSid) . '">Click Here</a>',
            $dti => $dt
        ];

        //
        foreach ($employers as $employer) {
            //
            $replaceArray['{{contact-name}}'] = $employer['contact_name'];
            //
            $body = str_replace(
                array_keys($replaceArray),
                $replaceArray,
                $content
            );
            //
            log_and_sendEmail(
                $fromEmail,
                $employer['email'],
                $subject,
                $body,
                $fromName
            );
        }
    }
}


/**
 * Get people for email notifications
 * 
 * @param number $companySid
 * @param number $slug
 * @param number $mainSlug
 * 
 * @return array
 */
if (!function_exists('getNotificationContacts')) {
    function getNotificationContacts(
        $companySid,
        $slug,
        $mainSlug = false
    ) {
        //
        $mainSlug = $mainSlug ? $mainSlug : $slug;
        //
        $CI = &get_instance();
        //
        if (!$CI->db
            ->where('company_sid', $companySid)
            ->where($mainSlug, 1)
            ->count_all_results('notifications_emails_configuration')) return [];
        //
        $a = $CI->db
            ->select('
            notifications_emails_management.contact_name, 
            notifications_emails_management.email, 
            notifications_emails_management.employer_sid,
            users.active as useractive,
            users.terminated_status
        ')
            ->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left')
            ->where('notifications_emails_management.company_sid', $companySid)
            ->where('notifications_emails_management.status', 'active')
            ->where('notifications_emails_management.notifications_type', $slug)
            ->group_by('notifications_emails_management.email')
            ->get('notifications_emails_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        // Remove the in-active / terminated employers
        if (count($b)) {
            //
            foreach ($b as $k => $v) {
                //
                if ($v['employer_sid'] != 0 && $v['employer_sid'] != null) {
                    if ($v['useractive'] == 0 || $v['terminated_status'] == 1) unset($b[$k]);
                }
            }
            //
            $b = array_values($b);
        }
        //
        return $b;
    }
}

//
if (!function_exists('checkAndUpdateDD')) {
    //
    function checkAndUpdateDD(
        $userSid,
        $userType,
        $companySid,
        $documentType
    ) {
        $CI = &get_instance();
        //
        $CI->db
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('company_sid', $companySid)
            ->where('document_type', $documentType)
            ->update('documents_assigned_general', [
                'is_completed' => 1
            ]);

        // Get documents_assigned_general_sid
        $a = $CI->db
            ->select('sid')
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('company_sid', $companySid)
            ->where('document_type', $documentType)
            ->get('documents_assigned_general');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) {
            $CI->db
                ->insert('documents_assigned_general', [
                    'company_sid' => $companySid,
                    'user_sid' => $userSid,
                    'user_type' => $userType,
                    'document_type' => $documentType,
                    'status' => 1,
                    'is_completed' => 1,
                    'note' => '',
                    'assigned_at' => date('Y-m-d H:i:s')
                ]);
            //
            $b['sid'] = $CI->db->insert_id();
        }
        //
        $actionTakerId = $userSid;
        // //
        if ($CI->session->userdata('logged_in')['employer_detail']['sid']) {
            $actionTakerId = $CI->session->userdata('logged_in')['employer_detail']['sid'];
            $userType = 'employee';
        }
        //
        $CI->db
            ->insert(
                'documents_assigned_general_assigners',
                [
                    'documents_assigned_general_sid' => $b['sid'],
                    'user_sid' => $actionTakerId,
                    'user_type' => $userType,
                    'action' => 'completed'
                ]
            );
    }
}

//
if (!function_exists('get_applicant_name')) {

    function get_applicant_name($sid)
    {
        $CI = &get_instance();
        $CI->db->select('portal_job_applications.first_name');
        $CI->db->select('portal_job_applications.last_name');
        $CI->db->where('portal_job_applications.sid', $sid);
        $result = $CI->db->get('portal_job_applications')->result_array();

        if (empty($result)) { // applicant does not exits
            return 'error';
        } else {
            return $result[0]['first_name'] . ' ' . $result[0]['last_name'];
        }
    }
}

/**
 * Save completed document information
 * to be sent as report from
 * cron
 * 
 * @param Array $ins [
 * Integer document_sid
 * Integer company_sid
 * Integer user_sid
 * String  document_type
 * String  user_type
 * ]
 * 
 * @return Integer
 */
if (!function_exists('checkAndInsertCompletedDocument')) {
    function checkAndInsertCompletedDocument(
        $ins
    ) {
        // Get CI instance
        $_this = &get_instance();
        // Check if document already exists
        if (
            !$_this->db
                ->where('document_sid', $ins['document_sid'])
                ->where('document_type', $ins['document_type'])
                ->where('company_sid', $ins['company_sid'])
                ->where('user_sid', $ins['user_sid'])
                ->where('user_type', $ins['user_type'])
                ->where('completion_date', date('Y-m-d', strtotime('now')))
                ->count_all_results('portal_completed_documents_list')
        ) {
            //
            $ins['completion_date'] = date('Y-m-d', strtotime('now'));
            //
            $_this->db->insert('portal_completed_documents_list', $ins);
            //
            return $_this->db->insert_id();
        }
        //
        return -1;
    }
}


/**
 * Save completed document information
 * to be sent as report from
 * cron
 * 
 * @param Array $ins [
 * Integer document_sid
 * Integer company_sid
 * Integer user_sid
 * String  document_type
 * String  user_type
 * ]
 * 
 * @return Integer
 */
if (!function_exists('checkAndInsertCompletedDocument')) {
    function checkAndInsertCompletedDocument(
        $ins
    ) {
        // Get CI instance
        $_this = &get_instance();
        // Check if document already exists
        if (
            !$_this->db
                ->where('document_sid', $ins['document_sid'])
                ->where('document_type', $ins['document_type'])
                ->where('company_sid', $ins['company_sid'])
                ->where('user_sid', $ins['user_sid'])
                ->where('user_type', $ins['user_type'])
                ->where('completion_date', date('Y-m-d', strtotime('now')))
                ->count_all_results('portal_completed_documents_list')
        ) {
            //
            $ins['completion_date'] = date('Y-m-d', strtotime('now'));
            //
            $_this->db->insert('portal_completed_documents_list', $ins);
            //
            return $_this->db->insert_id();
        }
        //
        return -1;
    }
}


/**
 * Check if a document is assigned to the 
 * current employee
 * 
 * @param Array $employerArray
 * 
 * @return Booleon
 */
if (!function_exists('hasDocumentsAssigned')) {
    function hasDocumentsAssigned($employerArray)
    {
        // Check if the user is access level plus or payrol plus
        if ($employerArray['access_level_plus'] == 1 || $employerArray['pay_plan_flag'] == 1)  return true;
        // Set role
        $role = strtolower(preg_replace('/[^a-zA-Z]/', '_', $employerArray['access_level']));
        //
        if ($role == 'admin') return true;
        // Get CI instance
        $_this = &get_instance();
        // Load HR model
        $_this->load->model('hr_documents_management_model', 'hrm');
        //
        $_SESSION['mydepts'] = $_this->hrm->getMyDepartTeams($employerArray['sid']);
        // Fetch the documents 
        return $_this->hrm->hasAssignedDocuments(
            $employerArray['parent_sid'],
            $role,
            $employerArray['sid'],
            $_SESSION['mydepts']
        );
    }
}

/**
 * Check if a document is assigned to the 
 * current employee
 * 
 * @param Array $employerArray
 * 
 * @return Booleon
 */
if (!function_exists('hasEMSPermission')) {
    function hasEMSPermission($employerArray)
    {
        //
        $role = strtolower(preg_replace('/[^a-zA-Z]/', '_', $employerArray['access_level']));
        // Check if the user is not access level plus and role is manager
        if ($employerArray['access_level_plus'] == 0 && $role == 'manager')
            return false;
        else
            return true;
    }
}

/**
 * Check user session and set data
 * 
 * @employee Mubashir Ahmed
 * @date     02/02/2021
 *
 * @param Reference $data
 * @param Bool      $return (Default is 'FALSE')
 * 
 * @return VOID
 */
if (!function_exists('CheckLogin')) {
    function CheckLogin(&$data, $return = FALSE)
    {
        //
        $_this = &get_instance();
        //
        if (!$_this->session->userdata('logged_in')) {
            if ($return) {
                return false;
            }
            redirect('login', 'refresh');
        }
        //
        $data['session'] = $_this->session->userdata('logged_in');
        //
        if ($return) {
            return true;
        } else {
            //
            $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid'], NULL, $data['session']);
        }
    }
}





if (!function_exists('getnotifications_emails_configuration')) {
    function getnotifications_emails_configuration($companySid, $slug)
    {
        //
        $CI = &get_instance();
        //
        return $CI->db
            ->where('company_sid', $companySid)
            ->where($slug, 1)
            ->count_all_results('notifications_emails_configuration');
    }
}


if (!function_exists('getComplyNetLink')) {
    /**
     * Get the employee hash
     * 
     * @param int $companyId
     * @param int $employeeId
     * @return string
     */
    function getComplyNetLink(
        int $companyId,
        int $employeeId
    ) {
        // Get CI instance
        $CI = &get_instance();
        // Check if company is onboard
        if (!$CI->db->where([
            'company_sid'
        ])->count_all_results('complynet_companies')) {
            return '';
        }
        // Get email
        $record =
            $CI->db->select('email, complynet_json')->where([
                'employee_sid' => $employeeId
            ])
            ->get('complynet_employees')
            ->row_array();
        //
        if (empty($record)) {
            return '';
        }
        //
        $jsonToArray = json_decode($record['complynet_json'], true);
        //
        $username = isset($jsonToArray[0]['UserName']) ? $jsonToArray[0]['UserName'] : $jsonToArray['UserName'];
        //
        if (strpos($username, '@') === false) {
            $record['email'] = $username;
        }
        // Load ComplyNet library
        $CI->load->library('Complynet/Complynet_lib', '', 'complynet_lib');
        // Get the hash
        $response = $CI->complynet_lib->getUserHash($record['email']);
        //
        if ($response == 'Array' || !$response) {
            // let's try one more time with current email
            $currentRecord = $CI->db
                ->select('email')
                ->where('sid', $employeeId)
                ->get('users')
                ->row_array();
            //
            if (!$currentRecord) {
                return '';
            }
            // Get the hash
            $response = $CI->complynet_lib->getUserHash($currentRecord['email']);
            //
            if ($response == 'Array' || !$response) {
                return '';
            }
        }
        return $response;
    }
}


if (!function_exists('convertDateTimeToTimeZone')) {
    /**
     * Convert the timezones
     *
     * Only converts timezone from server's timezone
     * to employee timezone
     *
     * @method reset_datetime
     *
     * @param string $dateTime String containg the date time "Y-m-d H:i:s"
     * @param string $fromFormat Optional String containing the provided datetime format
     * @param string $toFormat Optional String containing the output datetime format
     * @return string
     */
    function convertDateTimeToTimeZone(
        string $dateTime,
        string $fromFormat = DB_DATE_WITH_TIME,
        string $toFormat = DB_DATE_WITH_TIME
    ) {
        // get CI instance
        $CI = &get_instance();
        //
        $timeZone = null;
        // Check if the session is in place
        $timeZone = $CI->session->userdata('logged_in')['employer_detail']['timezone'] ?? $CI->session->userdata('logged_in')['company_detail']['timezone'];
        //
        if (!$timeZone) {
            $timeZone = STORE_DEFAULT_TIMEZONE_ABBR;
        }
        //
        return reset_datetime([
            'datetime' => $dateTime, // sets the datetime string
            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // sets the from timezone
            'new_zone' => $timeZone, // set the to timezone
            'from_format' => $fromFormat, // set the from datetime format
            'format' => $toFormat, // set the to datetime format
            '_this' => $CI // set the instance of CI
        ]);
    }
}


if (!function_exists('getComplyNetEmployeeCheck')) {
    /**
     * Check the ComplyNet status of employee
     *
     * @param array $employee
     * @param int $payPlanPlus
     * @param int $accessLevelPlus
     * @param bool $showButton Optional
     * @return string
     */
    function getComplyNetEmployeeCheck(
        array $employee,
        int $payPlanPlus,
        int $accessLevelPlus,
        bool $showButton = true
    ) {
        if (!isCompanyOnComplyNet($employee['parent_sid'])) {
            return '';
        }
        //
        $CI = &get_instance();
        //
        if ($CI->db->where('employee_sid', $employee['sid'])->count_all_results('complynet_employees')) {
            return '<button class="btn btn-xs csBG2" title="Employee is on ComplyNet"><i class="fa fa-shield _csM0"></i></button>';
        }
        //
        $row = '';
        //
        if (($payPlanPlus || $accessLevelPlus) && $showButton) {
            $row = '<button class="btn csBG2 jsAddEmployeeToComplyNet" title="Add Employee To ComplyNet" placement="top" data-cid="' . ($employee['parent_sid']) . '" data-id="' . ($employee['sid']) . '">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </button>';
        }
        //
        if (!$showButton) {
            $row = 'Not on ComplyNet';
        }
        return $row;
    }
}


if (!function_exists('checkEmployeeMissingData')) {
    /**
     * Check the employee for missing data
     *
     * @param array $employee
     * @return array
     */
    function checkEmployeeMissingData(
        array $employee
    ) {
        //
        $errors = [];
        //
        if (!$employee['first_name']) {
            $errors[] = 'First name is missing.';
        }
        if (!$employee['last_name']) {
            $errors[] = 'Last name is missing.';
        }
        // if (!$employee['username']) {
        //     $errors[] = 'Username is missing.';
        // }
        if (!$employee['email']) {
            $errors[] = 'Email is missing.';
        }
        if (!$employee['PhoneNumber']) {
            $errors[] = 'Phone number is missing.';
        }
        // if (!$employee['job_title']) {
        //     $errors[] = 'Job title is missing.';
        // }
        if (!$employee['complynet_job_title']) {
            $errors[] = 'ComplyNet Job title is missing.';
        }
        if (!$employee['department_sid']) {
            $errors[] = 'Department is missing.';
        }
        if (!$employee['team_sid']) {
            $errors[] = 'Team is missing.';
        }

        return $errors;
    }
}


if (!function_exists('isCompanyOnComplyNet')) {
    /**
     * Check the company onboard on ComplyNet
     *
     * @param int $companyId
     * @return int
     */
    function isCompanyOnComplyNet(
        int $companyId
    ) {
        //
        $CI = &get_instance();
        //
        if (!$CI->db->where(['sid' => $companyId, 'complynet_status' => 1])->count_all_results('users')) {
            return 0;
        }
        //
        return $CI->db
            ->where('company_sid', $companyId)
            ->count_all_results('complynet_companies');
    }
}


if (!function_exists('findTheRightEmployee')) {
    function findTheRightEmployee(
        array $records,
        string $companyId,
        string $locationId
    ) {
        //
        $found = [];
        //
        foreach ($records as $record) {
            //
            if ($record['CompanyId'] == $companyId && $record['LocationId'] == $locationId) {
                $found = $record;
                break;
            }
        }
        //
        return $found;
    }
}


if (!function_exists('getUserColumnById')) {
    /**
     * Get the column from user
     * The function will only return a single column and
     * it will return empty in case no data is found.
     * 
     * @param int $id
     * @param string $column Optional
     * @return string
     */
    function getUserColumnById(
        int $id,
        string $column = 'sid'
    ) {
        //
        $CI = &get_instance();
        //
        $record =
            $CI->db->select($column)
            ->where('sid', $id)
            ->get('users')
            ->row_array();
        //
        if (empty($record)) {
            return '';
        }
        return $record[$column];
    }
}


if (!function_exists('checkAndSetEEOCForUser')) {
    /**
     * Remove duplicate records at run time
     *
     * @param int $userId
     * @param string $userType
     * @return int
     */
    function checkAndSetEEOCForUser(int $userId, string $userType)
    {
        // get CI instance
        $CI = &get_instance();
        // get all records of user
        $records =
            $CI->db
            ->where([
                'application_sid' => $userId,
                'users_type' => $userType
            ])
            ->order_by('sid', 'desc')
            ->get('portal_eeo_form')
            ->result_array();
        //
        if (empty($records)) {
            return 0;
        }
        // save last record
        $lastRecord = $records[0];
        // update tracker
        $CI->db
            ->where([
                'document_type' => 'eeoc',
                'user_type' => $userType,
                'user_sid' => $userId
            ])
            ->update('verification_documents_track', [
                'document_sid' => $lastRecord['sid']
            ]);

        //
        foreach ($records as $key => $value) {
            //
            if ($key == 0) {
                continue;
            }

            //
            if (strlen(trim($lastRecord['us_citizen'])) === 0 && strlen(trim($value['us_citizen'])) !== 0) {
                $lastRecord['us_citizen'] = trim($value['us_citizen']);
            }

            //
            if (strlen(trim($lastRecord['visa_status'])) === 0 && strlen(trim($value['visa_status'])) !== 0) {
                $lastRecord['visa_status'] = trim($value['visa_status']);
            }
            //
            if (strlen(trim($lastRecord['group_status'])) === 0 && strlen(trim($value['group_status'])) !== 0) {
                $lastRecord['group_status'] = trim($value['group_status']);
            }
            //
            if (strlen(trim($lastRecord['veteran'])) === 0 && strlen(trim($value['veteran'])) !== 0) {
                $lastRecord['veteran'] = trim($value['veteran']);
            }
            //
            if (strlen(trim($lastRecord['disability'])) === 0 && strlen(trim($value['disability'])) !== 0) {
                $lastRecord['disability'] = trim($value['disability']);
            }
            //
            if (strlen(trim($lastRecord['gender'])) === 0 && strlen(trim($value['gender'])) !== 0) {
                $lastRecord['gender'] = trim($value['gender']);
            }
            //
            $insArray = $value;
            $insArray['eeo_form_sid'] = $value['sid'];
            unset($insArray['sid']);
            // add to history
            $CI->db->insert('portal_eeo_form_history', $insArray);
            // delete record
            $CI->db->where('sid', $value['sid'])->delete('portal_eeo_form');
        }
        //
        $CI->db->where('sid', $lastRecord['sid'])->update('portal_eeo_form', $lastRecord);
        //
        return $lastRecord['sid'];
    }
}


if (!function_exists('getGroupOtherDocuments')) {
    /**
     * check and get group verification
     * and general documents
     * 
     * @param array $group
     * @param bool  $doCount Optional
     * @return int|array
     */
    function getGroupOtherDocuments(array $group, bool $doCount = false)
    {
        $stateForm = json_decode($group['state_forms_json']);

        //
        $documentArray = [];
        // check for I9
        if ($group['i9'] == 1) {
            $documentArray[] = 'I9 Fillable';
        }
        // check for w4
        if ($group['w4'] == 1) {
            $documentArray[] = 'W4 Fillable';
        }
        // check for w9
        if ($group['w9'] == 1) {
            $documentArray[] = 'W9 Fillable';
        }
        // check for eeoc
        if ($group['eeoc'] == 1) {
            $documentArray[] = 'EEOC';
        }
        // check for dependents
        if ($group['dependents'] == 1) {
            $documentArray[] = 'Dependents';
        }
        // check for DDI
        if ($group['direct_deposit'] == 1) {
            $documentArray[] = 'Direct Deposit Information';
        }
        // check for driving license
        if ($group['drivers_license'] == 1) {
            $documentArray[] = 'Drivers License Information';
        }
        // check for emergency contacts
        if ($group['emergency_contacts'] == 1) {
            $documentArray[] = 'Emergency Contacts';
        }
        // check for occupational license
        if ($group['occupational_license'] == 1) {
            $documentArray[] = 'Occupational License Information';
        }

        // check for occupational license
        if (!empty($stateForm) && $stateForm[0] == 1) {
            $documentArray[] = 'State Forms';
        }
        //
        return $doCount ? count($documentArray) : $documentArray;
    }


    //
    if (!function_exists('get_print_document_url_secure')) {
        function get_print_document_url_secure($document_sid)
        {
            $urls = [];
            $CI = &get_instance();
            $CI->db->select('*');
            $CI->db->where('sid', $document_sid);
            $CI->db->from('company_secure_documents');
            $CI->db->limit(1);
            $result = $CI->db->get()->result_array();
            $upload_document = $result[0]['document_s3_name'];
            $file_name = explode(".", $upload_document);
            $document_name = $file_name[0];
            $document_extension = $file_name[1];
            if ($document_extension == 'pdf') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
            } else if ($document_extension == 'doc') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
            } else if ($document_extension == 'docx') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
            } else if ($document_extension == 'ppt') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.ppt';
            } else if ($document_extension == 'pptx') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pptx';
            } else if ($document_extension == 'xls') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
            } else if ($document_extension == 'xlsx') {
                $urls['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
            } else if ($document_extension == 'csv') {
                $urls['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
            } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                $urls['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later_secure/' . $document_sid);
            }

            $document_path = $result[0]['document_s3_name'];
            $urls['download_url'] = base_url('hr_documents_management/download_upload_document/' . $document_path);

            return $urls;
        }
    }
}

if (!function_exists("isCompanyVerifiedForPayroll")) {
    /**
     * check if the company is verified with Gusto
     *
     * @param int $companyId Optional
     * @return int
     */
    function isCompanyVerifiedForPayroll(int $companyId = 0): int
    {
        // get the CI instance
        $CI = get_instance();
        // check and set the company id
        $companyId = $companyId === 0 ? $CI->session->userdata("logged_in")["company_detail"]["sid"] : $companyId;
        // check the status
        return $CI->db
            ->where([
                "company_sid" => $companyId,
                "status" => "approved"
            ])
            ->count_all_results("gusto_companies");
    }
}


if (!function_exists("getCompanyExtraColumn")) {
    /**
     * get the company extra column
     *
     * @param int $companyId
     * @param string $column
     * @return bool
     */
    function getCompanyExtraColumn(int $companyId, string $column)
    {
        // get CI
        $CI = &get_instance();
        // get the company extra fields
        $result = $CI->db
            ->select("extra_info")
            ->where("sid", $companyId)
            ->get("users")
            ->row_array();
        //
        if (!$result || !$result['extra_info']) {
            return "";
        }
        //
        $data = unserialize($result["extra_info"]);
        //
        return $data[$column] ? $data[$column] : "";
    }
}
