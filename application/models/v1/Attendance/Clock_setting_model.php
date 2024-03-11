<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Attendance/Base_model", "base_model");
/**
 * Clock settings model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Shift & Clock
 * @tables  cl_attendance_settings
 */
class Clock_setting_model extends Base_model
{
    /**
     * get the attendance settings
     */
    public function get()
    {
        // check if settings exists
        if (!$this->db->where(
            "cl_attendance_settings.company_sid",
            $this->loggedInCompanyId
        )->count_all_results("cl_attendance_settings")) {
            // add the setting to the database
            $this->addDefaultSettings();
        }
        //
        $record = $this->db
            ->select(
                getUserFieldsForDb("
                    cl_attendance_settings.updated_at,
                    cl_attendance_settings.setting_json,
                ")
            )
            ->join(
                "users",
                "users.sid = cl_attendance_settings.last_modified_by",
                "inner"
            )
            ->where(
                "cl_attendance_settings.company_sid",
                $this->loggedInCompanyId
            )
            ->get("cl_attendance_settings")
            ->row_array();
        //
        $returnArray = [];
        //
        if (!$record) {
            //
            $settingsArray = $this->getDefaultSettings();
            //
            $returnArray["employeeNameWithRole"] = "";
            $returnArray["updated_at"] = "";
            $returnArray["settings_json"] = $settingsArray;
        } else {
            $returnArray["updated_at"] = $record["updated_at"];
            $returnArray["employeeNameWithRole"] = remakeEmployeeName($record);
            $returnArray["settings_json"] = json_decode(
                $record["setting_json"],
                true
            );
        }
        //
        return $returnArray;
    }

    /**
     * Add the default settings to the table
     */
    private function addDefaultSettings()
    {
        // set insert array
        $insertArray = [];
        $insertArray["company_sid"] = $this->loggedInCompanyId;
        $insertArray["setting_json"] = json_encode($this->getDefaultSettings());
        $insertArray["last_modified_by"] = getCompanyAdminSid(
            $this->loggedInCompanyId
        );
        //
        $insertArray["created_at"] =
            $insertArray["updated_at"] =
            getSystemDate();
        // insert the data
        $this->db->insert(
            "cl_attendance_settings",
            $insertArray
        );
    }

    /**
     * get the default settings
     */
    private function getDefaultSettings(): array
    {
        // set settings array
        $settingsArray = [
            "general" => [],
            "controls" => [],
            "reminders" => [],
        ];
        // set general settings
        $settingsArray["general"] = [
            "daily_limit" => [
                "status" => 0,
                "value" => 2
            ],
            "auto_clock_out" => [
                "status" => 0,
                "value" => 2
            ],
        ];
        // set controls settings
        $settingsArray["controls"] = [
            "employee_can_clock_in" => 1,
            "employee_can_manipulate_time_sheet" => 0,
        ];
        // set reminders settings
        $settingsArray["reminders"] = [
            "days" => ["mon", "tue", "wed", "thu", "fri"],
            "remind_employee_to_clock_in" => [
                "status" => 0,
                "value" => ""
            ],
            "remind_employee_to_clock_out" => [
                "status" => 0,
                "value" => ""
            ],
            "daily_limit" => [
                "status" => 0,
                "value" => 2
            ]
        ];
        return $settingsArray;
    }
}
