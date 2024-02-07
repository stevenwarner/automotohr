<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift notification model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_notification_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    /**
     * send out email notifications to selected employees
     *
     * @param array $employeeIds
     * @param int $companyId
     */
    public function sendEmployeesNotificationsWithUpcomingShifts(
        array $employeeIds,
        int $companyId = 0
    ) {
        //
        if (!$employeeIds) {
            return ["errors" => ["No employees selected."]];
        }
        // check if company allows email reminder
        if ($companyId && !$this->enabledNotificationEmails($companyId)) {
            return ["errors" => ["Company disabled email reminder emails."]];
        }
        // get next day shift
        $tomorrowDate = getSystemDate(DB_DATE, "tomorrow");
        // get the employees
        $this->db
            ->select(
                getUserFields() .
                    "
                cl_shifts.start_time,
                cl_shifts.shift_date,
                cl_shifts.end_time,
                cl_shifts.breaks_count,
                cl_shifts.breaks_json,
                cl_shifts.job_sites,
                cl_shifts.notes,
                cl_shifts.employee_sid,
                cl_shifts.company_sid,
                users.parent_sid,
                company.CompanyName
            "
            )
            ->join(
                "users",
                "users.sid = cl_shifts.employee_sid",
                "inner"
            )
            ->join(
                "users as company",
                "company.sid = cl_shifts.company_sid",
                "inner"
            )
            ->where([
                "company.active" => 1,
                "users.active" => 1,
                "users.terminated_status" => 0,
                "users.is_executive_admin" => 0,
                "cl_shifts.shift_date" => $tomorrowDate,
            ]);
        // check for company
        if ($companyId !== 0) {
            $this->db->where("users.parent_sid", $companyId);
        }
        // check for employees
        if ($employeeIds && !in_array("all", $employeeIds)) {
            $this->db->where_in("users.sid", $employeeIds);
        }
        // get the records
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();

        if (!$records) {
            return ["errors" => ["No employees found."]];
        }

        // extract all company ids
        $companyIds = array_column($records, "parent_sid");
        // get company job sites
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        //
        $companyNotifications = [];
        //
        $companyJobSites = [];
        //
        foreach ($companyIds as $v0) {
            $companyNotifications[$v0] = $this->enabledNotificationEmails($v0);
            //
            $tmp
                = $this->job_sites_model->get($v0);

            foreach ($tmp as $v1) {

                $companyJobSites[$v0][$v1["sid"]] = $v1;
            }
        }
        $skipped = 0;
        //
        foreach ($records as $v0) {
            // check if company allows email reminder
            if (!$companyNotifications[$v0["parent_sid"]]) {
                $skipped++;
                continue;
            }
            // set replacement array
            $ra = [];
            $ra["shift_date"] = formatDateToDB(
                $v0["shift_date"],
                DB_DATE,
                DATE
            );
            $ra["shift_start_time"] = formatDateToDB(
                $v0["start_time"],
                TIME,
                "h:i a"
            );
            $ra["shift_end_time"] = formatDateToDB(
                $v0["end_time"],
                TIME,
                "h:i a"
            );
            $ra["company_name"] = $v0["CompanyName"];
            $ra["first_name"] = $v0["first_name"];
            $ra["last_name"] = $v0["last_name"];
            $ra["breaks_count"] = $v0["breaks_count"];
            $ra["job_site_count"] = count(json_decode($v0["job_sites"], true));
            $ra["breaks"] = "";
            $ra["job_sites"] = "";
            // for breaks
            if ($v0["breaks_count"]) {
                // decode the breaks
                $breaks = json_decode($v0["breaks_json"], true);
                //
                $ra["breaks"] .= "<p>You are assigned to " . ($v0["breaks_count"]) . " breaks during this shift, with the following details:</p>";
                $ra["breaks"] .= "<strong>Breaks:</strong>";
                $ra["breaks"] .= "<br />";
                //
                foreach ($breaks as $v1) {
                    $ra["breaks"] .= "<p><strong>Title:</strong> " . ($v1["break"]) . "</p>";
                    $ra["breaks"] .= "<p><strong>Duration:</strong> " . ($v1["duration"]) . " minutes</p>";
                    if ($v1["start_time"]) {
                        $ra["breaks"] .= "<p><strong>Starts at:</strong> " . (formatDateToDB($v1["start_time"], "H:i", "h:i a")) . "</p>";
                        $ra["breaks"] .= "<p><strong>Ends at:</strong> " . (formatDateToDB($v1["end_time"], "H:i", "h:i a")) . "</p>";
                    }
                    $ra["breaks"] .= "<br />";
                }
            }

            // for job sites
            if ($ra["job_site_count"]) {
                // decode the breaks
                $jobSites = json_decode($v0["job_sites"], true);
                //
                $ra["job_sites"] .= "<p>You are assigned to " . (count($jobSites)) . " job sites during this shift, with the following details:</p>";
                $ra["job_sites"] .= "<strong>Job sites:</strong>";
                $ra["job_sites"] .= "<br />";
                //
                foreach ($jobSites as $index => $v1) {
                    //
                    $address = $companyJobSites[$v0["parent_sid"]][$v1];
                    //
                    $ra["job_sites"] .= "<p>" . ($index + 1) . "- " . (makeAddress($address)) . "</p>";
                }
            }
            // sends out the email to employee
            log_and_send_templated_email(
                NEXT_DAY_SHIFT_REMINDER_EMAIL,
                $v0["email"],
                $ra,
                message_header_footer_domain(
                    $v0["parent_sid"],
                    $v0["CompanyName"]
                )
            );
        }

        return [
            "total" => count($records),
            "skipped" => $skipped
        ];
    }

    /**
     * check the notification
     *
     * @param int $companyId
     * @return bool
     */
    private function enabledNotificationEmails(int $companyId): bool
    {
        // get the company extra fields
        $result = $this->db
            ->select("extra_info")
            ->where("sid", $companyId)
            ->get("users")
            ->row_array();
        //
        if (!$result || !$result['extra_info']) {
            return 0;
        }
        //
        $data = unserialize($result["extra_info"]);
        //
        return $data["shift_reminder_email_for_next_day"] ?? 0;
    }
}
