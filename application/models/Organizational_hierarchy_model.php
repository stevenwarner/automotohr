<?php

class Organizational_hierarchy_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_department_details($department_sid, $company_sid){
        $this->db->select('departments.*');
        $this->db->select('t2.dept_name as parent_name');
        $this->db->where('departments.status', 1);
        $this->db->order_by('departments.dept_parent_sid', 'ASC');
        $this->db->where('departments.company_sid', $company_sid);
        $this->db->where('departments.sid', $department_sid);

        $this->db->join('departments AS t2', 'departments.dept_parent_sid = t2.sid', 'left');

        $department = $this->db->get('departments')->result_array();

        if(!empty($department)){
            return $department[0];
        } else {
            return array();
        }
    }

    function get_all_departments($company_sid, $parent_sid = 'all', $get_full_tree = false){
        $this->db->select('departments.*');
        $this->db->select('t2.dept_name as parent_name');
        $this->db->where('departments.status', 1);
        $this->db->order_by('departments.dept_parent_sid', 'ASC');
        $this->db->order_by('departments.dept_name', 'ASC');
        $this->db->where('departments.company_sid', $company_sid);

        $this->db->join('departments AS t2', 'departments.dept_parent_sid = t2.sid', 'left');

        if($parent_sid >= 0){
            $this->db->where('departments.dept_parent_sid', $parent_sid);
        }

        $departments = $this->db->get('departments')->result_array();



        if(!empty($departments)) {
            foreach ($departments as $key => $department) {
                if($get_full_tree == true){
                    $positions = $this->get_all_positions($company_sid, $department['sid'], array(), 0);

                    if(!empty($positions)) {
                        foreach ($positions as $pos_key => $position) {

                        }
                    }

                    $departments[$key]['positions'] = $positions;
                }

                $sub_departments = $this->get_all_departments($company_sid, $department['sid'], $get_full_tree);

                if(!empty($sub_departments)){
                    $departments[$key]['sub_departments'] = $sub_departments;
                    $departments[$key]['sub_departments_count'] = count($sub_departments);

                } else {
                    $departments[$key]['sub_departments'] = array();
                    $departments[$key]['sub_departments_count'] = 0;
                }
            }
        }

        return $departments;
    }

    function check_if_department_name_already_exists($company_sid, $department_name){
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('dept_name', $department_name);

        $data = $this->db->get('departments')->result_array();

        if(!empty($data)){
            return true;
        } else {
            return false;
        }
    }

    function check_if_position_name_already_exists($company_sid, $position_name, $department_sid){
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('position_name', $position_name);
        $this->db->where('department_sid', $department_sid);

        $data = $this->db->get('positions')->result_array();

        if(!empty($data)){
            return true;
        } else {
            return false;
        }
    }

    function insert_department($data){
        $this->db->insert('departments', $data);
    }

    function update_department($department_sid, $data){
        $this->db->where('sid', $department_sid);
        $this->db->update('departments', $data);
    }

    function delete_department($company_sid, $department_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $department_sid);
        $this->db->delete('departments');
    }


    function get_all_employees($company_sid){
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('is_primary_admin');
        $this->db->select('is_executive_admin');

        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);

        $this->db->where('username !=', '');

        $this->db->where('parent_sid', $company_sid);

//        $this->db->order_by('first_name', 'ASC');
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);

        return $this->db->get('users')->result_array();
    }

    function get_all_positions($company_sid, $department_sid = null, $excluded = array(), $parent_sid = null){
        $this->db->select('positions.*');
        $this->db->select('t2.position_name as parent_name');
        $this->db->select('departments.dept_name as department_name');
        $this->db->where('positions.company_sid', $company_sid);

        if($department_sid >= 0){
            $this->db->where('positions.department_sid', $department_sid);
        }

        if($parent_sid >= 0){
            $this->db->where('positions.parent_sid', $parent_sid);
        }

        $this->db->order_by('positions.department_sid', 'ASC');

        $this->db->join('departments', 'departments.sid = positions.department_sid', 'left');

        $this->db->join('positions AS t2', 'positions.parent_sid = t2.sid', 'left');

        $positions = $this->db->get('positions')->result_array();

        foreach($positions as $key => $position){
            if(in_array($position['sid'], $excluded)){
                unset($positions[$key]);
            } else {
                $vacancies = $this->get_all_filled_vacancies($company_sid, $position['sid']);

                if(!empty($vacancies)){
                    $positions[$key]['filled_vacancies'] = $vacancies;
                } else {
                    $positions[$key]['filled_vacancies'] = array();
                }

                $sub_positions = $this->get_all_positions($company_sid, $department_sid, $excluded, $position['sid']);

                $positions[$key]['sub_positions'] = $sub_positions;
            }



        }

        return $positions;
    }

    function get_position($position_sid, $company_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $position_sid);
        $data = $this->db->get('positions')->result_array();

        if(!empty($data)){
            return $data[0];
        } else {
            return array();
        }
    }

    function insert_position($data){
        $this->db->insert('positions', $data);
    }

    function update_position($position_sid, $data){
        $this->db->where('sid', $position_sid);
        $this->db->update('positions', $data);
    }

    function delete_position($position_sid, $company_sid, $department_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('department_sid', $department_sid);
        $this->db->where('sid', $position_sid);
        $this->db->delete('positions');
    }


    function get_all_vacancies($company_sid, $position_sid = null){
        $this->db->select('vacancies.*');
        $this->db->select('departments.dept_name');
        $this->db->select('positions.position_name');

        $this->db->where('vacancies.company_sid', $company_sid);

        if($position_sid > 0){
            $this->db->where('vacancies.position_sid', $position_sid);
        }

        $this->db->order_by('vacancies.created_date', 'DESC');

        $this->db->join('departments', 'departments.sid = vacancies.department_sid', 'left');
        $this->db->join('positions', 'positions.sid = vacancies.position_sid', 'left');

        return $this->db->get('vacancies')->result_array();
    }

    function get_vacancy($company_sid, $vacancy_sid){
        $this->db->select('vacancies.*');
        $this->db->select('departments.dept_name as department_name');
        $this->db->select('positions.position_name');

        $this->db->where('vacancies.company_sid', $company_sid);
        $this->db->where('vacancies.sid', $vacancy_sid);

        $this->db->join('departments', 'departments.sid = vacancies.department_sid', 'left');
        $this->db->join('positions', 'positions.sid = vacancies.position_sid', 'left');


        $vacancy = $this->db->get('vacancies')->result_array();

        if(!empty($vacancy)){
            return $vacancy[0];
        } else {
            return array();
        }
    }

    function insert_vacancy($data){
        $this->db->insert('vacancies', $data);
    }

    function update_vacancy($vacancy_sid, $data){
        $this->db->where('sid', $vacancy_sid);
        $this->db->update('vacancies', $data);
    }

    function delete_vacancy($vacancy_sid, $company_sid){
        $this->db->where('sid', $vacancy_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->delete('vacancies');
    }


    function insert_vacancies_status($data){
        $this->db->insert('vacancies_status', $data);
    }


    function update_hired_count($company_sid, $vacancy_sid){
        $this->db->select('hired_count');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $vacancy_sid);

        $count_data = $this->db->get('vacancies')->result_array();

        if(!empty($count_data)){
            $count_data = $count_data[0];

            $count = $count_data['hired_count'];

            $new_count = $count + 1;

            $data_to_update = array();
            $data_to_update['hired_count'] = $new_count;

            $this->db->where('company_sid', $company_sid);
            $this->db->where('sid', $vacancy_sid);

            $this->db->update('vacancies', $data_to_update);

        }
    }

    function get_all_vacancies_status($company_sid, $vacancies_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('vacancies_sid', $vacancies_sid);

        return $this->db->get('vacancies_status')->result_array();

    }

    function get_all_filled_vacancies($company_sid, $position_sid){
        $this->db->select('vacancies_status.*');
        $this->db->select('users.first_name as employee_first_name');
        $this->db->select('users.last_name as employee_last_name');
        $this->db->select('users.profile_picture');
        $this->db->where('vacancies_status.company_sid', $company_sid);
        $this->db->where('vacancies_status.position_sid', $position_sid);

        $this->db->join('users', 'users.sid = vacancies_status.employee_sid', 'left');

        return $this->db->get('vacancies_status')->result_array();
    }


    function get_all_departments_unfiltered($company_sid, $parent_sid = -1){
        $this->db->select('departments.*');
        $this->db->select('t2.dept_name as parent_name');
        $this->db->where('departments.status', 1);
        $this->db->order_by('departments.dept_parent_sid', 'ASC');
        $this->db->order_by('departments.dept_name', 'ASC');
        $this->db->where('departments.company_sid', $company_sid);

        $this->db->join('departments AS t2', 'departments.dept_parent_sid = t2.sid', 'left');


        if($parent_sid >= 0){
            $this->db->where('departments.dept_parent_sid', $parent_sid);
        }

        $departments = $this->db->get('departments')->result_array();

        if(!empty($departments)) {
            foreach ($departments as $key => $department) {

                $sub_departments = $this->get_all_departments_unfiltered($company_sid, $department['sid']);

                if(!empty($sub_departments)){
                    $departments[$key]['sub_departments'] = $sub_departments;
                    $departments[$key]['sub_departments_count'] = count($sub_departments);

                } else {
                    $departments[$key]['sub_departments'] = array();
                    $departments[$key]['sub_departments_count'] = 0;
                }
            }
        }

        return $departments;
    }

    function get_all_positions_unfiltered($company_sid, $department_sid = -1, $parent_sid = -1, $excluded = array()){
        $this->db->select('positions.*');
        $this->db->select('t2.position_name as parent_name');
        $this->db->select('departments.dept_name as department_name');
        $this->db->where('positions.company_sid', $company_sid);

        if($department_sid >= 0){
            $this->db->where('positions.department_sid', $department_sid);
        }

        if($parent_sid >= 0){
            $this->db->where('positions.parent_sid', $parent_sid);
        }

        $this->db->order_by('positions.department_sid', 'ASC');

        $this->db->join('departments', 'departments.sid = positions.department_sid', 'left');

        $this->db->join('positions AS t2', 'positions.parent_sid = t2.sid', 'left');

        $positions = $this->db->get('positions')->result_array();

        foreach($positions as $key => $position){
            if(in_array($position['sid'], $excluded)){
                unset($positions[$key]);
            } else {
                $sub_positions = $this->get_all_positions_unfiltered($company_sid, $department_sid, $position['sid']);

                $positions[$key]['sub_positions'] = $sub_positions;
            }
        }

        return $positions;
    }


    function get_parent_employees($position_sid){
        $this->db->select('*');
        $this->db->where('sid', $position_sid);
        $position_data = $this->db->get('positions')->result_array();

        if(!empty($position_data)){
            $position_data = $position_data[0];

            $parent_sid = $position_data['parent_sid'];

            $this->db->select('vacancies_status.employee_sid');
            $this->db->select('users.first_name');
            $this->db->select('users.last_name');

            $this->db->where('vacancies_status.position_sid', $parent_sid);

            $this->db->join('users', 'users.sid = vacancies_status.employee_sid', 'left');

            $employees = $this->db->get('vacancies_status')->result_array();

            return $employees;
        } else {
            return array();
        }
    }



    function get_departments_tree($company_sid, $parent_sid = -1){
        $this->db->select('departments.*');
        $this->db->where('departments.company_sid', $company_sid);

        if($parent_sid >= 0){
            $this->db->where('departments.dept_parent_sid', $parent_sid);
        }

        $departments = $this->db->get('departments')->result_array();


        if(!empty($departments)){
            foreach($departments as $key => $department){
                $employees = $this->get_vacancies_statuses($company_sid, 0, $department['sid']);

                if(!empty($employees)){
                    $departments[$key]['employees'] = $employees;
                } else {
                    $departments[$key]['employees'] = array();
                }


                $sub_departments = $this->get_departments_tree($company_sid, $department['sid']);

                if(!empty($sub_departments)){
                    $departments[$key]['sub_departments'] = $sub_departments;
                } else {
                    $departments[$key]['sub_departments'] = array();
                }
            }
        }

        return $departments;
    }


    function get_vacancies_statuses($company_sid, $parent_sid = -1, $department_sid = -1){
        $this->db->select('vacancies_status.*');

        $this->db->select('departments.dept_name as department_name');
        $this->db->select('positions.position_name');
        $this->db->select('users.first_name as employee_first_name');
        $this->db->select('users.profile_picture');
        $this->db->select('users.last_name as employee_last_name');

        $this->db->where('vacancies_status.company_sid', $company_sid);

        if($department_sid >= 0) {
            $this->db->where('vacancies_status.department_sid', $department_sid);
        }

        if($parent_sid >= 0) {
            $this->db->where('vacancies_status.parent_employee_sid', $parent_sid);
        }

        $this->db->join('departments', 'departments.sid = vacancies_status.department_sid', 'left');
        $this->db->join('positions', 'positions.sid = vacancies_status.position_sid', 'left');
        $this->db->join('users', 'users.sid = vacancies_status.employee_sid', 'left');

        $vacancies_status = $this->db->get('vacancies_status')->result_array();

        if(!empty($vacancies_status)){
            foreach($vacancies_status as $key => $status){
                $children = $this->get_vacancies_statuses($company_sid, $status['employee_sid'], $department_sid);

                if(!empty($children)){
                    $vacancies_status[$key]['children'] = $children;
                } else {
                    $vacancies_status[$key]['children'] = array();
                }
            }
        }


        return $vacancies_status;

    }

    function generate_json_array_for_org_chart($company_sid){

        $departments = $this->get_departments_tree($company_sid, 0);

        $return_array = array();

        $company_details = get_company_details($company_sid);

        $return_array['name'] = ucwords($company_details['CompanyName']);
        $return_array['title'] = 'Title goes here';
        $return_array['dept'] = 'The Company';
        $return_array['className'] = 'org_chart_company';


        $departments = $this->generate_departments_array($departments);


        $return_array['children'] = $departments;

        return json_encode($return_array);
    }


    function generate_departments_array($departments){
        $return = array();
        foreach($departments as $department){
            $node = array();
            $node['id'] = $department['sid'];
            $node['name'] = $department['dept_name'];
            $node['title'] = 'Department';

            $node['dept'] = $department['dept_name'];

            $children = $this->generate_departments_array($department['sub_departments']);

            if(!empty($department['employees'])){
                $node['className'] = 'org_chart_department drill-down asso-' . clean_domain($department['dept_name']);
            } else {
                $node['className'] = 'org_chart_department asso-' . clean_domain($department['dept_name']);
            }

            $node['children'] = $children;
            $return[] = $node;
        }

        return $return;
    }

    function generate_positions_array($employees){
        $return = array();
        foreach($employees as $employee){
            $node = array();
            $node['name'] = $employee['employee_first_name'] . ' ' . $employee['employee_last_name'];
            $node['title'] = $employee['position_name'];
            $node['className'] = 'org_chart_employee';

            $image = '';
            if($employee['profile_picture'] != '') {
                $image =  AWS_S3_BUCKET_URL . $employee['profile_picture'];
            } else {
                $image = base_url('assets/images') . '/img-applicant.jpg';
            }

            $node['dept'] = '<span>' .  $employee['position_name'] . '</span>';
            $node['profile_picture'] = $image;


            $children = $this->generate_positions_array($employee['children']);
            $node['children'] = $children;

            $return[] = $node;
        }

        return $return;
    }
}
