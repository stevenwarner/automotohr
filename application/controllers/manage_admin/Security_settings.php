<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Security_settings extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/security_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'security_settings';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Security Settings [Employee Access Level]';
        //$security_types                                                       = $this->security_model->get_enum_values();
        $security_types = $this->security_model->get_all_security_levels();
        $this->data['security_types'] = $security_types;
        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/security/listing_view');
        } else {
            $this->render('manage_admin/security/listing_view');
        }
    }

    public function manage_permissions($sid = NULL)
    {
        if (empty($sid)) {
            $this->session->set_flashdata('message', 'Security access level not found!');
            redirect('manage_admin/security_settings', 'refresh');
        } else {
            $access_level_details = $this->security_model->security_levels_details($sid);
            $available_modules = $this->security_model->security_levels_available_modules()['available_modules'];
            $available_modules = unserialize($available_modules);
            //echo '<pre>'; print_r($available_modules); exit;
            if (empty($access_level_details)) {
                $this->session->set_flashdata('message', 'Security access level not found!');
                redirect('manage_admin/security_settings', 'refresh');
            } else {
                if (isset($_POST['action']) && $_POST['action'] == 'true') {
                    $formdata = $this->input->post('action');
                    $function_name = '';
                    $description = $_POST['description'];

                    if (isset($_POST['function_name'])) {
                        $function_name = $_POST['function_name'];
                    }

                    $update_permission = array();

                    if (!empty($function_name)) { // update permissions for the user
                        foreach ($function_name as $mykey => $fn_name) {
                            $key = array_search($fn_name, array_column($available_modules, 'function_name'));
                            $mykey++;
                            $update_permission[] = $available_modules[$key];
                        }
                    }

                    $update_permission = serialize($update_permission);
                    $this->security_model->update_permission($sid, $update_permission, $description);
                    $access_level_details = $this->security_model->security_levels_details($sid);
                    redirect('manage_admin/security_settings', 'refresh');
                }

                $access_level_name = $access_level_details['access_level'];
                $permissions = array();
                $access_level_permissions = $access_level_details['permissions'];
                if (!empty($access_level_permissions)) {
                    $permissions = unserialize($access_level_permissions);
                }
                $this->data['page_title'] = 'Security Settings For ' . $access_level_name;
                $this->data['permissions'] = $permissions;
                $this->data['modules'] = $available_modules;
                $this->data['description'] = $access_level_details['description'];
                $this->data['access_level'] = $access_level_details['access_level'];
                $this->render('manage_admin/security/manage_permissions');
            }
        }
    }

    public function manage_members($access_level_sid = null)
    {

        if ($access_level_sid != null  &&  $access_level_sid > 1) {
            // ** Check Security Permissions Checks - Start ** //
            $redirect_url = 'manage_admin';
            $function_name = 'security_settings';

            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
            // ** Check Security Permissions Checks - End ** //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');


            if ($this->form_validation->run() == false) {
                $access_level_details = $this->security_model->security_levels_details($access_level_sid);

                if (!empty($access_level_details)) {
                    $this->data['access_level_details'] = $access_level_details;

                    $access_level = $access_level_details['access_level'];

                    $members = $this->security_model->get_access_level_specific_users($access_level);
                    $this->data['members'] = $members;


                    $this->data['access_level'] = $access_level;

                    $access_levels = $this->security_model->get_all_security_levels();
                    $this->data['access_levels'] = $access_levels;

                    $this->data['page_title'] = 'Security Settings - Manage Members';
                    $this->render('manage_admin/security/manage_members');
                } else {
                }
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'update_access_levels':

                        $current_access_level = $this->input->post('current_access_level');

                        $form_posts = $this->input->post();

                        unset($form_posts['perform_action']);
                        unset($form_posts['current_access_level']);
                        unset($form_posts['current_access_level_sid']);

                        foreach ($form_posts as $key => $access_level) {
                            $key_parts = explode('_', $key);

                            $company_sid = $key_parts[2];
                            $user_sid = $key_parts[3];

                            $this->security_model->set_access_level($company_sid, $user_sid, $access_level);
                        }

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Updated!');
                        redirect('manage_admin/security_settings/manage_members/' . $current_access_level, 'refresh');

                        break;

                    case 'activate_security_access_level':

                        $access_level_sid = $this->input->post('access_level_sid');

                        $this->security_model->set_access_level_status($access_level_sid, 1);

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Status Updated!');

                        redirect('manage_admin/security_settings/manage_members/' . $access_level_sid, 'refresh');

                        break;

                    case 'deactivate_security_access_level':

                        $access_level_sid = $this->input->post('access_level_sid');

                        $this->security_model->set_access_level_status($access_level_sid, 0);

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Status Updated!');

                        redirect('manage_admin/security_settings/manage_members/' . $access_level_sid, 'refresh');

                        break;
                }
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> No Such Access Level Defined in system!');
            redirect('manage_admin/security_settings', 'refresh');
        }
    }


    //
    public function manage_modules($sid = NULL)
    {
        if (empty($sid)) {
            $this->session->set_flashdata('message', 'Security access level not found!');
            redirect('manage_admin/companies/manage_company/' . $sid, 'refresh');
        } else {
            $available_modules = $this->security_model->get_modules();

            //
            if (isset($_POST['action']) && $_POST['action'] == 'true') {
                $this->security_model->set_company_module_status($_POST['module_name'], $sid);
            }

            foreach ($available_modules as $key => $val) {

                $available_modules[$key]['module_heading'] = $val['module_name'];

                if ($val['module_slug'] == 'ems') {

                    $status = $this->security_model->get_company_module_status($sid, 'ems');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Employee Management System', 'slug' => 'ems', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'emsdocumentmanagement');
                    $available_modules[$key]['module_chiled'][1] = array('heading' => 'Document Management', 'slug' => 'emsdocumentmanagement', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'onboardingconfiguration');
                    $available_modules[$key]['module_chiled'][2] = array('heading' => 'Onboarding Configuration', 'slug' => 'onboardingconfiguration', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'employeeemsnotification');
                    $available_modules[$key]['module_chiled'][3] = array('heading' => 'Employee EMS Notification', 'slug' => 'employeeemsnotification', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'announcements');
                    $available_modules[$key]['module_chiled'][4] = array('heading' => 'Announcements', 'slug' => 'announcements', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'learningcenter');
                    $available_modules[$key]['module_chiled'][5] = array('heading' => 'Learning Center', 'slug' => 'learningcenter', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'safetysheets');
                    $available_modules[$key]['module_chiled'][6] = array('heading' => 'Safety Sheets', 'slug' => 'safetysheets', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'departmentmanagement');
                    $available_modules[$key]['module_chiled'][7] = array('heading' => 'Department Management', 'slug' => 'departmentmanagement', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'onboardinghelpbox');
                    $available_modules[$key]['module_chiled'][8] = array('heading' => 'Onboarding Help Box', 'slug' => 'onboardinghelpbox', 'status' => $status);

                    $status = $this->security_model->get_company_module_status($sid, 'companyhelpbox');
                    $available_modules[$key]['module_chiled'][9] = array('heading' => 'Company Help Box', 'slug' => 'companyhelpbox', 'status' => $status);
                } elseif ($val['module_slug'] == 'timeoff') {
                    $status = $this->security_model->get_company_module_status($sid, 'timeoff');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Timeoff', 'slug' => 'timeoff', 'status' => $status);
                } elseif ($val['module_slug'] == 'attendance') {
                    $status = $this->security_model->get_company_module_status($sid, 'attendance');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Attendance', 'slug' => 'attendance', 'status' => $status);
                } elseif ($val['module_slug'] == 'indeedsponsor') {
                    $status = $this->security_model->get_company_module_status($sid, 'indeedsponsor');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Indeed Job Sponsor', 'slug' => 'indeedsponsor', 'status' => $status);
                } elseif ($val['module_slug'] == 'Hybrid document') {
                    $status = $this->security_model->get_company_module_status($sid, 'hybriddocument');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Hybrid document', 'slug' => 'hybriddocument', 'status' => $status);
                } elseif ($val['module_slug'] == 'Assure Hire') {
                    $status = $this->security_model->get_company_module_status($sid, 'assurehire');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Assure Hire', 'slug' => 'assurehire', 'status' => $status);
                } elseif ($val['module_slug'] == 'performancemanagement') {
                    $status = $this->security_model->get_company_module_status($sid, 'performancemanagement');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Performance Management', 'slug' => 'performancemanagement', 'status' => $status);
                } elseif ($val['module_slug'] == 'payroll') {
                    $status = $this->security_model->get_company_module_status($sid, 'payroll');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Payroll', 'slug' => 'payroll', 'status' => $status);
                } elseif ($val['module_slug'] == 'employeesurvey') {
                    $status = $this->security_model->get_company_module_status($sid, 'employeesurvey');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Employee Survey', 'slug' => 'employeesurvey', 'status' => $status);
                } elseif ($val['module_slug'] == 'documentlibrary') {
                    $status = $this->security_model->get_company_module_status($sid, 'documentlibrary');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Document Library', 'slug' => 'documentlibrary', 'status' => $status);
                } elseif ($val['module_slug'] == 'schedule') {
                    $status = $this->security_model->get_company_module_status($sid, 'schedule');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Schedule', 'slug' => 'schedule', 'status' => $status);
                } elseif ($val['module_slug'] == 'incidents') {
                    $status = $this->security_model->get_company_module_status($sid, 'incidents');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'incidents', 'slug' => 'incidents', 'status' => $status);
                } elseif ($val['module_slug'] == 'applicanttrackingsystem') {
                    $status = $this->security_model->get_company_module_status($sid, 'applicanttrackingsystem');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Applicant Tracking System', 'slug' => 'applicanttrackingsystem', 'status' => $status);
                } elseif ($val['module_slug'] == 'privatemessage') {
                    $status = $this->security_model->get_company_module_status($sid, 'privatemessage');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Private Message', 'slug' => 'privatemessage', 'status' => $status);
                } elseif ($val['module_slug'] == 'hybriddocument') {
                    $status = $this->security_model->get_company_module_status($sid, 'hybriddocument');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Hybrid document', 'slug' => 'hybriddocument', 'status' => $status);
                } elseif ($val['module_slug'] == 'assurehire') {
                    $status = $this->security_model->get_company_module_status($sid, 'assurehire');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Assure Hire', 'slug' => 'assurehire', 'status' => $status);
                } elseif ($val['module_slug'] == 'createnewjob') {
                    $status = $this->security_model->get_company_module_status($sid, 'createnewjob');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Create New Job', 'slug' => 'createnewjob', 'status' => $status);
                } elseif ($val['module_slug'] == 'marketplace') {
                    $status = $this->security_model->get_company_module_status($sid, 'marketplace');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'My Marketplace', 'slug' => 'marketplace', 'status' => $status);
                } elseif ($val['module_slug'] == 'myjobs') {
                    $status = $this->security_model->get_company_module_status($sid, 'myjobs');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'My Jobs', 'slug' => 'myjobs', 'status' => $status);
                } elseif ($val['module_slug'] == 'calendarevents') {
                    $status = $this->security_model->get_company_module_status($sid, 'calendarevents');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Calendar / Events', 'slug' => 'calendarevents', 'status' => $status);
                } elseif ($val['module_slug'] == 'etm') {
                    $status = $this->security_model->get_company_module_status($sid, 'etm');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Employee / Team Members', 'slug' => 'etm', 'status' => $status);
                } elseif ($val['module_slug'] == 'candidatequestionnaires') {
                    $status = $this->security_model->get_company_module_status($sid, 'candidatequestionnaires');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Candidate Questionnaires', 'slug' => 'candidatequestionnaires', 'status' => $status);
                } elseif ($val['module_slug'] == 'interviewquestionnaires') {
                    $status = $this->security_model->get_company_module_status($sid, 'interviewquestionnaires');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Interview Questionnaires', 'slug' => 'interviewquestionnaires', 'status' => $status);
                } elseif ($val['module_slug'] == 'backgroundchecksreport') {
                    $status = $this->security_model->get_company_module_status($sid, 'backgroundchecksreport');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Background Checks Report', 'slug' => 'backgroundchecksreport', 'status' => $status);
                } elseif ($val['module_slug'] == 'supporttickets') {
                    $status = $this->security_model->get_company_module_status($sid, 'supporttickets');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Support Tickets', 'slug' => 'supporttickets', 'status' => $status);
                } elseif ($val['module_slug'] == 'approvaldocuments') {
                    $status = $this->security_model->get_company_module_status($sid, 'approvaldocuments');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Approval Documents', 'slug' => 'approvaldocuments', 'status' => $status);
                } elseif ($val['module_slug'] == 'pendingauthorizeddocuments') {
                    $status = $this->security_model->get_company_module_status($sid, 'pendingauthorizeddocuments');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Pending Authorized Documents', 'slug' => 'pendingauthorizeddocuments', 'status' => $status);
                } elseif ($val['module_slug'] == 'employeeinformationchange') {
                    $status = $this->security_model->get_company_module_status($sid, 'employeeinformationchange');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Employee Information Change', 'slug' => 'employeeinformationchange', 'status' => $status);
                }elseif ($val['module_slug'] == 'accountactivity') {
                    $status = $this->security_model->get_company_module_status($sid, 'accountactivity');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Account Activity', 'slug' => 'accountactivity', 'status' => $status);
                }elseif ($val['module_slug'] == 'settings') {
                    $status = $this->security_model->get_company_module_status($sid, 'settings');
                    $available_modules[$key]['module_chiled'][0] = array('heading' => 'Settings', 'slug' => 'settings', 'status' => $status);
                } else {
                    unset($available_modules[$key]);
                }
            }


            $this->data['page_title'] = 'Activate/Dectivate Settings For Modules ';
            $this->data['modules'] = $available_modules;
            $this->data['companySid'] = $sid;
            $this->render('manage_admin/security/manage_module');
        }
    }
}
