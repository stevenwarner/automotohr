<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Organizational_hierarchy extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('organizational_hierarchy_model');

        $this->form_validation->set_error_delimiters('<span class="error_message text-left"><i class="fa fa-exclamation-circle"></i>', '</span>');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'dashboard', 'application_tracking');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy";
                $data['subtitle'] = "Organizational Hierarchy";


                $hierarchy = $this->organizational_hierarchy_model->get_departments_tree($company_sid, 0);
                $data['hierarchy'] = $hierarchy;

                //chart data

                $chart_data = $this->organizational_hierarchy_model->generate_json_array_for_org_chart($company_sid);
                $data['chart_data'] = $chart_data;



                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/index');
                $this->load->view('main/footer');
            } else {

            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    #region Departments

    public function departments()
    {
        if ($this->session->userdata('logged_in')) {

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy', 'list_departments');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Departments";

                $departments = $this->organizational_hierarchy_model->get_all_departments_unfiltered($company_sid);

                $data['departments'] = $departments;

                $company_name = $data["session"]["company_detail"]["CompanyName"];
                $data['company_name'] = $company_name;

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/departments');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_department':
                        $department_sid = $this->input->post('department_sid');
                        $company_sid = $this->input->post('company_sid');

                        $this->organizational_hierarchy_model->delete_department($company_sid, $department_sid);

                        $this->session->set_flashdata('message', '<strong>Success</strong> Department Deleted!');

                        redirect('organizational_hierarchy/departments', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_department()
    {
        if ($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('dept_name', 'Name', 'required|xss_clean|trim|callback_department_name_check');
            $this->form_validation->set_rules('dept_description', 'Description', 'xss_clean|trim');
            $this->form_validation->set_rules('dept_parent', 'Parent', 'xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy/departments', 'add_department');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Departments";
                $data['subtitle'] = "Add Department";
                $data['submit_btn_text'] = "Save";
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;


                $departments = $this->organizational_hierarchy_model->get_all_departments_unfiltered($company_sid);
                $data['departments'] = $departments;


                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/add_department');
                $this->load->view('main/footer');
            } else {
                $company_sid = $this->input->post('company_sid');
                $employer_sid = $this->input->post('employer_sid');
                $dept_name = $this->input->post('dept_name');
                $dept_description = $this->input->post('dept_description');
                $dept_parent_sid = $this->input->post('dept_parent_sid');

                $data_to_save = array();
                $data_to_save['dept_name'] = $dept_name;
                $data_to_save['dept_description'] = $dept_description;
                $data_to_save['dept_parent_sid'] = $dept_parent_sid;
                $data_to_save['status'] = 1;
                $data_to_save['date_created'] = date('Y-m-d H:i:s');
                $data_to_save['company_sid'] = $company_sid;
                $data_to_save['created_by'] = $employer_sid;

                $this->organizational_hierarchy_model->insert_department($data_to_save);

                $this->session->set_flashdata('message', '<strong>Success</strong>: Department Created.');
                redirect('organizational_hierarchy/departments', 'refresh');

            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_department($department_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            if ($department_sid > 0) {

                $this->form_validation->set_rules('dept_description', 'Description', 'xss_clean|trim');
                $this->form_validation->set_rules('dept_parent', 'Parent', 'xss_clean|trim');

                $old_department_name = $this->input->post('old_department_name');
                $new_department_name = $this->input->post('dept_name');

                if ($old_department_name == $new_department_name) {
                    $this->form_validation->set_rules('dept_name', 'Name', 'required|xss_clean|trim');
                } else {
                    $this->form_validation->set_rules('dept_name', 'Name', 'required|xss_clean|trim|callback_department_name_check');
                }


                if ($this->form_validation->run() == false) {
                    $data['session'] = $this->session->userdata('logged_in');

                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data['security_details'] = $security_details;
                    check_access_permissions($security_details, 'organizational_hierarchy/departments', 'edit_department');

                    $company_sid = $data["session"]["company_detail"]["sid"];
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                    $data['title'] = "Organizational Hierarchy - Departments";
                    $data['subtitle'] = "Add Department";
                    $data['submit_btn_text'] = "Save";
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;


                    $departments = $this->organizational_hierarchy_model->get_all_departments_unfiltered($company_sid);
                    $data['departments'] = $departments;

                    $department = $this->organizational_hierarchy_model->get_department_details($department_sid, $company_sid);

                    if (!empty($department)) {
                        $data['department'] = $department;


                        //load views
                        $this->load->view('main/header', $data);
                        $this->load->view('organizational_hierarchy/add_department');
                        $this->load->view('main/footer');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error</strong>: Department Not Found');
                        redirect('organizational_hierarchy/departments', "refresh");
                    }

                } else {
                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->input->post('employer_sid');
                    $dept_name = $this->input->post('dept_name');
                    $dept_description = $this->input->post('dept_description');
                    $dept_parent_sid = $this->input->post('dept_parent_sid');


                    $data_to_update = array();
                    $data_to_update['dept_name'] = $dept_name;
                    $data_to_update['dept_description'] = $dept_description;
                    $data_to_update['dept_parent_sid'] = $dept_parent_sid;
                    $data_to_update['status'] = 1;
                    $data_to_update['date_modified'] = date('Y-m-d H:i:s');
                    $data_to_update['company_sid'] = $company_sid;
                    $data_to_update['modified_by'] = $employer_sid;

                    $this->organizational_hierarchy_model->update_department($department_sid, $data_to_update);

                    $this->session->set_flashdata('message', '<strong>Success</strong>: Department Updated.');
                    redirect('organizational_hierarchy/departments', 'refresh');

                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong>: Department Not Found');
                redirect('organizational_hierarchy/departments', "refresh");
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function department_name_check($str)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $department_exists = $this->organizational_hierarchy_model->check_if_department_name_already_exists($company_sid, $str);

            if ($department_exists == true) {
                $this->form_validation->set_message('department_name_check', '{field} must be unique.');
                return FALSE;
            } else {
                return TRUE;
            }

        } else {
            return FALSE;
        }
    }

    #endregion


    #region Positions

    public function positions()
    {
        if ($this->session->userdata('logged_in')) {

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy', 'list_positions');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Positions";


                $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid);
                $data['positions'] = $positions;


                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/positions');
                $this->load->view('main/footer');
            } else {

                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_position':
                        $company_sid = $this->input->post('company_sid');
                        $department_sid = $this->input->post('department_sid');
                        $position_sid = $this->input->post('position_sid');

                        $this->organizational_hierarchy_model->delete_position($position_sid, $company_sid, $department_sid);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Position Deleted!');
                        redirect('organizational_hierarchy/positions', 'refresh');
                        break;
                }

            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_position()
    {
        if ($this->session->userdata('logged_in')) {

            $this->form_validation->set_rules('position_name', 'Name', 'required|trim|xss_clean|callback_position_name_check');
            $this->form_validation->set_rules('position_description', 'Description', 'trim|xss_clean');
            $this->form_validation->set_rules('department_sid', 'Department', 'trim|xss_clean');
            $this->form_validation->set_rules('employee_sid', 'Employee', 'trim|xss_clean');
            $this->form_validation->set_rules('parent_sid', 'Parent', 'trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy/positions', 'add_position');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Positions";
                $data['subtitle'] = "Add Position";
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['submit_btn_text'] = 'Add New Position';

                $departments = $this->organizational_hierarchy_model->get_all_departments_unfiltered($company_sid);
                $data['departments'] = $departments;


                $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid);
                $data['positions'] = $positions;


                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/add_position');
                $this->load->view('main/footer');

            } else {
                $company_sid = $this->input->post('company_sid');
                $employer_sid = $this->input->post('employer_sid');
                $position_name = $this->input->post('position_name');
                $position_description = $this->input->post('position_description');
                $department_sid = $this->input->post('department_sid');
                $parent_sid = $this->input->post('parent_sid');

                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['created_by'] = $employer_sid;
                $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                $data_to_insert['position_name'] = $position_name;
                $data_to_insert['position_description'] = $position_description;
                $data_to_insert['department_sid'] = $department_sid;
                $data_to_insert['parent_sid'] = $parent_sid;

                $this->organizational_hierarchy_model->insert_position($data_to_insert);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Position Added!');
                redirect('organizational_hierarchy/positions', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit_position($position_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            if ($position_sid > 0) {

                $this->form_validation->set_rules('position_description', 'Description', 'trim|xss_clean');
                $this->form_validation->set_rules('department_sid', 'Department', 'trim|xss_clean');
                $this->form_validation->set_rules('employee_sid', 'Employee', 'trim|xss_clean');
                $this->form_validation->set_rules('parent_sid', 'Parent', 'trim|xss_clean');

                $old_position_name = $this->input->post('old_position_name');
                $new_position_name = $this->input->post('position_name');

                if ($old_position_name == $new_position_name) {
                    $this->form_validation->set_rules('position_name', 'Name', 'required|trim|xss_clean');
                } else {
                    $this->form_validation->set_rules('position_name', 'Name', 'required|trim|xss_clean|callback_position_name_check');
                }


                if ($this->form_validation->run() == false) {
                    $data['session'] = $this->session->userdata('logged_in');

                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data['security_details'] = $security_details;
                    check_access_permissions($security_details, 'organizational_hierarchy/departments', 'edit_position');

                    $company_sid = $data["session"]["company_detail"]["sid"];
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                    $data['title'] = "Organizational Hierarchy - Positions";
                    $data['subtitle'] = "Edit Position";
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['submit_btn_text'] = 'Update Position';

                    $position = $this->organizational_hierarchy_model->get_position($position_sid, $company_sid);

                    $departments = $this->organizational_hierarchy_model->get_all_departments_unfiltered($company_sid);
                    $data['departments'] = $departments;

                    if (!empty($position)) {

                        $data['position'] = $position;

                        $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid, $position['department_sid'], -1, array($position['sid']));
                        $data['positions'] = $positions;

                        //load views
                        $this->load->view('main/header', $data);
                        $this->load->view('organizational_hierarchy/add_position');
                        $this->load->view('main/footer');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error: </strong> Position Not Found!');
                        redirect('organizational_hierarchy/positions', 'refresh');
                    }
                } else {
                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->input->post('employer_sid');
                    $position_name = $this->input->post('position_name');
                    $position_description = $this->input->post('position_description');
                    $department_sid = $this->input->post('department_sid');
                    $parent_sid = $this->input->post('parent_sid');

                    $data_to_update = array();
                    $data_to_update['company_sid'] = $company_sid;
                    $data_to_update['modified_by'] = $employer_sid;
                    $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                    $data_to_update['position_name'] = $position_name;
                    $data_to_update['position_description'] = $position_description;
                    $data_to_update['department_sid'] = $department_sid;
                    $data_to_update['parent_sid'] = $parent_sid;

                    $this->organizational_hierarchy_model->update_position($position_sid, $data_to_update);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Position Updated!');
                    redirect('organizational_hierarchy/positions', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Position Not Found!');
                redirect('organizational_hierarchy/positions', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function position_name_check($str)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $department_sid = $this->input->post('department_sid');

            $department_exists = $this->organizational_hierarchy_model->check_if_position_name_already_exists($company_sid, $str, $department_sid);

            if ($department_exists == true) {
                $this->form_validation->set_message('position_name_check', '{field} must be unique.');
                return FALSE;
            } else {
                return TRUE;
            }

        } else {
            return FALSE;
        }
    }

    #endregion


    #region Vacancies

    public function vacancies()
    {
        if ($this->session->userdata('logged_in')) {

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy', 'list_vacancies');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Vacancies";
                $data['subtitle'] = "Organizational Hierarchy - All Available Vacancies";


                $vacancies = $this->organizational_hierarchy_model->get_all_vacancies($company_sid);
                $data['vacancies'] = $vacancies;

                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/vacancies');
                $this->load->view('main/footer');
            } else {

                $perform_action = $this->input->post('perform_action');

                switch($perform_action){
                    case 'delete_vacancy':
                        $vacancy_sid = $this->input->post('vacancy_sid');
                        $company_sid = $this->input->post('company_sid');

                        $this->organizational_hierarchy_model->delete_vacancy($vacancy_sid, $company_sid);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Vacancy Deleted!');

                        redirect('organizational_hierarchy/vacancies', 'refresh');
                        break;
                }

            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function add_vacancy()
    {
        if ($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('position_sid', 'Position', 'required|xss_clean|trim');
            $this->form_validation->set_rules('vacancies_count', 'Number of Vacancies Available', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['session'] = $this->session->userdata('logged_in');

                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'organizational_hierarchy/vacancies', 'add_vacancy');

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Organizational Hierarchy - Vacancies";
                $data['subtitle'] = "Add New Vacancy";
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['submit_btn_text'] = 'Add New Vacancy';

                $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid);
                $data['positions'] = $positions;


                //load views
                $this->load->view('main/header', $data);
                $this->load->view('organizational_hierarchy/add_vacancy');
                $this->load->view('main/footer');

            } else {
                $company_sid = $this->input->post('company_sid');
                $employer_sid = $this->input->post('employer_sid');
                $position_sid = $this->input->post('position_sid');
                $vacancies_count = $this->input->post('vacancies_count');

                $position_details = $this->organizational_hierarchy_model->get_position($position_sid, $company_sid);

                $department_sid = $position_details['department_sid'];


                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['department_sid'] = $department_sid;
                $data_to_insert['position_sid'] = $position_sid;
                $data_to_insert['vacancies_count'] = $vacancies_count;
                $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                $data_to_insert['created_by'] = $employer_sid;
                $data_to_insert['hired_count'] = 0;

                $this->organizational_hierarchy_model->insert_vacancy($data_to_insert);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Vacancy Added!');
                redirect('organizational_hierarchy/vacancies', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit_vacancy($vacancy_sid = null)
    {
        if ($this->session->userdata('logged_in')) {

            if ($vacancy_sid > 0) {
                $this->form_validation->set_rules('position_sid', 'Position', 'required|xss_clean|trim');
                $this->form_validation->set_rules('vacancies_count', 'Number of Vacancies Available', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {
                    $data['session'] = $this->session->userdata('logged_in');

                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data['security_details'] = $security_details;
                    check_access_permissions($security_details, 'organizational_hierarchy/vacancies', 'edit_vacancy');

                    $company_sid = $data["session"]["company_detail"]["sid"];
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                    $data['title'] = "Organizational Hierarchy - Vacancies";
                    $data['subtitle'] = "Edit Vacancy";
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['submit_btn_text'] = 'Update Vacancy';

                    $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid);
                    $data['positions'] = $positions;

                    $vacancy = $this->organizational_hierarchy_model->get_vacancy($company_sid, $vacancy_sid);

                    if (!empty($vacancy)) {

                        $data['vacancy'] = $vacancy;

                        //load views
                        $this->load->view('main/header', $data);
                        $this->load->view('organizational_hierarchy/add_vacancy');
                        $this->load->view('main/footer');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error: </strong> Vacancy Not Found!');
                        redirect('organizational_hierarchy/vacancies', 'refresh');
                    }

                } else {
                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->input->post('employer_sid');
                    $position_sid = $this->input->post('position_sid');
                    $vacancies_count = $this->input->post('vacancies_count');

                    $position_details = $this->organizational_hierarchy_model->get_position($position_sid, $company_sid);

                    $department_sid = $position_details['department_sid'];


                    $data_to_update = array();
                    $data_to_update['company_sid'] = $company_sid;
                    $data_to_update['department_sid'] = $department_sid;
                    $data_to_update['position_sid'] = $position_sid;
                    $data_to_update['vacancies_count'] = $vacancies_count;
                    $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                    $data_to_update['modified_by'] = $employer_sid;


                    $this->organizational_hierarchy_model->update_vacancy($vacancy_sid, $data_to_update);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Vacancy Updated!');
                    redirect('organizational_hierarchy/vacancies', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Vacancy Not Found!');
                redirect('organizational_hierarchy/vacancies', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function manage_hires($vacancy_sid = null)
    {
        if ($this->session->userdata('logged_in')) {

            if ($vacancy_sid > 0) {
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {
                    $data['session'] = $this->session->userdata('logged_in');

                    $security_sid = $data['session']['employer_detail']['sid'];
                    $security_details = db_get_access_level_details($security_sid);
                    $data['security_details'] = $security_details;
                    check_access_permissions($security_details, 'organizational_hierarchy/vacancies', 'manage_hires');

                    $company_sid = $data["session"]["company_detail"]["sid"];
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                    $data['title'] = "Organizational Hierarchy - Vacancies";
                    $data['subtitle'] = "Manage Hires";
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['submit_btn_text'] = 'Save';


                    $vacancy = $this->organizational_hierarchy_model->get_vacancy($company_sid, $vacancy_sid);

                    $employees = $this->organizational_hierarchy_model->get_all_employees($company_sid);
                    $data['employees'] = $employees;

                    if (!empty($vacancy)) {

                        $data['vacancy'] = $vacancy;

                        $parent_employees = $this->organizational_hierarchy_model->get_parent_employees($vacancy['position_sid']);
                        $data['parent_employees'] = $parent_employees;

                        //load views
                        $this->load->view('main/header', $data);
                        $this->load->view('organizational_hierarchy/manage_hires');
                        $this->load->view('main/footer');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error: </strong> Vacancy Not Found!');
                        redirect('organizational_hierarchy/vacancies', 'refresh');
                    }

                } else {

                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->input->post('employer_sid');
                    $department_sid = $this->input->post('department_sid');
                    $position_sid = $this->input->post('position_sid');
                    $employees_sids = $this->input->post('employees_sid');
                    $hire_under = $this->input->post('hire_under');

                    if (count($hire_under) == count($employees_sids)) {

                        for ($count = 0; $count < count($employees_sids); $count++) {

                            if ($employees_sids[$count] > 0) {

                                $data_to_insert = array();
                                $data_to_insert['company_sid'] = $company_sid;
                                $data_to_insert['employer_sid'] = $employer_sid;
                                $data_to_insert['department_sid'] = $department_sid;
                                $data_to_insert['position_sid'] = $position_sid;
                                $data_to_insert['vacancies_sid'] = $vacancy_sid;
                                $data_to_insert['hired_status'] = 'hired';
                                $data_to_insert['hired_date'] = date('Y-m-d H:i:s');
                                $data_to_insert['employee_sid'] = $employees_sids[$count];
                                $data_to_insert['parent_employee_sid'] = intval($hire_under[$count]);

                                $this->organizational_hierarchy_model->insert_vacancies_status($data_to_insert);

                                $this->organizational_hierarchy_model->update_hired_count($company_sid, $vacancy_sid);

                            }
                        }

                    }


                    $this->session->set_flashdata('message', '<strong>Success</strong> Vacancy Hired!');
                    redirect('organizational_hierarchy/vacancies', 'refresh');

                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Vacancy Not Found!');
                redirect('organizational_hierarchy/vacancies', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    #endregion


    public function ajax_responder($perform_action = null, $type = null, $sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');


            if ($this->form_validation->run() == false) {
                switch($perform_action){
                    case 'get_json_data':



                        if($type == 'root-node'){
                            $return = $this->organizational_hierarchy_model->generate_json_array_for_org_chart($company_sid);

                            echo $return;
                        } else {
                            $employees = $this->organizational_hierarchy_model->get_vacancies_statuses($company_sid, 0, $sid);
                            $department_details = $this->organizational_hierarchy_model->get_department_details($sid, $company_sid);



                            $node = array();
                            $node['name'] = $department_details['dept_name'];
                            $node['title'] = 'title here';
                            $node['className'] = 'drill-up asso-' . clean_domain($department_details['dept_name']);
                            $node['dept'] = $department_details['dept_name'];

                            $return = $this->organizational_hierarchy_model->generate_positions_array($employees);

                            $node['children'] = $return;

                            echo json_encode($node);
                        }

                        break;
                }


            } else {
                $perform_action = $this->input->post('perform_action');


                switch ($perform_action) {
                    case 'get_positions':
                        $department_sid = $this->input->post('department_sid');
                        $current_position_sid = $this->input->post('current_position_sid');

                        $positions = $this->organizational_hierarchy_model->get_all_positions_unfiltered($company_sid, $department_sid, -1, array($current_position_sid));

                        echo '<option value="0">Please Select</option>';
                        if (!empty($positions)) {
                            foreach ($positions as $position) {
                                echo '<option value="' . $position['sid'] . '">' . $position['position_name'] . '</option>';
                            }
                        }
                        break;
                    default:


                        break;

                }
            }
        } else {
            return json_encode(array());
        }
    }
}
