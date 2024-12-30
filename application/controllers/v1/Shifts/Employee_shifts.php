<?php

use Twilio\TwiML\Voice\Record;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee shifts
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Shifts
 */
class Employee_shifts extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Manage shifts
     */
    public function my()
    {

        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }
     
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "My Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        $data['employee'] = $loggedInEmployee;
        //
        $employeeId = $loggedInEmployee["sid"];
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // get all active employees
        $filterStartDate = $this->input->get("start_date", true);
        $filterEndDate = $this->input->get("end_date", true);
        //
        $toggleFilter = $filterStartDate != '';
        //
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";

        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates['end_date'];
        } elseif ($data["filter"]["mode"] === "two_week") {
            // get the current week dates
            $weekDates = getWeekDates(true, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates["current_week"]['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates["next_week"]['end_date'];
        } else {
            $data["filter"]["month"] = $this->input->get("month", true) ?? getSystemDate("m");
            $data["filter"]["year"] = $this->input->get("year", true) ?? getSystemDate("Y");
            //
            $data["filter"]["start_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "01/m/Y");
            //
            $data["filter"]["end_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "t/m/Y");
        }
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            [$employeeId],
            true
        );
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] =  $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                [$employeeId],
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            );

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["filter_toggle"] = $toggleFilter;
        $data["filterStartDate"] = $filterStartDate;
        $data["filterEndDate"] = $filterEndDate;
        $data["loadView"] = true;
        $data["sanitizedView"] = true;
        // get off and holidays
        $data["holidays"] = $this->shift_model->getCompanyHolidaysWithTitle(
            $loggedInCompany["sid"],
            $data["filter"]
        );
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/ems_main"
        ], "public/v1/shifts/", "my_ems_shifts", true);

        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/my/listing');
        $this->load->view('main/footer');
    }


    //
    public function shiftsTrade()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }

        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Swap Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        $data['employee'] = $loggedInEmployee;
        //

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["loadView"] = true;
        $data["sanitizedView"] = true;

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(getSystemDate(SITE_DATE), SITE_DATE);
        $weekEndDate = formatDateToDB(date('Y-m-d', strtotime($weekStartDate . ' + 7 days')), 'Y-m-d', SITE_DATE);
        $weekStartDate = formatDateToDB($weekStartDate, 'Y-m-d', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getEmployeeShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/trade');
        $this->load->view('main/footer');
    }


    //
    public function MyshiftsTrade()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }
      
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data

        $data = [];
        $data["title"] = "Trade Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["loadView"] = true;
        $data["sanitizedView"] = true;

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(date('m-01-Y'), 'm-d-Y', SITE_DATE);
        $weekEndDate = formatDateToDB(date('m-t-Y'), 'm-d-Y', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getMySwapShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/my_trade');
        $this->load->view('main/footer');
    }


    //
    function swapShiftConfirm($shiftId, $toEmployeeId, $action)
    {
        $this->load->model("v1/Shift_model", "shift_model");

        $data = [];
        $data['updated_by'] = $toEmployeeId;
        $data['updated_at'] = getSystemDate();

        if ($action == 'admin_approve' || $action == 'admin_reject') {
            //
            $shiftRecord = $this->shift_model->getSwapShiftsRequestById($shiftId, 'confirmed');
            // _e($shiftRecord,true,true);
            //
            if (empty($shiftRecord)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Shift Request is not available !');
            } else {
                //
                $templateId = '';
                // Approve Shift        
                if ($action == 'admin_approve') {
                    $data['request_status'] = 'approved';
                    $message = 'Swap shift request is approved successfully!';
                    $templateId = SHIFTS_SWAP_ADMIN_APPROVED;
                } else {
                    $data['request_status'] = 'admin_reject';
                    $message = 'Swap shift request is rejected successfully!';
                    $templateId = SHIFTS_SWAP_ADMIN_REJECTED;
                }
                //
                $this->shift_model->updateShiftsTradeRequest($shiftId, $shiftRecord[0]['to_employee_sid'], $data);
                //
                // send mail
                $emailTemplateFromEmployee = get_email_template($templateId);
                $emailTemplateToEmployee = $emailTemplateFromEmployee;
                //
                foreach ($shiftRecord as $requestRow) {
                    //
                    if ($requestRow['from_employee_sid'] != '') {
                        //
                        $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplateFromEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee']);
                        //
                        $from = $emailTemplateFromEmployee['from_email'];
                        $to = $requestRow['from_employee_email'];
                        $subject = $emailTemplateFromEmployee['subject'];
                        $from_name = $emailTemplateFromEmployee['from_name'];
                        $body = EMAIL_HEADER
                            . $emailTemplateBodyFromEmployee
                            . EMAIL_FOOTER;

                        if ($_SERVER['SERVER_NAME'] != 'localhost') {

                            sendMail($from, $to, $subject, $body, $from_name);
                        }
                        //
                        $emailData = array(
                            'date' => date('Y-m-d H:i:s'),
                            'subject' => $subject,
                            'email' => $to,
                            'message' => $body,
                        );
                        save_email_log_common($emailData);
                    }

                    if ($requestRow['to_employee_sid'] != '') {
                        //
                        $emailTemplateBodyToEmployee = $this->shiftSwapEmailTemplate($emailTemplateToEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['to_employee']);
                        //
                        $from = $emailTemplateToEmployee['from_email'];
                        $to = $requestRow['to_employee_email'];
                        $subject = $emailTemplateToEmployee['subject'];
                        $from_name = $emailTemplateToEmployee['from_name'];
                        $body = EMAIL_HEADER
                            . $emailTemplateBodyToEmployee
                            . EMAIL_FOOTER;

                        if ($_SERVER['SERVER_NAME'] != 'localhost') {

                            sendMail($from, $to, $subject, $body, $from_name);
                        }
                        //saving email to logs
                        $emailData = array(
                            'date' => date('Y-m-d H:i:s'),
                            'subject' => $subject,
                            'email' => $to,
                            'message' => $body,
                        );
                        save_email_log_common($emailData);
                    }
                }
                //
                $this->session->set_flashdata('message', $message);
            }
        }

        if ($action == 'employee_approve' || $action == 'employee_reject') {
            //
            $shiftRecord = $this->shift_model->getSwapShiftsRequestById($shiftId, 'awaiting confirmation', $toEmployeeId);
            //
            if (empty($shiftRecord)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Shift Request is not available !');
            } else {
                //
                $templateId = '';
                // Approve Shift        
                if ($action == 'employee_approve') {
                    $data['request_status'] = 'confirmed';
                    $message = 'Swap shift request is approved successfully!';
                    $this->sendEmailToAdmin($shiftId, $toEmployeeId);
                } else {
                    $data['request_status'] = 'rejected';
                    $message = 'Swap shift request is rejected successfully!';
                    $this->sendEmailToEmployee($shiftId, $toEmployeeId);
                }
                //
                $this->shift_model->updateShiftsTradeRequest($shiftId, $toEmployeeId, $data);
                //
                $this->session->set_flashdata('message', $message);
            }
        }

        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        //
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        $this->disableMinifiedFiles = true;

        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'forgot', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min', // jquery
            'v1/plugins/bootstrap5/js/bootstrap.bundle', // bootstrap 5
            'js/jquery.validate.min', // validator
            "v1/app/js/pages/home",
            "v1/app/js/pages/forgot_password_recovery",
        ], $this->js, 'forgot', $this->disableMinifiedFiles);

        $this->load->view($this->header, $data);
        $this->load->view('v1/settings/shifts/my_trade_message');
    }

    //
    function shiftSwapEmailTemplate($emailTemplateBody, $replacementArray, $companyName, $employeeName, $isAdmin = false)
    {
        if (!$replacementArray) {
            return $emailTemplateBody;
        }
        //
        if ($isAdmin == true) {
            $reject_shift = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['admin_id'] . '/admin_reject' . '" target="_blank">Reject</a>';
            //
            $confirm_shift = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['admin_id'] . '/admin_approve' . '" target="_blank">Approve</a>';
        } else {
            $reject_shift = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['to_employee_sid'] . '/employee_reject' . '" target="_blank">Reject</a>';
            //
            $confirm_shift = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['to_employee_sid'] . '/employee_approve' . '" target="_blank">Approve</a>';
        }
        //
        $emailTemplateBody = str_replace('{{employee_name}}', $employeeName, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_date}}', $replacementArray['shift_date'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_time}}', $replacementArray['start_time'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_status}}', $replacementArray['request_status'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_status_by}}', $replacementArray['updated_by'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{reject_shift}}', $reject_shift, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{approve_shift}}', $confirm_shift, $emailTemplateBody);
        //
        return $emailTemplateBody;
    }

    function sendEmailToEmployee($shiftId, $toEmployeeId)
    {
        //
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftId, '', $toEmployeeId);
        //
        foreach ($requestsData as $requestRow) {
            //
            $emailTemplate = get_email_template(SHIFTS_SWAP_EMPLOYEE_REJECTION);
            //
            if ($requestRow['from_employee_sid'] != '') {
                //
                $emailTemplate['text'] = str_replace('{{to_employee}}', $requestRow['to_employee'], $emailTemplate['text']);
                $emailTemplate['text'] = str_replace('{{from_employee}}', $requestRow['from_employee'], $emailTemplate['text']);
                //
                $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplate['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee']);
                //
                $from = $emailTemplate['from_email'];
                $to = $requestRow['from_employee_email'];
                $subject = $emailTemplate['subject'];
                $from_name = $emailTemplate['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyFromEmployee
                    . EMAIL_FOOTER;
                //
                if ($_SERVER['SERVER_NAME'] != 'localhost') {
                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
                //
            }
        }
    }

    function sendEmailToAdmin($shiftId, $toEmployeeId)
    {
        //
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftId, '', $toEmployeeId);
        $adminList = getCompanyAdminPlusList($requestsData[0]['companyId']);
        //,
        if ($adminList) {
            foreach ($adminList as $admin) {
                $adminName = $admin['first_name'] . " " . $admin['last_name'];
                //
                foreach ($requestsData as $requestRow) {
                    //
                    $requestRow['admin_id'] = $admin['sid'];
                    //
                    $emailTemplate = get_email_template(SHIFTS_SWAP_ADMIN_APPROVAL);
                    //
                    if ($requestRow['from_employee_sid'] != '') {
                        //
                        $emailTemplate['text'] = str_replace('{{admin_name}}', $adminName, $emailTemplate['text']);
                        $emailTemplate['text'] = str_replace('{{to_employee}}', $requestRow['to_employee'], $emailTemplate['text']);
                        $emailTemplate['text'] = str_replace('{{from_employee}}', $requestRow['from_employee'], $emailTemplate['text']);
                        //
                        $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplate['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee'], true);
                        //
                        $from = $emailTemplate['from_email'];
                        $to = $admin['email'];
                        $subject = $emailTemplate['subject'];
                        $from_name = $emailTemplate['from_name'];
                        $body = EMAIL_HEADER
                            . $emailTemplateBodyFromEmployee
                            . EMAIL_FOOTER;
                        //
                        if ($_SERVER['SERVER_NAME'] != 'localhost') {
                            sendMail($from, $to, $subject, $body, $from_name);
                        }
                        //
                        $emailData = array(
                            'date' => date('Y-m-d H:i:s'),
                            'subject' => $subject,
                            'email' => $to,
                            'message' => $body,
                        );
                        save_email_log_common($emailData);
                        //
                    }
                }
            }
        }
    }
}
