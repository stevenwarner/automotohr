<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Cron CRON model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Cron_clock_model extends CI_Model
{
    /**
     * main entry point
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get the company ids for attendance module
     * enabled companies.
     */
    public function getCompaniesWithActivatedModuleWithSettings(): array
    {
        // get the module enabled company id
        $companyIds = $this->getCompaniesWithActivatedModule(MODULE_ATTENDANCE);
        //
        if (!$companyIds) {
            return [];
        }
        // get the settings
        $records = $this->db
            ->select("setting_json, company_sid")
            ->where_in("company_sid", $companyIds)
            ->get("cl_attendance_settings")
            ->result_array();
        // get the default settings
        $defaultSettings = $this
            ->getDefaultSettings();
        // when records are found
        if ($records) {
            foreach ($records as $i0 => $v0) {
                // get the index
                $index = array_search(
                    $v0["company_sid"],
                    $companyIds
                );
                // delete the value from array
                unset($companyIds[$index]);
                //
                $records[$i0] = [
                    "settings_json" => json_decode($v0["setting_json"], true),
                    "company_sid" => $v0["company_sid"]
                ];
            }
        }
        // for pending company ids
        if ($companyIds) {
            foreach ($companyIds as $companyId) {
                $records[] = [
                    "settings_json" => $defaultSettings,
                    "company_sid" => $companyId
                ];
            }
        }
        //
        return $records;
    }

    /**
     * get the default settings
     */
    public function getDefaultSettings(): array
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
                "status" => "0",
                "value" => "2"
            ],
            "auto_clock_out" => [
                "status" => "0",
                "value" => "2"
            ],
        ];
        // set controls settings
        $settingsArray["controls"] = [
            "employee_can_clock_in" => "1",
            "employee_can_manipulate_time_sheet" => "0",
        ];
        // set reminders settings
        $settingsArray["reminders"] = [
            "days" => ["mon", "tue", "wed", "thu", "fri"],
            "remind_employee_to_clock_in" => [
                "status" => "0",
                "value" => ""
            ],
            "remind_employee_to_clock_out" => [
                "status" => "0",
                "value" => ""
            ],
            "daily_limit" => [
                "status" => "0",
                "value" => "2"
            ]
        ];
        return $settingsArray;
    }

    /**
     * get the company ids for attendance module
     * enabled companies.
     */
    public function getCompaniesWithActivatedModule(string $moduleSlug): array
    {
        // get the module enabled company id
        $record = $this->db
            ->select("sid")
            ->where("module_slug", $moduleSlug)
            ->get("modules")
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        // get company ids
        $records = $this->db
            ->select("company_sid")
            ->where("module_sid", $record["sid"])
            ->where("is_active", 1)
            ->get("company_modules")
            ->result_array();
        //
        return $records ? array_column($records, "company_sid") : [];
    }
}
