<?php defined('BASEPATH') or exit('No direct script access allowed');

class Time_off extends Public_Controller
{

    private $res = array();
    private $limit = 50;
    private $timeSlot = PTO_DEFAULT_SLOT;
    private $prefix;
    private $templates;
    private $theme;

    function __construct()
    {
        parent::__construct();
        //
        $this->load->helper('timeoff');
        //
        $this->load->model('timeoff_model', '', false, getTimeOffCompaniesForyearly());
        $this->res['Status'] = FALSE;
        $this->res['Redirect'] = TRUE;
        $this->res['Response'] = 'Invalid request';
        $this->res['Code'] = 'INVALIDREQUEST';
        $this->load->library('user_agent');

        $this->prefix = $this->agent->is_mobile() ? 'm_' : '';

        //
        if ($this->router->fetch_method() != 'handler') {
            // Check if exists session
            if ($this->session->userdata('logged_in') && isset($this->session->userdata('logged_in')['company_detail'])) {
                // $this->timeoff_model->insertTimeOffSettings(
                //     $this->session->userdata('logged_in')['company_detail']['sid'],
                //     $this->session->userdata('logged_in')['employer_detail']['sid']
                // );
            }
            //
            $this->templates = $templates = [
                [
                    'title' => 'The entitled employee(s) will have 12 days each year.',
                    'value' => '1',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 12 days each year that can be availed between Jan and Jun.',
                    'value' => '2',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 12 days each year that can be availed between July and Dec.',
                    'value' => '3',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 1 day each month.',
                    'value' => '4',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 1 day each month that can be availed between Jan and Jun.',
                    'value' => '5',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 1 day each month that can be availed between Jun and Dec.',
                    'value' => '6',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 4 days every 4 months.',
                    'value' => '7',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 4 days every 4 months that can be availed between Jan and Jun.',
                    'value' => '8',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 4 days every 4 months that can be availed between Jun and Dec.',
                    'value' => '9',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 2 hours each month.',
                    'value' => '10',
                    'show' => true
                ],
                [
                    'title' => 'The entitled employee(s) will have 2 hours each month that can be availed between 01 and 15.',
                    'value' => '11',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 2 hours each month that can be availed between 16 and 30.',
                    'value' => '12',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 3.33 hours each month.',
                    'value' => '13',
                    'show' => true
                ],
                [
                    'title' => 'The entitled employee(s) will have 3.33 hours each month that can be availed between 01 and 15.',
                    'value' => '14',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 3.33 hours each month that can be availed between 16 and 30.',
                    'value' => '15',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 8 hours in every 4 months.',
                    'value' => '16',
                    'show' => true
                ],
                [
                    'title' => 'The entitled employee(s) will have 8 hours in every 4 months between 01 and 15.',
                    'value' => '17',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 8 hours in every 4 months between 16 and 30.',
                    'value' => '18',
                    'show' => false
                ],
                [
                    'title' => 'The entitled employee(s) will have 40 hours per year that can be accrued between 1 and 30.',
                    'value' => '19',
                    'show' => true
                ]
            ];
        }

        // Created By: Aleem Shaukat
        // Created On: 03-02-2021
        $this->theme = $this->timeoff_model->getCompanyTheme(
            $this->session->userdata('logged_in')['company_detail']['sid']
        );
    }


    function get_time_with_format($minutes, $defaultTimeFrame, $slug)
    {
        $text = get_array_from_minutes($minutes, $defaultTimeFrame, $slug);
        echo json_encode($text);
    }

    /**
     * Plan handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function plans($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        if ($data['is_super_admin'] == 0 && $data['session']['employer_detail']['pay_plan_flag'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'TimeOff Plans';
        $data['planSid'] = $id;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/plans');
        $this->load->view('main/footer');
    }

    /**
     * Create PTO
     *
     */
    function create_timeoff()
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['create_time_off'] == 0) redirect('dashboard', 'refresh');
        //
        $data['page'] = 'create';
        $data['title'] = 'Create a Time Off';
        $data['TeamMembers'] = isset($_SESSION['TeamMembers']) ? $_SESSION['TeamMembers'] : false;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/create_timeoff');
        $this->load->view('main/footer');
    }

    /**
     * Types handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function types($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_type'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Time Off Types';
        $data['planSid'] = $id;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/types');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_types');
        }

        $this->load->view('main/footer');
    }

    /**
     * Holidays handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function holidays($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['company_holiday'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Time Off Holidays';
        $data['holidaySid'] = $id;
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //

        //
        $this->timeoff_model->setHolidays($data['session']['company_detail']['sid']);


        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/holidays');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_holidays');
        }

        $this->load->view('main/footer');
    }

    /**
     * Policy traffic handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function policies($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_policies'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Time-off Policies';
        $data['policySid'] = $id;
        $data['templates'] = $this->templates;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //
        $data['get_policy_item_info'] = get_policy_item_info();
        $data['employeesAccrualSettings'] = $this->timeoff_model->getEmployeeAccuralSettings($data['session']['company_detail']['sid']);
        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/policies');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_policies');
        }

        $this->load->view('main/footer');
    }

    /**
     * Policy Overwrites
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function policy_overwrite($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_policy_overwrite'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'TimeOff Policy Overwrites';
        $data['policySid'] = $id;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/policy-overwrite');
        $this->load->view('main/footer');
    }

    /**
     * Approvers
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function approvers($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_approver'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Time-off Approvers';
        $data['approverSid'] = $id;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/' . ($this->prefix) . 'approvers');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_approvers');
        }

        $this->load->view('main/footer');
    }

    /**
     * Requests
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function requests($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_request'] == 0) redirect('dashboard', 'refresh');
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'TimeOff Approvers';
        $data['requestId'] = $id;
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/' . ($this->prefix) . 'requests');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_requests');
        }

        $this->load->view('main/footer');
    }


    /**
     * Request Reports
     */
    function request_report()
    {
        $data = array();
        $this->check_login($data);
        $employer_detail = $data['session']['employer_detail'];
        $pto_user_access = get_pto_user_access($employer_detail['parent_sid'], $employer_detail['sid']);
        if ($pto_user_access['time_off_report'] == 0) redirect('dashboard', 'refresh');

        $data['page'] = 'view';
        $data['title'] = 'Time off Report';
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/request_report');
        $this->load->view('main/footer');
    }


    /**
     * Requests
     *
     * @param $token
     *
     */
    function action($token)
    {
        // $data = array();
        // //
        // $data['title'] = 'Time off action';
        // $page = 'action';
        // // Parse in-coming token
        // $this->load->library('encryption', null, 'enc');
        // //
        // $token = $this->enc->decrypt(
        //     str_replace('$a$b', '/', $token)
        // );
        // //
        // $a = $data['Params'] = paramsToArray(
        //     $token
        // );
        // // Fetch single request
        // $data['Request'] = $this->timeoff_model->getSingleRequest([
        //     'companySid' => $a['companyId'],
        //     'requestSid' => $a['id']
        // ]);
        // //
        // if (!$data['Request']) {
        //     redirect('show404');
        // }
        // $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $a['companyId']));
        // // Get employer data
        // $data['employerData'] = $this->timeoff_model->getEmployerData($a['employeeId']);
        // $data['companyData'] = $this->timeoff_model->getCompanyData($a['companyId']);
        // //
        // $data['company_template_header_footer'] = message_header_footer($a['companyId'], $this->timeoff_model->getCompanyName($a['companyId']));
        // // Check for confirmed / expired and completed
        // if ($data['Request']['Info']['status'] != 'pending') {
        //     $page = 'info';
        // } else if ($data['Request']['Info']['request_from_date'] <= date('Y-m-d')) {
        //     $data['Expired'] = true;
        //     $page = 'info';
        // }


        // $level = $data['Request']['Info']['level_at'];
        // $level = $level == 1 ? 'teamlead' : ($level == 2 ? 'supervisor' : 'approver');
        // $page = 'info';
        // // Check for employee level
        // foreach ($data['Request']['Assigned'] as $key => $value) {
        //     // _e($value['employee_sid'].' == '.$a['employeeId'].' && '.$value['role'].' == '.$level, true);
        //     if ($value['employee_sid'] == $a['employeeId'] && $value['role'] == $level) {
        //         $page = 'action';
        //         break;
        //     }
        // }

        // // Check if cancelled is active
        // if ($a['action'] == 'cancel' && !isset($data['Expired']) && $data['Request']['Info']['status'] != 'cancelled') {
        //     $this->timeoff_model->updateRequestColumn(
        //         $a['id'],
        //         array(
        //             'status' => 'cancelled'
        //         )
        //     );
        //     $data['AlertBox'] = 'Cancelled';
        //     $data['Request']['Info']['status'] = 'Cancelled';
        //     $data['Request']['History'] = array();
        //     $page = 'info';
        // } else if ($data['Request']['Info']['status'] == 'cancelled') {
        //     $data['Request']['Info']['status'] = 'Cancelled';
        //     $data['Request']['History'] = array();
        //     $page = 'info';
        // }

        // $this->load->view('timeoff/header', $data);
        // $this->load->view('timeoff/' . ($page) . '');
        // $this->load->view('timeoff/footer');

        $data = array();
        $this->check_login($data);
        //
        $company_id = $data['session']['company_detail']['sid'];
        $company_name = $data['session']['company_detail']['CompanyName'];
        $domain_name = $this->timeoff_model->getCompanyDomainName($company_id);
        $data['company_name'] = $company_name;
        $data['domain_name'] = $domain_name;

        $this->load->view('timeoff/header', $data);
        $this->load->view('timeoff/' . ($page) . '');
        $this->load->view('timeoff/footer');
    }

    /**
     * Time Off LMS
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function timeoff_lms($page = 'view', $id = NULL, $filter = NULL)
    {
        $data = array();
        $this->check_login($data);
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'TimeOff';
        $data['employee_sid'] = $data['employer_sid'];
        $data['employee_name'] = $data['employee_full_name'];
        // Get TimeOff format
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['employee'] = $data['session']['employer_detail'];
        $data['load_view'] = 'old';
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['is_approver'] = $this->timeoff_model->getEmployerApprovalStatus($data['employer_sid'], true);
        $data['approvers'] = $this->timeoff_model->getEmployeeApprovers($data['company_sid'], $data['employer_sid']);
        $data['theme'] = 2;
        //
        $this->load->view('timeoff/includes/on_boarding_header', $data);

        $this->load->view('timeoff/lms/new_index');

        $this->load->view('main/footer');
    }


    /**
     * Settings traffic handler
     */
    function settings()
    {
        $data = array();
        $this->check_login($data);
        if ($data['is_super_admin'] == 0 && $data['session']['employer_detail']['pay_plan_flag'] == 0) redirect('dashboard', 'refresh');
        //
        $data['page'] = 'Settings';
        $data['title'] = 'TimeOff Settings';
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;
        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/settings');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_settings');
        }

        $this->load->view('main/footer');
    }

    /**
     * Balance Sheets
     */
    function balance()
    {
        $data = array();
        $this->check_login($data);
        $pto_user_access = get_pto_user_access($data['company_sid'], $data['employer_sid']);
        if ($pto_user_access['time_off_balance'] == 0) redirect('dashboard', 'refresh');
        //
        $data['page'] = 'balance';
        $data['title'] = 'Time Off - Balance';
        $data['timeOffFormat'] = $this->timeoff_model->getTimeOffFormat($data['session']['company_detail']['sid']);
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));
        $data['theme'] = $this->theme;

        //
        $this->load->view('main/header', $data);

        if ($this->theme == 1) {
            $this->load->view('timeoff/' . ($this->prefix) . 'balance');
        } else if ($this->theme == 2) {
            $this->load->view('timeoff/new_balance');
        }

        // 
        $this->load->view('main/footer');
    }

    /**
     * Import Time off
     */
    function import()
    {
        $data = array();
        $this->check_login($data);
        if ($data['is_super_admin'] == 0 && $data['session']['employer_detail']['pay_plan_flag'] == 0) redirect('dashboard', 'refresh');
        //
        $data['page'] = 'Import Time Off';
        $data['title'] = 'TimeOff - Import';
        $data['theme'] = $this->theme;
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/import');
        $this->load->view('main/footer');
    }

    /**
     * Import historic time off
     * 
     */
    public function importHistoricTimeOff()
    {
        // Set default array
        $data = array();
        // Check for login session
        $this->check_login($data);
        // Only plus are allowed
        // if ($data['is_super_admin'] == 0 && $data['session']['employer_detail']['pay_plan_flag'] == 0) {
        //     return redirect('dashboard', 'refresh');
        // }
        // Set the page title
        $data['page'] = 'Import Historical Time Off';
        // Set the page title
        $data['title'] = 'Historical Time Off Import';
        // Set the theme to 2
        $data['theme'] = $this->theme;
        //
        $data['PageScripts'] = [
            'lodash/loadash.min',
            'alertifyjs/alertify.min',
            'mFileUploader/index',
            '1.0.1' => 'timeoff/js/import_historic'
        ];
        // Load the views
        $this->load->view('main/header', $data)
            ->view('timeoff/import_historical')
            ->view('main/footer');
    }

    /**
     * Verify employees and polcies
     */
    public function verifyEmployeeAndPolicies()
    {
        //
        $data = [];
        //
        $hasLogin = $this->check_login($data, true);
        //
        if (!$hasLogin) {
            return SendResponse(200, 'Session expired.');
        }
        //
        $post = $this->input->post(null, true);
        // Check employees
        $employees = $this->timeoff_model->getEmployeesByName(
            $post['employees'],
            $data['session']['company_detail']['sid']
        );
        //
        $response = [
            'employees' => [],
            'policies' => []
        ];
        //
        foreach ($post['employees'] as $employee) {
            //
            $response['employees'][$employee] = $employees[$employee] ?? 0;
        }
        // Check policies
        $policies = $this->timeoff_model->getCompanyPolicies(
            $post['policies'],
            $data['session']['company_detail']['sid']
        );
        //
        foreach ($post['policies'] as $policy) {
            //
            $response['policies'][$policy] = $policies[$policy] ?? 0;
        }

        return SendResponse(200, $response);
    }

    /**
     * Start the import process of
     * historical time offs
     *
     * @return json
     */
    public function importHistoricProcess()
    {
        //
        $data = [];
        //
        $hasLogin = $this->check_login($data, true);
        //
        if (!$hasLogin) {
            return SendResponse(200, 'Session expired.');
        }
        //
        $companyId = $data['session']['company_detail']['sid'];
        $employerId = $data['session']['employer_detail']['sid'];
        //
        $post = $this->input->post(null, true);
        //
        $timeoffs = $post['chunk'];
        $foundEmployees = $post['data']['employees'];
        $foundPolicies = $post['data']['policies'];
        //
        $holder = [
            'failed' => 0,
            'existed' => 0,
            'success' => 0
        ];
        //
        $dateTime = date('Y-m-d H:i:s', strtotime('now'));
        //
        foreach ($timeoffs as $timeoff) {
            //
            $approverSlug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($timeoff['approver_full_name'])));
            $employeeSlug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($timeoff['employee_first_name'] . $timeoff['employee_last_name'])));
            $policySlug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($timeoff['policy'])));
            //
            if (
                isset($foundEmployees[$employeeSlug])
                && isset($foundPolicies[$policySlug])
                && $timeoff['leave_from']
                && $timeoff['leave_to']
                && $timeoff['status']
            ) {
                //
                $approverId = !isset($foundEmployees[$approverSlug]) ? $this->session->userdata('logged_in')['employer_detail']['sid'] : $foundEmployees[$approverSlug];
                //
                if (!$approverId) {
                    $holder['failed']++;
                    continue;
                }
                //
                if (!in_array(strtolower($timeoff['status']), ['pending', 'approved', 'rejected', 'cancelled'])) {
                    $holder['failed']++;
                    continue;
                }
                //
                //
                $startDate = formatDateToDB($timeoff['leave_from'], SITE_DATE, DB_DATE);
                $endDate = formatDateToDB($timeoff['leave_to'], SITE_DATE, DB_DATE);
                $submittedDate = formatDateToDB($timeoff['submitted_date'], SITE_DATE, DB_DATE) . ' 00:00:00';
                // Set ids
                $employeeId = $foundEmployees[$employeeSlug];
                $policyId = $foundPolicies[$policySlug];
                // Check if the policy already exists
                $timeOffExists = $this->timeoff_model
                    ->checkTimeOffForSpecificEmployee(
                        $companyId,
                        $employeeId,
                        $policyId,
                        $startDate,
                        $endDate
                    );
                //
                if ($timeOffExists) {
                    $holder['existed']++;
                } else {
                    // Set the insert array
                    $ins = [];
                    $ins['company_sid'] = $companyId;
                    $ins['employee_sid'] = $employeeId;
                    $ins['timeoff_policy_sid'] = $policyId;
                    $ins['request_from_date'] = $startDate;
                    $ins['requested_time'] = $timeoff['requested_hours'] * 60;
                    $ins['request_to_date'] = $endDate;
                    $ins['status'] = $ins['level_status'] = strtolower($timeoff['status']);
                    $ins['creator_sid'] = $employerId;
                    $ins['approved_by'] = $approverId;
                    $ins['reason'] = $timeoff['employee_comment'];
                    $ins['level_at'] = 3;
                    $ins['created_at'] = $submittedDate;
                    $ins['updated_at'] = $dateTime;
                    $ins['timeoff_days'] = json_encode([
                        'totalTime' => $timeoff['requested_hours'] * 60,
                        'days' => getDatesBetweenDates($startDate, $endDate, $timeoff['requested_hours'])

                    ]);
                    // Insert the main time off
                    $insertId = $this->timeoff_model->insertTimeOff($ins);
                    //
                    if (!$insertId) {
                        $holder['failed']++;
                    } else {
                        // Insert the time off timeline
                        $ins = [];
                        $ins['request_sid'] = $insertId;
                        $ins['employee_sid'] = $approverId;
                        $ins['action'] = 'update';
                        $ins['note'] = json_encode([
                            'status' => strtolower($timeoff['status']),
                            'canApprove' => 1,
                            'details' => [
                                'startDate' => $startDate,
                                'endDate' => $endDate,
                                'time' => $timeoff['requested_hours'] * 60,
                                'policyId' => $policyId,
                                'policyTitle' => $this->timeoff_model->getPolicyNameById($policyId),
                            ],
                            'comment' => $timeoff['status_comment']
                        ]);
                        $ins['created_at'] = $dateTime;
                        $ins['updated_at'] = $dateTime;
                        $ins['is_moved'] = 0;
                        $ins['comment'] = $timeoff['status_comment'];
                        //
                        $this->db->insert('timeoff_request_timeline', $ins);
                        //
                        $holder['success']++;
                    }
                }
                //
                continue;
            }
            //
            $holder['failed']++;
        }
        //
        return SendResponse(
            200,
            $holder
        );
    }

    /**
     * Balance Sheets
     */
    function create_employee($sid)
    {
        $data = array();
        $data = employee_right_nav($sid);
        $this->check_login($data);
        if ($data['is_super_admin'] == 0 && $data['session']['employer_detail']['pay_plan_flag'] == 0) redirect('dashboard', 'refresh');
        //
        $this->load->model('dashboard_model');
        $this->load->model('application_tracking_system_model');
        //
        $data['page'] = 'Settings';
        $data['title'] = 'TimeOff - Balance';
        //
        $data['company_id'] = $company_id = $data['session']['company_detail']['sid'];
        $data['employer'] = $data['employer'] = $this->dashboard_model->get_company_detail($sid);
        $data['sid'] = $sid;

        // Added on: 04-07-2019
        if (empty($data['employer']['resume'])) { // check if reseme is uploaded
            $data['employer']['resume_link'] = "javascript:void(0);";
            $data['resume_link_title'] = "No Resume found!";
        } else {
            $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $data['employer']['resume'];
            $data['resume_link_title'] = $data['employer']['resume'];
        }

        if (empty($data['employer']['cover_letter'])) { // check if cover letter is uploaded
            $data['employer']["cover_link"] = "javascript:void(0)";
            $data['cover_letter_title'] = "No Cover Letter found!";
        } else {
            $data['employer']["cover_link"] = AWS_S3_BUCKET_URL . $data['employer']['cover_letter'];
            $data['cover_letter_title'] = $data['employer']['cover_letter'];
        }

        $data['holidayDates'] = $this->timeoff_model->getDistinctHolidayDates(array('companySid' => $data['session']['company_detail']['sid']));

        $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
        $addresses = $this->employee_model->get_company_addresses($company_id);
        $data['addresses'] = $addresses;
        $departments = $this->employee_model->get_all_departments($company_id);
        $data['departments'] = $departments;
        $data['is_new_calendar'] = 1;
        $data['employee_sid'] = $sid;
        $data['employee_name'] = $data['employer']['first_name'] . ' ' . $data['employer']['last_name'];
        $data['timeOffDays'] = $this->timeoff_model->getTimeOffDays($data['session']['company_detail']['sid']);
        $data['theme'] = 2;

        $this->load->view('main/header', $data);

        // if ($this->theme == 1) {
        //     $this->load->view('timeoff/employee/'.($this->prefix).'index');
        // } else if ($this->theme == 2) {
        $this->load->view('timeoff/employee/new_index');
        // }

        //$this->load->view('manage_employer/employee_profile_view');
        $this->load->view('main/footer');
        //
    }

    /**
     * Print Page
     */
    function print_document($documentType, $documentSid)
    {
        $data = array();
        $this->check_login($data);
        //
        $data['page'] = 'view';
        $data['title'] = 'Time off Print';
        // Fetch data by sid
        $fmla = $this->timeoff_model->getFMLADetailsBySid($documentSid);
        if (!sizeof($fmla)) {
            $this->res['Response'] = 'Failed to verify FMLA.';
            $this->resp();
        }
        // Form name
        $slug = json_decode($fmla['serialized_data'], true)['type'];
        //
        $data['FMLA'] = $fmla;
        $data['Slug'] = $slug;
        $fileName = $slug . 'pdf';
        //
        if ($fmla['document_type'] == 'generated') {
            // Generate view
            $this->load->view('timeoff/print_and_download', array(
                'FMLA' => $fmla,
                'Slug' => $slug,
                'pd' => 'print',
                'fileName' => $fileName
            ));;
        } else {
        }
    }

    /**
     * Download Page
     */
    function download($documentType, $documentSid)
    {
        $data = array();
        $this->check_login($data);
        //
        $data['page'] = 'view';
        $data['title'] = 'Time off Print';
        // Fetch data by sid
        $fmla = $this->timeoff_model->getFMLADetailsBySid($documentSid);
        if (!sizeof($fmla)) {
            $this->res['Response'] = 'Failed to verify FMLA.';
            $this->resp();
        }
        // Form name
        $slug = json_decode($fmla['serialized_data'], true)['type'];
        //
        $data['FMLA'] = $fmla;
        $data['Slug'] = $slug;
        $fileName = $slug . 'pdf';
        //
        if ($fmla['document_type'] == 'generated') {
            // Generate view
            $this->load->view('timeoff/print_and_download', array(
                'FMLA' => $fmla,
                'Slug' => $slug,
                'pd' => 'download',
                'fileName' => $fileName
            ));;
        } else {
        }
    }


    /**
     * Public Print and Download Page
     */
    function print_download(
        $action,
        $requestSid
    ) {
        // Get the request
        $request = $this->timeoff_model->getSingleRequestBySid(
            $requestSid
        );
        //
        if (isset($_POST) && sizeof($_POST)) {
            //
            $post = $_POST;
            //
            $filename = preg_replace('/\s+/', '_', $post['employeeName']);
            // Set Path
            $basePath = APPPATH . '../assets/timeoff/' . ($filename) . '/';
            //
            $pdfFile = str_replace('data:application/pdf;base64,', '', $post['file']);
            // Create folder
            if (!is_dir($basePath)) mkdir($basePath, 0777, true);
            //
            $h = fopen($basePath . 'timeoff.pdf', 'w');
            fwrite($h, base64_decode($pdfFile));
            fclose($h);
            // Check and get all uploaded documents
            if (sizeof($request['Attachments'])) {
                foreach ($request['Attachments'] as $attachment) {
                    if ($attachment['s3_filename'] == '' || $attachment['s3_filename'] == null) continue;
                    $tfile = downloadFileFromAWS(
                        $basePath . $attachment['s3_filename'],
                        AWS_S3_BUCKET_URL . $attachment['s3_filename']
                    );
                }
            }

            echo $filename;
            exit(0);
        }
        // If time off not found redirect to timeoff module
        if (!sizeof($request)) redirect('timeoff/request-report', 'refresh');
        //
        $this->load->view(
            'timeoff/public_pd',
            [
                'Request' => $request,
                'RequestSid' => $requestSid,
                'Action' => $action
            ]

        );
    }

    function download_file($zipname)
    {
        ini_set('memory_limit', '-1');
        //
        $zip_name = 'timeoff_' . (strtolower($zipname) . '_' . time()) . '.zip';
        //
        $basePath = APPPATH . '../assets/timeoff/' . ($zipname) . '/';
        //
        $fileName = ROOTPATH . 'assets/temp_files' . '/' . $zip_name;
        //
        $this->load->library('zip');
        $this->zip->read_dir(rtrim($basePath, '/'), false);
        $this->zip->archive($fileName);
        // deleteFolderWithFiles($basePath);
        $this->zip->download($zip_name);
    }

    function download_export_timeoff($filename)
    {
        ini_set('memory_limit', '-1');
        //
        $zip_name = 'timeoff_' . (date('Y_m_d_H_i_s')) . '.zip';
        //
        $basePath = FCPATH . 'temp_files/timeoff/' . ($filename) . '/';
        //
        $fileName = FCPATH . 'temp_files' . '/' . $zip_name;
        //
        $this->load->library('zip');
        $this->zip->read_dir(rtrim($basePath, '/'), false);
        $this->zip->archive($fileName);
        $this->zip->download($zip_name);
        // deleteFolderWithFiles($basePath);
    }


    // Export timeoff
    public function export()
    {
        $data = array();
        $this->check_login($data);
        //
        $data['page'] = 'view';
        $data['title'] = 'Export time off';
        $data['theme'] = $this->theme;
        //
        $data['allEmp'] = $this->timeoff_model->getCompanyEmployees($data['company_sid']);

        $this->load->view('main/header', $data);
        $this->load->view('timeoff/export');
        $this->load->view('main/footer');
    }


    /**
     * Report
     */
    public function report()
    {
        $data = array();
        $this->check_login($data);
        //
        if ($_GET) {
            // $filter_session = $this->session->flashdata($_GET['token']);
            $filter_session = $this->session->userdata($_GET['token']);

            $filter_employees = $filter_session['employees'] != 'null' ? explode(',', $filter_session['employees']) : 'all';
            $filter_departments =  $filter_session['departments'] != 'null' ? explode(',', $filter_session['departments']) : 'all';
            $filter_teams = $filter_session['teams'] != 'null' ? explode(',', $filter_session['teams']) : 'all';

            $filter_policy = $filter_session['policy'] != 'null' ? explode(',', $filter_session['policy']) : 'all';
            $start_date = $_GET['startDate'];
            $end_date  = $_GET['endDate'];
        } else {
            $start_date = date('m/01/Y');
            $end_date  = date('m/t/Y');
            $filter_employees = "all";
            $filter_departments = "all";
            $filter_teams = "all";
            $filter_policy = "all";
        }

        $employeeStatus = $this->input->get("employee_status", true) ?? 0;


        //
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;


        if (isset($_GET['startDate']) && !isset($_GET['includeStartandEndDate'])) {
            $start_date = '';
            $end_date  = '';
        }

        //
        $data['page'] = 'view';
        $data['title'] = 'Report::time-off';
        //
        $empTimeoff = $this->timeoff_model->getEmployeesWithTimeoffRequestNew($data['company_sid'], 'employees_only', $start_date, $end_date, $filter_policy);
        $timeoffRequests = $this->timeoff_model->getEmployeesWithTimeoffRequestNew($data['company_sid'], 'records_only', $start_date, $end_date, $filter_policy);
        $company_employees = $this->timeoff_model->getEmployeesWithDepartmentAndTeams($data['company_sid'], $filter_employees, $filter_departments, $filter_teams, $employeeStatus);
        //
        $access_level_plus = $data['session']['employer_detail']['access_level_plus'];
        $employee_sid = $data['session']['employer_detail']['sid'];
        //
        if ($access_level_plus == 0) {
            $getEmployeeDepartmentsTeams = $this->timeoff_model->getAssignDepartmentAndTeams($employee_sid);
            $data['assign_departments'] = $getEmployeeDepartmentsTeams['departments'];
            $data['assign_teams'] = $getEmployeeDepartmentsTeams['teams'];
            $data['assign_employees'] = $getEmployeeDepartmentsTeams['employees'];
        } else if ($access_level_plus == 1) {
            $company_employees_filter = $this->timeoff_model->getEmployeesWithDepartmentAndTeams(
                $data['company_sid'],
                "all",
                "all",
                "all",
                $employeeStatus
            );
            $data['assign_departments'] = $this->timeoff_model->get_all_departments($data['company_sid']);
            $data['assign_teams'] = $this->timeoff_model->get_all_teams($data['company_sid']);
            $data['assign_employees'] = array_column($company_employees_filter, 'sid');
        }


        $data['policies'] = $this->timeoff_model->get_all_policies($data['company_sid']);
        //
        foreach ($company_employees as $ekey => $employee) {
            if (!in_array($employee['sid'], $empTimeoff) || !in_array($employee['sid'], $data['assign_employees'])) {
                unset($company_employees[$ekey]);
            } else {
                //
                foreach ($timeoffRequests as $request) {
                    //
                    if ($employee['sid'] == $request['employee_sid']) {
                        $policy_sid = $request['timeoff_policy_sid'];
                        $request['policy_name'] = $this->timeoff_model->getEPolicyName($policy_sid);
                        //
                        $processRequest = splitTimeoffRequest($request);
                        //
                        if ($employee['active'] == 1) {
                            $company_employees[$ekey]['employeeStatus'] = 'Active';
                        } else {
                            if ($employee['terminated_status'] == 1) {
                                $company_employees[$ekey]['employeeStatus'] = 'Terminated';
                            } else {
                                $this->load->model('export_csv_model');
                                $company_employees[$ekey]['employeeStatus'] = $this->export_csv_model->get_employee_last_status_info($employee['sid']);

                                if ($company_employees[$ekey]['employeeStatus'] == 'Archived Employee') {
                                    $company_employees[$ekey]['employeeStatus'] = 'Archived';
                                }
                            }
                        }
                        //
                        if ($processRequest['type'] == 'multiple') {
                            //
                            foreach ($processRequest['requestData'] as $split) {
                                $company_employees[$ekey]['timeoffs'][] = $split;
                            }
                            //
                        } else {
                            $company_employees[$ekey]['timeoffs'][] = $processRequest['requestData'];
                        }
                    }
                }
            }
        }
        //
        $data['company_employees'] = $company_employees;

        $data['DT'] = $this->timeoff_model->getCompanyDepartmentsAndTeams($data['company_sid']);
        $data['theme'] = $this->theme;
        //

        $data['filter_employees'] = $filter_employees;
        $data['filter_departments'] = $filter_departments;
        $data['filter_teams'] = $filter_teams;
        //
        $this->load->view('main/header', $data);
        $this->load->view('timeoff/report');
        $this->load->view('main/footer');
    }

    public function generateFilterSession()
    {
        $session = $this->session->userdata('logged_in');
        $token = date('Y_m_d_H_i_s') . '_' . $session['company_detail']['sid'];
        // $this->session->set_flashdata($token, $_POST);
        $this->session->set_userdata($token, $_POST);
        $this->res['Response'] = 'Token generated.';
        $this->res['token'] = $token;
        $this->res['status'] = 'success';
        $this->resp();
        exit();
    }

    public function get_employee_status($employee_sid)
    {
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $access_level_plus = $session['employer_detail']['access_level_plus'];

        $allow_access = 'no';

        if ($access_level_plus == 0) {
            $getEmployeeDepartmentsTeams = $this->timeoff_model->getAssignDepartmentAndTeams($employee_sid);
            $assign_departments = $getEmployeeDepartmentsTeams['departments'];
            $assign_teams = $getEmployeeDepartmentsTeams['teams'];
            $assign_employees = $getEmployeeDepartmentsTeams['employees'];

            if (!empty($getEmployeeDepartmentsTeams['employees'])) {
                $allow_access = 'yes';
            }
        } else if ($access_level_plus == 1) {
            $company_employees_filter = $this->timeoff_model->getEmployeesWithDepartmentAndTeams($company_sid);
            $assign_departments = $this->timeoff_model->get_all_departments($company_sid);
            $assign_teams = $this->timeoff_model->get_all_teams($company_sid);
            $assign_employees = array_column($company_employees_filter, 'sid');
            $allow_access = 'yes';
        }

        if ($allow_access == 'yes') {

            $department_option = '';

            if (!empty($assign_departments)) {
                foreach ($assign_departments as $department) {
                    $department_option .= '<option value="' . $department . '">' . getDepartmentNameBySID($department) . '</option>';
                }
            }

            $this->res['department'] = $department_option;

            $team_option = '';

            if (!empty($assign_teams)) {
                foreach ($assign_teams as $team) {
                    $team_option .= '<option value="' . $team . '">' . getTeamNameBySID($team) . '</option>';
                }
            }

            $this->res['team'] = $team_option;

            $employees_option = '';

            if (!empty($assign_employees)) {
                foreach ($assign_employees as $employee) {
                    $employees_option .= '<option value="' . $employee . '">' . getUserNameBySID($employee) . '</option>';
                }
            }

            $this->res['employee'] = $employees_option;
        }

        $this->res['allow_access'] = $allow_access;
        $this->res['status'] = 'success';
        $this->resp();
        exit();
    }

    public function get_report($employee_sid)
    {
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $access_level_plus = $session['employer_detail']['access_level_plus'];

        if ($_GET) {
            $filter_employees = isset($_GET['employees']) ? explode(',', $_GET['employees']) : 'all';
            $filter_departments =  isset($_GET['departments']) ? explode(',', $_GET['departments']) : 'all';
            $filter_teams = isset($_GET['teams']) ? explode(',', $_GET['teams']) : 'all';

            $start_date = isset($_GET['startDate']) ? $_GET['startDate'] : date('m/01/Y');
            $end_date = isset($_GET['endDate']) ? $_GET['endDate'] : date('m/t/Y');
            $request_type = isset($_GET['request_type']) ? $_GET['request_type'] : 'my';
        } else {
            $start_date = date('m/01/Y');
            $end_date  = date('m/t/Y');
            $filter_employees = "all";
            $filter_departments = "all";
            $filter_teams = "all";
            $request_type = 'my';
        }
        //
        $data['page'] = 'view';
        $data['title'] = 'Report::time-off';
        //
        $empTimeoff = $this->timeoff_model->getEmployeesWithTimeoffRequest($company_sid, 'employees_only', $start_date, $end_date);
        $timeoffRequests = $this->timeoff_model->getEmployeesWithTimeoffRequest($company_sid, 'records_only', $start_date, $end_date);
        $company_employees = $this->timeoff_model->getEmployeesWithDepartmentAndTeams($company_sid, $filter_employees, $filter_departments, $filter_teams);
        //

        //
        if ($access_level_plus == 0) {
            $getEmployeeDepartmentsTeams = $this->timeoff_model->getAssignDepartmentAndTeams($employee_sid);
            $data['assign_departments'] = $getEmployeeDepartmentsTeams['departments'];
            $data['assign_teams'] = $getEmployeeDepartmentsTeams['teams'];
            $data['assign_employees'] = !empty($getEmployeeDepartmentsTeams['employees']) ? $getEmployeeDepartmentsTeams['employees'] : [$employee_sid];
        } else if ($access_level_plus == 1) {
            $company_employees_filter = $this->timeoff_model->getEmployeesWithDepartmentAndTeams($company_sid);
            $data['assign_departments'] = $this->timeoff_model->get_all_departments($company_sid);
            $data['assign_teams'] = $this->timeoff_model->get_all_teams($company_sid);
            $data['assign_employees'] = array_column($company_employees_filter, 'sid');
        }
        //
        $session_token = '';

        //
        foreach ($company_employees as $ekey => $employee) {
            if ($request_type == 'my') {
                if ($employee_sid != $employee['sid']) {
                    unset($company_employees[$ekey]);
                } else {
                    $check_timeoff_flag = 0;
                    foreach ($timeoffRequests as $tkey => $request) {
                        if ($employee['sid'] == $request['employee_sid']) {
                            $check_timeoff_flag = 1;
                            $policy_sid = $request['timeoff_policy_sid'];
                            $request['policy_name'] = $this->timeoff_model->getEPolicyName($policy_sid);
                            $company_employees[$ekey]['timeoffs'][$tkey] = $request;
                        } else {
                            // unset($company_employees[$ekey]);
                        }
                    }

                    if ($check_timeoff_flag == 0) {
                        $company_employees = array();
                    } else {
                        $filter_array['employees'] = array_column($company_employees, 'sid');
                        $token = date('Y_m_d_H_i_s') . '_' . $company_sid;
                        $this->session->set_userdata($token, $filter_array);
                        $session_token = $token;
                    }
                }
            } else {
                if (!in_array($employee['sid'], $empTimeoff) || !in_array($employee['sid'], $data['assign_employees'])) {
                    unset($company_employees[$ekey]);
                } else {
                    foreach ($timeoffRequests as $tkey => $request) {
                        if ($employee['sid'] == $request['employee_sid']) {
                            $policy_sid = $request['timeoff_policy_sid'];
                            $request['policy_name'] = $this->timeoff_model->getEPolicyName($policy_sid);
                            $company_employees[$ekey]['timeoffs'][$tkey] = $request;
                        }
                    }


                    // $filter_array['employees'] = !empty($filter_employees) && $filter_employees != 'all' ? $filter_employees : array_column($company_employees, 'sid');
                    $filter_array['employees'] = array_column($company_employees, 'sid');
                    $token = date('Y_m_d_H_i_s') . '_' . $company_sid;

                    $this->session->set_userdata($token, $filter_array['employees']);
                    $session_token = $token;
                }
            }
        }
        //

        // echo '<pre>';
        // echo print_r($data['assign_departments']);
        // print_r($company_employees);
        // die('stop');
        //
        $modal = '';
        if (!empty($company_employees)) {
            foreach ($company_employees as $emp) {
                $modal .= '<tr class="jsReportEmployeeRow" data-id="' . $emp["sid"] . '">';
                $modal .=    '<td>';
                $modal .=        '<strong>' . ucwords($emp["first_name"] . ' ' . $emp["last_name"]) . '</strong>';
                $modal .=            '<br />' . remakeEmployeeName($emp, false);
                $modal .=    '</td>';
                $modal .=    '<td class="td_setting">';
                if (!empty($emp['DepartmentIds'])) {
                    //
                    $t = '';
                    foreach ($emp['DepartmentIds'] as $v) {
                        $t .= getDepartmentNameBySID($v) . ', ';
                    }
                    //
                    $modal .=            rtrim($t, ', ');
                } else {
                    $modal .=            'N/A';
                }
                $modal .=    '</td>';
                $modal .=    '<td class="td_setting">';

                if (!empty($emp['TeamIds'])) {
                    //
                    $t = '';
                    foreach ($emp['TeamIds'] as $v) {
                        $t .= getTeamNameBySID($v) . ', ';
                    }
                    //
                    $modal .=            rtrim($t, ', ');
                } else {
                    $modal .=            'N/A';
                }
                $modal .=    '</td>';
                $modal .=    '<td class="td_setting">';
                $modal .=        '<span class="timeoff_count" data-status="hide" data-id="timeoff_' . $emp['sid'] . '" data-toggle="tooltip" data-placement="top" title="Click to see request!" style="cursor: pointer; text-decoration: underline; color: #3554DC; font-wight: 700;">';
                $count = count($emp['timeoffs']);
                $modal .=            $count . ' Request(s)';
                $modal .=        '</span>';
                $modal .=    '</td>';
                $modal .=    '<td class="td_setting">';
                $print_url = base_url('timeoff/report/print/' . $emp["sid"]) . '?start=' . $start_date . '&end=' . $end_date;
                $download_url = base_url('timeoff/report/download/' . $emp["sid"]) . '?start=' . $start_date . '&end=' . $end_date;
                $modal .=        '<a class="btn btn-success jsReportLink" style="margin-right: 5px;" target="_blank" href="' . $print_url . '">';
                $modal .=            '<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print';
                $modal .=        '</a>';
                $modal .=        '<a class="btn btn-success jsReportLink" target="_blank" href="' . $download_url . '">';
                $modal .=            '<i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download';
                $modal .=        '</a>';
                $modal .=    '</td>';
                $modal .= '</tr>';
                if (!empty($emp['timeoffs'])) {

                    $modal .=    '<tr class="timeoff_' . $emp["sid"] . ' subheader" style="display: none; background: #444444; color:#fff;">';
                    $modal .=       '<th style="font-size: 14px !important;">Policy</th>';
                    $modal .=        '<th style="font-size: 14px !important;">Time Taken</th>';
                    $modal .=        '<th style="font-size: 14px !important;">Start Date</th>';
                    $modal .=        '<th style="font-size: 14px !important;">End Date</th>';
                    $modal .=        '<th style="font-size: 14px !important;">Status</th>';
                    $modal .=    '</tr>';
                    foreach ($emp['timeoffs'] as $timeoff) {
                        $modal .=        '<tr class="timeoff_' . $emp['sid'] . '" style="display: none;">';
                        $modal .=            '<td>' . $timeoff['policy_name'] . '</td>';

                        $hours = floor($timeoff['requested_time'] / 60);
                        $hours = $hours . ' Hour(s)';

                        $modal .=            '<td>' . $hours . '</td>';
                        $modal .=            '<td>' . DateTime::createfromformat('Y-m-d', $timeoff['request_from_date'])->format('m/d/Y') . '</td>';
                        $modal .=            '<td>' . DateTime::createfromformat('Y-m-d', $timeoff['request_to_date'])->format('m/d/Y') . '</td>';
                        $modal .=            '<td>';
                        $status = $timeoff['status'];

                        if ($status == 'approved') {
                            $modal .=                    '<p class="text-success"><b>APPROVED</b></p>';
                        } else if ($status == 'rejected') {
                            $modal .=                   '<p class="text-danger"><b>REJECTED</b></p>';
                        } else if ($status == 'pending') {
                            $modal .=                    '<p class="text-warning"><b>PENDING</b></p>';
                        }
                        $modal .=           '</td>';
                        $modal .=        '</tr>';
                    }
                }
            }

            $this->res['main_action_button'] = 'yes';
        } else {
            $modal .= '<tr>';
            $modal .=    '<td style="text-align: center;" colspan="5">';
            $modal .=    'No Record Found';
            $modal .=    '</td>';
            $modal .= '</tr>';

            $this->res['main_action_button'] = 'no';
        }

        //
        $this->res['company_employees'] = $company_employees;
        //
        $this->res['start_date'] = $start_date;
        $this->res['end_date'] = $end_date;
        $this->res['filter_employees'] = $filter_employees;
        $this->res['filter_departments'] = $filter_departments;
        $this->res['filter_teams'] = $filter_teams;
        $this->res['modal'] = $modal;
        $this->res['session_token'] = $session_token;

        $this->res['data'] = $company_employees;
        $this->res['Response'] = 'Proceed.';
        $this->resp();
        exit();
    }


    /**
     * Print & Download Report
     */
    public function pd_report($action, $employeeId)
    {
        $data = array();
        $this->check_login($data);
        //
        $data['title'] = 'Report::time-off';
        $data['theme'] = $this->theme;

        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $filter_session = $this->session->userdata($_GET['token']);
        }

        if (isset($filter_session['policy']) && $filter_session['policy'] != 'null' && $filter_session['policy'] != '') {
            $filter_policy = explode(',', $filter_session['policy']);
        } else {
            $filter_policy = 'all';
        }


        if ($employeeId == '' || $employeeId == 'all') {
            $employeeIds = $filter_session['employees'] != 'null' ? explode(',', $filter_session['employees']) : 'all';
        } else {
            $employeeIds = strpos($employeeId, ',') !== false ? explode(',', $employeeId) : $employeeId;
        }


        $data['data'] = $this->timeoff_model->getEmployeesTimeOffNew(
            $data['company_sid'],
            $employeeIds,
            $this->input->get('start', true),
            $this->input->get('end', true),
            $filter_policy
        );
        //
        if ($data['data']) {
            foreach ($data['data'] as $key => $row) {
                //
                $employeeStatus = '';
                //
                if ($row['active'] == 1) {
                    $employeeStatus = 'Active';
                } else {
                    if ($row['terminated_status'] == 1) {
                        $employeeStatus = 'Terminated';
                    } else {
                        $this->load->model('export_csv_model');
                        $employeeStatus = $this->export_csv_model->get_employee_last_status_info($row['employeeId']);

                        if ($employeeStatus == 'Archived Employee') {
                            $employeeStatus = 'Archived';
                        }
                    }
                }
                //
                $data['data'][$key]['employeeStatus'] = $employeeStatus;
            }
        }
        //
        $this->load->view('timeoff/' . (strtolower(trim($action))) . '_report', $data);
    }


    /**
     * Public view
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function public_action($key)
    {
        //
        $args = timeoffDecryptLink($key);
        if ($args['typeSid'] == 'approve') {
            $args['typeSid'] = 'approved';
        } else if ($args['typeSid'] == 'reject') {
            $args['typeSid'] = 'rejected';
        } else if ($args['typeSid'] == 'cancel') {
            $args['typeSid'] = 'cancelled';
        }
        //
        $request = $this->timeoff_model->getRequestById($args['requestSid']);
        //
        $data['request'] = $request;
        $data['action'] = $args['typeSid'];
        //
        if ($_POST) {
            $request_for = $_POST['status'];
            switch ($request_for) {
                case "view":
                case "approved":
                    //
                    $in = [];
                    //
                    $in['level_status'] = 'approved';
                    //
                    $canApprove = $this->timeoff_model->getEmployerApprovalStatus($args['employerSid']);
                    if ($canApprove === 1) $in['status'] = 'approved';
                    //
                    $this->timeoff_model->updateTable($in, $request['sid'], 'timeoff_requests');
                    //
                    $in = [];
                    $in['request_sid'] = $request['sid'];
                    $in['employee_sid'] = $args['employerSid'];
                    $in['action'] = 'update';
                    $in['comment'] = $_POST['comment'];
                    $in['note'] = json_encode([
                        'status' => 'approved',
                        'canApprove' => $canApprove,
                        'comment' => $_POST['comment'],
                        'details' => [
                            'startDate' => $request['request_from_date'],
                            'endDate' => $request['request_to_date'],
                            'time' => $request['requested_time'],
                            'policyId' => $request['timeoff_policy_sid'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $request['timeoff_policy_sid'])['title']
                        ]
                    ]);
                    //
                    $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                    // Send email notifications

                    //  $this->sendNotifications($args['requestId'], 'approved');
                    $this->sendNotifications($args['requestSid'], 'approved');

                    $data['hf'] = message_header_footer_domain($request['company_sid'], ucwords($request['CompanyName']));
                    //
                    // $this->load->view('timeoff/partials/thankyou', $data);
                    $this->load->view('timeoff/thank_you');

                    break;
                case "rejected":
                    //
                    $in = [];
                    //
                    $in['level_status'] = 'rejected';
                    //
                    $canApprove = $this->timeoff_model->getEmployerApprovalStatus($args['employerSid']);
                    if ($canApprove === 1) $in['status'] = 'rejected';
                    //
                    $this->timeoff_model->updateTable($in, $request['sid'], 'timeoff_requests');
                    //
                    $in = [];
                    $in['request_sid'] = $request['sid'];
                    $in['employee_sid'] = $args['employerSid'];
                    $in['action'] = 'update';
                    $in['comment'] = $_POST['comment'];
                    $in['note'] = json_encode([
                        'status' => 'rejected',
                        'canApprove' => $canApprove,
                        'comment' => $_POST['comment'],
                        'details' => [
                            'startDate' => $request['request_from_date'],
                            'endDate' => $request['request_to_date'],
                            'time' => $request['requested_time'],
                            'policyId' => $request['timeoff_policy_sid'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $request['timeoff_policy_sid'])['title']
                        ]
                    ]);
                    //
                    $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                    // Send email notifications
                    $this->sendNotifications($args['requestSid'], 'rejected');
                    $data['hf'] = message_header_footer_domain($request['company_sid'], ucwords($request['CompanyName']));
                    //
                    // $this->load->view('timeoff/partials/thankyou', $data);
                    $this->load->view('timeoff/thank_you');

                    break;
                case "cancelled":
                    //
                    $in = [];
                    //
                    $in['level_status'] = 'cancelled';
                    //
                    $canApprove = $this->timeoff_model->getEmployerApprovalStatus($args['employerSid']);
                    if ($canApprove === 1 || $args['employerSid'] == $request['employee_sid']) $in['status'] = 'cancelled';
                    //
                    $this->timeoff_model->updateTable($in, $request['sid'], 'timeoff_requests');
                    //
                    $in = [];
                    $in['request_sid'] = $request['sid'];
                    $in['employee_sid'] = $args['employerSid'];
                    $in['action'] = 'update';
                    $in['comment'] = $_POST['comment'];
                    $in['note'] = json_encode([
                        'status' => 'cancel',
                        'canApprove' => $canApprove,
                        'comment' => $_POST['comment'],
                        'details' => [
                            'startDate' => $request['request_from_date'],
                            'endDate' => $request['request_to_date'],
                            'time' => $request['requested_time'],
                            'policyId' => $request['timeoff_policy_sid'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $request['timeoff_policy_sid'])['title']
                        ]
                    ]);
                    //
                    $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                    // Send email notifications
                    $this->sendNotifications($request['sid'], 'cancel');
                    $data['hf'] = message_header_footer_domain($request['company_sid'], ucwords($request['CompanyName']));
                    //
                    $this->load->view('timeoff/thank_you');

                    break;
            }
        } else {
            $employeeName = getUserNameBySID($args['employerSid']);
            $employee_info = get_employee_profile_info($args['employerSid']);
            //
            $approvers = $this->timeoff_model->getEmployeeApprovers($args['companySid'], $request['userId']);
            $policies = $this->timeoff_model->getEmployeePoliciesById($args['companySid'], $request['employee_sid']);
            //
            $data = array();
            $data['title']              = 'Time-off';
            $data['user_first_name']    = $employee_info['first_name'];
            $data['user_last_name']     = $employee_info['last_name'];
            $data['user_email']         = $employee_info['email'];
            $data['user_phone']         = isset($employee_info['PhoneNumber']) ? $employee_info['PhoneNumber'] : '';
            $data['user_picture']       = isset($employee_info['profile_picture']) ? $employee_info['profile_picture'] : '';
            $data['company_sid']        = $args['companySid'];
            $data['company_name']       = $args['companyName'];
            $data['companyName']        = $args['companyName'];
            $data['employeeName']       = $employeeName;
            $data['employerId']         = $args['employerSid'];
            $data['requestId']          = $args['requestSid'];
            $data['request']            = $request;
            $data['policies']           = $policies;
            $data['approvers']          = $approvers;
            $data['employee_sid']       = $request['employee_sid'];
            $data['varification_key']   = $key;
            $data['request_status']     = $args['typeSid'];
            //
            $data['allow_update'] = 'yes';
            //
            if ($args['typeSid'] == 'cancelled') {
                if ($request['request_from_date'] <= date('Y-m-d', strtotime('now')) || $request['status'] == $args['typeSid']) {
                    $data['allow_update'] = 'no';
                }
            }
            //
            if ($request['status'] == 'cancelled') {
                $data['allow_update'] = 'no';
            }
            //
            if ($args['employerSid'] == $request['employee_sid']) {
                $data['user_type']      = 'self';
            } else {
                $data['user_type']      = 'approvers';
            }
            //
            $this->load->view('onboarding/onboarding_public_header', $data);
            $this->load->view('timeoff/timeoff_public_link');
            $this->load->view('onboarding/onboarding_public_footer');
        }
    }


    /*
    *******************************************************************************************
     HELPING FUNCTIUONS
    *******************************************************************************************
    */

    function checkForEmployees($sid)
    {
        $this->timeoff_model->fetchRelatedEmployees($sid);
    }

    public function responseToEmail($publicKey = '', $status = 'Pending')
    {
        if (!empty($publicKey)) {
            $pto = $this->timeoff_model->verifyPublicKey($publicKey);
            if (!sizeof($pto)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Not Authorized');
                redirect(base_url('login'), 'refresh');
            } else {
                if ($status == 'app') {
                    $url_status = 'Approve';
                    $default_status = 'Approved';
                } elseif ($status == 'rej') {
                    $url_status = 'Reject';
                    $default_status = 'Rejected';
                } elseif ($status == 'can') {
                    $url_status = 'Cancel';
                    $default_status = 'Cancelled';
                } else {
                    $url_status = 'Change';
                }
                $CompanyDetails = $this->timeoff_model->getCompanyDetails($pto[0]['parent_sid']);
                $pto_format = $this->timeoff_model->fetchPtoFormat($CompanyDetails['timeoff_format_sid']);
                $policies = $this->timeoff_model->fetchAllPoliciesByRequesterId($pto[0]['parent_sid'], $pto[0]['requester_sid'], $pto[0]['pto_policy_sid']);
                $footprints = $this->timeoff_model->fetchAllFootPrints($pto[0]['sid']);
                $responses = $this->timeoff_model->fetchAllResponses($pto[0]['supervisor_table_sid']);
                $employeeDetail = $this->timeoff_model->fetchEmployeeDetail($pto[0]['requester_sid']);
                $pto[0]['status_change_flag'] = strtotime($pto[0]['request_date']) > strtotime(date('Y-m-d 00:00:00')) ? 1 : 0;
                $minutes = $employeeDetail[0]['user_shift_minutes'];
                $hours = $employeeDetail[0]['user_shift_hours'];
                if ($minutes) {
                    $hours += round($minutes / 60, 2);
                }
                $data = array();
                $data['pto'] = $pto;
                $data['pid'] = $pto[0]['updated_policy_sid'];
                $data['url_status'] = $url_status;
                $data['pto_format'] = $pto_format[0]['slug'];
                $data['default_slot'] = $hours;
                $data['default_status'] = $default_status;
                $data['CompanyDetails'] = json_encode($CompanyDetails);
                $data['policies'] = json_encode($policies);
                $data['footprints'] = json_encode($footprints);
                $data['responses'] = json_encode($responses);
                $data['company_template_header_footer'] = message_header_footer($pto[0]['parent_sid'], ucwords($CompanyDetails['CompanyName']));
                $this->load->view('paid_time_off/header', $data);
                $this->load->view('paid_time_off/event-page');
                $this->load->view('paid_time_off/footer');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> PTO Not Found');
            redirect(base_url('login'), 'refresh');
        }
    }

    public function responseToTlEmail($publicKey = '', $status = 'Pending')
    {
        if (!empty($publicKey)) {
            $pto = $this->timeoff_model->verifyEmployeePublicKey($publicKey);
            if (!sizeof($pto)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Not Authorized');
                redirect(base_url('login'), 'refresh');
            } else {
                $CompanyDetails = $this->timeoff_model->getCompanyDetails($pto[0]['parent_sid']);
                $pto_format = $this->timeoff_model->fetchPtoFormat($CompanyDetails['timeoff_format_sid']);
                $policy_name = $this->timeoff_model->fetchPolicyName($pto[0]['pto_policy_sid']);
                $responses = $this->timeoff_model->fetchAllResponses($pto[0]['supervisor_table_sid']);
                $pto[0]['status_change_flag'] = (strtotime($pto[0]['request_date']) > strtotime(date('Y-m-d 00:00:00')) && !$pto[0]['responded']) ? 1 : 0;

                // echo '<pre>';
                // print_r($pto);
                // print_r($responses);
                // die();
                $minutes = $pto[0]['user_shift_minutes'];
                $hours = $pto[0]['user_shift_hours'];
                if ($minutes) {
                    $hours += round($minutes / 60, 2);
                }
                if ($status == 'app') {
                    $status = 'accept';
                } elseif ($status == 'rej') {
                    $status = 'reject';
                } else {
                    $status = '';
                }
                $data = array();
                $data['pto'] = $pto;
                $data['pid'] = $pto[0]['pto_policy_sid'];
                $data['pto_format'] = $pto_format[0]['slug'];
                $data['default_slot'] = $hours;
                $data['CompanyDetails'] = json_encode($CompanyDetails);
                $data['responses'] = json_encode($responses);
                $data['policy_name'] = $policy_name;
                $data['status'] = $status;
                $data['company_template_header_footer'] = message_header_footer($pto[0]['parent_sid'], ucwords($CompanyDetails['CompanyName']));
                $this->load->view('paid_time_off/header', $data);
                $this->load->view('paid_time_off/employee_public_view');
                $this->load->view('paid_time_off/footer');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> PTO Not Found');
            redirect(base_url('login'), 'refresh');
        }
    }

    public function pto_email_handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        $formpost = $this->input->post(NULL, TRUE);
        // Check post size and action
        if (!sizeof($formpost) || !isset($formpost['action'])) $this->resp();

        if (isset($formpost['sub_action']) && $formpost['sub_action'] == 'admin') {   //Change Request Is Submitted From Admins(TL, Supervisor, Approvers etc)
            $update_data = array();
            $designation = $formpost['designation'];
            $previous = json_decode($formpost['previous']);
            $latest = json_decode($formpost['latest']);
            $supervisor_table_sid = $formpost['supervisor_table_sid'];
            $approval_level = $formpost['pto_approval_check'];
            // $request_date = $formpost['request_date'];
            $request_date = date_format(date_create_from_format('m-d-Y', $formpost['request_date']), 'Y-m-d');
            $pto_request_sid = $formpost['id'];
            $user_date = $formpost['user_date'];
            $requester_id = $formpost['requester_id'];
            $employeeDetails = $this->timeoff_model->fetchEmployeeDetail($requester_id);
            $update_data['status'] = $formpost['status'];
            $update_data['note'] = $formpost['notes'];
            $update_data['updated_policy_sid'] = $formpost['policy'];
            $update_data['approved_hours'] = $formpost['hours'];
            $update_data['allowed_hours'] = $formpost['allowed_hours'] == 'Infinite' ? 0 : $formpost['allowed_hours'];
            $update_data['is_infinite'] = $formpost['is_infinite'];
            $update_data['request_date'] = $request_date;
            $update_data['updated_flag'] = 1;
            $update_data['updated_at'] = date('Y-m-d H:i:s');
            $formpost['date'] = $request_date;
            $confirm_check = $formpost['confirm_check'];
            $this->timeoff_model->changePtoSupervisorStatus($supervisor_table_sid, $update_data); //Supervisors Table Update
            $body = $this->pto_email_body($employeeDetails, $formpost, $designation);
            $change_data = array(); // Track Changes if done for history tab
            if ($previous->date != $latest->date) {
                $change_data['date']['last_date'] = $previous->date;
                $change_data['date']['new_date']  = $latest->date;
            }
            if ($previous->hours != $latest->hours) {
                $change_data['hours']['last_hours'] = $previous->hours;
                $change_data['hours']['new_hours']  = $latest->hours;
            }
            if ($previous->policy != $latest->policy) {
                $pre_pol = $this->timeoff_model->fetchPolicyName($previous->policy);
                $lat_pol = $this->timeoff_model->fetchPolicyName($latest->policy);
                $pre_pol = sizeof($pre_pol) ? $pre_pol[0]['title'] : 'Undefined';
                $lat_pol = sizeof($lat_pol) ? $lat_pol[0]['title'] : 'Undefined';

                $change_data['policy']['last_policy'] = $pre_pol;
                $change_data['policy']['new_policy']  = $lat_pol;
            }
            if ($previous->status != $latest->status) {
                $change_data['status']['last_status'] = $previous->status;
                $change_data['status']['new_status']  = $latest->status;
            }
            if (trim($previous->note) != trim($latest->note)) {
                $change_data['note']['last_note'] = $previous->note;
                $change_data['note']['new_note']  = $latest->note;
            }
            if (sizeof($change_data)) {
                $history = array();
                $history['history'] = json_encode($change_data);
                $history['pto_sid'] = $pto_request_sid;
                $history['employer_sid'] = $formpost['assigned_to'];
                $history['pto_supervisor_sid'] = $supervisor_table_sid;
                $history['designation'] = $designation;
                $this->timeoff_model->trackHistory($history);
            }

            if ($designation == 'team_lead') {

                // Disable previous all emails links if any sent to confirm the request date
                $resp_update_data = array('responded' => 1);
                $this->timeoff_model->updateEmployeeSupervisorResponses($supervisor_table_sid, 'employee', $resp_update_data);
                if (!$confirm_check && $user_date != $request_date) {  // Confirmation required from requester if date is changed by team_lead and confirm check is disabled
                    // Send Email To User for confirmation of requested date
                    $public_key = generateRandomString(48);
                    $insertArray = array();
                    $insertArray['pto_request_assigned_supervisors_sid'] = $supervisor_table_sid;
                    $insertArray['pto_request_sid'] = $pto_request_sid;
                    $insertArray['request_date'] = $request_date;
                    $insertArray['request_hours']   = $formpost['hours'];
                    $insertArray['designation'] = 'employee';
                    $insertArray['policy_sid'] = $formpost['policy'];
                    $insertArray['public_key'] = $public_key;
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for employee response tracking
                    $insertArray['designation'] = 'team_lead';
                    $insertArray['public_key'] = '';
                    $insertArray['note'] = $formpost['notes'];
                    $insertArray['responded'] = 1;
                    $insertArray['updated_at'] = date('Y-m-d');
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for TL response tracking

                    $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));
                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($formpost['companyName']);
                    $replacement_array['company-name'] = ucwords($formpost['companyName']);
                    $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['subject'] = 'PTO Changed By Team Lead';
                    $replacement_array['pto_details'] = $body;
                    $this->setEmployeeEmailBtn($replacement_array, $public_key);
                    log_and_send_templated_email(NEW_PTO_REQUESTED, $employeeDetails[0]['email'], $replacement_array, $message_hf);

                    $reset_array = array('updated_flag' => 0);
                    $this->timeoff_model->resetAllPtoSupervisorStatus($pto_request_sid, $designation, $reset_array); // Reset All TL confirm flags to reconfirm date
                } else {
                    $this->timeoff_model->changePtoSupervisorStatus($supervisor_table_sid, array('updated_flag' => 1)); //Update Confirm Flag because it is confirmed from this TL

                    if ($approval_level) { //Check if all level Team leads are required to update PTO
                        //Here check for other TL response
                        $team_lead = $this->timeoff_model->fetchApproverOrLeads($requester_id);
                        $teamLeadUpdatedFlag = $this->timeoff_model->checkTeamLeadsActions($pto_request_sid, sizeof($team_lead), 'team_lead');
                        if ($teamLeadUpdatedFlag) {   //If All TLs updated and confirmed the PTO
                            $supervisors = $this->timeoff_model->fetchSupervisors($requester_id);
                            foreach ($supervisors as $lead) {
                                $public_key = generateRandomString(48);
                                $insertArray = array();
                                $insertArray['assigned_to'] = $lead['supervisor'];
                                $insertArray['pto_request_sid'] = $pto_request_sid;
                                $insertArray['designation'] = 'supervisor';
                                $insertArray['allowed_hours'] = $formpost['allowed_hours'];
                                $insertArray['is_infinite']   = $formpost['is_infinite'];
                                $insertArray['approved_hours'] = $formpost['hours'];
                                $insertArray['updated_policy_sid'] = $formpost['policy'];
                                $insertArray['public_key'] = $public_key;
                                $insertArray['request_date'] = $request_date;
                                $insertArray['employee_response_request_date'] = $request_date;
                                $this->timeoff_model->insertRequestSupervisor($insertArray);
                                // Send Email To Supervisors of PTO creator
                                $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));


                                $replacement_array = array();
                                $replacement_array['company_name'] = ucwords($formpost['companyName']);
                                $replacement_array['company-name'] = ucwords($formpost['companyName']);
                                $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                                $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                                $replacement_array['subject'] = 'New PTO Notification of Employee - ' . ucwords($employeeDetails[0]['full_name']);
                                $replacement_array['pto_details'] = $body;
                                $this->setEmailBtn($replacement_array, $public_key);

                                log_and_send_templated_email(NEW_PTO_REQUESTED, $lead['email'], $replacement_array, $message_hf);
                            }
                            $main_update_data = array();
                            $main_update_data['pto_level_at'] = 'supervisor';
                            $main_update_data['pto_policy_sid'] = $formpost['policy'];
                            $main_update_data['request_hours'] = $formpost['hours'];
                            $main_update_data['request_date'] = $request_date;
                            $main_update_data['allowed_hours'] = $formpost['allowed_hours'];
                            $main_update_data['is_infinite'] = $formpost['is_infinite'];
                            $main_update_data['note'] = $formpost['notes'];
                            $this->changePtoStatusMainTable($pto_request_sid, $main_update_data);
                        }
                    } else {
                        $supervisors = $this->timeoff_model->fetchSupervisors($requester_id);
                        foreach ($supervisors as $lead) {
                            $public_key = generateRandomString(48);
                            $insertArray = array();
                            $insertArray['assigned_to'] = $lead['supervisor'];
                            $insertArray['pto_request_sid'] = $pto_request_sid;
                            $insertArray['designation'] = 'supervisor';
                            $insertArray['allowed_hours'] = $formpost['allowed_hours'];
                            $insertArray['is_infinite']   = $formpost['is_infinite'];
                            $insertArray['approved_hours'] = $formpost['hours'];
                            $insertArray['updated_policy_sid'] = $formpost['policy'];
                            $insertArray['public_key'] = $public_key;
                            $insertArray['request_date'] = $request_date;
                            $insertArray['employee_response_request_date'] = $request_date;
                            $this->timeoff_model->insertRequestSupervisor($insertArray);
                            // Send Email To Supervisors of PTO creator
                            $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));


                            $replacement_array = array();
                            $replacement_array['company_name'] = ucwords($formpost['companyName']);
                            $replacement_array['company-name'] = ucwords($formpost['companyName']);
                            $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                            $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                            $replacement_array['subject'] = 'New PTO Notification of Employee - ' . ucwords($employeeDetails[0]['full_name']);
                            $replacement_array['pto_details'] = $body;
                            $this->setEmailBtn($replacement_array, $public_key);

                            log_and_send_templated_email(NEW_PTO_REQUESTED, $lead['email'], $replacement_array, $message_hf);
                        }
                        $main_update_data = array();
                        $main_update_data['pto_level_at'] = 'supervisor';
                        $main_update_data['pto_policy_sid'] = $formpost['policy'];
                        $main_update_data['request_hours'] = $formpost['hours'];
                        $main_update_data['request_date'] = $request_date;
                        $main_update_data['allowed_hours'] = $formpost['allowed_hours'];
                        $main_update_data['is_infinite'] = $formpost['is_infinite'];
                        $main_update_data['note'] = $formpost['notes'];
                        $this->changePtoStatusMainTable($pto_request_sid, $main_update_data);
                    }
                }
            } else if ($designation == 'supervisor') {
                // Disable previous all emails links if any sent to confirm the request date
                $resp_update_data = array('responded' => 1);
                $this->timeoff_model->updateEmployeeSupervisorResponses($supervisor_table_sid, 'employee', $resp_update_data);
                if (!$confirm_check && $user_date != $request_date) {  // Confirmation required from requester if date is changed by supervisor and confirm check is disabled
                    // Send Email To User for confirmation of requested date
                    $public_key = generateRandomString(48);
                    $insertArray = array();
                    $insertArray['pto_request_assigned_supervisors_sid'] = $supervisor_table_sid;
                    $insertArray['pto_request_sid'] = $pto_request_sid;
                    $insertArray['request_date'] = $request_date;
                    $insertArray['request_hours']   = $formpost['hours'];
                    $insertArray['designation'] = 'employee';
                    $insertArray['policy_sid'] = $formpost['policy'];
                    $insertArray['public_key'] = $public_key;
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for employee response tracking
                    $insertArray['designation'] = 'supervisor';
                    $insertArray['public_key'] = '';
                    $insertArray['note'] = $formpost['notes'];
                    $insertArray['responded'] = 1;
                    $insertArray['updated_at'] = date('Y-m-d');
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for Supervisor response tracking

                    $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));
                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($formpost['companyName']);
                    $replacement_array['company-name'] = ucwords($formpost['companyName']);
                    $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['subject'] = 'PTO Changed By Supervisor';
                    $replacement_array['pto_details'] = $body;
                    $this->setEmployeeEmailBtn($replacement_array, $public_key);
                    log_and_send_templated_email(NEW_PTO_REQUESTED, $employeeDetails[0]['email'], $replacement_array, $message_hf);

                    $reset_array = array('updated_flag' => 0);
                    $this->timeoff_model->resetAllPtoSupervisorStatus($pto_request_sid, $designation, $reset_array); // Reset All Supervisors confirm flags to reconfirm date
                } else {
                    $this->timeoff_model->changePtoSupervisorStatus($supervisor_table_sid, array('updated_flag' => 1)); //Update Confirm Flag because it is confirmed from this Supervisor

                    if ($approval_level) { //Check if all level Supervisors are required to update PTO
                        //Here check for other TL response
                        $team_lead = $this->timeoff_model->fetchSupervisors($requester_id);
                        $teamLeadUpdatedFlag = $this->timeoff_model->checkTeamLeadsActions($pto_request_sid, sizeof($team_lead), 'supervisor');
                        if ($teamLeadUpdatedFlag) {   //If All TLs updated and confirmed the PTO
                            $supervisors = $this->timeoff_model->fetchApprovers($requester_id);
                            foreach ($supervisors as $lead) {
                                $public_key = generateRandomString(48);
                                $insertArray = array();
                                $insertArray['assigned_to'] = $lead['user_sid'];
                                $insertArray['pto_request_sid'] = $pto_request_sid;
                                $insertArray['designation'] = 'approver';
                                $insertArray['allowed_hours'] = $formpost['allowed_hours'];
                                $insertArray['is_infinite']   = $formpost['is_infinite'];
                                $insertArray['approved_hours'] = $formpost['hours'];
                                $insertArray['updated_policy_sid'] = $formpost['policy'];
                                $insertArray['public_key'] = $public_key;
                                $this->timeoff_model->insertRequestSupervisor($insertArray);
                                // Send Email To Approver of PTO creator
                                $message_hf = message_header_footer_domain($formpost['company_sid'], $formpost['companyName']);


                                $replacement_array = array();
                                $replacement_array['company_name'] = ucwords($formpost['companyName']);
                                $replacement_array['company-name'] = ucwords($formpost['companyName']);
                                $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                                $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                                $replacement_array['subject'] = 'New PTO Notification of Employee - ' . ucwords($employeeDetails[0]['full_name']);
                                $replacement_array['pto_details'] = $body;
                                $this->setEmailBtn($replacement_array, $public_key);

                                log_and_send_templated_email(NEW_PTO_REQUESTED, $lead['email'], $replacement_array, $message_hf);
                            }
                            $main_update_data = array();
                            $main_update_data['pto_level_at'] = 'approver';
                            $main_update_data['pto_policy_sid'] = $formpost['policy'];
                            $main_update_data['request_hours'] = $formpost['hours'];
                            $main_update_data['request_date'] = $request_date;
                            $main_update_data['allowed_hours'] = $formpost['allowed_hours'];
                            $main_update_data['is_infinite'] = $formpost['is_infinite'];
                            $main_update_data['note'] = $formpost['notes'];
                            $this->changePtoStatusMainTable($pto_request_sid, $main_update_data);
                        }
                    } else {
                        $supervisors = $this->timeoff_model->fetchApprovers($requester_id);
                        foreach ($supervisors as $lead) {
                            $public_key = generateRandomString(48);
                            $insertArray = array();
                            $insertArray['assigned_to'] = $lead['user_sid'];
                            $insertArray['pto_request_sid'] = $pto_request_sid;
                            $insertArray['designation'] = 'approver';
                            $insertArray['allowed_hours'] = $formpost['allowed_hours'];
                            $insertArray['is_infinite']   = $formpost['is_infinite'];
                            $insertArray['approved_hours'] = $formpost['hours'];
                            $insertArray['updated_policy_sid'] = $formpost['policy'];
                            $insertArray['public_key'] = $public_key;
                            $insertArray['request_date'] = $request_date;
                            $insertArray['employee_response_request_date'] = $request_date;
                            $this->timeoff_model->insertRequestSupervisor($insertArray);
                            //    $tlDetails = $this->timeoff_model->fetchEmployeeDetail($lead['supervisor']);
                            // Send Email To Approver of PTO creator
                            $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));


                            $replacement_array = array();
                            $replacement_array['company_name'] = ucwords($formpost['companyName']);
                            $replacement_array['company-name'] = ucwords($formpost['companyName']);
                            $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                            $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                            $replacement_array['subject'] = 'New PTO Notification of Employee - ' . ucwords($employeeDetails[0]['full_name']);
                            $replacement_array['pto_details'] = $body;
                            $this->setEmailBtn($replacement_array, $public_key);

                            log_and_send_templated_email(NEW_PTO_REQUESTED, $lead['email'], $replacement_array, $message_hf);
                        }
                        $main_update_data = array();
                        $main_update_data['pto_level_at'] = 'approver';
                        $main_update_data['pto_policy_sid'] = $formpost['policy'];
                        $main_update_data['request_hours'] = $formpost['hours'];
                        $main_update_data['request_date'] = $request_date;
                        $main_update_data['allowed_hours'] = $formpost['allowed_hours'];
                        $main_update_data['is_infinite'] = $formpost['is_infinite'];
                        $main_update_data['note'] = $formpost['notes'];
                        $this->changePtoStatusMainTable($pto_request_sid, $main_update_data);
                    }
                }
            } else if ($designation == 'approver') {
                if (!$confirm_check && $user_date != $request_date) {  // Confirmation required from requester if date is changed by supervisor and confirm check is disabled
                    // Send Email To User for confirmation of requested date
                    $public_key = generateRandomString(48);
                    $insertArray = array();
                    $insertArray['pto_request_assigned_supervisors_sid'] = $supervisor_table_sid;
                    $insertArray['pto_request_sid'] = $pto_request_sid;
                    $insertArray['request_date'] = $request_date;
                    $insertArray['request_hours']   = $formpost['hours'];
                    $insertArray['designation'] = 'employee';
                    $insertArray['policy_sid'] = $formpost['policy'];
                    $insertArray['public_key'] = $public_key;
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for employee response tracking
                    $insertArray['designation'] = 'approver';
                    $insertArray['public_key'] = '';
                    $insertArray['note'] = $formpost['notes'];
                    $insertArray['responded'] = 1;
                    $insertArray['updated_at'] = date('Y-m-d');
                    $this->timeoff_model->insertEmployeeSupervisorResponses($insertArray); // Add data for Approver response tracking

                    $message_hf = message_header_footer_domain($formpost['company_sid'], ucwords($formpost['companyName']));
                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($formpost['companyName']);
                    $replacement_array['company-name'] = ucwords($formpost['companyName']);
                    $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
                    $replacement_array['subject'] = 'PTO Changed By Approver';
                    $replacement_array['pto_details'] = $body;
                    $this->setEmployeeEmailBtn($replacement_array, $public_key);
                    log_and_send_templated_email(NEW_PTO_REQUESTED, $employeeDetails[0]['email'], $replacement_array, $message_hf);

                    $reset_array = array('updated_flag' => 0);
                    $this->timeoff_model->resetAllPtoSupervisorStatus($pto_request_sid, $designation, $reset_array); // Reset All Supervisors confirm flags to reconfirm date
                } else {
                    $this->timeoff_model->changePtoSupervisorStatus($supervisor_table_sid, array('updated_flag' => 1));

                    $main_update_data = array();
                    $main_update_data['pto_level_at'] = 'approver';
                    $main_update_data['pto_policy_sid'] = $formpost['policy'];
                    $main_update_data['request_hours'] = $formpost['hours'];
                    $main_update_data['request_date'] = $request_date;
                    $main_update_data['allowed_hours'] = $formpost['allowed_hours'];
                    $main_update_data['is_infinite'] = $formpost['is_infinite'];
                    $main_update_data['note'] = $formpost['notes'];
                    $main_update_data['status'] = $formpost['status'];
                    $main_update_data['approved_by'] = $formpost['assigned_to'];
                    $this->changePtoStatusMainTable($pto_request_sid, $main_update_data);
                    $localSubject = 'Your PTO is Finalised!';
                    $localFromName = ucwords($formpost['companyName']);
                    $localMessageBody = $body;
                    //Email to Employee
                    log_and_sendEmail(
                        FROM_EMAIL_NOTIFICATIONS,
                        $employeeDetails[0]['email'],
                        $localSubject,
                        $localMessageBody,
                        $localFromName
                    );
                    $localSubject = ucwords($employeeDetails[0]['full_name']) . "'s PTO is Finalised";
                    //Email to All Approvers
                    $approvers = $this->timeoff_model->fetchApprovers($requester_id);
                    foreach ($approvers as $appr) {
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $appr['email'],
                            $localSubject,
                            $localMessageBody,
                            $localFromName
                        );
                    }
                    $supervisor = $this->timeoff_model->fetchSupervisors($requester_id);
                    //Email to All Supervisors
                    foreach ($supervisor as $appr) {
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $appr['email'],
                            $localSubject,
                            $localMessageBody,
                            $localFromName
                        );
                    }
                    $team_lead = $this->timeoff_model->fetchApproverOrLeads($requester_id);
                    //Email to All Team Leads
                    foreach ($team_lead as $appr) {
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $appr['email'],
                            $localSubject,
                            $localMessageBody,
                            $localFromName
                        );
                    }
                }
            }
        } else {
            $this->resp();
        }
    }

    public function pto_employee_email_handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        $formpost = $this->input->post(NULL, TRUE);
        // Check post size and action
        if (!sizeof($formpost) || !isset($formpost['action'])) $this->resp();
        $update_data = array();
        $requester_designation = $formpost['designation'];
        $request_date = date_format(date_create_from_format('m-d-Y', $formpost['request_date']), 'Y-m-d');
        $response_id  = $formpost['id'];
        $requester_id = $formpost['requester_id'];
        $assigned_to = $formpost['assigned_to'];

        $update_data['request_date'] = $request_date;
        $update_data['action_performed'] = $formpost['response'];
        $update_data['responded'] = 1;
        $update_data['updated_at'] = date('Y-m-d');
        $update_data['note'] = $formpost['note'];
        $supervisor_table_sid = $formpost['supervisor_table_sid'];
        $this->timeoff_model->updateEmployeeResponse($response_id, $update_data); //Response Table Update
        $this->timeoff_model->updateSupervisorsTableRecord($supervisor_table_sid, array('employee_response_request_date' => $request_date)); //Update the user's changed date in supervisor's table to check in status_change ajax whether supervisor changed the date or not
        $employeeDetails = $this->timeoff_model->fetchEmployeeDetail($requester_id);
        $CompanyDetails = $this->timeoff_model->getCompanyDetails($employeeDetails[0]['parent_sid']);
        $pto_format = $this->timeoff_model->fetchPtoFormat($CompanyDetails['timeoff_format_sid']);
        $SupervisorDetails = $this->timeoff_model->fetchSupervisorDetails($supervisor_table_sid);
        $minutes = $employeeDetails[0]['user_shift_minutes'];
        $hours = $employeeDetails[0]['user_shift_hours'];
        if ($minutes) {
            $hours += round($minutes / 60, 2);
        }
        $body = '<strong>PTO Details are following</strong><br /><br />';
        $body .= '<p><strong>Requester:</strong> ' . ($employeeDetails[0]['full_name']) . '</p>';
        $body .= '<p><strong>Requested Date:</strong> ' . (DateTime::createFromFormat('Y-m-d', $request_date)->format('M d Y, D')) . '</p>';
        $body .= '<p><strong>Requested Hours:</strong> ' . ($this->pto_formated_minutes($pto_format[0]['slug'], $hours, $SupervisorDetails['approved_hours'])) . ' hours</p>';
        $body .= '<p><strong>Response:</strong> ' . ucwords($formpost['response']) . '</p>';
        $body .= '<p><strong>Policy:</strong> ' . ucwords($SupervisorDetails['title']) . '</p>';
        $body .= '<p><strong>Note:</strong> ' . ($formpost['note']) . '</p>';
        $body .= '<br />';
        // Send Email To Team Leads of PTO creator
        $message_hf = message_header_footer_domain($CompanyDetails['sid'], $CompanyDetails['CompanyName']);


        $replacement_array = array();
        $replacement_array['company_name'] = ucwords($CompanyDetails['CompanyName']);
        $replacement_array['company-name'] = ucwords($CompanyDetails['CompanyName']);
        $replacement_array['applicant_name'] = $employeeDetails[0]['full_name'];
        $replacement_array['applicant-name'] = $employeeDetails[0]['full_name'];
        $replacement_array['subject'] = 'PTO Response of Employee - ' . ucwords($employeeDetails[0]['full_name']);
        $replacement_array['pto_details'] = $body;
        $this->setEmailBtn($replacement_array, $SupervisorDetails['public_key']);

        log_and_send_templated_email(NEW_PTO_REQUESTED, $SupervisorDetails['email'], $replacement_array, $message_hf);
    }

    public function requests_status($companyId, $request_sid, $request_type)
    {
        //
        if ($request_type == "approve") {
            //
            $checkAlreadyApproved = $this->timeoff_model->checkRequestAlreadyApproved($request_sid);
            //
            if ($checkAlreadyApproved['code'] == 2) {
                $result['Status'] = true;
                $result['message'] = $checkAlreadyApproved['message'];
                $result['code'] = $checkAlreadyApproved['code'];
                //
                header('Content-type: application/json');
                echo json_encode($result);
                exit(0);
            }
        }
        //
        $request_info = $this->timeoff_model->fetchRequestHistoryInfo($request_sid);
        //
        $result = array();
        //
        if (!empty($request_info) && $request_info['action'] == "update") {
            $note = json_decode($request_info['note'], true);
            $old_status = $note['status'];
            $old_comment = $note['comment'];
            $canApprove = $note['canApprove'];
            $desire_status = '';
            $approve_status = '';
            //

            if ($request_type == 'reject') {
                $desire_status = 'rejected';
            } else if ($request_type == 'approve') {
                $desire_status = 'approved';
            } else {
                $desire_status = $request_type;
            }

            if ($canApprove == 1) {
                $approve_status = '100% approver';
            } else if ($canApprove == 0) {
                $approve_status = '50% approver';
            }

            //
            if ($old_status != $desire_status) {
                $msg = '';
                $employee_name = getUserNameBySID($request_info['employee_sid']) . ' - ' . $approve_status;
                $date = date('M d, Y, D', strtotime($request_info['created_at']));



                if ($old_status == 'rejected') {
                    if (empty($old_comment)) {
                        $msg = '<div>This time off has been rejected by <b>' . $employee_name . '</b> on <b>' . $date . '</b><br><hr>Do you want to approve this time off?</div>';
                    } else {
                        $msg = '<div>This time off has been rejected by <b>' . $employee_name . '</b> on <b>' . $date . '</b><br><hr>"' . $old_comment . '"<br><hr>Do you want to approve this time off?</div>';
                    }
                } else if ($old_status == 'approved') {
                    if (empty($old_comment)) {
                        $msg = '<div>This time off has been approved by <b>' . $employee_name . '</b> on <b>' . $date . '</b><br><hr>Do you want to reject this time off?</div>';
                    } else {
                        $msg = '<div>This time off has been approved by <b>' . $employee_name . '</b> on <b>' . $date . '</b><br><hr>"' . $old_comment . '"<br><hr>Do you want to reject this time off?</div>';
                    }
                }

                $result['Status'] = true;
                $result['message'] = $msg;
                $result['code'] = 1;
            } else {
                $result['Status'] = false;
                $result['message'] = '';
            }
        } else {
            $result['Status'] = false;
            $result['message'] = '';
        }

        header('Content-type: application/json');
        echo json_encode($result);
        exit(0);
    }

    /*
    *******************************************************************************************
     AJAX HANDLER
    *******************************************************************************************
    */


    /**
     * AJAX request handler
     *
     * @accepts POST
     * 'action'
     *
     * @return JSON
     */
    function handler()
    {
        // Check for ajax request

        if (!$this->input->is_ajax_request()) $this->resp();
        ///
        $post = $this->input->post(NULL, TRUE);
        // Check post size and action


        if (!sizeof($post) || !isset($post['action'])) $this->resp();
        if (!isset($post['companyId']) || $post['companyId'] == '') $this->resp();
        if (!isset($post['employerId']) || $post['employerId'] == '') $this->resp();
        if (!isset($post['employeeId']) || $post['employeeId'] == '') $this->resp();

        $post['public'] = 0;
        // For expired session
        if ($post['public'] == 0 && empty($this->session->userdata('logged_in'))) {
            $this->res['Redirect'] = true;
            $this->res['Response'] = 'Your login session has expired.';
            $this->res['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }

        //
        $this->res['Redirect'] = FALSE;
        //

        switch (strtolower($post['action'])) {
                // Fetch company all policy types
            case 'get_company_types_list':
                //
                $types = $this->timeoff_model->getCompanyTypesList($post);
                //
                if (empty($types)) {
                    $this->res['Response'] = "We are unable to find policy types. Please, add the policy types from \"Types\" section.";
                    $this->res['Code'] = 'EMPTYPOLICYTYPES.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $types;
                $this->res['Status'] = true;
                $this->res['Count'] = 0;
                $this->res['Limit'] = 100;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Fetch company polcies
            case 'get_policy_list_by_company':
                // Get policies
                $policies = $this->timeoff_model->getPoliciesListByCompany($post['companyId'], 'all');
                //
                if (empty($policies)) {
                    $this->res['Response'] = 'We are unable to find policies. Please, add a policy from \"Policies\" section.';
                    $this->resp();
                }
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;

                // Fetch company policies
            case 'get_policies_by_company':
                // Get policies
                $policies = $this->timeoff_model->getPoliciesByCompany(
                    $post,
                    $post['page'],
                    $this->limit
                );
                //
                if (empty($policies)) {
                    $this->res['Response'] = 'We are unable to find policies. Please, use the "Add Policy" button to add a new policy.';
                    $this->resp();
                }
                //
                if ($post['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $policies['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $policies['Policies'];
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Fetch company all employees
            case 'get_company_employees':
                // Check if policy already exists for current company
                $employees = $this->timeoff_model->getCompanyEmployees(
                    $post['companyId'],
                    $post['employerId'],
                    $post["all"] ?? 0
                );

                if (!sizeof($employees)) {
                    $this->res['Response'] = 'We are unable to find employee(s). Please, add employee(s) from "Create employee" page.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $employees;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Fetch company all employees also executive admin
            case 'get_company_employees_for_approvers':
                // Check if policy already exists for current company
                $employees = $this->timeoff_model->getCompanyAllEmployees(
                    $post['companyId'],
                    $post['employerId']
                );

                if (!sizeof($employees)) {
                    $this->res['Response'] = 'We are unable to find employee(s). Please, add employee(s) from "Create employee" page.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $employees;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Change sort order of policies
            case 'update_sort_order':
                $func = 'updatePoliciesSort';
                //
                if ($post['type'] == 'plans') $func = 'updatePlansSort';
                else if ($post['type'] == 'policy_overwrite') $func = 'updatePolicyOverwriteSort';
                else if ($post['type'] == 'approvers') $func = 'updateApproversSort';
                else if ($post['type'] == 'categories') $func = 'updateTypeSort';
                else if ($post['type'] == 'holidays') $func = 'updateHolidaySort';
                //
                foreach ($post['sort'] as $key => $value) {
                    $this->timeoff_model->$func(array('id' =>  $key, 'sort' => $value));
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Create policy
            case "create_policy":
                // Check if policy name already exists
                $doPolicyExists = $this->timeoff_model->policyExists(
                    $post['companyId'],
                    $post['title']
                );
                //
                if ($doPolicyExists) {
                    $this->res['Response'] = "The policy title is already in use. Please, use a different policy title.";
                    $this->res['Code'] = 'POLICYEXISTS';
                    $this->resp();
                }
                // Set employees
                $entitledEmployees = NULL;
                //
                if (!is_array($post['entitledEmployees'])) {
                    $entitledEmployees = explode(',', $post['entitledEmployees']);
                } else $entitledEmployees = $post['entitledEmployees'];
                //
                if (in_array('all', $entitledEmployees)) $entitledEmployees = 'all';
                else $entitledEmployees = implode(',', $entitledEmployees);
                // Set Accruals
                $accruals = [];
                $accruals['method'] = $post['method'];
                $accruals['time'] = $post['time'];
                $accruals['frequency'] = $post['frequency'];
                $accruals['frequencyVal'] = $post['frequencyVal'];
                $accruals['rate'] = $post['rate'];
                $accruals['rateType'] = $post['rateType'];
                $accruals['applicableTime'] = $post['applicableTime'];
                $accruals['applicableTimeType'] = $post['applicableTimeType'];
                $accruals['carryOverCheck'] = $post['carryOverCheck'];
                $accruals['carryOverType'] = $post['carryOverType'];
                $accruals['carryOverVal'] = $post['carryOverVal'];
                $accruals['carryOverCycle'] = !empty($post['carryOverCycle']) ? $post['carryOverCycle'] : 0;
                $accruals['negativeBalanceCheck'] = $post['negativeBalanceCheck'];
                $accruals['negativeBalanceType'] = $post['negativeBalanceType'];
                $accruals['negativeBalanceVal'] = $post['negativeBalanceVal'];
                $accruals['applicableDate'] = $post['applicableDate'];
                $accruals['applicableDateType'] = $post['applicableDateType'];
                $accruals['resetDate'] = $post['resetDate'];
                $accruals['resetDateType'] = $post['resetDateType'];
                $accruals['newHireTime'] = $post['newHireTime'];
                $accruals['newHireTimeType'] = $post['newHireTimeType'];
                $accruals['newHireRate'] = $post['newHireRate'];
                $accruals['employeeTypes'] = $post['employeeTypes'];
                $accruals['plans'] = isset($post['plans']) ? $post['plans'] : [];
                $accruals['defaultFlow'] = $post['accuralDefaultFlow'];
                // Set policy insert array
                $in = [];
                //
                $in['company_sid'] = $post['companyId'];
                $in['type_sid'] = $post['type'];
                $in['creator_sid'] = $post['employerId'];
                $in['title'] = $post['title'];
                $in['assigned_employees'] = $entitledEmployees;
                $in['note'] = NULL;
                $in['is_default'] = 0;
                $in['for_admin'] = $post['approver'];
                $in['is_archived'] = $post['deactivate'];
                $in['is_included'] = $post['include'];
                $in['is_esst'] = $post['isESST'];
                $in['is_unlimited'] = $post['rate'] == 0 ? 1 : 0;
                $in['creator_type'] = 'employee';
                $in['status'] = 1;
                $in['sort_order'] = $post['order'];
                $in['fmla_range'] = NULL;
                $in['accruals'] = json_encode($accruals);
                $in['policy_start_date'] = $post['applicableDateType'] == 'customHireDate' ? formatDate($post['applicableDate'], 'm-d-Y', 'Y-m-d')  : NULL;
                $in['reset_policy'] = $in['accruals'];
                $in['off_days'] = implode(',', $post['offDays']);
                $in['is_entitled_employee'] = $post['isEntitledEmployees'];
                $in['policy_category_type'] = $post['policyCategory'];
                $in['allowed_approvers'] = $post['approver'] == 1 ? implode(',', $post['approverList']) : '';

                //
                $policyId = $this->timeoff_model->insertPolicy($in);
                //
                if (!$policyId) {
                    $this->res['Response'] = "Something went wrong while adding the policy. Please, try again in a few moments.";
                    $this->res['Code'] = "INSERTFAILED";
                    $this->resp();
                }
                // Lets save who created the policy
                $in = [];
                $in['policy_sid'] = $policyId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'create';
                $in['note'] = '{}';
                //
                $this->timeoff_model->insertPolicyHistory($in);
                //
                $this->res['Response'] = 'You have successfully added a policy with the title <b>"' . (stripcslashes($post['title'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Update policy
            case "update_policy":
                // Check if policy name already exists
                $doPolicyExists = $this->timeoff_model->policyExists(
                    $post['companyId'],
                    $post['title'],
                    $post['policyId']
                );
                //
                if ($doPolicyExists) {
                    $this->res['Response'] = "The policy title is already in use. Please, use a different policy title.";
                    $this->res['Code'] = 'POLICYEXISTS';
                    $this->resp();
                }
                // Get old row
                $oldPolicy = $this->timeoff_model->getSinglePolicyById($post['policyId']);
                // Set employees
                $entitledEmployees = NULL;
                //
                if (!is_array($post['entitledEmployees'])) {
                    $entitledEmployees = explode(',', $post['entitledEmployees']);
                } else $entitledEmployees = $post['entitledEmployees'];
                //
                if (in_array('all', $entitledEmployees)) $entitledEmployees = 'all';
                else $entitledEmployees = implode(',', $entitledEmployees);
                // Set Accruals

                $accruals = [];
                $accruals['method'] = $post['method'];
                $accruals['time'] = $post['time'];
                $accruals['frequency'] = $post['frequency'];
                $accruals['frequencyVal'] = $post['frequencyVal'];
                $accruals['rate'] = $post['rate'];
                $accruals['rateType'] = $post['rateType'];
                $accruals['applicableTime'] = $post['applicableTime'];
                $accruals['applicableTimeType'] = $post['applicableTimeType'];
                $accruals['carryOverCheck'] = $post['carryOverCheck'];
                $accruals['carryOverType'] = $post['carryOverType'];
                $accruals['carryOverVal'] = $post['carryOverVal'];
                $accruals['carryOverCycle'] = !empty($post['carryOverCycle']) ? $post['carryOverCycle'] : 0;
                $accruals['negativeBalanceCheck'] = $post['negativeBalanceCheck'];
                $accruals['negativeBalanceType'] = $post['negativeBalanceType'];
                $accruals['negativeBalanceVal'] = $post['negativeBalanceVal'];
                $accruals['applicableDate'] = $post['applicableDate'];
                $accruals['applicableDateType'] = $post['applicableDateType'];
                $accruals['resetDate'] = $post['resetDate'];
                $accruals['resetDateType'] = $post['resetDateType'];
                $accruals['newHireTime'] = $post['newHireTime'];
                $accruals['newHireTimeType'] = $post['newHireTimeType'];
                $accruals['newHireRate'] = $post['newHireRate'];
                $accruals['employeeTypes'] = $post['employeeTypes'];
                $accruals['plans'] = isset($post['plans']) ? $post['plans'] : [];
                $accruals['defaultFlow'] = $post['accuralDefaultFlow'];

                // Set policy insert array
                $up = [];
                //
                $up['type_sid'] = $post['type'];
                $up['title'] = $post['title'];
                $up['assigned_employees'] = $entitledEmployees;
                $up['for_admin'] = $post['approver'];
                $up['is_archived'] = $post['deactivate'];
                $up['is_included'] = $post['include'];
                $up['is_unlimited'] = $post['rate'] == 0 ? 1 : 0;
                $up['is_esst'] = $post['isESST'];
                $up['sort_order'] = $post['order'];
                $up['off_days'] = implode(',', $post['offDays']);
                $up['accruals'] = json_encode($accruals);
                $up['policy_start_date'] = $post['applicableDateType'] == 'customHireDate' ? formatDate($post['applicableDate'], 'm-d-Y', 'Y-m-d')  : NULL;

                $up['is_entitled_employee'] = $post['isEntitledEmployees'];
                $up['policy_category_type'] = $post['policy_category_type'];
                $up['allowed_approvers'] = $post['approver'] == 1 ? implode(',', $post['approverList']) : '';

                //
                $policyId = $post['policyId'];
                //
                $this->timeoff_model->updatePolicy($up, $policyId);
                // Lets save who created the policy
                $in = [];
                $in['policy_sid'] = $policyId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode($oldPolicy);
                //
                $this->timeoff_model->insertPolicyHistory($in);
                //
                $this->res['Response'] = 'You have successfully updated the policy with the title <b>"' . (stripcslashes($post['title'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Update reset policy
            case "update_reset_policy":
                // Get old row
                $oldPolicy = $this->timeoff_model->getSinglePolicyById($post['policyId'])['reset_policy'];
                // Set employees
                $entitledEmployees = NULL;
                //
                if (!is_array($post['entitledEmployees'])) {
                    $entitledEmployees = explode(',', $post['entitledEmployees']);
                } else $entitledEmployees = $post['entitledEmployees'];
                //
                if (in_array('all', $entitledEmployees)) $entitledEmployees = 'all';
                else $entitledEmployees = implode(',', $entitledEmployees);
                // Set Accruals
                $accruals = [];
                $accruals['method'] = $post['method'];
                $accruals['time'] = $post['time'];
                $accruals['frequency'] = $post['frequency'];
                $accruals['frequencyVal'] = $post['frequencyVal'];
                $accruals['rate'] = $post['rate'];
                $accruals['rateType'] = $post['rateType'];
                $accruals['applicableTime'] = $post['applicableTime'];
                $accruals['applicableTimeType'] = $post['applicableTimeType'];
                $accruals['carryOverCheck'] = $post['carryOverCheck'];
                $accruals['carryOverType'] = $post['carryOverType'];
                $accruals['carryOverVal'] = $post['carryOverVal'];
                $accruals['negativeBalanceCheck'] = $post['negativeBalanceCheck'];
                $accruals['negativeBalanceType'] = $post['negativeBalanceType'];
                $accruals['negativeBalanceVal'] = $post['negativeBalanceVal'];
                $accruals['applicableDate'] = $post['applicableDate'];
                $accruals['applicableDateType'] = $post['applicableDateType'];
                $accruals['resetDate'] = $post['resetDate'];
                $accruals['resetDateType'] = $post['resetDateType'];
                $accruals['newHireTime'] = $post['newHireTime'];
                $accruals['newHireTimeType'] = $post['newHireTimeType'];
                $accruals['newHireRate'] = $post['newHireRate'];
                $accruals['employeeTypes'] = $post['employeeTypes'];
                $accruals['plans'] = isset($post['plans']) ? $post['plans'] : [];
                // Set policy insert array
                $up = [];
                //
                $up['reset_policy'] = json_encode($accruals);
                //
                $policyId = $post['policyId'];
                //
                $this->timeoff_model->updatePolicy($up, $policyId);
                // Lets save who created the policy
                $in = [];
                $in['policy_sid'] = $policyId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['action_type'] = 'reset';
                $in['note'] = $oldPolicy;
                //
                $this->timeoff_model->insertPolicyHistory($in);
                //
                $this->res['Response'] = 'You have successfully updated the policy with the title <b>"' . (stripcslashes($post['title'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Deactivate policy
            case 'archive_company_policy':
                // 
                $this->timeoff_model->updateCompanyPolicy(
                    $post['policySid'],
                    array('is_archived' => 1)
                );
                //
                $in = [];
                $in['policy_sid'] = $post['policySid'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 0]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_policy_timeline');
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully deactivated the policy.';
                $this->resp();
                break;

                // Activate policy
            case 'activate_company_policy':
                // 
                $this->timeoff_model->updateCompanyPolicy(
                    $post['policySid'],
                    array('is_archived' => 0)
                );
                //
                $in = [];
                $in['policy_sid'] = $post['policySid'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 1]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_policy_timeline');
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully activated the policy.';
                $this->resp();
                break;

                // Get single policyby id
            case 'get_single_policy_by_id':
                // 
                $policy = $this->timeoff_model->getSinglePolicyById(
                    $post['policyId']
                );
                //
                if (empty($policy)) {
                    $this->res['Response'] = 'We are unable to find the requested policy.';
                    $this->res['Code'] = 'NOTFOUND';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policy;
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Get policy history
            case "get_policy_history":
                //
                $policyHistory = $this->timeoff_model->getPolicyHistory($post['policyId']);
                //
                if (empty($policyHistory)) {
                    $this->res['Response'] = 'We are unable to find history against this policy.';
                }
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Data'] = $policyHistory;
                $this->resp();
                break;

            case "get_allowed_employees":
                //
                $allowed_employees = $this->timeoff_model->getPolicyEmployees($post['policyId'], $post['companyId']);
                $policy_title = $this->timeoff_model->getPolicyTitleById($post['policyId']);
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['View'] = $this->load->view('timeoff/partials/policies/allowed_employees', [
                    'allowed_employees' => $allowed_employees,
                    'policy_title' => $policy_title
                ], true);
                //
                $this->resp();
                break;

            case "get_policy_request":
                //
                $company_employees = $this->timeoff_model->getEmployeesWithDepartmentAndTeams($post['companyId']);
                $policyRequests = $this->timeoff_model->getPolicyRequests($post['policyId']);
                $policies = $this->timeoff_model->getAllActivePolicies($post['companyId']);
                //
                if (empty($policyRequests)) {
                    $this->res['Response'] = 'We are unable to find requests against this policy.';
                }
                //
                $empTimeoff = array_column($policyRequests, 'employee_sid');
                //
                foreach ($company_employees as $ekey => $employee) {
                    if (!in_array($employee['sid'], $empTimeoff)) {
                        unset($company_employees[$ekey]);
                    } else {
                        //
                        foreach ($policyRequests as $request) {
                            //
                            if ($employee['sid'] == $request['employee_sid']) {
                                //
                                $processRequest = splitTimeoffRequest($request);
                                //
                                if ($processRequest['type'] == 'multiple') {
                                    //
                                    foreach ($processRequest['requestData'] as $split) {
                                        $company_employees[$ekey]['timeoffs'][] = $split;
                                    }
                                    //
                                } else {
                                    $company_employees[$ekey]['timeoffs'][] = $processRequest['requestData'];
                                }
                            }
                        }
                    }
                }
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['View'] = $this->load->view('timeoff/partials/policies/manage_policy', [
                    'company_employees' => $company_employees,
                    'company_policies' => $policies
                ], true);
                $this->resp();
                break;

            case "migrate_employee_requests_policy":
                //
                $employeeList = $post['Data'];
                //
                if (empty($employeeList)) {
                    $this->res['Response'] = 'We are unable to process your request.';
                    $this->resp();
                }
                //
                foreach ($employeeList as $employee) {
                    $oldPolicy = $employee['oldPolicyId'];
                    $newPolicy = $employee['newPolicyId'];
                    $employeeSid = $employee['employeeId'];
                    $this->timeoff_model->updateEmployeeRequestPolicy($employeeSid, $oldPolicy, $newPolicy);
                    //
                    $in = [];
                    $in['policy_sid'] = $newPolicy;
                    $in['employee_sid'] = $post['employerId'];
                    $in['action'] = 'update';
                    //
                    $note = [];
                    $note['title'] = $this->timeoff_model->getPolicyTitleById($oldPolicy);
                    $note['employee_sid'] = $employee['employeeId'];
                    $note['transferred'] = true;
                    //
                    $in['note'] = json_encode($note);
                    //
                    $this->timeoff_model->insertPolicyHistory($in);
                }
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Fetch company types
            case 'get_types_by_company':
                // Get types
                $types = $this->timeoff_model->getTypesListByCompany(
                    $post,
                    $post['page'],
                    $this->limit
                );
                //
                if (empty($types)) {
                    $this->res['Response'] = 'We are unable to find types. Please, use the "Add Type" button to add a new type.';
                    $this->resp();
                }
                //
                if ($post['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $types['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $types['Types'];
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Create type
            case "create_type":
                // Check type name
                $doTypeExists = $this->timeoff_model->typeExists(
                    $post['type'],
                    $post['companyId']
                );
                //
                if ($doTypeExists[1] > 0) {
                    $this->res['Response'] = "The title already in use. Please, use a different type title.";
                    $this->res['Code'] = 'TYPEEXISTS';
                    $this->resp();
                }
                // Insert in categories list 
                if ($doTypeExists[0] == 0) {
                    $categoryId = $this->timeoff_model->insertMainCategory($post['type']);
                } else $categoryId = $doTypeExists[0];

                //
                $in = [];
                //
                $in['company_sid'] = $post['companyId'];
                $in['timeoff_category_list_sid'] = $categoryId;
                $in['creator_sid'] = $post['employerId'];
                $in['status'] = 1;
                $in['is_archived'] = $post['deactivate'];
                $in['sort_order'] = 1;
                $in['category_type'] = $post['typeNew'];
                //
                $typeId = $this->timeoff_model->insertCategory($in);
                //
                if (!$typeId) {
                    $this->res['Response'] = "Something went wrong while adding the type. Please, try again in a few moments.";
                    $this->res['Code'] = "INSERTFAILED";
                    $this->resp();
                }
                //
                if (is_array($post['policies'])) {
                    $this->timeoff_model->updatePolicyTypes(
                        $typeId,
                        in_array('all', $post['policies']) ? 0 : $post['policies'],
                        $post['companyId']
                    );
                }
                // Lets save who created the type
                $in = [];
                $in['type_sid'] = $typeId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'create';
                $in['note'] = '{}';
                //
                $this->timeoff_model->insertTypeHistory($in);
                //
                $this->res['Response'] = 'You have successfully added the type with the title <b>"' . (stripcslashes($post['type'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Deactivate type
            case 'archive_company_type':
                // 
                $this->timeoff_model->updateCompanyType(
                    $post['typeId'],
                    array('is_archived' => 1)
                );
                //
                $in = [];
                $in['type_sid'] = $post['typeId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 0]);
                //
                $this->timeoff_model->insertTypeHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully deactivated the type.';
                $this->resp();
                break;

                // Activate type
            case 'activate_company_type':
                // 
                $this->timeoff_model->updateCompanyType(
                    $post['typeId'],
                    array('is_archived' => 0)
                );
                //
                $in = [];
                $in['type_sid'] = $post['typeId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 1]);
                //
                $this->timeoff_model->insertTypeHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully activated the type.';
                $this->resp();
                break;

                // Get type history
            case "get_type_history":
                //
                $typeHistory = $this->timeoff_model->getTypeHistory($post['typeId']);
                //
                if (empty($typeHistory)) {
                    $this->res['Response'] = 'We are unable to find history against this type.';
                }
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Data'] = $typeHistory;
                $this->resp();
                break;

                // Get single type
            case "get_single_type":
                //
                $type = $this->timeoff_model->getSingleType($post['typeId'], $post['companyId']);
                //
                if (empty($type)) {
                    $this->res['Response'] = 'We are unable to find the requested type.';
                    $this->res['Code'] = 'NOTFOUND';
                    $this->resp();
                }
                //
                $this->res['Data'] = $type;
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Update type
            case "update_type":
                // Check type name
                $doTypeExists = $this->timeoff_model->typeExists(
                    $post['type'],
                    $post['companyId'],
                    $post['typeId']
                );
                //
                if ($doTypeExists[1] > 0) {
                    $this->res['Response'] = "The type already in use. Please, use a different type title.";
                    $this->res['Code'] = 'TYPEEXISTS';
                    $this->resp();
                }
                // Insert in categories list 
                if ($doTypeExists[0] == 0) {
                    $categoryId = $this->timeoff_model->insertMainCategory($post['type']);
                } else $categoryId = $doTypeExists[0];

                //
                $up = [];
                //
                $up['timeoff_category_list_sid'] = $categoryId;
                $up['is_archived'] = $post['deactivate'];
                $up['category_type'] = $post['typeNew'];
                //
                $typeId = $post['typeId'];
                //
                $this->timeoff_model->updateCompanyType($typeId, $up);
                //
                if (!$typeId) {
                    $this->res['Response'] = "Something went wrong while updating the type. Please, try again in a few moments.";
                    $this->res['Code'] = "INSERTFAILED";
                    $this->resp();
                }
                //
                if (is_array($post['policies'])) {
                    $this->timeoff_model->updatePolicyTypes(
                        $typeId,
                        in_array('all', $post['policies']) ? 0 : $post['policies'],
                        $post['companyId']
                    );
                }
                // Lets save who created the type
                $in = [];
                $in['type_sid'] = $typeId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = '{}';
                //
                $this->timeoff_model->insertTypeHistory($in);
                //
                $this->res['Response'] = 'You have successfully updated the type with the title <b>"' . (stripcslashes($post['type'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Company holidays
            case "get_holidays_by_company":
                // Get holidays
                $holidays = $this->timeoff_model->getCompanyHolidays(
                    $post,
                    $post['page'],
                    $this->limit
                );
                //
                if (!sizeof($holidays)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any holidays yet.';
                    $this->resp();
                }
                //
                if ($post['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $holidays['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $holidays['Holidays'];
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                break;

                // Deactivate type
            case 'archive_company_holiday':
                // 
                $this->timeoff_model->updateCompanyHoliday(
                    array('is_archived' => 1),
                    $post['holidayId']
                );
                //
                $in = [];
                $in['holiday_sid'] = $post['holidayId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 0]);
                //
                $this->timeoff_model->inserttHolidayHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully deactivated the holiday.';
                $this->resp();
                break;

                // Activate holiday
            case 'activate_company_holiday':
                // 
                $this->timeoff_model->updateCompanyHoliday(
                    array('is_archived' => 0),
                    $post['holidayId']
                );
                //
                $in = [];
                $in['holiday_sid'] = $post['holidayId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 1]);
                //
                $this->timeoff_model->inserttHolidayHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully activated the holiday.';
                $this->resp();
                break;

                // Get holiday history
            case "get_holiday_history":
                //
                $holidayHistory = $this->timeoff_model->getHolidayHistory($post['holidayId']);
                //
                if (empty($holidayHistory)) {
                    $this->res['Response'] = 'We are unable to find history against this holiday.';
                }
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Data'] = $holidayHistory;
                $this->resp();
                break;

                // Get company all years
            case 'get_company_holiday_years':
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->res['Data'] = $this->timeoff_model->getCompanyDistinctHolidays($post);
                $this->resp();
                break;

                // Create holiday
            case 'create_holiday':
                // Check if holiday already exists
                $isExists = $this->timeoff_model->companyHolidayExists($post);
                //
                if ($isExists) {
                    $this->res['Response'] = 'A holiday with same name already exists.';
                    $this->res['Code'] = 'FOUND';
                    $this->resp();
                }

                $post['startDate'] = DateTime::createFromFormat('m-d-Y', $post['startDate'])->format('Y-m-d');
                $post['endDate'] = DateTime::createFromFormat('m-d-Y', $post['endDate'])->format('Y-m-d');

                //
                $holidayId = $this->timeoff_model->insertCompanyHoliday(array(
                    'company_sid' => $post['companyId'],
                    'creator_sid' => $post['employerId'],
                    'holiday_year' => $post['year'],
                    'holiday_title' => $post['holiday'],
                    'frequency' => 'yearly',
                    'icon' => $post['icon'],
                    'sort_order' => 1,
                    'from_date' => $post['startDate'],
                    'to_date' => $post['endDate'],
                    'is_archived' => $post['deactivate'],
                    'work_on_holiday' => $post['workOnHoliday']
                ));
                //
                if (!$holidayId) {
                    $this->res['Response'] = "Something went wrong while adding holiday. Please, try again in a few moments.";
                    $this->res['Code'] = "INSERTFAILED";
                    $this->resp();
                }
                //
                $in = [];
                $in['holiday_sid'] = $holidayId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'create';
                $in['note'] = json_encode(['is_archived' => 1]);
                //
                $this->timeoff_model->inserttHolidayHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'You have successfully created a new Holiday.';
                $this->res['InsertId'] = $holidayId;
                $this->resp();
                break;

                // Get single holiday
            case "get_single_holiday":
                // Check if holiday exists
                $holiday = $this->timeoff_model->getHolidayById(
                    $post['companyId'],
                    $post['holidayId']
                );
                //
                if (empty($holiday)) {
                    $this->res['Response'] = 'We are unable to find the requested holiday.';
                    $this->res['Code'] = 'NOTFOUND';
                    $this->resp();
                }
                //
                $this->res['Data'] = $holiday;
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                break;

                // Update holiday
            case 'update_holiday':
                // Check if holiday already exists
                $isExists = $this->timeoff_model->companyHolidayExists($post);
                //
                if ($isExists) {
                    $this->res['Response'] = 'A holiday with same name already exists.';
                    $this->res['Code'] = 'FOUND';
                    $this->resp();
                }
                // Get old holiday
                $holiday = $this->timeoff_model->getCurrentHolidayById($post['companyId'], $post['holidayId']);
                //
                $post['startDate'] = DateTime::createFromFormat('m-d-Y', $post['startDate'])->format('Y-m-d');
                $post['endDate'] = DateTime::createFromFormat('m-d-Y', $post['endDate'])->format('Y-m-d');
                //
                $holidayId = $post['holidayId'];
                //
                $this->timeoff_model->updateCompanyHoliday(array(
                    'company_sid' => $post['companyId'],
                    'creator_sid' => $post['employerId'],
                    'holiday_year' => $post['year'],
                    'holiday_title' => $post['holiday'],
                    'frequency' => 'yearly',
                    'icon' => $post['icon'],
                    'sort_order' => 1,
                    'from_date' => $post['startDate'],
                    'to_date' => $post['endDate'],
                    'is_archived' => $post['deactivate'],
                    'work_on_holiday' => $post['workOnHoliday']
                ), $post['holidayId']);
                //
                if (!$holidayId) {
                    $this->res['Response'] = "Something went wrong while updating holiday. Please, try again in a few moments.";
                    $this->res['Code'] = "INSERTFAILED";
                    $this->resp();
                }
                //
                $in = [];
                $in['holiday_sid'] = $holidayId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode($holiday);
                //
                $this->timeoff_model->inserttHolidayHistory($in);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'You have successfully updated the Holiday.';
                $this->res['InsertId'] = $holidayId;
                $this->resp();
                break;

                // Get company settings
            case 'get_settings_by_company':
                // Get plans
                $settings = $this->timeoff_model->getSettingsAndFormats($post['companyId'], true);
                //
                $this->res['Data'] = $settings;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                //
                $this->resp();
                break;

                // Company settings
            case 'edit_settings':
                //
                $settings = $this->timeoff_model->updateSettings($post);
                //
                if ($settings['settingId'] == 0) {
                    $this->res['Response'] = 'We are unable to update settings at this moment. Please, try again in a few moments.';
                    $this->resp();
                }
                //
                $in = [];
                $in['setting_sid'] = $settings['settingId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['update_employees'] = json_encode([
                    'employee' => $post['forAllEmployees'],
                    'time' => $post['defaultTimeslot']
                ]);
                $in['note'] = json_encode($settings['setting']);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_setting_timeline');
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully updated the settings.';
                $this->resp();
                break;

                // Get department & teams
            case 'get_company_departments_and_teams':
                //
                $dt = $this->timeoff_model->getCompanyDepartmentsAndTeams(
                    $post['companyId']
                );
                //
                $this->res['Data'] = $dt;
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Get approvers by company
            case 'get_approvers_by_company':
                // Get approvers
                $approvers = $this->timeoff_model->getApproversByCompany(
                    $post,
                    $post['page'],
                    $this->limit
                );
                //
                if (!sizeof($approvers)) {
                    $this->res['Response'] = 'We are unable to find any approvers. Please use the "Add Approver" button to add approvers.';
                    $this->resp();
                }
                //
                if ($post['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $approvers['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $approvers['Approvers'];
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Deactivate approver
            case 'archive_company_approver':
                // 
                $this->timeoff_model->updateTable(
                    array('is_archived' => 1),
                    $post['approverId'],
                    'timeoff_approvers'
                );
                //
                $in = [];
                $in['approver_sid'] = $post['approverId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 0]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_approver_timeline');
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully deactivated the approver.';
                $this->resp();
                break;

                // Activate approver
            case 'activate_company_approver':
                // 
                $this->timeoff_model->updateTable(
                    array('is_archived' => 0),
                    $post['approverId'],
                    'timeoff_approvers'
                );
                //
                $in = [];
                $in['approver_sid'] = $post['approverId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode(['is_archived' => 1]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_approver_timeline');
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully activated the approver.';
                $this->resp();
                break;

                // Get approver history
            case "get_approver_history":
                //
                $approverHistory = $this->timeoff_model->getHistory(
                    'approver_sid',
                    $post['approverId'],
                    'timeoff_approver_timeline'
                );
                //
                if (empty($approverHistory)) {
                    $this->res['Response'] = 'We are unable to find history against this approver.';
                }
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Data'] = $approverHistory;
                $this->resp();
                break;

                // Insert approver
            case "create_approver":
                // Check for approver
                $approverExists = $this->timeoff_model->companyApproverCheck($post);
                //
                if ($approverExists != 0) {
                    $this->res['Response'] = 'The employee already exists against the selcted department / team.';
                    $this->resp();
                }
                //
                $in = array();
                $in['employee_sid'] = $post['employee'][0];
                $in['company_sid'] = $post['companyId'];
                $in['creator_sid'] = $post['employeeId'];
                $in['is_department'] = $post['type'];
                $in['department_sid'] = in_array('all', $post['selectedEmployees']) ? 'all' : implode(',', $post['selectedEmployees']);
                $in['approver_percentage'] = $post['canApprove'];
                $in['is_archived'] = $post['deactivate'];
                $in['status'] = 1;
                //
                $approverId = $this->timeoff_model->insert($in, 'timeoff_approvers');
                //
                if (!$approverId) {
                    $this->res['Response'] = 'We are unable to add this approver. Please, try again in a few minutes.';
                    $this->resp();
                }
                //
                $in = [];
                $in['approver_sid'] = $approverId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'create';
                $in['note'] = '{}';
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_approver_timeline');
                //
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully added the approver.';
                $this->resp();
                break;

                // Get single approver
            case "get_single_approver":
                //
                $approver = $this->timeoff_model->getCompanyApprover($post);
                //
                if (!sizeof($approver)) {
                    $this->res['Response'] = 'We are unable to find this approver.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $approver;
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;

                // Update approver
            case "update_approver":
                //
                if (!is_array($post['employee'])) {
                    $t = $post['employee'];
                    $post['employee'] = [$t];
                }
                // Check for approver
                $approverExists = $this->timeoff_model->companyApproverCheck($post);
                //
                if ($approverExists != 0) {
                    $this->res['Response'] = 'The employee already exists against the selcted department / team.';
                    $this->resp();
                }
                //
                $up = array();
                $up['employee_sid'] = $post['employee'][0];
                $up['is_department'] = $post['type'];
                $up['department_sid'] = in_array('all', $post['selectedEmployees']) ? 'all' : implode(',', $post['selectedEmployees']);
                $up['approver_percentage'] = $post['canApprove'];
                $up['is_archived'] = $post['deactivate'];
                //
                $this->timeoff_model->updateTable($up, $post['approverId'], 'timeoff_approvers');
                //
                $in = [];
                $in['approver_sid'] = $post['approverId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['note'] = json_encode($this->timeoff_model->getAprroverById($post['approverId']));
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_approver_timeline');
                //
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'You have successfully updated the approver.';
                $this->resp();
                break;

                // Balances
            case "get_balances":
                //
                $balances = $this->timeoff_model->getBalanceSheet(
                    $post,
                    10
                );
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $balances;
                $this->resp();
                break;
                break;

                // EMOPLOYEE POLCIIES
            case "get_employee_policies":
                //
                $policies = $this->timeoff_model->getEmployeePoliciesById(
                    $post['companyId'],
                    $post['employeeId']
                );
                //

                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $policies;
                $this->resp();
                break;


                // EMOPLOYEE POLCIIES
            case "get_employee_policies_with_timeoffs":
                //
                $policies = $this->timeoff_model->getEmployeePoliciesByIdWithTimeoffs(
                    $post['companyId'],
                    $post['employeeId']
                );
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $policies;
                $this->resp();
                break;

                // EMOPLOYEE POLCIIES
            case "get_employee_policies_with_approvers":
                //
                $policies = $this->timeoff_model->getEmployeePoliciesWithApproversById(
                    $post['companyId'],
                    $post['employeeId']
                );
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $policies;
                $this->resp();
                break;
                break;

                // Get employee balance history
            case "get_employee_balance_history":
                //
                $history = $this->timeoff_model->getEmployeeBalanceHistory(
                    $post['companyId'],
                    $post['employeeId']
                );
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $history;
                $this->resp();
                break;
                break;

                // Add employee balances
            case 'add_employee_balance':
                //
                $data = $this->timeoff_model->addEmployeeBalance($post);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                $this->resp();
                break;

                // Get requests
                // TODO
            case "get_requests":
                //
                $data = $this->timeoff_model->getRequests($post);

                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;
                // Get requests
                // TODO
            case "get_employee_requests_by_status":
                //
                $data = $this->timeoff_model->getRequestsByStatus($post);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;
                //
            case "get_employee_approvers":
                //
                $data = $this->timeoff_model->getEmployeeApprovers($post['companyId'], $post['employeeId']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;

                // Get Modal
            case "get_modal":
                if ($post['formLMS'] == 0 && $this->theme == 1) {
                    echo $this->load->view('timeoff/partials/' . ($this->prefix . $post['type']) . '', [], true);
                } else {
                    echo $this->load->view('timeoff/partials/new_' . ($post['type']) . '', [], true);
                }
                exit;
                break;

                //
            case "get_employee_upcoming_timeoffs":
                //
                $data = $this->timeoff_model->getEmployeeUpcomingTimeoffs($post['companyId'], $post['employeeId']);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;

                // EMOPLOYEE POLCIIES
            case "get_employee_policies_by_date":
                //
                if (!isset($post['fromDate'])) $post['fromDate'] = '';
                //
                if (!empty($post['fromDate'])) {
                    $post['fromDate'] = DateTime::createfromformat('m/d/Y', $post['fromDate'])->format('Y-m-d');
                }
                //
                $policies = $this->timeoff_model->getEmployeePoliciesByDate(
                    $post['companyId'],
                    $post['employeeId'],
                    $post['fromDate']
                );
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $policies;
                $this->resp();
                break;
                break;

            case "check_timeoff_request":
                $request_from_date = DateTime::createfromformat('m/d/Y', $post['startDate'])->format('Y-m-d');
                $request_to_date = DateTime::createfromformat('m/d/Y', $post['endDate'])->format('Y-m-d');
                $response = $this->timeoff_model->checkEmployeeTimeoffRequestExist($post['employeeId'], $request_from_date, $request_to_date, $post['dateRows']);
                //
                $this->res['Response'] = $response;
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;

                // Create timeoff
            case "create_timeoff":
                //
                $in = [];
                $in['company_sid'] = $post['companyId'];
                $in['creator_sid'] = $post['employerId'];
                $in['employee_sid'] = $post['employeeId'];
                $in['timeoff_policy_sid'] = $post['policyId'];
                $in['requested_time'] = $post['dateRows']['totalTime'];
                $in['request_from_date'] = DateTime::createfromformat('m/d/Y', $post['startDate'])->format('Y-m-d');
                $in['request_to_date'] = DateTime::createfromformat('m/d/Y', $post['endDate'])->format('Y-m-d');
                $in['reason'] = $post['reason'];
                $in['timeoff_days'] = json_encode($post['dateRows']);
                //
                if ($post['fromAdmin'] == 1) {
                    $canApprove = $this->timeoff_model->getEmployerApprovalStatus($post['employerId']);
                    if ($canApprove === 1) $in['status'] = $post['status'];
                    else $in['level_status'] = $post['status'];
                } else {
                    $in['status'] = 'pending';
                }
                //
                $insertId = $this->timeoff_model->insertTimeOff($in);
                //
                if (!$insertId) {
                    $this->res['Response'] = 'We are unable to process this request at this moment. Please, try again in a few moments.';
                    $this->resp();
                }
                //
                $in = [];
                $in['request_sid'] = $insertId;
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'create';
                $in['comment'] = $post['comment'];
                if ($post['fromAdmin'] == 1) {
                    $in['note'] = json_encode([
                        'status' => $post['status'],
                        'canApprove' => $canApprove,
                        'comment' => $post['comment'],
                        'details' => [
                            'startDate' => $post['startDate'],
                            'endDate' => $post['endDate'],
                            'time' => $post['dateRows']['totalTime'],
                            'policyId' => $post['policyId'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $post['policyId'])['title']
                        ]
                    ]);
                } else {
                    $in['note'] = json_encode([
                        'status' => 'pending', 'comment' => $post['reason'],
                        'details' => [
                            'startDate' => $post['startDate'],
                            'endDate' => $post['endDate'],
                            'time' => $post['dateRows']['totalTime'],
                            'policyId' => $post['policyId'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $post['policyId'])['title']
                        ]
                    ]);
                }
                //
                if ($post['fromAdmin'] == 1) {
                    //
                    $in = [];
                    $in['request_sid'] = $insertId;
                    $in['employee_sid'] = $post['employerId'];
                    $in['action'] = 'update';
                    $in['comment'] = $post['comment'];
                    $in['note'] = json_encode([
                        'status' => $post['status'], 'canApprove' => $canApprove, 'comment' => $post['comment'],
                        'details' => [
                            'startDate' => $post['startDate'],
                            'endDate' => $post['endDate'],
                            'time' => $post['dateRows']['totalTime'],
                            'policyId' => $post['policyId'],
                            'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $post['policyId'])['title']
                        ]
                    ]);
                }
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                // Send email notifications
                if ($post['sendEmailNotification'] == 1 || $post['sendEmailNotification'] == 'yes') $this->sendNotifications($insertId, 'created');
                //
                $this->res['Response'] = 'You have succesfully created the time off.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;


                // Create timeoff
            case "update_timeoff":
                //
                $update_time = 'no';
                $update_date = 'no';
                //
                $old_request_data = $this->timeoff_model->getRequestById($post['requestId'], false);
                $old_start_date = date('Y-m-d', strtotime($old_request_data['request_from_date']));
                $old_end_date = date('Y-m-d', strtotime($old_request_data['request_to_date']));
                $old_request_time = $old_request_data['requested_time'];
                $new_start_date = DateTime::createfromformat('m/d/Y', $post['startDate'])->format('Y-m-d');
                $new_end_date = DateTime::createfromformat('m/d/Y', $post['endDate'])->format('Y-m-d');
                $new_request_time = $post['dateRows']['totalTime'];
                //
                if ($old_start_date != $new_start_date || $old_end_date != $new_end_date) {
                    $update_date = 'yes';
                }
                //
                if ($old_request_time != $new_request_time) {
                    $update_time = 'yes';
                }
                //
                $in = [];
                $in['timeoff_policy_sid'] = $post['policyId'];
                $in['requested_time'] = $post['dateRows']['totalTime'];
                $in['request_from_date'] = $new_start_date;
                $in['request_to_date'] = $new_end_date;
                $in['reason'] = $post['reason'];
                $in['timeoff_days'] = json_encode($post['dateRows']);
                //
                $canApprove = $this->timeoff_model->getEmployerApprovalStatus($post['employerId']);
                //
                if ($post['fromAdmin'] == 1) {
                    $in['level_status'] = $post['status'];
                    //

                    if ($canApprove === 1) $in['status'] = $post['status'];
                }
                //
                $send_email = 'yes';
                //
                if ($canApprove != 1 && ($post['status'] == "approved" || $post['status'] == "rejected")) {
                    $request = $this->timeoff_model->getRequestById($post['requestId']);
                    //
                    if ($request['status'] != 'pending') {
                        $this->sendEmailOtherApprovers($post);
                        $send_email = 'no';
                    }
                }
                //
                $this->timeoff_model->updateTable($in, $post['requestId'], 'timeoff_requests');
                //
                $in = [];
                $in['request_sid'] = $post['requestId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['comment'] = $post['comment'];
                if ($post['fromAdmin'] == 1) {
                    $note = array();
                    $note['status'] = $post['status'];
                    $note['canApprove'] = $canApprove;
                    $note['comment'] = $post['comment'];
                    $note['details'] = array(
                        'startDate' => $post['startDate'],
                        'endDate' => $post['endDate'],
                        'time' => $post['dateRows']['totalTime'],
                        'policyId' => $post['policyId'],
                        'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $post['policyId'])['title']
                    );
                    //
                    if ($update_date == 'yes') {
                        $note['old_start_date'] = $old_start_date;
                        $note['old_end_date'] = $old_end_date;
                        $note['new_start_date'] = $new_start_date;
                        $note['new_end_date'] = $new_end_date;
                    }
                    //
                    if ($update_time == 'yes') {
                        $note['old_request_time'] = $old_request_time;
                        $note['new_request_time'] = $new_request_time;
                    }
                    //
                    $in['note'] = json_encode($note);
                    // $in['note'] = json_encode([
                    //     'status' => $post['status'], 
                    //     'canApprove' => $canApprove, 
                    //     'comment' => $post['comment'],
                    //     'details' => [
                    //         'startDate' => $post['startDate'],
                    //         'endDate' => $post['endDate'],
                    //         'time' => $post['dateRows']['totalTime'],
                    //         'policyId' => $post['policyId'],
                    //         'policyTitle' => $this->timeoff_model->getPolicyColumn('title', $post['policyId'])['title']
                    //     ]
                    //     ]);
                } else {
                    $in['note'] = json_encode(['status' => 'pending', 'comment' => $post['reason']]);
                }
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                // Send email notifications
                if ($send_email == 'yes' && ($post['sendEmailNotification'] == 1 || $post['sendEmailNotification'] == 'yes')) {
                    if ($post['status'] == "approved" || $post['status'] == "rejected") {
                        $this->sendNotifications($post['requestId'], $post['status']);
                    } else {
                        $this->sendNotifications($post['requestId'], 'update');
                    }
                }
                //
                $this->res['Response'] = 'You have succesfully updated the time-off.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;


                // Create timeoff
            case "archive_request":
                //
                $in = [];
                $in['archive'] = $post['archive'];
                //
                $this->timeoff_model->updateTable($in, $post['requestId'], 'timeoff_requests');
                //
                $in = [];
                $in['request_sid'] = $post['requestId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['comment'] = '';
                $in['note'] = json_encode(['status' => $post['archive'] == 1 ? 'archive' : 'activate']);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                //
                $this->res['Response'] = 'You have succesfully ' . ($post['archive'] == 1 ? 'archived' : 'activated') . ' the time off.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;


                // Create timeoff
            case "cancel_request":
                //

                $in = [];
                $in['status'] = 'cancelled';
                //
                $previous_status = $this->timeoff_model->getPreviousStatus($post['requestId']);
                //
                $this->timeoff_model->updateTable($in, $post['requestId'], 'timeoff_requests');
                //
                if ($previous_status["status"] == "approved" && $previous_status["level_status"] == "approved") {
                    $this->sendEmailToInformApprovers($post['requestId']);
                }
                //
                $in = [];
                $in['request_sid'] = $post['requestId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['comment'] = '';
                $in['note'] = json_encode(['status' => 'cancelled']);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                //
                $this->res['Response'] = 'You have succesfully cancelled the time off request.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;

                // 
            case "get_request_by_id":
                //
                $data = $this->timeoff_model->getRequestById($post['requestId'], false);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;


                // Get approver history
            case "get_request_history":
                //
                $history = $this->timeoff_model->getHistory(
                    'request_sid',
                    $post['requestId'],
                    'timeoff_request_timeline'
                );
                //
                if (empty($history)) {
                    $this->res['Response'] = 'We are unable to find history against this time-off.';
                }
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Data'] = $history;
                $this->resp();
                break;

            case 'get_company_all_holidays':
                // Get holidays
                $holidays = $this->timeoff_model->getCompanyAllHolidays(
                    $post
                );
                $this->res['Data'] = $holidays;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

            case 'get_employee_note':
                // Get Employee Previous Note
                $request_sid = $post['requestId'];
                //
                $previous_note = $this->timeoff_model->getEmployeePreviousNote($post['requestId'], $post["employerId"]);
                //
                $this->res['Comment'] = $previous_note['comment'];
                $this->res['Response'] = 'You have succesfully get previous employee comment.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;

            case 'update_employee_note':
                // Update Employee Note
                //
                $in = [];
                //
                $previous_note = $this->timeoff_model->getEmployeePreviousNote($post['requestId'], $post["employerId"]);
                //
                $in['note'] = json_encode([
                    'status' => $previous_note['status'],
                    'canApprove' => $previous_note['canApprove'],
                    'comment' => $post['comment'],
                    'details' => $previous_note['details']
                ]);
                $in['comment'] = $post['comment'];
                //
                $this->timeoff_model->updateEmployeeNote($in, $post['requestId'], $post["employerId"]);
                //
                $this->res['Response'] = 'You have succesfully add new note.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;


            case 'get_my_team_employees':
                $employees = $this->timeoff_model->getMyEmployees($post);
                //
                if (!sizeof($employees)) {
                    $this->res['Response'] = 'No employees found for the selected employee.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $employees;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;


            case 'get_all_company_policies':
                // Get policies
                $policies = $this->timeoff_model->getAllCompanyPolicies(
                    $post
                );
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any policies yet.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;


                // Balances
            case "get_employee_balances":
                //
                $balances = $this->timeoff_model->getGraphBalanceOfEmployee($post['companyId'], $post['employeeId']);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $balances;
                $this->resp();
                break;

                // Get employee breakdown
            case "get_employee_breakdown":
                //
                $balances = $this->timeoff_model->getEmployeeR($post['companyId'], $post['employeeId']);
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $balances;
                $this->resp();
                break;

                // Get employee breakdown
            case "import_balance":
                // Start the import process
                foreach ($post['data'] as $balance) {
                    // Get employee id
                    $employeeId = $this->timeoff_model->getEmployeeIdByemail(
                        $post['companyId'],
                        $balance['email']
                    );
                    $policyId = $this->timeoff_model->getPolicyIdByName(
                        $post['companyId'],
                        trim($balance['policy'])
                    );
                    //
                    if (empty($employeeId)) return;
                    if (empty($policyId)) return;
                    //
                    $this->timeoff_model->addEmployeeBalance([
                        'employeeId' => $employeeId,
                        'employerId' => $post['employerId'],
                        'btype' => '0',
                        'note' => 'Subtracted from import',
                        'effectedDate' => date('m/d/Y', strtotime('now')),
                        'policy' => $policyId,
                        'time' => $balance['hours'] * 60
                    ]);
                }
                //
                $this->res['Status'] = true;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;


                // Get off employees
            case "get_today_off_employees":
                //
                $this->res['Status'] = true;
                $this->res['Data'] = $this->timeoff_model->getTodayOffEmployees($post);
                $this->res['Code'] = 'SUCCESS';
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;


                // Update request status
            case "request_status":
                //
                $in = []; //echo '<pre>'; print_r($post); die();
                //
                $in['level_status'] = $post['status'];
                //
                $send_email = 'yes';
                //
                $canApprove = $this->timeoff_model->getEmployerApprovalStatus($post['employerId']);
                if ($canApprove === 1) {
                    $in['status'] = $post['status'];
                } else {
                    $request = $this->timeoff_model->getRequestById($post['requestId']);
                    //
                    if ($request['status'] != 'pending' && ($post['status'] == "approved" || $post['status'] == "rejected")) {

                        $this->sendEmailOtherApprovers($post);
                        $send_email = 'no';
                    }
                }

                //
                $this->timeoff_model->updateTable($in, $post['requestId'], 'timeoff_requests');
                //
                $in = [];
                $in['request_sid'] = $post['requestId'];
                $in['employee_sid'] = $post['employerId'];
                $in['action'] = 'update';
                $in['comment'] = $post['comment'];
                $in['note'] = json_encode([
                    'status' => $post['status'],
                    'canApprove' => $canApprove,
                    'comment' => $post['comment'],
                    'details' => []
                ]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                // Send email notifications
                if ($send_email == 'yes') {
                    if ($post['sendEmailNotification'] == 1 || $post['sendEmailNotification'] == 'yes') {
                        if ($post['status'] == "approved" || $post['status'] == "rejected") {
                            $this->sendNotifications($post['requestId'], $post['status']);
                        } else {
                            $this->sendNotifications($post['requestId'], 'update');
                        }
                    }
                }
                //
                $this->res['Response'] = 'You have succesfully updated the time-off.';
                $this->res['Status'] = TRUE;
                $this->res['Code'] = 'SUCCESS';
                $this->resp();
                break;





                // Deprecated as of 12/12/2020
                // -------------------------------------------------
                //
            case 'check_available_time':
                //
                $startMonth = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m');
                $endMonth = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m');
                //
                $r = $this->timeoff_model->getAllowedTimeOffByPolicyIdAndMonths(
                    $startMonth,
                    $endMonth,
                    $formpost['policyId'],
                    $formpost['employeeSid']
                );
                //
                $this->res['Data'] = $r;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Plans
                // Get plan list by company sid
            case 'get_plan_list_by_company':
                // Get plans
                $plans = $this->timeoff_model->getPlanListByCompany($formpost['companySid']);
                if (!sizeof($plans)) {
                    $this->res['Response'] = 'No plans found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $plans;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Get plan employee list by company sid
            case 'get_plan_creators_by_company':
                // Get plans
                $creators = $this->timeoff_model->getPlanEmployeeListByCompany($formpost['companySid']);
                if (!sizeof($creators)) {
                    $this->res['Response'] = 'No creators found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $creators;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Get plans by company sid
            case 'get_plan_by_company':
                // Check if policy already exists for current company
                $plans = $this->timeoff_model->getPlansByCompany(
                    $formpost['companySid'],
                    $formpost['page'],
                    $this->limit,
                    $formpost
                );

                if (!sizeof($plans['Plans'])) {
                    $this->res['Response'] = 'It looks like you haven\'t added any plans.';
                    $this->resp();
                }
                //
                if ($formpost['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $plans['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                $this->res['Data'] = $plans['Plans'];
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Add company planSid
            case 'add_company_plan':
                if ($formpost['plan'] != '') {
                    $planSid = $this->timeoff_model->checkAndInsertPlanList($formpost['year'], $formpost['month']);
                    if ((int)$planSid === 0) {
                        $this->res['Response'] = 'Something went wrong while verifying plan.';
                        $this->resp();
                    }
                    $formpost['planSid'] = $planSid;
                }
                // Check if configuration already exists for current company
                $doExist = $this->timeoff_model->checkConfigurationForCompany($formpost['companySid'], $formpost['planSid']);
                // Company already exists
                if ($doExist != 0) {
                    $this->res['Response'] = 'Plan already exists.';
                    $this->resp();
                }
                $format = $this->timeoff_model->getTimeOffFormat($formpost['companySid'], false);
                $timeSlot = $this->timeoff_model->getTimeOffSetting($formpost['companySid'], 'default_timeslot');
                if ($timeSlot) $this->timeSlot = $timeSlot;
                //
                if (sizeof($format)) $format = $format['slug'];
                else $format = 'H:M';
                //
                $finalMinutes = 0;
                if ($format == 'D:H:M')
                    $finalMinutes = $this->calculateFormatMinutes($formpost['allowedDays'], $formpost['allowedHours'], $formpost['allowedMinutes']);
                elseif ($format == 'H:M')
                    $finalMinutes = $this->calculateFormatMinutes(0, $formpost['allowedHours'], $formpost['allowedMinutes']);
                elseif ($format == 'D')
                    $finalMinutes = $this->calculateFormatMinutes($formpost['allowedDays']);
                elseif ($format == 'H')
                    $finalMinutes = $this->calculateFormatMinutes(0, $formpost['allowedHours'], 0);
                elseif ($format == 'M')
                    $finalMinutes = $this->calculateFormatMinutes(0, 0, $formpost['allowedMinutes']);
                $formpost['allowed_minutes'] = $finalMinutes; // Because we are now tracking in minutes
                //
                $insertID = $this->timeoff_model->insertCompanyPlan($formpost);
                //
                if (!$insertID) {
                    $this->res['Response'] = 'Plan failed to add.';
                    $this->resp();
                }
                //
                $this->res['Minutes'] = $finalMinutes;
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'New Plan is added.';
                $this->resp();
                break;

                // Archive company plans
            case 'archive_company_plan':
                // Get plans
                $this->timeoff_model->updateCompanyPlanWithData(
                    $formpost['planSid'],
                    array('is_archived' => 1)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Plan is marked as archive.';
                $this->resp();
                break;

                // Activate company plan
            case 'activate_company_plan':
                // Get plans
                $this->timeoff_model->updateCompanyPlanWithData(
                    $formpost['planSid'],
                    array('is_archived' => 0)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Plan is marked as activate.';
                $this->resp();
                break;

                // Edit company planSid
            case 'edit_company_plan':
                if ($formpost['plan'] != '') {
                    $planListSid = $this->timeoff_model->checkAndInsertPlanList($formpost['year'], $formpost['month']);
                    if ((int)$planListSid === 0) {
                        $this->res['Response'] = 'Something went wrong while verifying plan.';
                        $this->resp();
                    }
                    $formpost['planListSid'] = $planListSid;
                }
                // Check if configuration already exists for current company
                $doExist = $this->timeoff_model->checkConfigurationForCompany($formpost['companySid'], $formpost['planListSid'], $formpost['recordSid']);
                // Company already exists
                if ($doExist != 0) {
                    $this->res['Response'] = 'Plan already exists.';
                    $this->resp();
                }
                $format = $this->timeoff_model->getTimeOffFormat($formpost['companySid'], false);
                $timeSlot = $this->timeoff_model->getTimeOffSetting($formpost['companySid'], 'default_timeslot');
                if ($timeSlot) $this->timeSlot = $timeSlot;
                //
                if (sizeof($format)) $format = $format['slug'];
                else $format = 'H:M';
                //
                $finalMinutes = 0;
                if ($format == 'D:H:M')
                    $finalMinutes = $this->calculateFormatMinutes($formpost['allowedDays'], $formpost['allowedHours'], $formpost['allowedMinutes']);
                elseif ($format == 'H:M')
                    $finalMinutes = $this->calculateFormatMinutes(0, $formpost['allowedHours'], $formpost['allowedMinutes']);
                elseif ($format == 'D')
                    $finalMinutes = $this->calculateFormatMinutes($formpost['allowedDays']);
                elseif ($format == 'H')
                    $finalMinutes = $this->calculateFormatMinutes(0, $formpost['allowedHours'], 0);
                elseif ($format == 'M')
                    $finalMinutes = $this->calculateFormatMinutes(0, 0, $formpost['allowedMinutes']);
                $formpost['allowed_minutes'] = $finalMinutes; // Because we are now tracking in minutes
                //
                $this->timeoff_model->updateCompanyPlan($formpost);
                //
                $this->res['Status'] = TRUE;
                $this->res['Minutes'] = $finalMinutes;
                $this->res['Response'] = 'Plan is modified.';
                $this->resp();
                break;

                // Policies
                // Get policies list by company sid

                // Get company plans
            case 'get_company_plans':
                // Check if policy already exists for current company
                $plans = $this->timeoff_model->getCompanyPlans(
                    $formpost['companySid'],
                    1,
                    0
                );

                if (!sizeof($plans)) {
                    $this->res['Response'] = 'It looks like you haven\'t added any plans.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $plans;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // Add company Policy
            case 'add_company_policy':
                $formpost['policy'] = trim($formpost['policy']);
                // $formpost['policy'] = strtolower(trim($formpost['policy']));
                // Check for policy
                $policyExists = $this->timeoff_model->companyPolicyExists($formpost);
                //
                if ($policyExists != 0) {
                    $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> already exists.';
                    $this->resp();
                }
                //
                $insertID = $this->timeoff_model->insertPolicy($formpost);
                //
                if (!$insertID) {
                    $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> failed to add.';
                    $this->resp();
                }
                // Check if categories were set
                $this->timeoff_model->linkCategoryToPolicy(array(
                    'timeoff_policy_sid' => $insertID,
                    'timeoff_category_sid' => $formpost['types']
                ));
                // if(is_array($formpost['types']) && sizeof($formpost['types'])){
                //     foreach ($formpost['types'] as $k => $v) {
                //         $this->timeoff_model->linkCategoryToPolicy(array(
                //             'timeoff_policy_sid' => $insertID,
                //             'timeoff_category_sid' => $v
                //         ));
                //     }
                // }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> is added.';
                $this->resp();
                // _e($formpost, true);
                break;
                // Get policies list by company sid

                // Archive company plans

                // Activate company plan

                // Get a single company policy for edit
            case 'get_single_company_policy':
                $policy = $this->timeoff_model->getCompanyPolicyById($formpost);
                //
                if (!sizeof($policy)) {
                    $this->res['Response'] = 'Oops! System failed to verify policy.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policy;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;
                // Edit company Policy
            case 'edit_company_policy':
                $formpost['policy'] = (trim($formpost['policy']));
                $formpost['policy'] = (trim($formpost['policy']));
                // Check for policy
                $policyExists = $this->timeoff_model->companyPolicyExists($formpost);
                //
                if ($policyExists != 0) {
                    $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> already exists.';
                    $this->resp();
                }
                //
                $insertID = $this->timeoff_model->updatePolicy($formpost);
                //
                if (!$insertID) {
                    $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> failed to update.';
                    $this->resp();
                }
                // Drop link
                $this->timeoff_model->dropPolicyCategoriesByPolicyId($insertID);
                //
                $this->timeoff_model->linkCategoryToPolicy(array(
                    'timeoff_policy_sid' => $insertID,
                    'timeoff_category_sid' => $formpost['types']
                ));
                // Check if categories were set
                // if(is_array($formpost['types']) && sizeof($formpost['types'])){
                //     foreach ($formpost['types'] as $k => $v) {
                //         $this->timeoff_model->linkCategoryToPolicy(array(
                //             'timeoff_policy_sid' => $insertID,
                //             'timeoff_category_sid' => $v
                //         ));
                //     }
                // }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Policy <b>' . (ucwords($formpost['policy'])) . '</b> is updated.';
                $this->resp();
                // _e($formpost, true);
                break;

                // Policy Overwrites
                // Add company Policy
            case 'add_company_policy_overwrite':
                // Check for overwrite
                $policyOverwriteExists = $this->timeoff_model->companyPolicyOverwriteExists($formpost);
                //
                if ($policyOverwriteExists != 0) {
                    $this->res['Response'] = 'Policy overwrite already exists.';
                    $this->resp();
                }
                // _e($formpost, true, true);
                //
                $insertID = $this->timeoff_model->insertPolicyOverwrite($formpost);
                //
                if (!$insertID) {
                    $this->res['Response'] = 'Policy overwrite has failed to add.';
                    $this->resp();
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Policy overwrite is added.';
                $this->resp();
                // _e($formpost, true);
                break;
                // Get policies list by company sid
            case 'get_policy_overwrites_by_company':
                // Get policies
                $policiesOverwrites = $this->timeoff_model->getPolicyOverwritesByCompany(
                    $formpost,
                    $formpost['page'],
                    $this->limit
                );
                //
                if (!sizeof($policiesOverwrites)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any policy overwrites yet.';
                    $this->resp();
                }
                //
                if ($formpost['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $policiesOverwrites['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $policiesOverwrites['PolicyOverwrites'];
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // Archive company policy overwrite
            case 'archive_company_policy_overwrite':
                //
                $this->timeoff_model->updateCompanyPolicyOverwrite(
                    $formpost['policyOverwriteSid'],
                    array('is_archived' => 1)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Policy Overwrite is marked as archive.';
                $this->resp();
                break;
                // Activate company policy overwrite
            case 'activate_company_policy_overwrite':
                //
                $this->timeoff_model->updateCompanyPolicyOverwrite(
                    $formpost['policyOverwriteSid'],
                    array('is_archived' => 0)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Policy Overwrite is marked as activate.';
                $this->resp();
                break;
                // Get a single company policy for edit
            case 'get_single_company_policy_overwrite':
                $policyOverwrite = $this->timeoff_model->getCompanyPolicyOverwriteById($formpost);
                //
                if (!sizeof($policyOverwrite)) {
                    $this->res['Response'] = 'Oops! System failed to verify policy overwrite.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policyOverwrite;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;
                // Edit company Policy Overwrite
            case 'edit_company_policy_overwrite':
                // Check for overwrite
                $policyOverwriteExists = $this->timeoff_model->companyPolicyOverwriteExists($formpost);
                //
                if ($policyOverwriteExists != 0) {
                    $this->res['Response'] = 'Policy overwrite already exists.';
                    $this->resp();
                }
                //
                $insertID = $this->timeoff_model->updatePolicyOverwrite($formpost);
                //
                if (!$insertID) {
                    $this->res['Response'] = 'Policy failed to update.';
                    $this->resp();
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Policy is updated.';
                $this->resp();
                // _e($formpost, true);
                break;


                // Approvers
                // Add Approver

                // Archive company policy overwrite
            case 'archive_company_approver':
                //
                $this->timeoff_model->updateCompanyApprover(
                    $formpost['approverSid'],
                    array('is_archived' => 1)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Approver is marked as archive.';
                $this->resp();
                break;
                // Activate company policy overwrite
            case 'activate_company_approver':
                //
                $this->timeoff_model->updateCompanyApprover(
                    $formpost['approverSid'],
                    array('is_archived' => 0)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Approver is marked as activate.';
                $this->resp();
                break;
                // Get approvers list by company sid

                // Get a single company policy for edit

            case 'get_company_departments':
                //
                $departments = $this->timeoff_model->getCompanyDepartments(
                    $formpost['companySid']
                );
                //
                $this->res['Data'] = $departments;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Get company departments and teams


                // Get employee policies
            case 'get_employee_policies':
                $policies = $this->timeoff_model->getEmployeePolicies($formpost);
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'No policies found for the selected employee.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;

            case "get_policy_requests_with_employees":
                // get all requests for active employees
                $policyRequests = $this->timeoff_model->getPolicyRequestsWithEmployees($post['policyId'], true);
                //
                if (!$policyRequests) {
                    $this->res['Response'] = 'We are unable to find requests against this policy.';
                    return $this->resp();
                }
                $policies = $this->timeoff_model->getAllPolicies($post['companyId']);
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['View'] = $this->load->view('timeoff/partials/policies/manage_policy', [
                    'policyRequests' => $policyRequests,
                    'policies' => $policies,
                    'selectedPolicyId' => $post['policyId']
                ], true);
                $this->resp();
                break;

                // Create employee time off
            case 'create_employee_timeoff':
                // _e($formpost, true, true);
                // // Check if PTO exists for the same date
                // $toExists = $this->timeoff_model->isTimeOffExists($formpost);
                // //
                // if($toExists){
                //     $this->res['Response'] = 'You already have a Time Off scheduled on <b>{{TIMEOFFDDATE}}</b>';
                //     $this->resp();
                // }

                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];

                // Set insert array
                $ins = array();
                $ins['company_sid'] = $formpost['companySid'];
                $ins['employee_sid'] = $formpost['employeeSid'];
                $ins['timeoff_policy_sid'] = $formpost['policyId'];
                $ins['requested_time'] = $formpost['requestedDays']['totalTime'];
                $ins['allowed_timeoff'] = $formpost['allowedTimeOff'];
                $ins['request_from_date'] = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('Y-m-d');
                $ins['request_to_date'] = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('Y-m-d');
                //
                $ins['timeoff_days'] = @json_encode($formpost['requestedDays']['days']);
                $ins['reason'] = $formpost['reason'];
                $ins['creator_sid'] = $data['employer_sid'];
                $ins['fmla'] = isset($formpost['isFMLA']) && $formpost['isFMLA'] != 'no' ? strtolower($formpost['isFMLA']) : NULL;
                // Phase 3
                if (isset($formpost['compensationStartTime'])) $ins['compensation_start_time'] = $formpost['compensationStartTime'];
                if (isset($formpost['compensationEndTime'])) $ins['compensation_end_time'] = $formpost['compensationEndTime'];
                if (isset($formpost['returnDate'])) {
                    $ins['return_date'] = $formpost['returnDate'] == '' ? NULL : DateTime::createFromFormat((preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $formpost['returnDate']) ? 'm-d-Y' : 'm/d/Y'), $formpost['returnDate'])->format('Y-m-d');
                }
                if (isset($formpost['relationship'])) $ins['relationship'] = $formpost['relationship'];
                if (isset($formpost['temporaryAddress'])) $ins['temporary_address'] = $formpost['temporaryAddress'];
                if (isset($formpost['emergencyContactNumber'])) $ins['emergency_contact_number'] = $formpost['emergencyContactNumber'];
                // Phase 3 ends
                //
                if (isset($formpost['sendEmail'])) $ins['status'] = $formpost['status'];
                if (isset($formpost['sendEmail'])) $ins['level_at'] = 3;
                // Fetch departments and add entry
                $tls = $this->timeoff_model->fetchTimeOffEmployers(
                    $formpost['employeeSid'],
                    $formpost['companySid']
                );
                // Insert timeof record
                $requestId = $this->timeoff_model->insertTimeOffRequest($ins);
                //
                if (!$requestId) {
                    $this->res['Response'] = 'Oops! Something went wrong while createing a Time Off Request. Please try again in a few moments.';
                    $this->resp();
                }
                // Link FMLA to timeoff request
                if (isset($formpost['fmla']) && sizeof($formpost['fmla'])) {
                    $this->timeoff_model->addFMLAToRequest(
                        array(
                            'timeoff_request_sid' => $requestId,
                            'document_title' => $formpost['fmla']['type'],
                            'document_type' => 'generated',
                            'serialized_data' => @json_encode($formpost['fmla'])
                        )
                    );
                }
                //
                if (!isset($formpost['employeeFullName']) || empty($formpost['employeeFullName'])) {
                    $formpost['employeeFullName'] = $formpost['employeeFirstName'] . ' ' . $formpost['employeeLastName'];
                }
                //
                if (isset($formpost['sendEmail'])) {
                    $company_sid = $formpost['companySid'];
                    $send_email = $this->timeoff_model->get_email_to_supervisor_status($company_sid);
                    $send_email_status = isset($send_email[0]) ? $send_email[0]['send_email_to_supervisor'] : 0;
                    $emailSend = true;
                } else $send_email_status = 1;

                if (isset($formpost['doSendTheEmail'])) $emailSend = true;

                $formpost['sendEmail'] = true;
                $send_email_status = 0;
                //
                $this->load->library('encryption', null, 'enc');
                if ($emailSend) {
                    //
                    if (true) {
                        $this->load->library('encryption', null, 'enc');
                        //
                        $requesterArray = array();
                        $requesterArray['single_day_leave'] = 0;
                        $requesterArray['to_full_name'] = isset($formpost['employeeFullName']) && !empty($formpost['employeeFullName']) ? $formpost['employeeFullName'] : $formpost['employeeFirstName'] . ' ' . $formpost['employeeLastName'];
                        $requesterArray['exp'] = 'You have ';
                        $requesterArray['policy_name'] = $formpost['policyName'];
                        $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                        $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                        if ($leave_start_date == $leave_end_date) {
                            $requesterArray['single_day_leave'] = 1;
                        }

                        $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                        $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                        $requesterArray['request_details'] = '';
                        foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                            $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                                $v1['time'],
                                $formpost['timeslot'],
                                $formpost['slug']
                            )['text']) . '</p>';
                        }
                        // $requesterArray['partial_leave']  = $formpost['isPartial'] == 1 ? 'Yes' : 'No';
                        // $requesterArray['partial_leave_note']  = $formpost['note'];
                        $requesterArray['reason']  = $formpost['reason'];
                        $requesterArray['type']  = 'requester';
                        $requesterArray['request_status']  = 'Pending';
                        $requesterArray['rejectBtnToken']  = $requesterArray['approveBtn']  = '';
                        $requesterArray['cancelBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=requester&action=cancel&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($formpost['employeeSid']) . ''
                        ));
                        // Send an email to Requester
                        $emailBody = getTimeOffEmailTemplate(
                            $requesterArray,
                            $formpost['companySid'],
                            $data['company_name']
                        );
                        // Email sender
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $formpost['employeeEmail'],
                            'You have requested a Time Off request for ' . ($requesterArray['requested_date']) . '',
                            $emailBody,
                            $data['company_name']
                        );
                    }
                }
                // Add team leads, supervisors, approvers
                if (sizeof($tls)) {
                    //
                    // $level = 'teamlead';
                    if (isset($formpost['doSendTheEmail'])) {

                        $teamlead = $supervisor = $approver = false;
                        foreach ($tls as $tl) {
                            $tl = (array) $tl;
                            if ($tl['type'] == 'teamlead') $teamlead = true;
                            else if ($tl['type'] == 'supervisor') $supervisor = true;
                            else if ($tl['type'] == 'approver') $approver = true;
                        }
                        //
                        $level = $teamlead == true ? 'teamlead' : ($supervisor == true ? 'supervisor' : 'approver');
                        //
                        if ($level == 'supervisor') {
                            $this->timeoff_model->updateRequestColumn(
                                $requestId,
                                array(
                                    'level_at' => 2
                                )
                            );
                        } else if ($level == 'approver') {
                            $this->timeoff_model->updateRequestColumn(
                                $requestId,
                                array(
                                    'level_at' => 3
                                )
                            );
                        }
                    } else $level = 'approver';

                    // Get request employee id
                    $requesterId = $this->timeoff_model->getRequestFieldById($requestId, 'employee_sid');
                    // Fetch latest tls
                    $newTLS = $this->timeoff_model->fetchTimeOffEmployers(
                        $requesterId,
                        $formpost['companySid']
                    );

                    foreach ($tls as $tl) {
                        $tl = (array) $tl;
                        // Make sure emails will not go out to other company employees ever
                        if ($this->checkEmployeeCompany($tl, $formpost)) continue;
                        // Make sure this employer still has access to the request
                        if (
                            !$this->checkEmployeeRequestAccess(
                                $requestId,
                                $newTLS,
                                $tl
                            )
                        ) continue;
                        // Assign
                        $assignId = $this->timeoff_model->assignEmployeeToRequest(
                            array(
                                'timeoff_request_sid' => $requestId,
                                'employee_sid' => $tl['id'],
                                'role' => $tl['type'],
                                'status' => 1
                            )
                        );
                        if ($tl['type'] != $level) continue;
                        //
                        if ($send_email_status == 1) continue;
                        $requesterArray = array();
                        $requesterArray['single_day_leave'] = 0;
                        $requesterArray['to_full_name'] = $tl['full_name'];
                        $requesterArray['exp'] = '<b>' . (isset($formpost['employeeFullName']) && !empty($formpost['employeeFullName']) ? $formpost['employeeFullName'] : $formpost['employeeFirstName'] . ' ' . $formpost['employeeLastName']) . '</b> has ';
                        $requesterArray['policy_name'] = $formpost['policyName'];
                        $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                        $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                        if ($leave_start_date == $leave_end_date) {
                            $requesterArray['single_day_leave'] = 1;
                        }

                        $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                        $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                        // $requesterArray['partial_leave']  = $formpost['isPartial'] == 1 ? 'Yes' : 'No';
                        // $requesterArray['partial_leave_note']  = $formpost['note'];
                        $requesterArray['request_details'] = '';
                        foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                            $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                                $v1['time'],
                                $formpost['timeslot'],
                                $formpost['slug']
                            )['text']) . '</p>';
                        }
                        $requesterArray['reason']  = $formpost['reason'];
                        $requesterArray['type']  = 'tls';
                        $requesterArray['request_status']  = 'Pending';
                        $requesterArray['rejectBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=reject&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['approveBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=approve&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['viewBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=pending&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['cancelBtnToken']  = '';
                        // Send an email to Requester
                        $emailBody = getTimeOffEmailTemplate(
                            $requesterArray,
                            $formpost['companySid'],
                            $data['company_name']
                        );
                        // Email sender
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $tl['email'],
                            'Time Off request from ' . ($formpost['employeeFullName']) . '',
                            $emailBody,
                            $data['company_name']
                        );
                    }
                    if (isset($formpost['comment'])) {
                        //
                        $assignId = $this->timeoff_model->checkAndInsertAssignedRequest($requestId, $data['employer_sid']);
                        //
                        $this->timeoff_model->addHistoryRecord(array(
                            'timeoff_request_sid' => $requestId,
                            'timeoff_request_assignment_sid' => $assignId,
                            'is_partial_leave' => 0,
                            'request_time' => 0,
                            'reason' => $formpost['comment']
                        ));
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['InsertId'] = $requestId;
                $this->res['Response'] = 'Time Off request is created!';
                $this->resp();
                break;

                // Create employee time off
            case 'create_employee_timeoff_draft':
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];
                // Set insert array
                $ins = array();
                $ins['company_sid'] = $formpost['companySid'];
                $ins['employee_sid'] = $formpost['employeeSid'];
                $ins['timeoff_policy_sid'] = $formpost['policyId'];
                $ins['requested_time'] = $formpost['requestedDays']['totalTime'];
                $ins['allowed_timeoff'] = $formpost['allowedTimeOff'];
                $ins['request_from_date'] = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('Y-m-d');
                $ins['request_to_date'] = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('Y-m-d');
                //
                $ins['timeoff_days'] = @json_encode($formpost['requestedDays']['days']);
                $ins['reason'] = $formpost['reason'];
                $ins['creator_sid'] = $data['employer_sid'];
                $ins['is_draft'] = 1;
                $ins['fmla'] = isset($formpost['isFMLA']) ? strtolower($formpost['isFMLA']) : NULL;
                if (isset($formpost['startTime'])) $ins['start_time'] = $formpost['startTime'];
                if (isset($formpost['endTime'])) $ins['end_time'] = $formpost['endTime'];
                // Phase 3
                if (isset($formpost['compensationStartTime'])) $ins['compensation_start_time'] = $formpost['compensationStartTime'];
                if (isset($formpost['compensationEndTime'])) $ins['compensation_end_time'] = $formpost['compensationEndTime'];
                if (isset($formpost['returnDate'])) {
                    $ins['return_date'] = $formpost['returnDate'] == '' ? NULL : DateTime::createFromFormat('m/d/Y', $formpost['returnDate'])->format('Y-m-d');
                }
                if (isset($formpost['relationship'])) $ins['relationship'] = $formpost['relationship'];
                if (isset($formpost['temporaryAddress'])) $ins['temporary_address'] = $formpost['temporaryAddress'];
                if (isset($formpost['emergencyContactNumber'])) $ins['emergency_contact_number'] = $formpost['emergencyContactNumber'];
                // Phase 3 ends
                //
                if (isset($formpost['sendEmail'])) $ins['status'] = $formpost['status'];
                if (isset($formpost['sendEmail'])) $ins['level_at'] = 3;
                // Fetch departments and add entry
                $tls = $this->timeoff_model->fetchTimeOffEmployers(
                    $formpost['employeeSid'],
                    $formpost['companySid']
                );
                // Insert timeof record
                $requestId = $this->timeoff_model->insertTimeOffRequest($ins);
                //
                if (!$requestId) {
                    $this->res['Response'] = 'Oops! Something went wrong while createing a Time Off Request. Please try again in a few moments.';
                    $this->resp();
                }
                // Link FMLA to timeoff request
                if (isset($formpost['fmla']) && sizeof($formpost['fmla'])) {
                    $this->timeoff_model->addFMLAToRequest(
                        array(
                            'timeoff_request_sid' => $requestId,
                            'document_title' => $formpost['fmla']['type'],
                            'document_type' => 'generated',
                            'serialized_data' => @json_encode($formpost['fmla'])
                        )
                    );
                }

                // Add team leads, supervisors, approvers
                if (sizeof($tls)) {
                    //
                    $teamlead = $supervisor = $approver = false;
                    foreach ($tls as $tl) {
                        $tl = (array) $tl;
                        if ($tl['type'] == 'teamlead') $teamlead = true;
                        else if ($tl['type'] == 'supervisor') $supervisor = true;
                        else if ($tl['type'] == 'approver') $approver = true;
                    }
                    //
                    $level = $teamlead == true ? 'teamlead' : ($supervisor == true ? 'supervisor' : 'approver');
                    //
                    if ($level == 'supervisor') {
                        $this->timeoff_model->updateRequestColumn(
                            $requestId,
                            array(
                                'level_at' => 2
                            )
                        );
                    } else if ($level == 'approver') {
                        $this->timeoff_model->updateRequestColumn(
                            $requestId,
                            array(
                                'level_at' => 3
                            )
                        );
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['InsertId'] = $requestId;
                $this->res['Response'] = 'Time Off request is created but not submitted!';
                $this->resp();
                break;

                // Get employee timeoff requests
            case 'fetch_employee_requests':
                $requests = $this->timeoff_model->fetchEmployeeRequestsLMS(
                    $formpost
                );
                //
                if (!sizeof($requests)) {
                    $this->res['Response'] = 'No timeoff requests found.';
                    $this->resp();
                }
                //
                $this->res['Response'] = 'Proceed..';
                $this->res['Status'] = true;
                $this->res['Data'] = $requests;
                $this->resp();
                break;

                // Cancel employee request
            case 'cancel_employee_request':
                $cancelled = $this->timeoff_model->cancelEmployeeRequest(
                    $formpost
                );

                // TODO: Send emails to TLS
                // // Fetch tls by request sid
                // $tls = $this->timeoff_model->fetchTlsByRequestSid($formpost);
                // // Emails
                // _e(
                //     $tls,
                //     true
                // );
                // if(sizeof($tls)){
                //     foreach ($tls as $tl) {
                //         // Send emails
                //     }
                // }
                //
                $this->res['Response'] = 'Time Off request is canceled..';
                $this->res['Status'] = true;
                $this->resp();
                break;

                // Get employee total policy status
            case 'get_employee_policies_status':
                $policies = $this->timeoff_model->getEmployeePoliciesStatus($formpost);
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'No policies found for the selected employee.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;
            case 'get_employee_policies_status_wr':
                $policies = $this->timeoff_model->getEmployeePoliciesStatusWR($formpost);
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'No policies found for the selected employee.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;

                // Get balance sheet
            case 'get_balance_sheet':
                $balanceSheet = $this->timeoff_model->getBalanceSheet($formpost);
                //
                if (!sizeof($balanceSheet)) {
                    $this->res['Response'] = 'No balanceSheet found for the selected employee.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $balanceSheet;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->resp();
                break;

                // Get my team employees

                // Get all company policies

                // Get all company policies
            case 'get_employee_all_policies':
                // Get policies
                $policies = $this->timeoff_model->getEmployeeAllPolicies(
                    $formpost
                );
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any policies yet.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
            case 'get_employee_all_policies_wr':
                // Get policies
                $policies = $this->timeoff_model->getEmployeeAllPoliciesWR(
                    $formpost
                );
                //
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any policies yet.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // Get requests by team authority
            case 'get_assigned_requests':
                // Get policies
                $requests = $this->timeoff_model->getIncomingRequestByPerm(
                    $formpost,
                    $formpost['page'],
                    $this->limit
                );
                //
                if (!sizeof($requests)) {
                    $this->res['Response'] = 'No pending time off requests found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $requests;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // Get requests by team authority
            case 'get_assigned_request_report':
                // Get policies
                $requests = $this->timeoff_model->getIncomingRequestReportByPerm(
                    $formpost,
                    $formpost['page'],
                    $this->limit
                );
                //
                if (!sizeof($requests)) {
                    $this->res['Response'] = 'Oops! look like there are no Time Off requests.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $requests;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // Get single request
            case 'get_single_request':
                // Get policies
                $request = $this->timeoff_model->getSingleRequest(
                    $formpost
                );
                //
                if (!sizeof($request)) {
                    $this->res['Response'] = 'Failed to verify request.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $request;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                //  Update employee timeoff
            case 'update_employee_timeoff':
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];

                if (isset($formpost['approver'])) {
                    $this->timeoff_model->checkAndInsertAssignedRequest(
                        $formpost['requestId'],
                        $data['employer_sid']
                    );
                }
                // update
                $request = $this->timeoff_model->updateEmployeeTimeOff(
                    $formpost
                );
                if (!isset($formpost['approver'])) {
                    if ($request['SendEmailToAll']) {
                        $this->sendEmailToTlsAndRequester($formpost, $data);
                    } else if ($request['SendEmailToNextLevel']) {
                        $this->sendEmailToTls($formpost, $data, $request['SendEmailToNextLevel']);
                    }
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Time off request is updated.';
                $this->resp();
                break;
                //  Update archive status
            case 'update_archive_status':
                $archive_status = $formpost['archive'];
                $request_sid = $formpost['requestId'];
                $this->timeoff_model->update_request_archive_status($request_sid, $archive_status);
                //
                $this->res['Status'] = true;
                if ($archive_status == 1) {
                    $this->res['Response'] = 'Time off is archived successfully.';
                } else {
                    $this->res['Response'] = 'Time off is activated successfully.';
                }

                $this->resp();
                break;
                //  Update employee timeoff
            case 'update_employee_timeoff_from_employee':
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];
                // update
                $request = $this->timeoff_model->updateEmployeeTimeOffFromEmployee(
                    $formpost
                );
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];
                //
                $this->sendChangeEmailToTls($formpost, $data, 1);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Time off request is updated.';
                $this->resp();
                break;
                //  Update employee timeoff
            case 'update_employee_timeoff_draft':
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];
                // update
                $request = $this->timeoff_model->updateEmployeeTimeOffDraft(
                    $formpost
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Time off request is updated but no submitted.';
                $this->resp();
                break;

                //  Update employee timeoff
            case 'convert_employee_timeoff':
                $formpost['requestedTimeDetails']['formated'] = get_array_from_minutes(
                    $formpost['requestedDays']['totalTime'],
                    $formpost['timeslot'],
                    $formpost['slug']
                )['text'];
                // update
                $requestId = $this->timeoff_model->updateAndConvertEmployeeTimeOffDraft(
                    $formpost
                );
                // Fetch departments and add entry
                $tls = $this->timeoff_model->fetchTimeOffEmployers(
                    $formpost['employeeSid'],
                    $formpost['companySid']
                );
                // Link FMLA to timeoff request
                if (isset($formpost['fmla']) && sizeof($formpost['fmla'])) {
                    $this->timeoff_model->addFMLAToRequest(
                        array(
                            'timeoff_request_sid' => $requestId,
                            'document_title' => $formpost['fmla']['type'],
                            'document_type' => 'generated',
                            'serialized_data' => @json_encode($formpost['fmla'])
                        )
                    );
                }
                //
                $company_sid = $formpost['companySid'];
                $send_email = $this->timeoff_model->get_email_to_supervisor_status($company_sid);
                $send_email_status = isset($send_email[0]) ? $send_email[0]['send_email_to_supervisor'] : 0;
                //
                if ($send_email_status == 0) {
                    $this->load->library('encryption', null, 'enc');
                    //
                    $requesterArray = array();
                    $requesterArray['single_day_leave'] = 0;
                    $requesterArray['to_full_name'] = $formpost['employeeFullName'];
                    $requesterArray['exp'] = 'You have ';
                    $requesterArray['policy_name'] = $formpost['policyName'];
                    $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                    $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                    if ($leave_start_date == $leave_end_date) {
                        $requesterArray['single_day_leave'] = 1;
                    }

                    $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                    $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                    $requesterArray['request_details'] = '';
                    foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                        $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                        $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                        $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                            $v1['time'],
                            $formpost['timeslot'],
                            $formpost['slug']
                        )['text']) . '</p>';
                    }
                    $requesterArray['reason']  = $formpost['reason'];
                    $requesterArray['type']  = 'requester';
                    $requesterArray['request_status']  = 'Pending';
                    $requesterArray['rejectBtnToken']  = $requesterArray['approveBtn']  = '';
                    $requesterArray['cancelBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                        'id=' . ($requestId) . '&type=requester&action=cancel&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($formpost['employeeSid']) . ''
                    ));
                    // Send an email to Requester
                    $emailBody = getTimeOffEmailTemplate(
                        $requesterArray,
                        $formpost['companySid'],
                        $data['company_name']
                    );
                    // Email sender
                    log_and_sendEmail(
                        FROM_EMAIL_NOTIFICATIONS,
                        $formpost['employeeEmail'],
                        'You have requested a Time Off request for ' . ($requesterArray['requested_date']) . '',
                        $emailBody,
                        $data['company_name']
                    );
                }
                // Add team leads, supervisors, approvers
                if (sizeof($tls)) {
                    //
                    // $level = 'teamlead';

                    $teamlead = $supervisor = $approver = false;
                    foreach ($tls as $tl) {
                        $tl = (array) $tl;
                        if ($tl['type'] == 'teamlead') $teamlead = true;
                        else if ($tl['type'] == 'supervisor') $supervisor = true;
                        else if ($tl['type'] == 'approver') $approver = true;
                    }
                    //
                    $level = $teamlead == true ? 'teamlead' : ($supervisor == true ? 'supervisor' : 'approver');
                    //
                    if ($level == 'supervisor') {
                        $this->timeoff_model->updateRequestColumn(
                            $requestId,
                            array(
                                'level_at' => 2
                            )
                        );
                    } else if ($level == 'approver') {
                        $this->timeoff_model->updateRequestColumn(
                            $requestId,
                            array(
                                'level_at' => 3
                            )
                        );
                    }

                    foreach ($tls as $tl) {
                        $tl = (array) $tl;
                        // Make sure emails will not go out to other company employees ever
                        if ($this->checkEmployeeCompany($tl, $formpost)) continue;
                        // Assign
                        $assignId = $this->timeoff_model->assignEmployeeToRequest(
                            array(
                                'timeoff_request_sid' => $requestId,
                                'employee_sid' => $tl['id'],
                                'role' => $tl['type'],
                                'status' => 1
                            )
                        );
                        if ($tl['type'] != $level) continue;
                        //
                        if ($send_email_status == 1) continue;
                        $requesterArray = array();
                        $requesterArray['single_day_leave'] = 0;
                        $requesterArray['to_full_name'] = $tl['full_name'];
                        $requesterArray['exp'] = '<b>' . ($formpost['employeeFullName']) . '</b> has ';
                        $requesterArray['policy_name'] = $formpost['policyName'];
                        $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                        $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                        if ($leave_start_date == $leave_end_date) {
                            $requesterArray['single_day_leave'] = 1;
                        }

                        $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                        $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                        $requesterArray['request_details'] = '';
                        foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                            $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                            $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                                $v1['time'],
                                $formpost['timeslot'],
                                $formpost['slug']
                            )['text']) . '</p>';
                        }
                        $requesterArray['reason']  = $formpost['reason'];
                        $requesterArray['type']  = 'tls';
                        $requesterArray['request_status']  = 'Pending';
                        $requesterArray['rejectBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=reject&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['approveBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=approve&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['viewBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                            'id=' . ($requestId) . '&type=' . ($tl['type']) . '&action=pending&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                        ));
                        $requesterArray['cancelBtnToken']  = '';
                        // Send an email to Requester
                        $emailBody = getTimeOffEmailTemplate(
                            $requesterArray,
                            $formpost['companySid'],
                            $data['company_name']
                        );
                        // Email sender
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $tl['email'],
                            'Time Off request from ' . ($formpost['employeeFullName']) . '',
                            $emailBody,
                            $data['company_name']
                        );
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['InsertId'] = $requestId;
                $this->res['Response'] = 'Time Off request is created!';
                $this->resp();
                break;


                // Settings
                //Get settings and formats by company sid

                // Edit settings and formats by company sid


                // Import time off
            case 'import':
                //
                if (!sizeof($formpost['timeoffs'])) {
                    $this->res['Response'] = 'Empty list provided.';
                    $this->resp();
                }
                //
                $failCount = $insertCount = $existCount = 0;
                //
                foreach ($formpost['timeoffs'] as $k => $v) {
                    if (!isset($v['email_address']) || $v['email_address'] == '') {
                        $failCount++;
                        continue;
                    } else if (
                        (!isset($v['requested_date'])) &&
                        (!isset($v['requested_from_date'], $v['requested_to_date']))
                    ) {
                        $failCount++;
                        continue;
                    } else if (!isset($v['requested_hours']) || $v['requested_hours'] == '') {
                        $failCount++;
                        continue;
                    } else if (!isset($v['policy']) || $v['policy'] == '') {
                        $failCount++;
                        continue;
                    }

                    // Get policy Id
                    $policyId = $this->timeoff_model->getPolicyIdByTitle($v['policy'], $formpost['companySid']);
                    // $policyId = $this->timeoff_model->getPolicyIdByTitle($v['policy'], 11276);
                    //
                    if (!$policyId) {
                        $failCount++;
                        continue;
                    }
                    // Get emeployee Id
                    // $employeeDetails = $this->timeoff_model->getEmployeeIdByEmail($v['email_address'], 11276);
                    $employeeDetails = $this->timeoff_model->getEmployeeIdByEmail($v['email_address'], $formpost['companySid']);
                    //
                    if (!$employeeDetails) {
                        $failCount++;
                        continue;
                    }
                    //
                    $s = $e = '';
                    // Set Insert array
                    $i = array();
                    $i['company_sid'] = $formpost['companySid'];
                    $i['timeoff_policy_sid'] = $policyId;
                    $i['employee_sid'] = $employeeDetails['sid'];
                    if (isset($v['requested_date'])) {

                        $s = $e = $i['request_to_date'] = $i['request_from_date'] = DateTime::createFromFormat('m/d/Y', $v['requested_date'])->format('Y-m-d');
                    }
                    //
                    if (isset($v['requested_from_date'])) {
                        $i['request_from_date'] = DateTime::createFromFormat('m/d/Y', $v['requested_from_date'])->format('Y-m-d');
                        $s = $i['request_from_date'];
                    }
                    if (isset($v['requested_to_date'])) {
                        $i['request_to_date'] = DateTime::createFromFormat('m/d/Y', $v['requested_to_date'])->format('Y-m-d');
                        $e = $i['request_to_date'];
                    }
                    $i['is_partial_leave'] = strtolower($v['partial_leave']) == 'no' ? 0 : 1;
                    $i['partial_leave_note'] = $v['partial_leave_note'];
                    $i['status'] = strtolower($v['status']);
                    $i['level_status'] = $i['status'];
                    $i['reason'] = $v['reason'];
                    $i['requested_time'] = $this->calculateFormatMinutes(0, $v['requested_hours'], 0, $employeeDetails['user_shift_hours'] + (round($employeeDetails['user_shift_minutes'] / 60, 2)));
                    $i['creator_sid'] = $i['approved_by'] = $formpost['employeeSid'];
                    $i['level_at'] = 3;
                    // Set timeoff days
                    //
                    $s = date_create($s);
                    $e = date_create($e);
                    $d = date_diff($s, $e);
                    //
                    $i['timeoff_days'] = [];
                    //
                    // if($d->days > 1){
                    //     $perDayTime =  $v['requested_hours'] / 8;
                    //     for($j = 0; $j < $i; $j++){

                    //     }
                    //     $ra = [];
                    //     $ra['date'] = '';
                    //     $ra['partial'] = '';
                    //     $ra['time'] = '';
                    // }
                    //
                    $i['timeoff_days'] = json_encode($i['timeoff_days']);

                    // Check if already have a timeoff
                    $hasTimeOff = $this->timeoff_model->hasTimeOff(
                        $i['request_from_date'],
                        $i['employee_sid'],
                        $policyId
                    );
                    //
                    if ($hasTimeOff) {
                        $existCount++;
                        continue;
                    }
                    //
                    $lastId = $this->timeoff_model->insertTimeOffRequestFromImport($i);
                    //
                    if (!$lastId) $failCount++;
                    else $insertCount++;
                    // Set request assignment
                    $i = array();
                    $i['timeoff_request_sid'] = $policyId;
                    $i['employee_sid'] = $employeeDetails['sid'];
                    $i['role'] = 'approver';
                    $i['is_responded'] = 1;
                    $i['status'] = 1;
                    //
                    $assignmentId = $this->timeoff_model->insertTimeOffRequestAssignmentFromImport($i);

                    // Set request history
                    $i = array();
                    $i['timeoff_request_sid'] = $policyId;
                    $i['timeoff_request_assignment_sid'] = $assignmentId;
                    $i['reason'] = $v['comment'];
                    $i['status'] = 'approved';
                    //
                    $this->timeoff_model->insertTimeOffRequestHistoryFromImport($i);
                }
                //
                $this->res['Status'] = true;
                $this->res['Inserted'] = $insertCount;
                $this->res['Existed'] = $existCount;
                $this->res['Failed'] = $failCount;
                $this->res['Response'] = 'Time off requests imported.';
                $this->resp();
                break;

                // Update sort codes



                // Fetch company policy list
            case 'get_company_policies_list':
                //
                $policies = $this->timeoff_model->getCompanyPolicies($formpost);
                if (!sizeof($policies)) {
                    $this->res['Response'] = 'No policies found.';
                    $this->resp();
                }
                $this->res['Data'] = $policies;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                // Fetch company types list


                // Gey company types list


                // Policy Types
                // Add policy type
            case 'add_company_catgeory':
                // Check if type already exists
                $alreadyExists = $this->timeoff_model->checkIfTypeAlreadyExists($formpost);
                if ($alreadyExists) {
                    $this->res['Response'] = 'Type name already exists.';
                    $this->resp();
                }
                // Insert category list id
                $typeId = $this->timeoff_model->insertCompanyType($formpost);
                if ((int)$typeId === 0) {
                    $this->res['Response'] = 'Something went wrong while adding Type.';
                    $this->resp();
                }
                // Drop all the attached policies
                $this->timeoff_model->dropPolicyCategories($typeId);
                // Attach category with selected policies
                if (is_array($formpost['policies']) && sizeof($formpost['policies'])) {
                    foreach ($formpost['policies'] as $k => $v) {
                        $this->timeoff_model->linkCategoryToPolicy(array(
                            'timeoff_policy_sid' => $v,
                            'timeoff_category_sid' => $typeId
                        ));
                    }
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Type has been added.';
                $this->resp();
                break;

                // Edit policy type
            case 'edit_company_catgeory':
                // Check if type already exists
                $alreadyExists = $this->timeoff_model->checkIfTypeAlreadyExists($formpost);
                if ($alreadyExists) {
                    $this->res['Response'] = 'Type name already exists.';
                    $this->resp();
                }
                // Insert category list id
                $typeId = $formpost['typeSid'];
                $this->timeoff_model->updateCompanyType($formpost);
                // Drop all the attached policies
                $this->timeoff_model->dropPolicyCategories($typeId);
                // Attach category with selected policies
                if (is_array($formpost['policies']) && sizeof($formpost['policies'])) {
                    foreach ($formpost['policies'] as $k => $v) {
                        $this->timeoff_model->linkCategoryToPolicy(array(
                            'timeoff_policy_sid' => $v,
                            'timeoff_category_sid' => $typeId
                        ));
                    }
                }
                $this->res['Status'] = true;
                $this->res['Response'] = 'Type has been updated.';
                $this->resp();
                break;

                // Archive company type
            case 'archive_company_type':
                // Get plans
                $this->timeoff_model->updateCompanyTypeWithData(
                    $formpost['typeSid'],
                    array('is_archived' => 1)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Type is marked as archive.';
                $this->resp();
                break;

                // Activate company type
            case 'activate_company_type':
                // Get plans
                $this->timeoff_model->updateCompanyTypeWithData(
                    $formpost['typeSid'],
                    array('is_archived' => 0)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Type is  marked as activate.';
                $this->resp();
                break;

                //   
            case 'add_attachment_to_request':
                if (!sizeof($_FILES)) {
                    $this->res['Response'] = 'Failed to save attachment.';
                    $this->resp();
                }
                // Set insert array
                $ins = array();
                $ins['timeoff_request_sid'] = $formpost['requestSid'];
                $ins['document_title'] = $formpost['title'];
                $ins['document_type'] = 'uploaded';
                $ins['s3_filename'] = put_file_on_aws(key($_FILES));
                // Insert attachment
                $this->timeoff_model->insertAttachment(
                    $ins
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Time off has been created.';
                $this->resp();
                break;

                //   
            case 'attach_document_to_request':
                if (!sizeof($_FILES) && !isset($formpost['attachmentSid'])) {
                    $this->res['Response'] = 'Failed to save attachment.';
                    $this->resp();
                }
                // Set insert array
                $ins = array();
                $ins['timeoff_request_sid'] = $formpost['requestSid'];
                $ins['document_title'] = $formpost['title'];
                $ins['document_type'] = 'uploaded';
                if (sizeof($_FILES))
                    $this->res['S3_filename'] = $ins['s3_filename'] = put_file_on_aws(key($_FILES));
                if (isset($formpost['attachmentSid'])) {
                    // Update attachment
                    $this->timeoff_model->updateAttachment(
                        $ins,
                        $formpost['attachmentSid']
                    );
                } else {
                    // Insert attachment
                    $attachmentSid = $this->timeoff_model->insertAttachment(
                        $ins
                    );
                }
                //
                $this->res['AttachmentSid'] = isset($formpost['attachmentSid']) ? $formpost['attachmentSid'] : $attachmentSid;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Document has been attached.';
                $this->resp();
                break;

                //   
            case 'remove_attachment':
                // Insert attachment
                $this->timeoff_model->removeAttachment(
                    $formpost['attachmentSid']
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Document has been attached.';
                $this->resp();
                break;

                //   
            case 'update_fmla':
                // Get the latest description
                $fmla = $this->timeoff_model->getLatestFMLAData($formpost['requestSid']);
                //
                if (!sizeof($fmla)) {
                    $this->res['Response'] = 'Failed to verify FMLA';
                    $this->resp();
                }
                //
                $fmlaJSON = @json_decode($fmla['serialized_data'], true);
                $fmlaJSON['employer'] = $formpost['fmla'];
                // Update FMLA employer section
                $this->timeoff_model->udateFMLAEmployerSection(
                    $formpost['requestSid'],
                    array('serialized_data' => @json_encode($fmlaJSON))
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Employer section has been updated.';
                $this->resp();
                break;

                //   
            case 'get_generated_fmla_view':
                // Fetch data by sid
                $fmla = $this->timeoff_model->getFMLADetailsBySid($formpost['fmla']['id']);
                if (!sizeof($fmla)) {
                    $this->res['Response'] = 'Failed to verify FMLA.';
                    $this->resp();
                }
                // Generate view
                $this->res['View'] = $this->load->view('timeoff/fmla/preview/' . ($formpost['fmla']['slug']) . '', array('FMLA' => $fmla, 'Data' => $data), true);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Employer section has been updated.';
                $this->resp();
                break;

                //   
            case 'update_fmla_attachment':
                if (!sizeof($_FILES)) {
                    $this->res['Response'] = 'Failed to upload FMLA.';
                    $this->resp();
                }
                // Set update array
                $ins = array();
                $ins['s3_filename'] = put_file_on_aws(key($_FILES));
                // Insert attachment
                $this->timeoff_model->updateAttachment(
                    $ins,
                    $formpost['attachmentSid']
                );
                //
                $this->res['Status'] = true;
                $this->res['S3_filename'] = $ins['s3_filename'];
                $this->res['AttachmentSid'] = $formpost['attachmentSid'];
                $this->res['Response'] = 'FMLA form updated.';
                $this->resp();
                break;

                //   
            case 'get_company_holidays':
                // Get holidays
                $holidays = $this->timeoff_model->getCompanyHolidays(
                    $formpost,
                    $formpost['page'],
                    $this->limit
                );
                //
                if (!sizeof($holidays)) {
                    $this->res['Response'] = 'Oops! looks like you haven\'t created any holidays yet.';
                    $this->resp();
                }
                //
                if ($formpost['page'] == 1) {
                    $this->res['Limit'] = $this->limit;
                    $this->res['ListSize'] = 5;
                    $this->res['TotalRecords'] = $holidays['Count'];
                    $this->res['TotalPages'] = ceil($this->res['TotalRecords'] / $this->res['Limit']);
                }
                //
                $this->res['Data'] = $holidays['Holidays'];
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

                //   


                // Archive company plans
            case 'archive_company_holiday':
                // Get plans
                $this->timeoff_model->updateCompanyHolidayWithData(
                    $formpost['holidaySid'],
                    array('is_archived' => 1)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Holiday is marked as archive.';
                $this->resp();
                break;

                // Activate company plan
            case 'activate_company_holiday':
                // Get plans
                $this->timeoff_model->updateCompanyHolidayWithData(
                    $formpost['holidaySid'],
                    array('is_archived' => 0)
                );
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Holiday is marked as activate.';
                $this->resp();
                break;

                // Get company distinct years


                // Add company holiday
            case 'add_company_holiday':

                break;

                // Edit company holiday
            case 'edit_company_holiday':
                // Check if holiday alreadu exists
                $isExists = $this->timeoff_model->companyHolidayExists($formpost);
                if ($isExists) {
                    $this->res['Response'] = 'Holiday already exists.';
                    $this->resp();
                }
                $formpost['fromDate'] = DateTime::createFromFormat('m-d-Y', $formpost['fromDate'])->format('Y-m-d');
                $formpost['toDate'] = DateTime::createFromFormat('m-d-Y', $formpost['toDate'])->format('Y-m-d');
                //
                $this->timeoff_model->updateCompanyHoliday(array(
                    'company_sid' => $formpost['companySid'],
                    'creator_sid' => $formpost['employerSid'],
                    'holiday_year' => $formpost['year'],
                    'holiday_title' => $formpost['title'],
                    'frequency' => $formpost['frequency'],
                    'sort_order' => $formpost['sortOrder'],
                    'icon' => $formpost['icon'],
                    'from_date' => $formpost['fromDate'],
                    'to_date' => $formpost['toDate'],
                    'is_archived' => $formpost['archiveCheck'],
                    'work_on_holiday' => $formpost['workOnHoliday']
                ), $formpost['holidaySid']);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Holiday has been updated successfully.';
                $this->res['InsertId'] = $formpost['holidaySid'];
                $this->resp();
                break;

                // Get single company holiday
            case 'get_single_company_holiday':


                // Get distinct holiday dates
            case 'get_holiday_dates':
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $this->timeoff_model->getDistinctHolidayDates($formpost);
                $this->resp();
                break;
                // Export time off
            case 'export':
                //
                $data = $this->timeoff_model->getDataForExport($post);

                // _e
                //  die();

                //$data = $this->timeoff_model->getDataForExport($formpost);
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                $this->resp();
                break;
                //

                // get employee balance history
            case 'get_employee_balance_history':

                //
            case 'export_by_sids':
                set_time_limit(120);

                // _e($post['ids'],true,true);


                $data = $this->timeoff_model->getTimeOffByIds(
                    $post['companySid'],
                    $post['ids']
                );
                //
                if (!count($data)) {
                    $this->res['Response'] = 'Records not found.';
                    $this->resp();
                }

                //
                $path = FCPATH . 'temp_files/timeoff/' . $post['fileName'] . '/' . $post['employeeName'] . '/';
                //
                if (!is_dir($path)) mkdir($path, 0777, true);
                //
                $f = fopen($path . 'timeoff.csv', 'w');
                //

                // fputcsv($f, [
                //     'Request Reference',
                //     'Employee Name',
                //     'Policy Type',
                //     'Policy Title',
                //     'Start Date',
                //     'End Date',
                //     'Status',
                //     'Level',
                //     'Reason',
                //     'Partial Leave',
                //     'Partial Note',
                //     'Comments',
                //     'Total Days',
                //     'Total Minutes',
                //     'Breakdown',
                //     'Attachments'
                // ]);
                // foreach ($data as $timeoff) {
                //     $t = [];
                //     $t[] = $timeoff['requestId'];
                //     $t[] = remakeEmployeeName($timeoff, true);
                //     $t[] = $timeoff['Category'];
                //     $t[] = $timeoff['policy_title'];
                //     $t[] = $timeoff['requested_date'];
                //     $t[] = $timeoff['request_to_date'];
                //     $t[] = $timeoff['status'];
                //     $t[] = $timeoff['level_at'] == 1 ? 'Team Lead' : ($timeoff['level_at'] == 2 ? "Supervisor" : "Approver");
                //     $t[] = $timeoff['reason'];
                //     $t[] = $timeoff['is_partial_leave'] == 1 ? "Yes" : "No";
                //     $t[] = $timeoff['partial_leave_note'];
                //     // Comments
                //     $a = '';
                //     if (count($timeoff['History'])) {
                //         foreach ($timeoff['History'] as $b) {
                //             $a .= "Employee: " . remakeEmployeeName($b) . "\n";
                //             $a .= "Status: " . $b["status"] . "\n";
                //             $a .= "Comment: " . $b["comment"] . "\n\n";
                //         }
                //     }
                //     $t[] = $a;
                //     $t[] = str_replace(',', ' - ', $timeoff['timeoff_breakdown']['text']);
                //     $t[] = $timeoff['timeoff_breakdown']['M']['minutes'];
                //     // Breakdown
                //     $breakdown = json_decode($timeoff['timeoff_days'], true);
                //     //
                //     $a = '';
                //     if (count($breakdown)) {
                //         foreach ($breakdown as $b) {
                //             $a .= "Day Type: " . (!isset($b["partial"]) ? 'fullday' : $b['partial']) . " | Date: " . $b['date'] . " | Minutes: " . $b['time'] . "\n\n";
                //         }
                //     }
                //     //
                //     $t[] = $a;
                //     // Attachments
                //     //
                //     $a = '';
                //     if (count($timeoff['Attachments'])) {
                //         $a = implode("\n", array_column($timeoff['Attachments'], 's3_filename'));
                //         // Check and set attachment path
                //         $aPath = $path . 'attachments/' . $timeoff['requestId'];
                //         //
                //         if (!is_dir($aPath)) mkdir($aPath, 0777, true);
                //         //
                //         foreach ($timeoff['Attachments'] as $b) {
                //             if (empty($b['s3_filename'])) continue;
                //             $fp = fopen($aPath . '/' . $b['s3_filename'], 'w+');
                //             //Here is the file we are downloading, replace spaces with %20
                //             $ch = curl_init(str_replace(" ", "%20", AWS_S3_BUCKET_URL . $b['s3_filename']));
                //             curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                //             // write curl response to file
                //             curl_setopt($ch, CURLOPT_FILE, $fp);
                //             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                //             // get curl response
                //             curl_exec($ch);
                //             curl_close($ch);
                //             fclose($fp);
                //             //
                //             usleep(250);
                //         }
                //     }
                //     $t[] = $a;
                //     //
                //     fputcsv($f, $t);
                // }
                // //
                // fclose($f);
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed';
                $this->resp();
                break;

            case 'add-employee-accural-settings':
                //
                $insert_data['company_id'] = $post['companyId'];
                $insert_data['employee_id'] = $post['employeeId'];
                $insert_data['employer_id'] = $post['employerId'];
                $insert_data['employee_minimum_applicable_hours'] = $post['employeeMinimumApplicableHours'];
                $insert_data['employee_minimum_applicable_time'] = $post['employeeMinimumApplicableTimeAdd'];

                $this->timeoff_model->addEmployeeAccuralSettings($insert_data);
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Employee Accural Settings Saved Sucessfully';
                $this->resp();
                break;

            case 'get_company_employees_for_accrual_settings':
                // Check if policy already exists for current company
                $employees = $this->timeoff_model->getCompanyEmployeesForAccrualSettings(
                    $post['companyId'],
                    $post['employerId'],
                    $post["all"] ?? 0
                );

                if (!sizeof($employees)) {
                    $this->res['Response'] = 'We are unable to find employee(s). Please, add employee(s) from "Create employee" page.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $employees;
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;

            case 'update-employee-accural-settings':
                //
                $update_data['employee_minimum_applicable_hours'] = $post['employeeMinimumApplicableHours'];
                $update_data['employee_minimum_applicable_time'] = $post['employeeMinimumApplicableTimeAdd'];
                $sid = $post['sid'];
              
                $this->timeoff_model->updateEmployeeAccuralSettings($update_data, $sid);
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Employee Accural Settings Updated Sucessfully';
                $this->resp();
                break;

            case 'delete-employee-accural-settings':
                // Check if policy already exists for current company
                $employees = $this->timeoff_model->deleteEmployeeAccuralSettings(
                    $post['sid']
                );
                //
                $this->res['Code'] = 'SUCCESS';
                $this->res['Status'] = true;
                $this->res['Response'] = 'Employee Accural Settings Deleted Sucessfully';
                $this->resp();
                break;
        }
        //
        $this->resp();
    }

    function calendar_timeoff_handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        $formpost = $this->input->post(NULL, TRUE);

        // Check post size and action
        if (!sizeof($formpost) || !isset($formpost['action'])) $this->resp();
        if (!isset($formpost['companySid']) || $formpost['companySid'] == '') $this->resp();
        //
        $data = array();
        $this->res['Redirect'] = $this->check_login($data, true);
        // For expired session
        if ($this->res['Redirect'] == false) {
            $this->res['Response'] = 'Your login session has expired.';
            $this->resp();
        }
        $this->res['Redirect'] = FALSE;
        switch (strtolower($formpost['action'])) {
            case 'get_single_request':
                // Get policies
                $request = $this->timeoff_model->getSingleRequestForCalendar(
                    $formpost
                );
                //
                if (!sizeof($request)) {
                    $this->res['Response'] = 'Failed to verify request.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $request;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
            case 'update_employee_timeoff':
                // update 
                $request = $this->timeoff_model->updateEmployeeTimeOff(
                    $formpost
                );
                // if(isset($formpost['sendEmail']) && $formpost['sendEmail'] == 'yes'){
                //     $this->sendEmailToTlsAndRequester($formpost, $data);
                // }

                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Time off request is updated.';
                $this->resp();
                break;
        }
        $this->resp();
    }
    /*
    *******************************************************************************************
     PRIVATE FUNCTIONS
    *******************************************************************************************
    */

    //
    private function sendEmailOtherApprovers($post)
    {
        $request = $this->timeoff_model->getRequestById($post['requestId']);
        $approver_sid = $post['employerId'];
        $approver_arr = getUserNameBySID($approver_sid, false);
        $approver_name = ucwords($approver_arr[0]['first_name'] . ' ' . $approver_arr[0]['last_name']);
        $other_approvers = $this->timeoff_model->getEmployeeApprovers($request['company_sid'], $request['employee_sid']);
        //
        $approverTemplate = $this->timeoff_model->getEmailTemplate(APPROVER_TIMEOFF_REQUEST);
        $CHF = message_header_footer($request['company_sid'], $request['CompanyName']);
        //
        $lastApprover = [];
        //
        if (!empty($request['history'])) {
            foreach ($request['history'] as $ap) {
                //
                if (!empty($lastApprover)) {
                    continue;
                }
                //
                $det = json_decode($ap['note']);
                //
                if ($det->canApprove) {
                    $lastApprover = [
                        'Id' => $ap['userId'],
                        'Name' => ucwords($ap['first_name'] . ' ' . $ap['last_name']),
                        'Comment' => $det->comment
                    ];
                }
            }
        }
        // 
        foreach ($other_approvers as $approver) {
            if ($approver['approver_percentage'] == 1) {
                //
                $eRP['{{approver_first_name}}'] = $approver['first_name'];
                $eRP['{{approver_last_name}}'] = $approver['last_name'];
                $eRP['{{approver_name}}'] = $approver_name;

                $eRP['{{reason}}'] = '<p style="font-style: italic;font-size: 20px;"><strong>' . strip_tags($request['reason']) . '</strong></p>';
                $eRP['{{policy_name}}'] = $request['title'];
                $eRP['{{requested_date}}'] =  $request['request_from_date'] == $request['request_to_date'] ?
                    DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') :
                    DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') . ' - ' . DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D');

                $eRP['{{last_approver}}'] = $lastApprover['Name'];
                $eRP['{{company_name}}'] = $request['CompanyName'];
                $eRP['{{approvers_comment}}'] = '<br><p><strong>Comment from ' . ($eRP['{{approver_name}}']) . ':</strong> ' . ($post['comment']) . '</p>';
                $eRP['{{approver_comment}}'] = '<br><p><strong>Comment from ' . ($eRP['{{last_approver}}']) . ':</strong> ' . ($lastApprover['Comment']) . '</p>';
                $eRP['{{requester_name}}'] = ucwords($request['first_name'] . ' ' . $request['last_name']);
                //
                if ($post['status'] == "approved") {
                    $eRP['{{previous_status}}'] = 'Rejected';
                    $eRP['{{new_status}}'] = 'Approve';
                    $eRP['{{public_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetApproverEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'approved'
                            ]),
                            '{{color}}' => '#28a745',
                            '{{text}}' => 'View Detail'
                        ]
                    );
                } else if ($post['status'] == "rejected") {
                    $eRP['{{previous_status}}'] = 'Approved';
                    $eRP['{{new_status}}'] = 'Reject';
                    $eRP['{{public_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetApproverEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'rejected'
                            ]),
                            '{{color}}' => '#dc3545',
                            '{{text}}' => 'Reject Request'
                        ]
                    );
                }

                //
                $approverTemplateI = timeoffMagicQuotesReplace($approverTemplate, $eRP);

                //
                log_and_sendEmail(
                    $approverTemplateI['FromEmail'],
                    $approver['email'],
                    $approverTemplateI['Subject'],
                    $CHF['header'] . $approverTemplateI['Body'] . $CHF['footer'],
                    $approverTemplateI['FromName']
                );
            }
        }
    }

    private function sendEmailToInformApprovers($requestId)
    {
        $request = $this->timeoff_model->getRequestById($requestId);
        $approver_sid = $post['employerId'];
        $user_arr = getUserNameBySID($employeeId, false);
        $user_name = ucwords($user_arr[0]['first_name'] . ' ' . $user_arr[0]['last_name']);
        $approvers_list = $this->timeoff_model->getEmployeeApprovers($request['company_sid'], $request['employee_sid']);
        //
        $approverTemplate = $this->timeoff_model->getEmailTemplate(CANCELED_TIMEOFF_REQUEST);
        $CHF = message_header_footer($request['company_sid'], $request['CompanyName']);
        //
        foreach ($approvers_list as $approver) {
            //
            $eRP['{{approver_first_name}}'] = $approver['first_name'];
            $eRP['{{approver_last_name}}'] = $approver['last_name'];
            $eRP['{{approver_name}}'] = $approver_name;
            //
            $eRP['{{reason}}'] = $request['reason'];
            $eRP['{{policy_name}}'] = $request['title'];
            $eRP['{{requested_date}}'] =  $request['request_from_date'] == $request['request_to_date'] ?
                DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') :
                DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') . ' - ' . DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D');
            //
            $eRP['{{company_name}}'] = $request['CompanyName'];
            $eRP['{{requester_name}}'] = ucwords($request['first_name'] . ' ' . $request['last_name']);
            //
            $approverTemplateI = timeoffMagicQuotesReplace($approverTemplate, $eRP);
            //
            log_and_sendEmail(
                $approverTemplateI['FromEmail'],
                $approver['email'],
                $approverTemplateI['Subject'],
                $CHF['header'] . $approverTemplateI['Body'] . $CHF['footer'],
                $approverTemplateI['FromName']
            );
        }
    }

    function approver_public($varification_key)
    {
        $decrypted_key = timeoffDecryptLink($varification_key);
        // echo '<pre>';
        // print_r($decrypted_key);

        //
        $request_id         = $decrypted_key['requestSid'];
        $company_sid        = $decrypted_key['companySid'];
        $companyName        = $decrypted_key['companyName'];
        $approver_sid       = $decrypted_key['employerSid'];
        $required_status    = $decrypted_key['typeSid'];
        //
        if ($_POST) {

            $this->form_validation->set_rules('comment', 'Comment', 'required');

            if ($this->form_validation->run() == FALSE) {
                //------------errors stored in array------------//
                $errorsArray['comment']    =   form_error('comment');
                $data["errors"] = $errorsArray;
            } else {
                //
                $in = [];
                //
                $in['status'] = $_POST['status'];
                //
                $this->timeoff_model->updateTable($in, $_POST['request_sid'], 'timeoff_requests');
                //
                $in = [];
                $in['request_sid'] = $_POST['request_sid'];
                $in['employee_sid'] = $_POST['employer_sid'];
                $in['action'] = 'update';
                $in['comment'] = $_POST['comment'];
                $in['note'] = json_encode([
                    'status' => $_POST['status'],
                    'canApprove' => 1,
                    'comment' => $_POST['comment'],
                    'details' => []
                ]);
                //
                $this->timeoff_model->insertHistory($in, 'timeoff_request_timeline');
                // Send email notifications
                $this->sendNotifications($_POST['request_sid'], $_POST['status']);
                //
                $this->load->view('timeoff/thank_you');
            }
        }
        //
        $employeeName = getUserNameBySID($approver_sid);
        $employee_info = get_employee_profile_info($approver_sid);
        //
        $request = $this->timeoff_model->getRequestById($request_id);
        $approvers = $this->timeoff_model->getEmployeeApprovers($company_sid, $request['userId']);
        $policies = $this->timeoff_model->getEmployeePoliciesById($company_sid, $request['employee_sid']);
        //
        $data = array();
        $data['title']              = 'Time-off';
        $data['user_first_name']    = $employee_info['first_name'];
        $data['user_last_name']     = $employee_info['last_name'];
        $data['user_email']         = $employee_info['email'];
        $data['user_phone']         = $employee_info['PhoneNumber'];
        $data['user_picture']       = $employee_info['profile_picture'];
        $data['company_sid']        = $company_sid;
        $data['company_name']       = $companyName;
        $data['companyName']        = $companyName;
        $data['employeeName']       = $employeeName;
        $data['employerId']         = $approver_sid;
        $data['requestId']          = $request_id;
        $data['request']            = $request;
        $data['policies']           = $policies;
        $data['approvers']          = $approvers;
        $data['employee_sid']       = $request['employee_sid'];
        $data['varification_key']   = $varification_key;

        if ($request['status'] == 'rejected') {
            $data['request_status'] = 'approved';
        } else {
            $data['request_status'] = 'rejected';
        }

        //
        $data['allow_update'] = 'yes';
        //
        // echo $request['status'];
        // die();
        if ($request['status'] == $required_status) {
            $data['allow_update'] = 'no';
        }
        //
        $this->load->view('onboarding/onboarding_public_header', $data);
        $this->load->view('timeoff/approver_public_link');
        $this->load->view('onboarding/onboarding_public_footer');
    }

    //
    private function sendEmailToTlsAndRequester($formpost, $data)
    {
        $CHF = message_header_footer($formpost['companySid'], $data['company_name']);
        // Get the template
        $body  = '<p>Dear <b>{{TO_FULLNAME}}</b>,</p>';
        $body .= '<p>{{EXP}} time off request has been {{TIMEOFF_STATUS}}. Below are the time off request details.</p>';
        $body .= '<p><strong>Policy:</strong> {{POLICY_NAME}}</p>';
        $body .= '<p><strong>Time Off Date:</strong> {{REQUESTED_DATE}}</p>';
        $body .= '<p><strong>Requested Time:</strong> {{REQUESTED_HOURS}}</p>';
        $body .= '<p><strong>Request Details:</strong> {{REQUEST_DETAILS}}</p>';
        $body .= '<p><strong>Status:</strong> {{TIMEOFF_STATUS}}</p>';
        $body .= '<p><strong>Approver\'s Comment:</strong> {{REASON}} </p>';
        $body .= '<br />';
        //
        $i = array(
            '{{TO_FULLNAME}}',
            '{{POLICY_NAME}}',
            '{{REQUESTED_DATE}}',
            '{{REQUESTED_HOURS}}',
            '{{REQUEST_DETAILS}}',
            '{{REASON}}',
            '{{TIMEOFF_STATUS}}',
            '{{EXP}}'
        );
        //
        $v = array();
        $v[0] = '<b>' . ($formpost['request']['full_name']) . '</b>';
        $v[1] = $formpost['request']['policy_title'];
        $v[2] = DateTime::createFromFormat(
            'm/d/Y',
            $formpost['startDate']
        )->format('m-d-Y') . ' - ' . DateTime::createFromFormat(
            'm/d/Y',
            $formpost['endDate']
        )->format('m-d-Y');
        $v[3] = $formpost['requestedTimeDetails']['formated'];
        $requesterArray = [];
        $requesterArray['request_details'] = '';
        foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
            $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
            $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
            $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                $v1['time'],
                $formpost['timeslot'],
                $formpost['slug']
            )['text']) . '</p>';
        }
        $v[4] = $requesterArray['request_details'];
        $v[5] = $formpost['comment'] == '' ? 'N/A' : $formpost['comment'];
        $v[6] = ucwords($formpost['status']);

        $v[7] = 'Your';
        // Send email to requester
        log_and_sendEmail(
            FROM_EMAIL_NOTIFICATIONS,
            $formpost['request']['email'],
            'Time off has ' . ($formpost['status']) . '',
            $CHF['header'] . str_replace($i, $v, $body) . $CHF['footer'],
            $data['company_name']
        );

        $sentEmployeeArray = array();
        if (isset($formpost['tls'])) {
            // Get request employee id
            $requesterId = $this->timeoff_model->getRequestFieldById($formpost['requestId'], 'employee_sid');
            // Fetch latest tls
            $newTLS = $this->timeoff_model->fetchTimeOffEmployers(
                $requesterId,
                $formpost['companySid']
            );
            //
            foreach ($formpost['tls'] as $tl) {
                // Making sure emails will not go out to other company employees ever
                if ($this->checkEmployeeCompany($tl, $formpost)) continue;
                // Make sure this employer still has access to the request
                if (
                    !$this->checkEmployeeRequestAccess(
                        $formpost['requestId'],
                        $newTLS,
                        $tl
                    )
                ) continue;
                //
                if (isset($sentEmployeeArray[$tl['employee_sid']])) continue;
                $sentEmployeeArray[$tl['employee_sid']] = true;
                // / Logic goes here
                $v[0] = '<b>' . $tl['full_name'] . '</b>';
                $v[7] = $formpost['request']['full_name'];
                // Email sender
                log_and_sendEmail(
                    FROM_EMAIL_NOTIFICATIONS,
                    $tl['email'],
                    'Time off request has been ' . ($formpost['status']) . ' ',
                    $CHF['header'] . str_replace($i, $v, $body) . $CHF['footer'],
                    $data['company_name']
                );
            }
        }
    }

    //
    private function sendEmailToTls($formpost, $data, $role)
    {
        // Fetch departments and add entry
        $tls = $this->timeoff_model->fetchTimeOffHandlers(
            isset($formpost['requesterSid']) ? $formpost['requesterSid'] : $formpost['employeeSid'],
            $formpost['companySid'],
            $role
        );
        // Add team leads, supervisors, approvers
        if (sizeof($tls)) {
            $this->load->library('encryption', null, 'enc');
            $this->load->model('Timeoff_model');
            // Get requester id
            $requesterId = $this->timeoff_model->getRequestFieldById($formpost['requestId'], 'employee_sid');
            // Fetch latest tls
            $newTLS = $this->timeoff_model->fetchTimeOffEmployers(
                $requesterId,
                $formpost['companySid']
            );

            foreach ($tls as $tl) {
                $tl = (array) $tl;
                // Making sure emails will not go out to other company employees ever
                if ($this->checkEmployeeCompany($tl, $formpost)) continue;
                // Make sure this employer still has access to the request
                // Make sure this employer still has access to the request
                if (
                    !$this->checkEmployeeRequestAccess(
                        $formpost['requestId'],
                        $newTLS,
                        $tl
                    )
                ) continue;
                //
                // Assign
                $assignId = $this->timeoff_model->checkAndAssignEmployeeToRequest(
                    array(
                        'timeoff_request_sid' => $formpost['requestId'],
                        'employee_sid' => $tl['id'],
                        'role' => $tl['type'],
                        'status' => 1
                    )
                );

                $requesterArray = array();
                $requesterArray['single_day_leave'] = 0;
                $requesterArray['to_full_name'] = $tl['full_name'];
                $requesterArray['exp'] = '<b>' . ($formpost['request']['full_name']) . '</b> has ';
                $requesterArray['policy_name'] = $formpost['policyName'];
                $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                if ($leave_start_date == $leave_end_date) {
                    $requesterArray['single_day_leave'] = 1;
                }

                $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                $requesterArray['request_details'] = '';
                foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                    $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                    $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                    $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                        $v1['time'],
                        $formpost['timeslot'],
                        $formpost['slug']
                    )['text']) . '</p>';
                }
                $requesterArray['reason']  = $formpost['request']['reason'];
                $requesterArray['type']  = 'tls';
                $requesterArray['request_status']  = 'Pending';
                $requesterArray['rejectBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=reject&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['approveBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=approve&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['viewBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=pending&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['cancelBtnToken']  = '';
                // Send an email to Requester
                $emailBody = getTimeOffEmailTemplate(
                    $requesterArray,
                    $formpost['companySid'],
                    $data['company_name']
                );
                // Email sender

                log_and_sendEmail(
                    FROM_EMAIL_NOTIFICATIONS,
                    $tl['email'],
                    'Time Off request from ' . ($formpost['request']['full_name']) . '',
                    $emailBody,
                    $data['company_name']
                );
            }
        }
    }

    //
    private function sendChangeEmailToTls($formpost, $data, $role)
    {
        // Fetch departments and add entry
        $tls = $this->timeoff_model->fetchTimeOffHandlers(
            isset($formpost['requesterSid']) ? $formpost['requesterSid'] : $formpost['employeeSid'],
            $formpost['companySid'],
            $role
        );
        // Add team leads, supervisors, approvers
        if (sizeof($tls)) {
            $this->load->library('encryption', null, 'enc');
            $this->load->model('Timeoff_model');
            // Get request employee id
            $requesterId = $this->timeoff_model->getRequestFieldById($formpost['requestId'], 'employee_sid');
            // Fetch latest tls
            $newTLS = $this->timeoff_model->fetchTimeOffEmployers(
                $requesterId,
                $formpost['companySid']
            );
            //
            foreach ($tls as $tl) {
                $tl = (array) $tl;
                // Making sure emails will not go out to other company employees ever
                if ($this->checkEmployeeCompany($tl, $formpost)) continue;
                // Make sure this employer still has access to the request
                if (
                    !$this->checkEmployeeRequestAccess(
                        $formpost['requestId'],
                        $newTLS,
                        $tl
                    )
                ) continue;
                // Assign
                $assignId = $this->timeoff_model->checkAndAssignEmployeeToRequest(
                    array(
                        'timeoff_request_sid' => $formpost['requestId'],
                        'employee_sid' => $tl['id'],
                        'role' => $tl['type'],
                        'status' => 1
                    )
                );

                $requesterArray = array();
                $requesterArray['single_day_leave'] = 0;
                $requesterArray['to_full_name'] = $tl['full_name'];
                $requesterArray['exp'] = '<b>' . (isset($formpost['employeeFullName']) ? $formpost['employeeFullName'] : $formpost['request']['full_name']) . '</b> has ';
                $requesterArray['policy_name'] = $formpost['policyName'];
                $leave_start_date = DateTime::createFromFormat('m/d/Y', $formpost['startDate'])->format('m-d-Y');
                $leave_end_date = DateTime::createFromFormat('m/d/Y', $formpost['endDate'])->format('m-d-Y');

                if ($leave_start_date == $leave_end_date) {
                    $requesterArray['single_day_leave'] = 1;
                }

                $requesterArray['requested_date'] = $leave_start_date . ' - ' . $leave_end_date;
                $requesterArray['requested_time'] = $formpost['requestedTimeDetails']['formated'];
                $requesterArray['request_details'] = '';
                foreach ($formpost['requestedDays']['days'] as $k1 => $v1) {
                    $requesterArray['request_details'] .= '<p><strong>Date: </strong>' . ($v1['date']) . '</p>';
                    $requesterArray['request_details'] .= '<p><strong>Partial Day: </strong>' . ($v1['partial'] == 'fullday' ? 'No' : 'Yes') . '</p>';
                    $requesterArray['request_details'] .= '<p><strong>Time: </strong>' . (get_array_from_minutes(
                        $v1['time'],
                        $formpost['timeslot'],
                        $formpost['slug']
                    )['text']) . '</p>';
                }
                $requesterArray['reason']  = $formpost['reason'];
                $requesterArray['type']  = 'tls';
                $requesterArray['request_status']  = 'Pending';
                $requesterArray['rejectBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=reject&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['approveBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=approve&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['viewBtnToken']  = str_replace('/', '$a$b', $this->enc->encrypt(
                    'id=' . ($formpost['requestId']) . '&type=' . ($tl['type']) . '&action=pending&companyId=' . ($formpost['companySid']) . '&employeeId=' . ($tl['id']) . ''
                ));
                $requesterArray['cancelBtnToken']  = '';
                // Send an email to Requester
                $emailBody = getTimeOffEmailTemplate(
                    $requesterArray,
                    $formpost['companySid'],
                    $data['company_name'],
                    true
                );
                // Email sender

                log_and_sendEmail(
                    FROM_EMAIL_NOTIFICATIONS,
                    $tl['email'],
                    '' . (isset($formpost['employeeFullName']) ? $formpost['employeeFullName'] : $formpost['request']['full_name']) . ' updated Time Off request.',
                    $emailBody,
                    $data['company_name']
                );
            }
        }
    }

    private function pto_formated_minutes($pto_format, $default_slot, $Minutes)
    {

        $D = 0;
        $H = 0;
        $M = 0;
        $finalResult = '';
        if ($pto_format == 'D:H:M') {
            $D = (int)(($Minutes) / ($default_slot * 60));
            $H = (int)((($Minutes) % ($default_slot * 60)) / 60);
            $M = (int)((($Minutes) % ($default_slot * 60)) % 60);
            $finalResult = $D . ' Day(s) ' . $H . ' Hour(s) ' . $M . 'Minute(s)';
        } else if ($pto_format == 'H:M') {
            $H = (int)(($Minutes) / 60);
            $M = (int)(($Minutes) % 60);
            $finalResult = $H . ' Hour(s) ' . $M . 'Minute(s)';
        } else if ($pto_format == 'D') {
            $D = (($Minutes) / ($default_slot * 60)) . toFixed(2);
            $finalResult = $D . ' Day(s) ';
        } else if ($pto_format == 'M') {
            $M = $Minutes;
            $finalResult =  $M . 'Minute(s)';
        } else if ($pto_format == 'H') {
            $H = (($Minutes) / 60) . toFixed(2);
            $finalResult = $H . ' Hour(s) ';
        }
        return $finalResult;
    }

    private function changePtoStatusMainTable($pto_request_sid, $update_data)
    {
        $this->timeoff_model->changePtoStatus($pto_request_sid, $update_data); //PTO request main table
    }

    private function calculateFormatMinutes($days = 0, $hours = 0, $minutes = 0, $slot = 9)
    {
        $totalMinutes = ($days * $this->timeSlot * 60) + ($hours * 60) + $minutes;
        return $totalMinutes;
    }

    /**
     * AJAX Responder
     */
    private function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }

    /**
     * Emails Buttons
     */
    private function setEmailBtn(&$replacement_array, $public_key)
    {

        $replacement_array['approve_btn'] = '<a href="' . base_url() . 'new_pto/'  . $public_key . '/app" style="' . DEF_EMAIL_BTN_STYLE_SUCCESS . '" target="_blank">Approve</a>';
        $replacement_array['reject_btn'] = '<a href="' . base_url() . 'new_pto/'  . $public_key . '/rej" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank">Reject</a>';
        $replacement_array['cancel_btn'] = '<a href="' . base_url() . 'new_pto/'  . $public_key . '/can" style="' . DEF_EMAIL_BTN_STYLE_WARNING . '" target="_blank">Cancel</a>';
    }
    /**
     * Emails Buttons
     */
    private function setEmployeeEmailBtn(&$replacement_array, $public_key)
    {

        $replacement_array['approve_btn'] = '<a href="' . base_url() . 'employee_view/'  . $public_key . '/app" style="' . DEF_EMAIL_BTN_STYLE_SUCCESS . '" target="_blank">I Accept</a>';
        $replacement_array['reject_btn'] = '<a href="' . base_url() . 'employee_view/'  . $public_key . '/rej" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank">I Reject</a>';
        $replacement_array['cancel_btn'] = '';
    }

    /**
     * PTO email body
     */
    private function pto_email_body($employeeDetails, $ptoDetail, $designation = '')
    {

        $company_detail = $this->timeoff_model->getCompanyDetails($employeeDetails[0]['parent_sid']);
        $pto_format = $this->timeoff_model->fetchPtoFormat($company_detail['timeoff_format_sid']);
        $body  = '<strong>PTO Details are following</strong><br /><br />';
        $body .= '<p><strong>Requester:</strong> ' . ($employeeDetails[0]['full_name']) . '</p>';
        $body .= '<p><strong>Date:</strong> ' . (DateTime::createFromFormat('Y-m-d', $ptoDetail['date'])->format('M d Y, D')) . '</p>';
        $body .= '<p><strong>Requested Hours:</strong> ' . ($this->pto_formated_minutes($pto_format[0]['slug'], PTO_DEFAULT_SLOT, $ptoDetail['hours'])) . ' </p>';
        $body .= '<p><strong>Status:</strong> ' . ((empty($designation) || $designation == 'approver') ? $ptoDetail['status'] : $ptoDetail['request_status']) . '</p>';
        $body .= '<p><strong>Policy:</strong> ' . ($ptoDetail['typeName']) . '</p>';
        $body .= '<p><strong>Is this for a partial day?:</strong> ' . ($ptoDetail['isPartial'] == 1 ? 'Yes' : 'No') . '</p>';
        if ($ptoDetail['isPartial'] == 1) $body .= '<p><strong>Partial Note:</strong> ' . ($ptoDetail['partialNote']) . '</p>';
        $body .= '<br />';
        return $body;
    }

    /**
     * Check  user sessiona nd set data
     * Created on: 23-08-2019
     *
     * @param $data     Reference
     * @param $return   Bool
     * Default is 'FALSE'
     *
     * @return VOID
     */
    private function check_login(&$data, $return = FALSE)
    {
        //
        if ($this->input->post('fromPublic', true) && $this->input->post('fromPublic', true) == 1 && !$this->session->userdata('logged_in')) {
            $this->load->config('config');
            $result = $this->timeoff_model->login($this->input->post('employerSid'));

            if ($result) {
                if ($result['employer']['timezone'] == '' || $result['employer']['timezone'] == NULL || !preg_match('/^[A-Z]/', $result['employer']['timezone'])) {
                    if ($result['company']['timezone'] != '' && preg_match('/^[A-Z]/', $result['company']['timezone'])) $result['employer']['timezone'] = $result['company']['timezone'];
                    else $result['employer']['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
                }
                $data['session'] = array(
                    'company_detail' => $result["company"],
                    'employer_detail' => $result["employer"]
                );
            }
        }
        //
        if (!isset($data['session'])) {
            if (!$this->session->userdata('logged_in')) {
                if ($return) return false;
                redirect('login', 'refresh');
            }
            $data['session'] = $this->session->userdata('logged_in');
        }
        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['companyData'] = $data['session']['company_detail'];
        $data['employerData'] = $data['session']['employer_detail'];
        $data['company_name'] = $data['session']['company_detail']['CompanyName'];
        $data['timeoff_format_sid'] = $data['session']['company_detail']['pto_format_sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['is_super_admin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['employee_full_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        if (!$return)
            $data['security_details'] = db_get_access_level_details($data['employer_sid'], NULL, $data['session']);
        if ($return) return true;
    }

    //
    private function checkEmployeeCompany($tl, $formpost)
    {
        // Double check if employee belongs to Time off company
        if (!$this->timeoff_model->checkEmployeeBelongsToLoggedInCompany(
            isset($tl['employee_sid']) ? $tl['employee_sid'] : $tl['id'],
            $formpost['companySid']
        )) {
            // Notification if ever other company employee found attached
            @mail('mubashar.ahmed@egenienext.com', 'Other Company employee found in Time off Request on' . (date('Y-m-d H:i:s')) . '', print_r(
                array_merge($tl, array('requestId' => $formpost['requestId'])),
                true
            ));
            return true;
        }
        return false;
    }


    /**
     * 
     */
    private function checkEmployeeRequestAccess(
        $requestId,
        $newTLS,
        $tl
    ) {
        return true;
        //
        $has = false;
        //
        foreach ($newTLS as $k => $v) if ($tl['type'] == $v['type'] && $tl['email'] == $v['email']) $has = true;
        //
        if (!$has) {
            // Lets delete the employer
            $this->timeoff_model->removeAssignedEmployer(['employee_sid' => $tl['id'], 'timeoff_request_sid' => $requestId, 'role' => $tl['type']]);
        }
        return $has;
    }

    function sendNotifications(
        $requestId,
        $type = 'created'
    ) {
        // Get request
        $request = $this->timeoff_model->getRequestById($requestId);
        //
        $policiesDetail  = $this->timeoff_model->getEmployeePoliciesByDate(
            $request['company_sid'],
            $request['userId'],
            $request['request_from_date'],
            [$request['timeoff_policy_sid']]
        );
        //
        $allowedTime = $policiesDetail[0]['AllowedTime']['text'];
        $consumedTime = $policiesDetail[0]['ConsumedTime']['text'];
        $remainingTime = $policiesDetail[0]['RemainingTime']['text'];
        $policyCycle = formatDateToDB($policiesDetail[0]['lastAnniversaryDate'], DB_DATE, DATE) . ' - ' . formatDateToDB($policiesDetail[0]['upcomingAnniversaryDate'], DB_DATE, DATE);
        //
        $CHF = message_header_footer($request['company_sid'], $request['CompanyName']);
        // Get template
        $employeeTemplate = [];
        $approverTemplate = [];
        // Set Replace Array
        $eRP = [];
        $eRP['{{company_name}}'] = $request['CompanyName'];
        $eRP['{{policy_name}}'] = $request['title'];
        $eRP['{{reason}}'] = '<p style="font-style: italic;font-size: 20px;"><strong>' . strip_tags($request['reason']) . '</strong></p>';
        $eRP['{{requester_first_name}}'] = $request['first_name'];
        $eRP['{{requester_last_name}}'] = $request['last_name'];
        $eRP['{{requested_date}}'] =  $request['request_from_date'] == $request['request_to_date'] ?
            DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') :
            DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D') . ' - ' . DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D');
        $eRP['{{cancel_link}}'] = getButton(
            [
                '{{url}}' => timeoffGetEncryptedLink([
                    'companySid' => $request['company_sid'],
                    'companyName' => $request['CompanyName'],
                    'requestSid' => $request['sid'],
                    'employerSid' => $request['userId'],
                    'typeSid' => 'cancel'
                ]),
                '{{color}}' => '#dc3545',
                '{{text}}' => 'Cancel Request'
            ]
        );
        //
        switch ($type):
            case "created":
                // Get employee template
                $employeeTemplate = timeoffMagicQuotesReplace(
                    $this->timeoff_model->getEmailTemplate(TIMEOFF_CREATE_FOR_EMPLOYEE),
                    $eRP
                );
                // Get approver template
                $approverTemplate = $this->timeoff_model->getEmailTemplate(TIMEOFF_CREATE_FOR_APPROVER);
                break;
            case "update":
                // Get approver template
                $approverTemplate = $this->timeoff_model->getEmailTemplate(TIMEOFF_UPDATE_FOR_APPROVER);
                //
                $eRP['{{first_name}}'] = $request['history'][0]['first_name'];
                $eRP['{{last_name}}'] = $request['history'][0]['last_name'];
                break;
        endswitch;

        if ($type == "approved" || $type == "rejected") {
            // Get approver template
            $requesterTemplate = $this->timeoff_model->getEmailTemplate(USER_TIMEOFF_REQUEST);
            $approversTemplate = $this->timeoff_model->getEmailTemplate(APPROVER_TIMEOFF_REQUEST_UPDATE);
            //
            $eRP['{{approver_or_rejecter_first_name}}'] = $request['history'][0]['first_name'];
            $eRP['{{approver_or_rejecter_last_name}}'] = $request['history'][0]['last_name'];
            $eRP['{{user_first_name}}'] = $request['first_name'];
            $eRP['{{user_last_name}}'] = $request['last_name'];
            $eRP['{{request_type}}'] = $type;

            $eRP['{{remaining_time}}'] = $remainingTime;
            $eRP['{{allowed_time}}'] = $allowedTime;
            $eRP['{{consumed_time}}'] = $consumedTime;
            $eRP['{{policy_cycle}}'] = $policyCycle;


            //
            if (!empty($requesterTemplate)) {
                //
                $requesterTemplateI = timeoffMagicQuotesReplace($requesterTemplate, $eRP);
                //
                log_and_sendEmail(
                    $requesterTemplate['FromEmail'],
                    $request['email'],
                    $requesterTemplateI['Subject'],
                    $CHF['header'] . $requesterTemplateI['Body'] . $CHF['footer'],
                    $requesterTemplateI['FromName']
                );
            }

            // Send Email to approver
            if (!empty($approversTemplate) && !empty($request['approvers'])) {
                //
                foreach ($request['approvers'] as $approver) {
                    //
                    $eRP['{{approver_first_name}}'] = $approver['first_name'];
                    $eRP['{{approver_last_name}}'] = $approver['last_name'];

                    $eRP['{{approve_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'approve'
                            ]),
                            '{{color}}' => '#4CBB17',
                            '{{text}}' => 'Approve Request'
                        ]
                    );
                    $eRP['{{reject_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'reject'
                            ]),
                            '{{color}}' => '#dc3545',
                            '{{text}}' => 'Reject Request'
                        ]
                    );
                    //
                    $approverTemplateI = timeoffMagicQuotesReplace($approversTemplate, $eRP);

                    //
                    log_and_sendEmail(
                        $approverTemplateI['FromEmail'],
                        $approver['email'],
                        $approverTemplateI['Subject'],
                        $CHF['header'] . $approverTemplateI['Body'] . $CHF['footer'],
                        $approverTemplateI['FromName']
                    );
                }
            }
        } else {
            // Send email to employee
            if (!empty($employeeTemplate)) {
                //
                log_and_sendEmail(
                    $employeeTemplate['FromEmail'],
                    $request['email'],
                    $employeeTemplate['Subject'],
                    $CHF['header'] . $employeeTemplate['Body'] . $CHF['footer'],
                    $employeeTemplate['FromName']
                );
            }

            // Send Email to approver
            if (!empty($approverTemplate) && !empty($request['approvers'])) {

                //
                foreach ($request['approvers'] as $approver) {
                    //
                    $eRP['{{approver_first_name}}'] = $approver['first_name'];
                    $eRP['{{approver_last_name}}'] = $approver['last_name'];

                    $eRP['{{approve_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'approve'
                            ]),
                            '{{color}}' => '#4CBB17',
                            '{{text}}' => 'Approve Request'
                        ]
                    );
                    $eRP['{{reject_link}}'] = getButton(
                        [
                            '{{url}}' => timeoffGetEncryptedLink([
                                'companySid' => $request['company_sid'],
                                'companyName' => $request['CompanyName'],
                                'requestSid' => $request['sid'],
                                'employerSid' => $approver['userId'],
                                'typeSid' => 'reject'
                            ]),
                            '{{color}}' => '#dc3545',
                            '{{text}}' => 'Reject Request'
                        ]
                    );

                    $eRP['{{remaining_time}}'] = $remainingTime;
                    $eRP['{{allowed_time}}'] = $allowedTime;
                    $eRP['{{consumed_time}}'] = $consumedTime;
                    $eRP['{{policy_cycle}}'] = $policyCycle;


                    //
                    $approverTemplateI = timeoffMagicQuotesReplace($approverTemplate, $eRP);

                    //
                    log_and_sendEmail(
                        $approverTemplateI['FromEmail'],
                        $approver['email'],
                        $approverTemplateI['Subject'],
                        $CHF['header'] . $approverTemplateI['Body'] . $CHF['footer'],
                        $approverTemplateI['FromName']
                    );
                }
            }
        }
    }

    //
    function print_and_download($action, $request, $request_id = 0, $section = null)
    {
        $data = array();
        $this->check_login($data);
        //

        //
        $from_date = '';
        $to_date = '';


        if (isset($_GET) && !empty($_GET)) {
            $from_date = $_GET['from_date'];
            $to_date = $_GET['to_date'];
        }

        if ($action == 'print') {
            $data['download'] = 'no';
        } else if ($action == 'download') {
            $data['download'] = 'yes';
        }
        //
        $data['data'] = [];
        $data['page_title'] = 'Time-off';
        $data['file_name'] = 'timeoff_requests';
        //
        $page = '';

        switch (strtolower($request)) {
                // Requests
            case 'requests':
                //
                if ($request_id > 0) {
                    $data['request'] = $this->timeoff_model->getRequestById($request_id);
                    $data['policies'] = $this->timeoff_model->getEmployeePoliciesById($data['request']['company_sid'], $data['request']['employee_sid']);
                    //
                    $page = 'request';
                } else {
                    // Get company all timeoff request
                }
                break;
                //
            case 'balance':
                //
                $data['balances'] = $this->timeoff_model->getBalanceSheet([
                    'level' =>  $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0,
                    'companyId' => $data['session']['company_detail']['sid'],
                    'employerId' => $data['session']['employer_detail']['sid'],
                    'filter' => [
                        'employees' => 'all',
                        'policies' => 'all',
                    ]
                ]);
                //
                $page = 'balance';
                break;
        }
        //
        // $company_id = $data['session']['company_detail']['sid'];
        // $company_name = $data['session']['company_detail']['CompanyName'];
        // $domain_name = $this->timeoff_model->getCompanyDomainName($company_id);
        // $data['company_name'] = $company_name;
        // $data['domain_name'] = $domain_name;
        //
        $this->load->view('timeoff/print_and_download/' . ($page) . '', $data);
    }

    function get_image_base64()
    {
        $img_url = $this->input->get('img_url');

        $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
        $file_name = $img_url;
        $temp_file_path = $temp_path . $file_name;

        if (file_exists($temp_file_path)) {
            unlink($temp_file_path);
        }

        $this->load->library('aws_lib');
        $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $img_url, $temp_file_path);

        $base64_img = '';
        $image_type = '';
        if (file_exists($temp_file_path)) {
            $image_type = pathinfo($temp_file_path, PATHINFO_EXTENSION);
            $image_data = file_get_contents($temp_file_path);
            $base64_img = base64_encode($image_data);
            unlink($temp_file_path);
        }

        print_r(json_encode(array('type' => $image_type, 'string' => $base64_img)));
    }


    //
    function test(
        $companyId = 0,
        $employeeId = 0,
        $asOfToday = ''
    ) {
        //
        //

        // _e( $this->timeoff_model->getEmployeeTeamMemberIds(11737), true );
        // $this->timeoff_model->adjustRequests();
        $this->timeoff_model->shiftHolidays();
        // $this->timeoff_model->shiftApprovers();
        // $this->timeoff_model->push_default_timeoff_policy();
        // $this->timeoff_model->movePolicies($companyId);
        // $this->timeoff_model->handleResetPolicy($companyId);
        //
        // $approvers = $this->timeoff_model->getBalanceSheet([
        //     'level' => 1,
        //     'employerId' => '11738',
        //     'companyId' => '11710',
        //     'filter' => [
        //         'employees' => '11738'
        //     ]
        // ]);
        // $approvers = $this->timeoff_model->getEmployerApprovalStatus($employeeId);
        // $approvers = $this->timeoff_model->getEmployeeHandlers($companyId, $employeeId);
        // _e($approvers, true);
        // $asOfToday = '2020-01-01';
        // //
        // _e($asOfToday);
        // //
        // $policies = $this->timeoff_model->getEmployeePoliciesByDate(
        //     $companyId,
        //     $employeeId,
        //     $asOfToday
        // );
        // //
        // foreach($policies as $policy){
        //     _e('-----------------');
        //     _e($policy['Title']);
        //     _e($policy['RemainingTime']['text']);
        // }
        //
    }


    //
    function getTimeOffs($employeeId)
    {
        //
        $data = array();
        $this->check_login($data);
        //
        header('Content-Type: application/json');
        //
        echo json_encode($this->timeoff_model->getMyTimeOffs($data['company_sid'], $employeeId));
        exit(0);
    }


    /**
     * Get policy history
     * 
     * Fetches the changes done by the employers to the policy
     * 
     * @param int $policyId
     * @return json
     */
    public function getPolicyHistory(int $policyId)
    {
        // get and check session
        $session = checkAndGetSession('employee');
        // lets sanitize the id
        $policyId = (int) $policyId;
        // get the company id to match
        $companyId = $session['parent_sid'];
        // get the records
        $records = $this->timeoff_model->getPolicyHistoryWithDifference($policyId, $companyId);
        //
        $view = $this->load->view('timeoff/policy_history', ['records' => $records], true);
        //  
        return SendResponse(200, ['view' => $view]);
    }


    //

    public function exportTimeoffToCSV($employeeId)
    {
        $data = array();
        $this->check_login($data);
        //
        $data['title'] = 'Report::time-off';
        $data['theme'] = $this->theme;

        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $filter_session = $this->session->userdata($_GET['token']);
        }

        if (isset($filter_session['policy']) && $filter_session['policy'] != 'null' && $filter_session['policy'] != '') {
            $filter_policy = explode(',', $filter_session['policy']);
        } else {
            $filter_policy = 'all';
        }


        if ($employeeId == '' || $employeeId == 'all') {
            $employeeIds = $filter_session['employees'] != 'null' ? explode(',', $filter_session['employees']) : 'all';
        } else {
            $employeeIds = strpos($employeeId, ',') !== false ? explode(',', $employeeId) : $employeeId;
        }


        $data['data'] = $this->timeoff_model->getEmployeesTimeOffNew(
            $data['company_sid'],
            $employeeIds,
            $this->input->get('start', true),
            $this->input->get('end', true),
            $filter_policy
        );
        //
        $start = '';
        $period = '';
        if ($this->input->get('start', true) && $this->input->get('end', true)) {
            $start = $this->input->get('start', true) . ' - ' . $this->input->get('end', true);
            $period = $this->input->get('start', true) . '_' . $this->input->get('end', true);
        } else if ($this->input->get('start', true)) {
            $start = $this->input->get('start', true) . ' - N/A';
            $period = $this->input->get('start', true) . '_N/A';
        } else if ($this->input->get('end', true)) {
            $start = 'N/A - ' . $this->input->get('end', true);
            $period = 'N/A_' . $this->input->get('end', true);
        } else {
            $start = 'N/A';
            $period = 'N/A';
        }


        $companyHeader = "Company Name: " . $data['session']['company_detail']['CompanyName'] . ',,,Report Date:' . date('m/d/Y H:i', strtotime('now'));
        $companyHeader .= PHP_EOL . 'Employee Name: ' . ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $companyHeader .= PHP_EOL . 'Report Period: ' . $start . PHP_EOL . PHP_EOL;

        $header_row . PHP_EOL;

        $header_row = 'Employee,Employee Status,Policy,Time Taken,Start Date,End Date,Status,Joining Date,Rehire Date';

        $file_content = '';
        $file_content .= $header_row . PHP_EOL;
        $file_content .= $rows;
        $file_size = 0;

        if (function_exists('mb_strlen')) {
            $file_size = mb_strlen($file_content, '8bit');
        } else {
            $file_size = strlen($file_content);
        }

        $rows = '';


        if (!empty($data['data'])) {
            foreach ($data['data'] as $row) {
                //
                $employeeStatus = '';
                //
                if ($row['active'] == 1) {
                    $employeeStatus = 'Active';
                } else {
                    if ($employee['terminated_status'] == 1) {
                        $employeeStatus = 'Terminated';
                    } else {
                        $this->load->model('export_csv_model');
                        $employeeStatus = $this->export_csv_model->get_employee_last_status_info($row['employeeId']);

                        if ($employeeStatus == 'Archived Employee') {
                            $employeeStatus = 'Archived';
                        }
                    }
                }
                //

                $joiningDate = get_employee_latest_joined_date($row["registration_date"], $row["joined_at"], "", false);
                //
                if (empty($joiningDate)) {
                    $joiningDate = 'N/A';
                } else {
                    $joiningDate = DateTime::createfromformat('Y-m-d', $joiningDate)->format('m/d/Y');
                }

                $rehireDate = get_employee_latest_joined_date("", "", $row["rehire_date"], false);
                //
                if (empty($rehireDate)) {
                    $rehireDate = "N/A";
                } else {
                    $rehireDate = DateTime::createfromformat('Y-m-d', $rehireDate)->format('m/d/Y');
                }


                $processRequest = splitTimeoffRequest($row);
                //
                if ($processRequest['type'] == 'multiple') {
                    //
                    foreach ($processRequest['requestData'] as $request) {

                        $consumed_time = $request['consumed_time'];
                        $hours = floor($request['requested_time'] / 60);

                        if ($hours > 1) {
                            $consumed_time = $hours . ' Hours';
                        } else {
                            $consumed_time = $hours . ' Hour';
                        }
                        //
                        //
                        $rows  .=  (ucwords($request['first_name'] . ' ' . $request['last_name'])) . ' ' . (remakeEmployeeName($request, false)) . ',';
                        $rows  .=  $employeeStatus . ',';
                        $rows  .=  $request['title'] . ',';
                        $rows  .= $consumed_time . ',';
                        $rows .= DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('m/d/Y') . ',';
                        $rows .= DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('m/d/Y') . ',';
                        //
                        $status = $request['status'];
                        //
                        if ($status == 'approved') {
                            $rows .= 'APPROVED' . ' (' . strip_tags($request['request_status']) . ' )' . ',';
                        } else if ($status == 'rejected') {
                            $rows .= 'REJECTED (PENDING)' . ',';
                        } else if ($status == 'pending') {
                            $rows .= 'PENDING (PENDING)' . ',';
                        }

                        $rows  .=  $joiningDate . ',' . $rehireDate;

                        $rows .= PHP_EOL;
                    }
                    //
                } else {

                    $consumed_time = $processRequest['requestData']['consumed_time'];

                    $rows  .=  (ucwords($processRequest['requestData']['first_name'] . ' ' . $processRequest['requestData']['last_name'])) . ' ' . (remakeEmployeeName($processRequest['requestData'], false)) . ',';
                    $rows  .=  $employeeStatus . ',';
                    $rows  .=  $processRequest['requestData']['title'] . ',';
                    $rows  .= $consumed_time . ',';
                    $rows .= DateTime::createfromformat('Y-m-d', $processRequest['requestData']['request_from_date'])->format('m/d/Y') . ',';
                    $rows .= DateTime::createfromformat('Y-m-d', $processRequest['requestData']['request_to_date'])->format('m/d/Y') . ',';
                    //
                    $status = $processRequest['requestData']['status'];
                    //
                    if ($status == 'approved') {
                        $rows .= 'APPROVED' . ' (' . strip_tags($processRequest['requestData']['request_status']) . ')' . ',';
                    } else if ($status == 'rejected') {
                        $rows .= 'REJECTED (PENDING)' . ',';
                    } else if ($status == 'pending') {
                        $rows .= 'PENDING (PENDING)' . ',';
                    }
                    $rows  .=  $joiningDate . ',' . $rehireDate;

                    $rows .= PHP_EOL;
                }
            }
        }

        $outputFile = $companyHeader . PHP_EOL;
        $outputFile .= $header_row . PHP_EOL;
        $outputFile .= $rows . PHP_EOL;

        //
        $fileName = 'employees_time_off/Company_Name:' . str_replace(" ", "_", $data['session']['company_detail']['CompanyName']) . "/Generated_By:" . $data['session']['employer_detail']['first_name'] . '_' . $data['session']['employer_detail']['last_name'] . "/Report_Period:" . $period . "/Generated_Date:" . date('Y_m_d-H:i:s') . '.csv';

        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');  // Add the mime type from Code igniter.
        header('Content-Disposition: attachment; filename="' . $fileName . '"');  // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($outputFile)); // provide file size
        header('Connection: close');
        echo $outputFile;
    }
}
