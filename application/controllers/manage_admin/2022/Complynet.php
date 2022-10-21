<?php defined('BASEPATH') or exit('No direct script access allowed');

class Complynet extends Admin_Controller
{

    /**
     * Main entry point
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('2022/Company_model', 'company_model');
        $this->load->model('2022/Complynet_model', 'complynet_model');
        $this->load->library('pagination');
    }

    /**
     * 
     */
    public function manage()
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllCompanies(
            ['sid', 'CompanyName']
        );

        $this->render('complynet/admin/manage');
    }

    /**
     * 
     */
    public function report($company_sid = 'all', $status = 'all', $page_number = 1)
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet - Report';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllComplynetCompanies(
            ['automotohr_id', 'automotohr_name']
        );
        // get complyNet companies Data
        $this->data['onComplaynetCompnies'] = $this->complynet_model->complaynetCompaniesData(null, null,  null, null, false);
    
        $total_records = $this->complynet_model->complaynetCompaniesData(urldecode($company_sid), $status,  null, null, true);
        $base_url = base_url('manage_admin/complynet/report') . '/' . $company_sid . '/' . $status;

        $page_number  = ($this->uri->segment(6)) ? $this->uri->segment(6) : 1;

        $offset = 0;
        $records_per_page = 2; // PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }

        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 6;
        $config['num_links'] = 4;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav>';
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

        $this->data['links'] = $this->pagination->create_links();
        $this->data['complaynetCompaniesData'] = $this->complynet_model->complaynetCompaniesData(urldecode($company_sid), $status, $records_per_page, $offset);

        //
        $this->data['companySid'] = explode(',', urldecode($company_sid));
    
        $this->data['status'] =  $status;
        $this->data['TotalCompanies'] = $total_records;
        $this->render('complynet/admin/report');
    }


    public function reportcsv($company_sid = 'all', $status = 'all', $page_number = 1)
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet - Report';
      
        // get complyNet companies Data
        $this->data['complaynetCompaniesData'] = $this->complynet_model->complaynetCompaniesData(urldecode($company_sid), $status, $records_per_page, $offset);
        if (sizeof($this->data['complaynetCompaniesData'])) {
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=ComplyNet Companies " . (date('YmdHis')) . ".csv");
            $output = fopen('php://output', 'w');
            fputcsv($output, array('Company', 'ComplyNet Company', 'Onboard Status','Status'));

            foreach ($this->data['complaynetCompaniesData'] as $rowData) {


                $complynetLocationCount = getComplynetLocationsCount($rowData['automotohr_id']);
                $complynetDepartmentsCount = getComplynetDepartmentsCount($rowData['automotohr_id']);
                $complynetjobRoleCount = getComplynetjobRoleCount($rowData['automotohr_id']);

                if ($complynetLocationCount >= 1) {
                    $complynetLocationCount = 1;
                }

                $totalComplynetRequirement = $complynetLocationCount + $complynetDepartmentsCount + $complynetjobRoleCount;

                $automotohrLocationCount = 1; //getAutomotohrLocationsCount($rowData['automotohr_id']);
                $automotohrjobRoleCount = getAutomotohrjobRoleCount($rowData['automotohr_id']);
                $automotohrDepartmentCount = getAutomotohrDepartmentsCount($rowData['automotohr_id']);
                $totalAutomotohrRequirement = $automotohrLocationCount + $automotohrjobRoleCount + $automotohrDepartmentCount;
                //

                $onbordingStatusCompleted = ($totalComplynetRequirement * 100) / $totalAutomotohrRequirement;
                $a = array();
                $a[] = $rowData['automotohr_name'].'\r\n id:'.$rowData['automotohr_id'];
                $a[] = $rowData['complynet_name'].'\r\n id:'.$rowData['complynet_id'];
                $a[] = ceil($onbordingStatusCompleted) . '% Completed';
                $a[] =  $rowData['status'] ? 'ENABLED' : 'DISABLED';
              fputcsv($output, $a);
            }

            fclose($output);
            exit;
        }
 
        //
      
    }


    public function employee($company_sid = 'all', $employee_sid='all', $status = 'all', $page_number = 1)
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'Company Name: ' . getCompanyNameBySid($company_sid);
        // get all Employees
        $this->data['allEmployees'] = $this->complynet_model->get_active_employees_detail($company_sid);

        $this->data['complynetEmployees'] = $this->data['employeeSid'] = $this->complynet_model->employeeData(urldecode($company_sid), null, null,  null, null, false);

        $total_records = $this->complynet_model->employeeData(urldecode($company_sid), urldecode($employee_sid), $status,  null, null, true);
        $base_url = base_url('manage_admin/complynet/employee') . '/' . $company_sid . '/'. $employee_sid . '/' . urlencode($status);

        $page_number  = ($this->uri->segment(7)) ? $this->uri->segment(7) : 1;

        $offset = 0;
        $records_per_page = 10; // PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }

        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 7;
        $config['num_links'] = 4;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav>';
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

        $this->data['links'] = $this->pagination->create_links();
        $this->data['complaynetCompaniesData'] = $this->complynet_model->employeeData(urldecode($company_sid), urldecode($employee_sid), $status, $records_per_page, $offset);
           //

        $this->data['employeeSid'] = explode(',', urldecode($employee_sid));
        $this->data['companySid'] = $company_sid;
        $this->data['status'] =  $status;
        $this->data['TotalCompanies'] = $total_records;
        $this->render('complynet/admin/employees');
    }


    public function employeecsv($company_sid = 'all', $employee_sid='all', $status = 'all', $page_number = 1)
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // get all Employees
        $this->data['complaynetEmployeesData'] = $this->complynet_model->employeeData(urldecode($company_sid), urldecode($employee_sid), $status, $records_per_page, $offset);
        //
            if (sizeof($this->data['complaynetEmployeesData'])) {
                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=ComplyNet Employees " . (date('YmdHis')) . ".csv");
                $output = fopen('php://output', 'w');
                fputcsv($output, array('Employee Name', 'ComplyNet ID', 'ComplyNet Status'));

                foreach ($this->data['complaynetEmployeesData'] as $rowData) {
                    $a = array();
                    $a[] = getUserNameBySID($rowData['sid'], $remake = true);
                    $a[] = $rowData['complynet_email'] ? $rowData['complynet_email'] : ' - ';
                    $a[] = $rowData['complynet_email'] ? 'ON COMPLYNET' : 'OFF COMPLYNET';
                  fputcsv($output, $a);
                }

                fclose($output);
                exit;
            }
     
       }





    //
    public function employee_old($company_sid = 'all', $status = 'all', $page_number = 1)
    {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if (!$admin_id) {
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'Company Name: ' . getCompanyNameBySid($company_sid);
        // get complyNet companies Data
        $this->data['allEmployees'] =  $this->complynet_model->get_active_employees_detail($company_sid);


        $total_records = $this->complynet_model->employeeData($company_sid, null, null, true);
        $base_url = base_url('manage_admin/complynet/employee') . '/' . $company_sid;

        $page_number  = ($this->uri->segment(5)) ? $this->uri->segment(5) : 1;

        $offset = 0;
        $records_per_page = 10; // PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }

        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 5;
        $config['num_links'] = 4;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav>';
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

        $this->data['links'] = $this->pagination->create_links();
        $this->data['employeesData'] = $this->complynet_model->employeeData($company_sid, $records_per_page, $offset);

        //
        //    $this->data['companySid'] = explode(',', urldecode($company_sid));
        $this->data['Totalemployees'] = $total_records;
        $this->render('complynet/admin/employees');
    }






    //
    public function getcompanyemployees($company_id)
    {

        $employees = $this->complynet_model->get_active_employees_detail($company_id);
        $result_head = '<table class="table table-bordered table-hover table-striped table-condensed">
     <thead>
         <tr>
             <th class="col-xs-4">Employee Name</th>
             <th class="col-xs-2">Email</th>
         </tr>
     </thead>
     <thead>';

        //
        $result_row = '';
        if (!empty($employees)) {
            foreach ($employees as $emp_row) {

                $result_row .=  '<tr>                  
         <td>' . getUserNameBySID($emp_row['sid'], $remake = true) . '</td>
         <td>' . $emp_row['email'] . '</td>
     </tr>';
            }
        } else {

            $result_row .=  '<tr><td colspan="5" class="text-center"><div class="no-data">No employee found.</div></td> </tr>';
        }

        //
        $result_footer = '</thead>
     </table>';
        echo $result_head . $result_row . $result_footer;
    }
}
