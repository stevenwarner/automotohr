<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Department_management extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('department_management_model');
    }

    function index () {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $departments = $this->department_management_model->get_all_departments($company_sid);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $data['title'] = 'Department Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['departments'] = $departments;

            if ($this->form_validation->run() == false) {
                
                $this->load->view('main/header', $data);
                $this->load->view('department_management/index');
                $this->load->view('main/footer');
            } 
        } else {
            redirect('login', 'refresh');
        }
    }

    function add_edit_department ($department_sid = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $employees = $this->department_management_model->fetch_all_company_employees($company_sid);
            $data['title'] = 'Department Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['employees'] = $employees;

            if ($department_sid != NULL) {
                $department = $this->department_management_model->get_department($department_sid);
                $data['department'] = $department;
                $data['submit_button_text'] = 'Update';
                $data['perform_action'] = 'edit_document_group';
            } else {
                $data['submit_button_text'] = 'Save';
                $data['perform_action'] = 'add_document_group';
            }

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('department_management/add_edit_department'); 
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
    
                switch ($perform_action) {
                    case 'add_document_group':
                        $department_name = $this->input->post('name');
                        $department_description = $this->input->post('description');
                        $department_supervisor = $this->input->post('supervisor');
                        $department_rm = $this->input->post('reporting_manager');
                        $approvers = $this->input->post('approvers');
                        $department_sort_order = $this->input->post('sort_order');

                        // TODO
                        // link approvers with time off table
                        
                        $data_to_insert = array();
                        $department_description = htmlentities($department_description);
                        
                        if (empty($department_sort_order)) {
                            $department_sort_order = 0;
                        }

                        $data_to_insert['name'] = $department_name;
                        $data_to_insert['description'] = $department_description;
                        $data_to_insert['supervisor'] = implode(',', $department_supervisor);
                        $data_to_insert['reporting_managers'] = implode(',', $department_rm);
                        $data_to_insert['approvers'] = implode(',', $approvers);
                        $data_to_insert['status'] = 1;
                        $data_to_insert['sort_order'] = $department_sort_order;
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['created_by_sid'] = $employer_sid;
                        
                        $this->department_management_model->insert_department($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Department Created Successfully!');
                        redirect('department_management', 'refresh');
                        break;
                        case 'edit_document_group':
                        $department_name = $this->input->post('name');
                        $department_description = $this->input->post('description');
                        $department_supervisor = $this->input->post('supervisor');
                        $department_sort_order = $this->input->post('sort_order');
                        $department_rm = $this->input->post('reporting_manager');
                        $approvers = $this->input->post('approvers');
                        
                        
                        $data_to_update = array();
                        $department_description = htmlentities($department_description);
                        
                        if (empty($department_sort_order)) {
                            $department_sort_order = 0;
                        }
                        
                        $data_to_update['name'] = $department_name;
                        $data_to_update['description'] = $department_description;
                        $data_to_update['supervisor'] = implode(',', $department_supervisor);
                        $data_to_update['reporting_managers'] = implode(',', $department_rm);
                        $data_to_update['approvers'] = implode(',', $approvers);
                        $data_to_update['sort_order'] = $department_sort_order;
                        $data_to_update['modified_by_sid'] = $employer_sid;
                        $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                        
                        $this->department_management_model->update_department($department_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Department Updated Successfully!');
                        redirect('department_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function manage_department ($department_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $teams = $this->department_management_model->get_all_department_teams($company_sid, $department_sid);
            $department_name = $this->department_management_model->get_department_name($department_sid);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $data['title'] = 'Manage Teams';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['teams'] = $teams;
            $data['department_sid'] = $department_sid;
            $data['department_name'] = $department_name;

            if ($this->form_validation->run() == false) {
                
                $this->load->view('main/header', $data);
                $this->load->view('department_management/manage_department');
                $this->load->view('main/footer');
            } 
        } else {
            redirect('login', 'refresh');
        }
    }

    function add_edit_team ($department_sid, $team_sid = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $employees = $this->department_management_model->fetch_all_company_employees($company_sid);
            $data['title'] = 'Team Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['department_sid'] = $department_sid;
            $data['employees'] = $employees;

            if ($team_sid != NULL) {
                $team = $this->department_management_model->get_team($team_sid);
                $data['team'] = $team;
                $data['submit_button_text'] = 'Update';
                $data['perform_action'] = 'edit_department_team';
            } else {
                $data['submit_button_text'] = 'Save';
                $data['perform_action'] = 'add_department_team';
            }

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('department_management/add_edit_department_team');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
    
                switch ($perform_action) {
                    case 'add_department_team':
                        $team_name = $this->input->post('name');
                        $team_description = $this->input->post('description');
                        $team_lead_name = $this->input->post('teamlead_name');
                        $team_sort_order = $this->input->post('sort_order');
                        $department_rm = $this->input->post('reporting_manager');
                        $approvers = $this->input->post('approvers');

                        $data_to_insert = array();
                        $team_description = htmlentities($team_description);
                        
                        if (empty($team_sort_order)) {
                            $team_sort_order = 0;
                        }

                        $data_to_insert['name'] = $team_name;
                        $data_to_insert['description'] = $team_description;
                        $data_to_insert['team_lead'] = implode(',', $team_lead_name);
                        $data_to_insert['reporting_managers'] = implode(',', $department_rm);
                        $data_to_insert['approvers'] = implode(',', $approvers);
                        $data_to_insert['status'] = 1;
                        $data_to_insert['sort_order'] = $team_sort_order;
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['department_sid'] = $department_sid;
                        $data_to_insert['created_by_sid'] = $employer_sid;
                        $this->department_management_model->insert_team($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Team Created Successfully!');
                        redirect('department_management/manage_department/'.$department_sid, 'refresh');
                        break;
                    case 'edit_department_team':
                        $team_name = $this->input->post('name');
                        $team_description = $this->input->post('description');
                        $team_lead_name = $this->input->post('teamlead_name');
                        $team_sort_order = $this->input->post('sort_order');
                        $department_rm = $this->input->post('reporting_manager');
                        $approvers = $this->input->post('approvers');
                        
                        $data_to_update = array();
                        $team_description = htmlentities($team_description);
                        
                        if (empty($team_sort_order)) {
                            $team_sort_order = 0;
                        }

                        $data_to_update['name'] = $team_name;
                        $data_to_update['description'] = $team_description;
                        $data_to_update['team_lead'] = implode(',', $team_lead_name);
                        $data_to_update['sort_order'] = $team_sort_order;
                        $data_to_update['modified_by_sid'] = $employer_sid;
                        $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                        $data_to_update['reporting_managers'] = implode(',', $department_rm);
                        $data_to_update['approvers'] = implode(',', $approvers);
                        $this->department_management_model->update_team($team_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Team Updated Successfully!');
                        redirect('department_management/manage_department/'.$department_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function assign_employee ($department_sid, $team_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $data['title'] = 'Assign Employees';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $employees = $this->department_management_model->fetch_all_company_employees($company_sid);
            $pre_assign_employees = $this->department_management_model->get_all_employees_to_team($department_sid, $team_sid);
            $team_name = $this->department_management_model->get_team_name($team_sid);
            $assigned_employees = array();

            if (!empty($pre_assign_employees)) {
                foreach ($pre_assign_employees as $key => $pre_assign) {
                    array_push($assigned_employees, $pre_assign['employee_sid']);
                }
            }

            $data['assigned_employees'] = $assigned_employees;
            $data['department_sid'] = $department_sid;
            $data['employees'] = $employees;
            $data['team_name'] = $team_name;

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('department_management/employee_2_team');
                $this->load->view('main/footer');
            } else {
                $assign_employees = $this->input->post('employees');

                $this->department_management_model->delete_employees_from_team($department_sid, $team_sid);
                
                if (!empty($assign_employees)) {
                    foreach ($assign_employees as $key => $employee_sid) {
                        $data_to_insert = array();
                        $data_to_insert['department_sid'] = $department_sid;
                        $data_to_insert['team_sid'] = $team_sid;
                        $data_to_insert['employee_sid'] = $employee_sid;
                        $this->department_management_model->updateDepartmentTeamForEmployee($department_sid, $team_sid, $employee_sid);
                        $this->department_management_model->assign_employee_to_team($data_to_insert);
                    }
                }
                
                $this->session->set_flashdata('message', '<strong>Success:</strong> Employees Update Successfully!');
                redirect('department_management/manage_department/'.$department_sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function import () {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $data['title'] = 'Assign Employees';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

        


            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('department_management/import');
                $this->load->view('main/footer');
            } else {
               
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function delete_department_and_team($type, $sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data_to_update = array();
            $data_to_update['is_deleted'] = 1;
            $data_to_update['deleted_by_sid'] = $employer_sid;
            $data_to_update['deleted_date'] = date('Y-m-d H:i:s');
            if ($type == 'department') {
                $this->department_management_model->update_department($sid, $data_to_update);
            } else if ($type == 'team') {
                $this->department_management_model->update_team($sid, $data_to_update);
            }
        } else {
            redirect('login', 'refresh');
        }   
    }

    public function ajax_responder() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            if ($this->form_validation->run() == false) {
                echo 'error'; 
            } else {
                $perform_action = $this->input->post('perform_action');
                
                switch ($perform_action) {
                    case 'get_all_departments':
                        $departments = $this->department_management_model->get_all_company_departments($company_sid);

                        echo json_encode($departments);
                        break;
                    case 'get_all_teams':
                        $teams = $this->department_management_model->get_all_company_teams($company_sid);

                        echo json_encode($teams);
                        break; 

                    case 'get_all_team_employees':
                        $team_department_sid = $this->input->post('team_department_sid');
                        $provided_sids = explode(',', $team_department_sid);
                        $team_sid = $provided_sids[0];
                        $department_sid = $provided_sids[1];
                        $team_employees = $this->department_management_model->get_all_employees_to_team($department_sid, $team_sid);
                        //
                        $supervisor_sids = $this->department_management_model->get_department_name_and_supervisor($department_sid);
                        //
                        $supervisor_names = array();
                        //
                        if (!empty($supervisor_sids)) {
                            foreach ($supervisor_sids as $sup_key => $supervisor_sid) {
                                $supervisor_name = getUserNameBySID($supervisor_sid);
                                $supervisor_names[$sup_key]['name'] = $supervisor_name;
                            }
                        }
                        //
                        $teamlead_sids = $this->department_management_model->get_team_name_and_teamlead($team_sid);
                        //
                        $teamlead_names = array();
                        //
                        if (!empty($teamlead_sids)) {
                            foreach ($teamlead_sids as $tm_key => $teamlead_sid) {
                                $teamlead_name = getUserNameBySID($teamlead_sid);
                                $teamlead_names[$tm_key]['name'] = $teamlead_name;
                            }
                        }
                        //
                        $return_data = array();

                        $return_data['team_employees'] = array_column($team_employees, "employee_sid");  
                        //
                        $return_data['supervisor_names'] = $supervisor_names;  
                        //
                        $return_data['teamlead_names'] = $teamlead_names;  
                        //
                        echo json_encode($return_data);
                        break;
                    case 'get_all_active_company_employees':  
                        $active_employees = $this->department_management_model->fetch_all_company_employees($company_sid);
                        //
                        $return_data = array();
                        //
                        foreach ($active_employees as $eKey => $employee) {
                            $employee_data = array(
                                'first_name' => $employee['first_name'],
                                'last_name' => $employee['last_name'],
                                'access_level' => $employee['access_level'],
                                'access_level_plus' => $employee['access_level_plus'],
                                'is_executive_admin' => $employee['is_executive_admin'],
                                'pay_plan_flag' => $employee['pay_plan_flag'],
                                'job_title' => $employee['job_title']
                            );
                            //
                            $employee_name = remakeEmployeeName($employee_data);
                            //
                            $return_data[$eKey]['employee_sid'] = $employee['sid'];
                            //
                            $return_data[$eKey]['employee_name'] = $employee_name;
                            //
                        }
                        //
                        echo json_encode($return_data);
                        break;    
                    case 'save_changes':
                        $team_department_sid = $this->input->post('team_department_sid');
                        $provided_sids = explode(',', $team_department_sid);
                        $team_sid = $provided_sids[0];
                        $department_sid = $provided_sids[1];

                        if (isset($_POST['add_employee'])) {
                            foreach (explode(',', $_POST['add_employee']) as  $add_employee_sid) {
                                $data_to_insert = array();
                                $data_to_insert['department_sid'] = $department_sid;
                                $data_to_insert['team_sid'] = $team_sid;
                                $data_to_insert['employee_sid'] = $add_employee_sid;
                                $this->department_management_model->assign_employee_to_team($data_to_insert);
                            }
                        }

                        if (isset($_POST['remove_employee'])) {
                            foreach (explode(',', $_POST['remove_employee']) as  $remove_employee_sid) {
                                $this->department_management_model->remove_employee_from_team($department_sid, $team_sid, $remove_employee_sid);
                            }
                        }

                        echo 'success';
                        break;          
                }
            }
        } else {
            echo 'error';
        }
    }

}
