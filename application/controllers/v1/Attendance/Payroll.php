<?php defined("BASEPATH") || exit("Access is denied.");
// load the base controller
loadUpController("v1/Attendance/Base");
/**
 * Payroll
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Payroll extends Base
{
    public function __construct()
    {
        parent::__construct();
        // load the main model
        $this->load->model("v1/Attendance/Dashboard_model", "dashboard_model");
    }

    /**
     * User payroll dashboard
     *
     * @param int    $userId
     * @param string $userType
     */
    public function dashboard(int $userId, string $userType)
    {
        //
        $this->data["title"] = "Payroll dashboard";
        //
        $this->data["userId"] = $userId;
        $this->data["userType"] = $userType;
        $this->data["loadJsFiles"] = true;
        //
        $this->data["employer"] = $this->base_model->getEmployeeDetails($userId);
        //
        $this->data['return_title_heading'] = "Employee Profile";
        $this->data['return_title_heading_link'] = base_url() . 'employee_profile/' . $userId;
        //
        check_access_permissions($this->data["securityDetails"], 'employee_management', 'payroll_dashboard');
        //
        $this->data = employee_right_nav($userId, $this->data);
        $this->data["left_navigation"] = 'manage_employer/employee_management/profile_right_menu_employee_new';
        // add plugins
        $this->data["pageJs"] = [
            // high charts
            main_url("public/v1/plugins/ms_modal/main.min.js?v=3.0"),
        ];
        $this->data["pageCSS"] = [
            // high charts
            main_url("public/v1/plugins/ms_modal/main.min.css?v=3.0"),
        ];
        // set js
        $this->setCommon("v1/attendance/js/payroll_dashboard", "js");
        $this->getCommon($this->data, "payroll_dashboard");
        // make the blue portal popup
        $this->renderView("v1/attendance/payroll/dashboard");
    }

    /**
     * get the page by slug
     *
     * @param int    $userId
     * @param string $userType
     * @param string $slug
     * @return array
     */
    public function getPageBySlug(
        int $userId,
        string $userType,
        string $slug
    ): array {
        // check and generate error for session
        checkAndGetSession();
        // convert the slug to function
        $func = "page" . preg_replace("/\s/i", "", ucwords(preg_replace("/[^a-z]/i", " ", $slug)));
        // get the data
        $data = $this->$func(
            $userId,
            $userType
        );
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/attendance/partials/page_" . $slug, $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * get the pay schedule
     *
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    private function pagePaySchedule(
        int $userId,
        string $userType
    ): array {
        // get company pay scheduled
        $companyPaySchedules = $this->dashboard_model
            ->getCompanyPaySchedules(
                $this->loggedInCompany["sid"]
            );
        // get the pay schedule
        $userPaySchedule = $this->dashboard_model
            ->getUserPayScheduleById(
                $userId,
                $userType
            );
        //
        return [
            "return" => $companyPaySchedules,
            "companyPaySchedules" => $companyPaySchedules,
            "userPaySchedule" => $userPaySchedule
        ];
    }
}
