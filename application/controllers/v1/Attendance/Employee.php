<?php defined("BASEPATH") || exit("Access is denied.");
// load the base controller
loadUpController("v1/Attendance/Base");
/**
 * Employee
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Employee extends Base
{
    /**
     * Employees dashboard
     */
    public function dashboard()
    {
        
        // add plugins
        $this->data["pageJs"] = [
            // high charts
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/highcharts.min.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/data.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/exporting.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/accessibility.js?v=3.0"),
        ];
        // set js
        $this->setCommon("v1/attendance/js/my/dashboard", "js");
        $this->getCommon($this->data, "my_dashboard");
        // make the blue portal popup
        $this->data["loadView"] = true;
        $this->renderView("v1/attendance/my_dashboard");
    }
}
