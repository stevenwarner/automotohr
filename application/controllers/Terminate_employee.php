<?php defined('BASEPATH') or exit('No direct script access allowed');

class Terminate_employee extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('terminate_employee_model');
    }

    /**
     * Employee Status Listing Page
     * Created on: 28-05-2019
     *
     * @return VOID
     */
    function index($sid)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'), "refresh");
        } else {
            $data = employee_right_nav($sid);
            $session = $this->session->userdata('logged_in');
            $employer_detail = $session['employer_detail'];
            $employer_sid = $employer_detail['sid'];
            $employer_parent_sid = $employer_detail['parent_sid'];
            $access_level_plus = $employer_detail['access_level_plus'];
            $security_details = db_get_access_level_details($employer_sid);
            $exists = $this->terminate_employee_model->employee_exists($sid, $employer_parent_sid);

            if ($exists == 0 || !$access_level_plus) {
                $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                redirect('employee_management');
            }

            $data['employer_id'] = $employer_id = $data['session']['employer_detail']['sid'];
            $title = 'Employee Status Listing';
            $employer = $this->dashboard_model->get_company_detail($sid);
            $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
            $employee_status_records = $this->terminate_employee_model->get_employee_status_detail($sid);

            foreach ($employee_status_records as $key => $record) {
                $record_sid = $record['sid'];
                $attach_documents = $this->terminate_employee_model->get_terminated_employees_documents($sid, $record_sid);
                $employee_status_records[$key]['attach_documents'] = $attach_documents;
            }

            $data['id'] = $sid;
            $data['title'] = $title;
            $data['session'] = $session;
            $data['employer'] = $employer;
            $data['employer_sid'] = $employer_sid;
            $data['left_navigation'] = $left_navigation;
            $data['security_details'] = $security_details;
            $data['employee_status_records'] = $employee_status_records;

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('terminate_employee/index');
                $this->load->view('main/footer');
            }
        }
    }

    /**
     * Change Employee Status page
     * Created on: 28-05-2019
     *
     * @return VOID
     */
    function change_status($sid)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'), "refresh");
        } else {
            $data = employee_right_nav($sid);
            $session = $this->session->userdata('logged_in');
            $employer_detail = $session['employer_detail'];
            $employer_sid = $employer_detail['sid'];
            $employer_parent_sid = $employer_detail['parent_sid'];
            $access_level_plus = $employer_detail['access_level_plus'];
            $security_details = db_get_access_level_details($employer_sid);
            $exists = $this->terminate_employee_model->employee_exists($sid, $employer_parent_sid);

            if ($exists == 0 || !$access_level_plus) {
                $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                redirect('employee_management');
            }

            $title = 'Change Employee Status';
            $employer = $this->dashboard_model->get_company_detail($sid);
            $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';

            $data['id'] = $sid;
            $data['title'] = $title;
            $data['session'] = $session;
            $data['employer'] = $employer;
            $data['left_navigation'] = $left_navigation;
            $data['security_details'] = $security_details;

            $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
            $this->form_validation->set_rules('termination_details', 'Termination Details', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('terminate_employee/change_employee_status');
                $this->load->view('main/footer');
            } else {
                $status = $this->input->post('status');
                $termination_reason = $this->input->post('terminated_reason');
                $termination_date = $this->input->post('termination_date');
                $status_change_date = $this->input->post('status_change_date');
                $termination_details = $this->input->post('termination_details');
                $involuntary = isset($_POST['involuntary']) ? $_POST['involuntary'] : 0;
                $rehire = isset($_POST['rehire']) ? $_POST['rehire'] : 0;
                $system_access = isset($_POST['system_access']) ? $_POST['system_access'] : 0;

                $data_to_insert = array();
                $data_to_insert['employee_status'] = $status;
                $data_to_insert['termination_reason'] = empty($termination_reason) ? 0 : $termination_reason;
                if ($status == 1) {
                    $data_to_insert['termination_date'] = formatDateToDB($termination_date, 'm-d-Y'); //date('Y-m-d', strtotime($termination_date));
                }

                $data_to_insert['involuntary_termination'] = $involuntary;
                $data_to_insert['do_not_hire'] = $rehire;
                $data_to_insert['status_change_date'] = formatDateToDB($status_change_date, 'm-d-Y'); // date('Y-m-d', strtotime($status_change_date));
                $data_to_insert['details'] = htmlentities($termination_details);
                $data_to_insert['employee_sid'] = $sid;
                $data_to_insert['changed_by'] = $employer_sid;
                $data_to_insert['ip_address'] = getUserIP();
                $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


                $data_to_update = array();

                if ($status == 1) {
                    if ($system_access == 1) {
                        $data_to_update['active'] = 0;
                    } elseif (date('m-d-Y') >= $termination_date) {
                        $data_to_update['active'] = 0;
                    }
                    $data_to_update['terminated_status'] = 1;
                    $data_to_update['general_status'] = 'terminated';
                } else {
                    if ($status == 5) {
                        $data_to_update['active'] = 1;
                        $data_to_update['general_status'] = 'active';
                    } else if ($status == 6) {
                        $data_to_update['general_status'] = 'inactive';
                        $data_to_update['active'] = 0;
                    } else if ($status == 7) {
                        $data_to_update['general_status'] = 'leave';
                    } else if ($status == 4) {
                        $data_to_update['general_status'] = 'suspended';
                        $data_to_update['active'] = 0;
                    } else if ($status == 3) {
                        $data_to_update['general_status'] = 'deceased';
                        $data_to_update['active'] = 0;
                    } else if ($status == 2) {
                        $data_to_update['general_status'] = 'retired';
                        $data_to_update['active'] = 0;
                    } else if ($status == 8) {
                        $data_to_update['active'] = 1;
                        $data_to_update['general_status'] = 'rehired';
                        $data_to_update['rehire_date'] = $data_to_insert['status_change_date'];
                    }
                    $data_to_update['terminated_status'] = 0;
                }
                //
                $rowId = $this->terminate_employee_model->terminate_user($sid, $data_to_insert);
                //
                $this->load->model("v1/payroll_model", "payroll_model");
                //
                $companyPayrollStatus = $this->payroll_model->GetCompanyPayrollStatus($employer_parent_sid);
                $employeePayrollStatus = $this->payroll_model->checkEmployeePayrollStatus($sid, $employer_parent_sid);
                //
                if ($companyPayrollStatus && $employeePayrollStatus) {
                    if ($status == 1 || $status == 8) {
                        //
                        $effective_date = $data_to_insert['status_change_date'];
                        //
                        if ($status == 1) {
                            $effective_date = $data_to_insert['termination_date'];
                        }
                        //
                        $employeeData[] = [
                            'sid' => $rowId,
                            'effective_date' => $effective_date,
                            'employee_status' => $status
                        ];
                        //
                        $response = $this->payroll_model->syncEmployeeStatus(
                            $sid,
                            $employeeData
                        );
                        //
                        if (isset($response['errors'])) {
                            //
                            // Delete inserted record because gusto error
                            $this->terminate_employee_model->deleteEmployeeStatus($rowId);
                            //
                            $this->session->set_flashdata('message', '<b>Error:</b> '.$response['errors'][0]['message'].'!');
                            redirect(base_url('employee_status/' . $sid), 'refresh');
                        }
                    }
                }    
                //
                if ($status == 9) {
                    $data_transfer_log_update['to_company_sid'] = $employer_parent_sid;
                    $data_transfer_log_update['employee_copy_date'] = formatDateToDB($status_change_date, 'm-d-Y');
                    $this->terminate_employee_model->employees_transfer_log_update($sid, $data_transfer_log_update);

                    // Update the user table as well
                    $this->db->where('sid', $sid)->update('users', ['transfer_date' => formatDateToDB($status_change_date, 'm-d-Y', DB_DATE)]);
                    //
                    // ToDo if transfer then complynet status update pending
                }
                if ($status != 9) {
                    $this->terminate_employee_model->change_terminate_user_status($sid, $data_to_update);
                }
                //
                $employeeStatus = $data_to_update['active'] == 1 ? "active" : "deactive";
                changeComplynetEmployeeStatus($sid, $employeeStatus);
                //
                $this->session->set_flashdata('message', '<b>Success:</b> Status Updated Successfully!');
                redirect(base_url('employee_status/' . $sid), 'refresh');
            }
        }
    }

    function edit_status($sid, $status_id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'), "refresh");
        } else {
            $data = employee_right_nav($sid);
            $session = $this->session->userdata('logged_in');
            $employer_detail = $session['employer_detail'];
            $employer_sid = $employer_detail['sid'];
            $employer_parent_sid = $employer_detail['parent_sid'];
            $access_level_plus = $employer_detail['access_level_plus'];
            $security_details = db_get_access_level_details($employer_sid);
            $exists = $this->terminate_employee_model->employee_exists($sid, $employer_parent_sid);
            if ($exists == 0 || !$access_level_plus) {
                $this->session->set_flashdata('message', '<b>Error:</b> User not found!');
                redirect('employee_management');
            }

            $title = 'Change Employee Status';
            $employer = $this->dashboard_model->get_company_detail($sid);
            $status_data = $this->terminate_employee_model->get_status_by_id($status_id);
            $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
            if (!sizeof($status_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Record Not Found!');
                redirect('employee_status/' . $sid);
            }

            if ($status_data[0]['changed_by'] != 0) {
                if (!isPayrollOrPlus() && ($status_data[0]['changed_by'] != $employer_sid) ) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Un-Authorized!');
                    redirect('employee_status/' . $sid);
                }
            }

            $status_documents = $this->terminate_employee_model->get_status_documents($status_id);
            $data['id'] = $sid;
            $data['title'] = $title;
            $data['session'] = $session;
            $data['employer'] = $employer;
            $data['employer_sid'] = $employer_sid;
            $data['status_id'] = $status_id;
            $data['status_data'] = $status_data[0];
            $data['status_documents'] = $status_documents;
            $data['left_navigation'] = $left_navigation;
            $data['security_details'] = $security_details;

            $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
            $this->form_validation->set_rules('termination_details', 'Termination Details', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('terminate_employee/edit_employee_status');
                $this->load->view('main/footer');
            } else {

                $status = $this->input->post('status');
                $termination_reason = $this->input->post('terminated_reason');
                $termination_date = $this->input->post('termination_date');
                $status_change_date = $this->input->post('status_change_date');
                $termination_details = $this->input->post('termination_details');
                $involuntary = isset($_POST['involuntary']) ? $_POST['involuntary'] : 0;
                $rehire = isset($_POST['rehire']) ? $_POST['rehire'] : 0;
                $system_access = isset($_POST['system_access']) ? $_POST['system_access'] : 0;

                $data_to_insert = array();
                $data_to_insert['employee_status'] = $status;
                $data_to_insert['termination_reason'] = empty($termination_reason) ? 0 : $termination_reason;

                if ($status == 1) {
                    $data_to_insert['termination_date'] = formatDateToDB($termination_date, 'm-d-Y'); // date('Y-m-d', strtotime($termination_date));
                } else {
                    $data_to_insert['termination_date'] = NULL;
                }

                $data_to_insert['involuntary_termination'] = $involuntary;
                $data_to_insert['do_not_hire'] = $rehire;
                $data_to_insert['status_change_date'] = formatDateToDB($status_change_date, 'm-d-Y'); // date('Y-m-d', strtotime($status_change_date));
                $data_to_insert['details'] = htmlentities($termination_details);
                $data_to_insert['employee_sid'] = $sid;
                $data_to_insert['changed_by'] = $employer_sid;
                $data_to_insert['ip_address'] = getUserIP();
                $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $data_to_update = array();

                if ($status == 1) {
                    if ($system_access == 1) {
                        $data_to_update['active'] = 0;
                    } elseif (date('m-d-Y') >= $termination_date) {
                        $data_to_update['active'] = 0;
                    } else {
                        $data_to_update['active'] = 1;
                    }
                    $data_to_update['terminated_status'] = 1;
                    $data_to_update['general_status'] = 'terminated';
                } else {
                    if ($status == 5) {
                        $data_to_update['active'] = 1;
                        $data_to_update['general_status'] = 'active';
                    } else if ($status == 6) {
                        $data_to_update['active'] = 0;
                        $data_to_update['general_status'] = 'inactive';
                    } else if ($status == 7) {
                        $data_to_update['general_status'] = 'leave';
                    } else if ($status == 4) {
                        $data_to_update['active'] = 0;
                        $data_to_update['general_status'] = 'suspended';
                    } else if ($status == 3) {
                        $data_to_update['active'] = 0;
                        $data_to_update['general_status'] = 'deceased';
                    } else if ($status == 2) {
                        $data_to_update['active'] = 0;
                        $data_to_update['general_status'] = 'retired';
                    } else if ($status == 8) {
                        $data_to_update['active'] = 1;
                        $data_to_update['general_status'] = 'rehired';
                        $data_to_update['rehire_date'] = $data_to_insert['status_change_date'];
                    }
                    $data_to_update['terminated_status'] = 0;
                }
                //
                $this->load->model("v1/payroll_model", "payroll_model");
                //
                $companyPayrollStatus = $this->payroll_model->GetCompanyPayrollStatus($employer_parent_sid);
                $employeePayrollStatus = $this->payroll_model->checkEmployeePayrollStatus($sid, $employer_parent_sid);
                //
                if ($companyPayrollStatus && $employeePayrollStatus) {
                    if ($status == 1 || $status == 8) {
                        $old_effective_date = @unserialize($status_data['payroll_object'])['effective_date'];
                        $oldDate = strtotime($old_effective_date);
                        $newDate = strtotime($data_to_insert['status_change_date']);
                        $effectiveDate = $data_to_insert['status_change_date'];
                        //
                        if ($status == 1) {
                            $newDate = strtotime($data_to_insert['termination_date']);
                            $effectiveDate = $data_to_insert['termination_date'];
                        }
                        //
                        if ($oldDate != $newDate) {
                            $employeeData = [];
                            $employeeData['sid'] = $status_id;
                            $employeeData['effective_date'] = $effectiveDate;
                            $employeeData['version'] = $status_data[0]['payroll_version'];
                            $employeeData['employee_status'] = $status;
                            //
                            $response = $this->payroll_model->updateEmployeeStatusOnGusto(
                                $sid,
                                $employer_parent_sid,
                                $employeeData
                            );
                            //
                            if (isset($response['errors'])) {
                                $this->session->set_flashdata('message', '<b>Error:</b> '.$response['errors'][0]['message'].'!');
                                redirect(base_url('employee_status/' . $sid), 'refresh');
                            }
                        }
                    }
                }    
                //
                $this->terminate_employee_model->update_terminate_user($status_id, $data_to_insert);
                //
                if ($status == 9) {
                    $data_transfer_log_update['from_company_sid'] = 0;
                    $data_transfer_log_update['previous_employee_sid'] = 0;
                    $data_transfer_log_update['to_company_sid'] = $employer_parent_sid;
                    $data_transfer_log_update['employee_copy_date'] = formatDateToDB($status_change_date, 'm-d-Y');
                    $this->terminate_employee_model->employees_transfer_log_update($sid, $data_transfer_log_update);
                    // Update the user table as well
                    $this->db->where('sid', $sid)->update('users', ['transfer_date' => formatDateToDB($status_change_date, 'm-d-Y', DB_DATE)]);
                    // ToDo if transfer then complynet status update pending
                }
                // Check its current status then update in user primary data
                if ($this->terminate_employee_model->check_for_main_status_update($sid, $status_id)) {
                    //
                    if ($status != 9) {
                        $this->terminate_employee_model->change_terminate_user_status($sid, $data_to_update);
                        //
                        $employeeStatus = $data_to_update['active'] == 1 ? "active" : "deactive";
                        changeComplynetEmployeeStatus($sid, $employeeStatus);
                    }
                }
                //
                $this->session->set_flashdata('message', '<b>Success:</b> Status Updated Successfully!');
                redirect(base_url('employee_status/' . $sid), 'refresh');
            }
        }
    }

    public function ajax_handler()
    {
        if ($this->input->is_ajax_request()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $action = $this->input->post('perform_action');

            if ($action == 'file_upload') {
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $file = '0057-testing_uploaded_doc-58-AAH.docx';
                } else {
                    $file = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
                }

                if (!empty($file) && $file != 'error') {
                    $docs = array();
                    $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
                    $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
                    $docs['file_name'] = $_FILES["docs"]["name"];
                    $docs['file_type'] = $file_ext;
                    $docs['file_code'] = $file;
                    $docs['terminated_user_id'] = $this->input->post('id');
                    $docs['uploaded_date'] = date('Y-m-d H:i:s');
                    $docs['status'] = 0;
                    if (isset($_POST['record-id']) && !empty($_POST['record-id'])) {
                        $docs['terminated_record_sid'] = $this->input->post('record-id');
                        $docs['status'] = 1;
                    }
                    $this->terminate_employee_model->insert_termination_docs($docs);
                    echo 'success';
                } else {
                    echo 'error';
                }
            } elseif ($action == 'delete_file') {
                $sid = $this->input->post('id');
                $this->terminate_employee_model->delete_file($sid);
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }
}
