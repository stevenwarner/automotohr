<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_model extends CI_Model
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
     * get the overtime rules
     *
     * @param int $companyId
     * @return array
     */
    public function get(
        int $companyId
    ): array {
        return $this->db
            ->select("sid, break_name, break_duration, break_type, job_sites")
            ->where("company_sid", $companyId)
            ->order_by("sid", "DESC")
            ->get("company_breaks")
            ->result_array();
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $wageId
     * @return array
     */
    public function getSingle(
        int $companyId,
        int $shiftId
    ): array {
        return $this->db
            ->select("
            sid,
            employee_sid,
            shift_date,
            start_time,
            end_time,
            breaks_count,
            notes,
            job_sites,
            breaks_json,
            is_published,
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $shiftId)
            ->get("cl_shifts")
            ->row_array();
    }


    /**
     * process
     *
     * @param int   $companyId
     * @param array $post
     * @return array
     */
    public function process(
        int $companyId,
        array $post
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating break."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                ),
            ])->where("sid <>", $post["id"])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("company_breaks", [
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "updated_at" => getSystemDate(),
                    ]);

                $status = 200;
                $response = ["msg" => "You have successfully updated break."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where([
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                )
            ])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // insert
                $this->db
                    ->insert("company_breaks", [
                        "company_sid" => $companyId,
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new break."];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * delete
     *
     * @param int $companyId
     * @param int $breakId
     * @return array
     */
    public function delete(
        int $companyId,
        int $breakId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while deleting break."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $breakId])->count_all_results("company_breaks")) {
            $response["msg"] = "System failed to verify the break.";
        } else {
            // update
            $this->db
                ->where("sid", $breakId)
                ->delete("company_breaks");

            $status = 200;
            $response = ["msg" => "You have successfully deleted the break."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * get the company employees
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyEmployees(int $companyId, $employeeFilter = []): array
    {


        $this->db
            ->select(getUserFields())
            ->where([
                "users.parent_sid" => $companyId,
                "users.is_executive_admin" => 0,
            ]);
        //
        if ($employeeFilter['employees'][0] != 'all' && !empty($employeeFilter['employees'][0])) {
            $this->db->where_in("users.sid", $employeeFilter['employees']);
        }

        if ($employeeFilter['team'] != 0) {
            $this->db->where("users.team_sid", $employeeFilter['team']);
        }


        if ($employeeFilter['jobtitle'][0] != 'all' && !empty($employeeFilter['jobtitle'][0])) {

            $this->db->group_start();
            for ($i = 0; $i < count($employeeFilter['jobtitle']); $i++) {
                $this->db->or_where("LOWER(job_title) = ", strtolower($employeeFilter['jobtitle'][$i]));
            }
            $this->db->group_end();
        }


        $this->db->order_by("users.first_name", "ASC");
        return $this->db->get("users")->result_array();
    }

    /**
     * get the company employees only 
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyEmployeesOnly(int $companyId): array
    {


        $this->db
            ->select(getUserFields())
            ->where([
                "users.parent_sid" => $companyId,
                "users.is_executive_admin" => 0,
                "users.active" => 1,
                "users.terminated_status" => 0
            ]);
        //

        $this->db->order_by("users.first_name", "ASC");
        return $this->db->get("users")->result_array();
    }

    /**
     * get the company single employee
     *
     * @param int $companyId
     * @param int $employeeId
     * @return array
     */
    public function getCompanySingleEmployee(int $companyId, int $employeeId): array
    {
        return $this->db
            ->select(getUserFields())
            ->where([
                "parent_sid" => $companyId,
                "sid" => $employeeId
            ])
            ->get("users")
            ->result_array();
    }

    /**
     * apply shift template
     *
     * @param int $companyId
     * @param array $post
     * @return array
     */
    public function applyTemplate(int $companyId, array $post)
    {
        // convert the dates
        $startDate = formatDateToDB(
            $post["start_date"],
            SITE_DATE,
            DB_DATE
        );
        //
        $endDate = formatDateToDB(
            $post["end_date"],
            SITE_DATE,
            DB_DATE
        );
        //
        $dates = getDatesInRange($startDate, $endDate, DB_DATE);
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        // get the shift
        $scheduleDetails = $this->shift_template_model->getSingle($companyId, $post["schedule_id"]);
        // get company off days
        $holidaysAndOffDays = $this->getCompanyHolidays($companyId, $startDate, $endDate);
        //
        $employeesAlreadyExists = [];
        //
        $currentDateAndTime = getSystemDate();
        //
        foreach ($post["employees"] as $employeeId) {
            foreach ($dates as $v0) {
                //
                if (in_array($v0, $holidaysAndOffDays)) {
                    continue;
                }
                //
                $where = [
                    "employee_sid" => $employeeId,
                    "shift_date" => $v0
                ];
                // check if shift already assigned
                if ($this->db->where($where)->count_all_results("cl_shifts")) {
                    if (!$employeesAlreadyExists[$employeeId]) {
                        $employeesAlreadyExists[$employeeId] = [
                            "dates" => []
                        ];
                    }
                    $employeesAlreadyExists[$employeeId]["dates"][] = $v0;
                    continue;
                }
                // add the shift
                $ins = [];
                $ins["company_sid"] = $companyId;
                $ins["employee_sid"] = $employeeId;
                $ins["shift_date"] = $v0;
                $ins["start_time"] = $scheduleDetails["start_time"];
                $ins["end_time"] = $scheduleDetails["end_time"];
                $ins["breaks_count"] = $scheduleDetails["breaks_count"];
                $ins["breaks_json"] = $scheduleDetails["breaks_json"];
                $ins["created_at"] = $ins["updated_at"] = $currentDateAndTime;
                //
                $this->db->insert("cl_shifts", $ins);
                //
                $insertId = $this->db->insert_id();
                //
                if ($insertId) {
                    $ins = [];
                    $ins["cl_shift_sid"] = $insertId;
                    $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                    $ins["action"] = "created";
                    $ins["action_json"] = "{}";
                    $ins["created_at"] = $currentDateAndTime;
                    //
                    $this->db->insert("cl_shifts_logs", $ins);
                }
            }
        }

        return SendResponse(
            200,
            [
                "msg" => "You have successfully applied the selected shift to he selected employees." . (
                    $employeesAlreadyExists ? "<p>However, the below employees already have shifts.</p>" : ""
                ),
                "list" => $employeesAlreadyExists
            ]
        );
    }

    /**
     * get the shifts
     *
     * @param array $filter
     * @param array $employeeIds
     * @return array
     */
    public function getShifts(array $filter, array $employeeIds, $publishedOnly = false): array
    {
        //
        if (empty($employeeIds)) {
            $employeeIds = ['0'];
        }
        $this->db
            ->select("sid, employee_sid, shift_date, start_time, end_time, job_sites,is_published")
            ->where_in("employee_sid", $employeeIds);

        if ($filter["mode"] === "month") {
            //
            $startDate = $filter["year"] . '-' . $filter["month"] . '-01';
            //
            $endDateObj = new DateTime($startDate);
            $endDate = $endDateObj->format("Y-m-t");
            //
            $this->db
                ->where("shift_date >= ", $startDate)
                ->where("shift_date <= ", $endDate);
        } else {
            //
            $this->db
                ->where("shift_date >= ", formatDateToDB($filter["start_date"], SITE_DATE, DB_DATE))
                ->where("shift_date <= ", formatDateToDB($filter["end_date"], SITE_DATE, DB_DATE));
        }
        //
        if ($publishedOnly == true) {
            $this->db->where("cl_shifts.is_published", 1);
        }

        $records = $this->db
            ->get("cl_shifts")
            ->result_array();
        //
        if ($records) {
            // extract employee ids
            $employeeIds = array_column($records, "employee_sid");
            // get the job color codes by employees jobs
            $employeesJobColorCodes = $this->getEmployeesJobColor($employeeIds);
            //
            $employees = [];
            //
            foreach ($records as $v0) {
                //
                if (!$employees[$v0["employee_sid"]]) {
                    $employees[$v0["employee_sid"]] = [
                        "totalTimeText" => '0h',
                        "totalTime" => 0,
                        "dates" => [],
                        "jobColor" => $employeesJobColorCodes[$v0["employee_sid"]] ?? "#eeeeee"
                    ];
                }
                //
                $employees[$v0["employee_sid"]]["dates"][$v0["shift_date"]][] = [
                    "sid" => $v0["sid"],
                    "start_time" => $v0["start_time"],
                    "end_time" => $v0["end_time"],
                    "job_sites" => json_decode($v0["job_sites"], true),
                    "totalTime" => getTimeBetweenTwoDates(
                        $v0["shift_date"] . ' ' . $v0["start_time"],
                        $v0["shift_date"] . ' ' . $v0["end_time"],
                    ),
                    "is_published" => $v0["is_published"],
                    "shift_date" => $v0["shift_date"],

                ];

                //
                $employees[$v0["employee_sid"]]["totalTime"] += getTimeBetweenTwoDates(
                    $v0["shift_date"] . ' ' . $v0["start_time"],
                    $v0["shift_date"] . ' ' . $v0["end_time"],
                );
                //
                $employees[$v0["employee_sid"]]["totalTimeText"] = convertSecondsToTime(
                    $employees[$v0["employee_sid"]]["totalTime"]
                );
            }
            $records = $employees;
        }

        return $records;
    }

    /**
     * get the company holidays
     *
     * @param int $companyId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getCompanyHolidays(int $companyId, string $startDate, string $endDate): array
    {
        $records = $this->db
            ->select("from_date, to_date")
            ->where("from_date >= ", $startDate)
            ->where("to_date <= ", $endDate)
            ->where("is_archived", 0)
            ->where("company_sid", $companyId)
            ->get("timeoff_holidays")
            ->result_array();
        //
        if ($records) {
            //
            $holidays = [];
            //
            foreach ($records as $record) {
                $holidays = array_merge(
                    $holidays,
                    getDatesInRange(
                        $record["from_date"],
                        $record["to_date"],
                    )
                );
            }
            //
            $records = $holidays;
        }
        //
        return array_merge($records, getCompanyOffDaysDatesWithinRange($startDate, $endDate, getCompanyExtraColumn($companyId, "week_off_days")));
    }

    /**
     * get the company holidays
     *
     * @param int $companyId
     * @param array $filter
     * @return array
     */
    public function getCompanyHolidaysWithTitle(int $companyId, array $filter): array
    {
        $startDate = $endDate = "";

        if ($filter["mode"] === "month") {
            //
            $startDate = $filter["year"] . '-' . $filter["month"] . '-01';
            //
            $endDateObj = new DateTime($startDate);
            $endDate = $endDateObj->format("Y-m-t");
        } else {
            $startDate = formatDateToDB($filter["start_date"], SITE_DATE, DB_DATE);
            $endDate = formatDateToDB($filter["end_date"], SITE_DATE, DB_DATE);
        }
        $records = $this->db
            ->select("from_date, to_date, holiday_title")
            ->where("from_date >= ", $startDate)
            ->where("to_date <= ", $endDate)
            ->where("is_archived", 0)
            ->where("company_sid", $companyId)
            ->get("timeoff_holidays")
            ->result_array();
        //
        if ($records) {
            //
            $holidays = [];
            //
            foreach ($records as $record) {
                $range = getDatesInRange(
                    $record["from_date"],
                    $record["to_date"],
                );
                foreach ($range as $date) {
                    $holidays[$date] = [
                        "title" => $record["holiday_title"],
                        "date" => $date
                    ];
                }
            }
            //
            $records = $holidays;
        }

        // get company off days
        $offs = getCompanyOffDaysDatesWithinRange($startDate, $endDate, getCompanyExtraColumn($companyId, "week_off_days"));
        foreach ($offs as $date) {
            if (!$records[$date]) {
                $records[$date] = [
                    "title" => "Day off",
                    "date" => $date
                ];
            }
        }
        //
        return $records;
    }

    /**
     * create single shift
     *
     * @param int   $companyId
     * @param array $post
     * @return array
     */
    public function processCreateSingleShift(
        int $companyId,
        array $post
    ): array {
        //

        $post["start_time"] = formatDateToDB(
            $post["start_time"],
            "h:i A",
            "H:i"
        );
        $post["end_time"] = formatDateToDB(
            $post["end_time"],
            "h:i A",
            "H:i"
        );

        //
        if ($post["shift_date"]) {
            $post["shift_date"] = formatDateToDB(
                $post["shift_date"],
                SITE_DATE,
                DB_DATE
            );
        }
        //
        $post["breaks"] = array_values($post["breaks"]);
        //
        if ($post["breaks"]) {
            foreach ($post["breaks"] as $index => $break) {
                if ($break["start_time"]) {
                    $post["breaks"][$index]["start_time"] = formatDateToDB(
                        $break["start_time"],
                        "h:i a",
                        "H:i"
                    );
                    $time = new DateTime(getSystemDate(DB_DATE) . " " . $post["breaks"][$index]["start_time"]);
                    $time->add(new DateInterval('PT' . $break["duration"] . 'M'));
                    $post["breaks"][$index]["end_time"] = $time->format('H:i');
                }
                $post["breaks"][$index]["id"] = uniqid(time());
            }
        } else {
            $post["breaks"] = [];
        }
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while creating shift."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "company_sid" => $companyId,
                "employee_sid" => $post["shift_employee"],
                "shift_date" => $post["shift_date"],
                "start_time" => $post["start_time"],
                "end_time" => $post["end_time"],
            ])->where("sid <>", $post["id"])->count_all_results("cl_shifts")) {
                $response["msg"] = "Shift already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("cl_shifts", [
                        "shift_date" => $post["shift_date"],
                        "start_time" => $post["start_time"],
                        "end_time" => $post["end_time"],
                        "breaks_count" => count($post["breaks"]),
                        "breaks_json" => json_encode($post["breaks"]),
                        "job_sites" => json_encode($post["job_sites"] ?? []),
                        "notes" => $post["notes"],
                        "updated_at" => getSystemDate(),
                    ]);

                $ins = [];
                $ins["cl_shift_sid"] = $post["id"];
                $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                $ins["action"] = "updated";
                $ins["action_json"] = "{}";
                $ins["created_at"] = getSystemDate();
                //
                $this->db->insert("cl_shifts_logs", $ins);

                $status = 200;
                $response = ["msg" => "You have successfully updated shift."];
            }
        } else {

            // insert
            // check if entry already exists

            $this->db->where("company_sid", $companyId);
            $this->db->where("employee_sid", $post["shift_employee"]);
            $this->db->where("shift_date", $post["shift_date"]);
            $this->db->where("end_time >=", $post["start_time"]);

            $shiftExist = $this->db->count_all_results("cl_shifts");

            if ($shiftExist) {
                $response["msg"] = "Conflict! Shift overlaps with an existing shift starting at " . formatDateToDB(
                    $post["start_time"] . ":00",
                    "H:i:s",
                    "h:i a"
                ) . " and endding at " . formatDateToDB(
                    $post["end_time"] . ":00",
                    "H:i:s",
                    "h:i a"
                );
            } else {                // insert
                $this->db
                    ->insert("cl_shifts", [
                        "company_sid" => $companyId,
                        "employee_sid" => $post["shift_employee"],
                        "shift_date" => $post["shift_date"],
                        "start_time" => $post["start_time"],
                        "end_time" => $post["end_time"],
                        "breaks_count" => count($post["breaks"]),
                        "breaks_json" => json_encode($post["breaks"]),
                        "job_sites" => json_encode($post["job_sites"] ?? []),
                        "notes" => $post["notes"],
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($insertId = $this->db->insert_id()) {
                    //
                    $ins = [];
                    $ins["cl_shift_sid"] = $insertId;
                    $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                    $ins["action"] = "created";
                    $ins["action_json"] = "{}";
                    $ins["created_at"] = getSystemDate();
                    //
                    $this->db->insert("cl_shifts_logs", $ins);
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new shift."];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }


    //
    public function applyMultiShifts(int $companyId, array $post)
    {
        //
        $post["start_time"] = formatDateToDB(
            $post["start_time"],
            "h:i A",
            "H:i"
        );
        //
        $post["end_time"] = formatDateToDB(
            $post["end_time"],
            "h:i A",
            "H:i"
        );


        $post["breaks"] = array_values($post["breaks"]);

        $markoffDayAsWorking = 0;
        if ($post["job_dayoff"]) {
            $markoffDayAsWorking = 1;
        } else {
            $markoffDayAsWorking = 0;
        }
        //

        if ($post["breaks"]) {
            foreach ($post["breaks"] as $index => $break) {
                if ($break["start_time"]) {
                    $post["breaks"][$index]["start_time"] = formatDateToDB(
                        $break["start_time"],
                        "h:i a",
                        "H:i"
                    );
                    $time = new DateTime(getSystemDate(DB_DATE) . " " . $post["breaks"][$index]["start_time"]);
                    $time->add(new DateInterval('PT' . $break["duration"] . 'M'));
                    $post["breaks"][$index]["end_time"] = $time->format('H:i');
                }
                $post["breaks"][$index]["id"] = uniqid(time());
            }
        } else {
            $post["breaks"] = [];
        }

        // convert the dates
        $startDate = formatDateToDB(
            $post["shift_date_from"],
            SITE_DATE,
            DB_DATE
        );
        //
        $endDate = formatDateToDB(
            $post["shift_date_to"],
            SITE_DATE,
            DB_DATE
        );
        //
        $dates = getDatesInRange($startDate, $endDate, DB_DATE);
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");

        // get company off days

        if ($markoffDayAsWorking == 0) {
            $holidaysAndOffDays = $this->getCompanyHolidays($companyId, $startDate, $endDate);
        }
        //
        $employeesAlreadyExists = [];
        //
        $currentDateAndTime = getSystemDate();
        //
        foreach ($post["employees"] as $employeeId) {
            foreach ($dates as $v0) {
                //
                if ($markoffDayAsWorking == 0) {
                    if (in_array($v0, $holidaysAndOffDays)) {
                        continue;
                    }
                }

                //                            
                $this->db->where("company_sid", $companyId);
                $this->db->where("employee_sid", $employeeId);
                $this->db->where("shift_date", $v0);
                $this->db->where("end_time >=", $post["start_time"]);

                $shiftExist = $this->db->count_all_results("cl_shifts");

                // check if shift already assigned
                if ($shiftExist) {
                    if (!$employeesAlreadyExists[$employeeId]) {
                        $employeesAlreadyExists[$employeeId] = [
                            "dates" => []
                        ];
                    }
                    $employeesAlreadyExists[$employeeId]["dates"][] = $v0;
                    continue;
                }
                // add the shift
                $ins = [];
                $ins["company_sid"] = $companyId;
                $ins["employee_sid"] = $employeeId;
                $ins["shift_date"] = $v0;
                $ins["start_time"] = $post["start_time"];
                $ins["end_time"] = $post["end_time"];
                $ins["breaks_count"] = count($post["breaks"]);
                $ins["breaks_json"] = json_encode($post['breaks']);
                $ins["job_sites"] = json_encode($post["job_sites"] ?? []);
                $ins["notes"] = $post['notes'];
                $ins["created_at"] = $ins["updated_at"] = $currentDateAndTime;
                //
                $this->db->insert("cl_shifts", $ins);
                //
                $insertId = $this->db->insert_id();
                //
                if ($insertId) {
                    $ins = [];
                    $ins["cl_shift_sid"] = $insertId;
                    $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                    $ins["action"] = "created";
                    $ins["action_json"] = "{}";
                    $ins["created_at"] = $currentDateAndTime;
                    //
                    $this->db->insert("cl_shifts_logs", $ins);
                }
            }
        }

        return SendResponse(
            200,
            [
                "msg" => "You have successfully applied the shift to the selected employees." . (
                    $employeesAlreadyExists ? "<p>However, the below employees already have shifts.</p>" : ""
                ),
                "list" => $employeesAlreadyExists
            ]
        );
    }


    //
    public function copyMultiShifts(int $companyId, array $post)
    {

        //
        $lastStartDate = formatDateToDB(
            $post["last_shift_date_from"],
            SITE_DATE,
            DB_DATE
        );
        //
        $lastEndDate = formatDateToDB(
            $post["last_shift_date_to"],
            SITE_DATE,
            DB_DATE
        );

        // convert the dates
        $startDate = formatDateToDB(
            $post["shift_date_from"],
            SITE_DATE,
            DB_DATE
        );
        //
        $endDate = formatDateToDB(
            $post["shift_date_to"],
            SITE_DATE,
            DB_DATE
        );

        //
        $markoffDayAsWorking = 0;
        if ($post["job_dayoff"]) {
            $markoffDayAsWorking = 1;
        } else {
            $markoffDayAsWorking = 0;
        }

        //
        $dates = getDatesInRange($startDate, $endDate, DB_DATE);
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");

        $markoffDayAsWorking = 0;
        if ($post["job_dayoff"]) {
            $markoffDayAsWorking = 1;
        } else {
            $markoffDayAsWorking = 0;
        }

        // get company off days
        if ($markoffDayAsWorking == 0) {
            $holidaysAndOffDays = $this->getCompanyHolidays($companyId, $startDate, $endDate);
        }
        //
        $employeesAlreadyExists = [];
        //
        $currentDateAndTime = getSystemDate();
        //
        foreach ($post["employees"] as $employeeId) {
            $this->db->select("
                            sid,
                            employee_sid,
                            start_time,
                            end_time,
                            breaks_count,
                            notes,
                            job_sites,
                            breaks_json,
                        ");
            $this->db->where("company_sid", $companyId);
            $this->db->where('shift_date >=',  $lastStartDate);
            $this->db->where('shift_date <=',  $lastEndDate);
            $this->db->where('employee_sid',  $employeeId);
            $lastCycleShiftData = $this->db->get("cl_shifts")->result_array();

            foreach ($lastCycleShiftData as $lastCycleShiftDataRow) {
                foreach ($dates as $key => $v0) {

                    $shiftDataToCopy = $lastCycleShiftData[$key];
                    //
                    if ($lastCycleShiftData[$key]) {

                        if ($markoffDayAsWorking == 0) {
                            if (in_array($v0, $holidaysAndOffDays)) {
                                continue;
                            }
                        }

                        //

                        /*
                        $where = [
                            "employee_sid" => $employeeId,
                            "shift_date" => $v0,
                            "start_time" => $lastCycleShiftDataRow['start_time'],
                            "end_time" => $lastCycleShiftDataRow['end_time']
                        ];
*/


                        $this->db->where("employee_sid", $employeeId);
                        $this->db->where("shift_date", $v0);
                        $this->db->where("end_time >=", $lastCycleShiftDataRow['start_time']);

                        $shiftExist = $this->db->count_all_results("cl_shifts");


                        // check if shift already assigned
                        if ($shiftExist) {
                            if (!$employeesAlreadyExists[$employeeId]) {
                                $employeesAlreadyExists[$employeeId] = [
                                    "dates" => []
                                ];
                            }
                            $employeesAlreadyExists[$employeeId]["dates"][] = $v0;
                            continue;
                        }
                        // add the shift
                        $ins = [];
                        $ins["company_sid"] = $companyId;
                        $ins["employee_sid"] = $employeeId;
                        $ins["shift_date"] = $v0;

                        $ins["start_time"] = $lastCycleShiftDataRow["start_time"];
                        $ins["end_time"] = $lastCycleShiftDataRow["end_time"];
                        $ins["breaks_count"] = $lastCycleShiftDataRow["breaks_count"];
                        $ins["breaks_json"] = $lastCycleShiftDataRow['breaks_json'];
                        $ins["job_sites"] = $lastCycleShiftDataRow["job_sites"] ?? "[]";
                        $ins["notes"] = $lastCycleShiftDataRow['notes'];


                        $ins["created_at"] = $ins["updated_at"] = $currentDateAndTime;
                        //

                        $this->db->insert("cl_shifts", $ins);
                        //
                        $insertId = $this->db->insert_id();
                        //
                        if ($insertId) {
                            $ins = [];
                            $ins["cl_shift_sid"] = $insertId;
                            $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                            $ins["action"] = "created";
                            $ins["action_json"] = "{}";
                            $ins["created_at"] = $currentDateAndTime;
                            //
                            $this->db->insert("cl_shifts_logs", $ins);
                        }
                    }
                }
            }
        }

        return SendResponse(
            200,
            [
                "msg" => "You have successfully copied the shift to the selected employees." . (
                    $employeesAlreadyExists ? "<p>However, the below employees already have shifts.</p>" : ""
                ),
                "list" => $employeesAlreadyExists
            ]
        );
    }


    //
    public function deleteMultiShifts(int $companyId, array $post)
    {

        // convert the dates
        $startDate = formatDateToDB(
            $post["shift_date_from"],
            SITE_DATE,
            DB_DATE
        );
        //
        $endDate = formatDateToDB(
            $post["shift_date_to"],
            SITE_DATE,
            DB_DATE
        );

        $employees = $post['employees'];

        $this->db->where('company_sid',  $companyId);
        $this->db->where('shift_date >=',  $startDate);
        $this->db->where('shift_date <=',  $endDate);
        $this->db->where_in('employee_sid',  $employees);
        $this->db->delete('cl_shifts');

        return SendResponse(
            200,
            [
                "msg" => "You have successfully deleted the shifts of the selected employees."
            ]
        );
    }

    //
    public function deleteSingleShift(int $companyId, array $post)
    {

        $employeeId = $post['employeeId'];
        $shiftDate = $post['shiftDate'];
        $this->db->where('company_sid',  $companyId);
        $this->db->where('employee_sid',  $employeeId);
        $this->db->where('shift_date',  $shiftDate);

        $this->db->delete('cl_shifts');

        return SendResponse(
            200,
            [
                "msg" => "You have successfully deleted the shift of the selected employee."
            ]
        );
    }

    /**
     * get the number of shifts within a range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function getShiftsCountByEmployeeId(int $employeeId, string $startDate, string $endDate): int
    {
        return $this->db
            ->where('employee_sid', $employeeId)
            ->where('shift_date >= ', $startDate)
            ->where('shift_date <= ', $endDate)
            ->where('is_published', 1)

            ->count_all_results('cl_shifts');
    }

    /**
     * get the employee subordinates with departments
     * and teams
     *
     * @param int $employeeId
     * @return array
     */
    public function getMySubordinates(int $employeeId, bool $doCount = false): array
    {
        $data = getMyDepartmentAndTeams($employeeId);
        // only return array of employees
        if ($doCount) {
            return $data["employees"];
        }
        //
        return $data;
    }

    /**
     * get the subordinates by employee
     *
     * @param int $employeeId
     * @param int $employeeId
     * @param array $departmentIds
     * @param array $teamIds
     * @param array $employeeIds
     * @return array
     */
    public function getEmployeeSubOrdinatesWithDepartmentAndTeams(
        int $employeeId,
        array $departmentIds,
        array $teamIds,
        array $employeeIds
    ): array {
        //
        $ra = [
            "teams" => [],
            "departments" => [],
            "employees" => []
        ];
        //
        $this->db->select("
            departments_team_management.sid as team_sid,
            departments_team_management.name as team_name,
            departments_management.sid,
            departments_management.name,
            departments_management.name,
        ")
            ->join(
                "departments_management",
                "departments_management.sid = departments_team_management.department_sid",
                "inner"
            )
            ->where("departments_management.is_deleted", 0)
            ->where("departments_team_management.is_deleted", 0)
            ->group_start()
            ->where("FIND_IN_SET({$employeeId}, departments_team_management.team_lead) > 0", null, null)
            ->or_where("FIND_IN_SET({$employeeId}, departments_management.supervisor) > 0", null, null)
            ->group_end();

        // for departments
        if ($departmentIds) {
            $this->db->where_in("departments_management.sid", $departmentIds);
        }

        // for teams
        if ($teamIds) {
            $this->db->where_in("departments_team_management.sid", $teamIds);
        }


        $records = $this->db
            ->get("departments_team_management")
            ->result_array();

        if (!$records) {
            return $ra;
        }

        foreach ($records as $v0) {
            // for departments
            if (!$ra["departments"][$v0["sid"]]) {
                $ra["departments"][$v0["sid"]] = [
                    "sid" => $v0["sid"],
                    "title" => $v0["name"]
                ];
            }
            // for teams
            if (!$ra["teams"][$v0["team_sid"]]) {
                $ra["teams"][$v0["team_sid"]] = [
                    "sid" => $v0["team_sid"],
                    "title" => $v0["team_name"]
                ];
            }
        }

        // get the employees list
        $teamIds = array_column($records, "team_sid");
        // get employees
        $this->db->select("employee_sid")
            ->where_in("departments_employee_2_team.team_sid", $teamIds);
        // for employee ids
        if ($employeeIds) {
            $this->db->where_in("departments_employee_2_team.employee_sid", $employeeIds);
        }
        //
        $employees = $this->db
            ->get("departments_employee_2_team")
            ->result_array();
        //
        if (!$employees) {
            return $ra;
        }

        $ra["employees"] = array_unique(array_column($employees, "employee_sid"));

        return $ra;
    }

    /**
     * get the employee details by id
     *
     * @param array $employeeIds
     * @param int $companyId
     * @return array
     */
    public function getEmployeeDetailsByIds(array $employeeIds, int $companyId): array
    {
        return $this->db
            ->select(getUserFields())
            ->where_in("sid", $employeeIds)
            ->where("parent_sid", $companyId)
            ->get("users")
            ->result_array();
    }

    /**
     * get the employee shifts within a specific
     * date range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getEmployeeShiftsWithinRange(
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        //
        $this->db
            ->select("
                shift_date,
                start_time,
                end_time,
                breaks_count,
                breaks_json,
                job_sites
            ")
            ->where([
                "employee_sid" => $employeeId,
                "shift_date >= " => $startDate,
                "shift_date <= " => $endDate,
            ]);
        // get the records
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();
        // convert the array to list
        $records = convertToList(
            $records,
            "shift_date",
            false
        );
        //
        if ($records) {
            foreach ($records as $index => $v0) {
                $records[$index]["breaks"] = convertToList(
                    json_decode(
                        $v0["breaks_json"],
                        true
                    ),
                    "id"
                );
            }
        }
        //
        return $records;
    }

    /**
     * get the employee job colors
     *
     * @param array $employeeIds
     * @return array
     */
    private function getEmployeesJobColor(array $employeeIds): array
    {
        //
        $result =
            $this->db
            ->select("portal_job_title_templates.color_code")
            ->select("users.sid")
            ->join(
                "portal_job_title_templates",
                "
                    LOWER(REGEXP_REPLACE(portal_job_title_templates.title, '[^a-zA-Z]', '')) = 
                    LOWER(REGEXP_REPLACE(users.job_title, '[^a-zA-Z]', ''))
                ",
                "inner"
            )
            ->where_in("users.sid", $employeeIds)
            ->get("users")
            ->result_array();
        //
        if (!$result) {
            return [];
        }
        //
        $ra = [];
        //
        foreach ($result as $v) {
            $ra[$v["sid"]] = $v["color_code"];
        }
        //
        return $ra;
    }

    /**
     * get shift by employee id and date
     *
     * @param int    $employeeId
     * @param string $clockedDate
     * @param bool   $convertToSeconds If false then hours will be returned otherwise seconds
     * @return int
     */
    public function getEmployeeShiftByDateAndId(int $employeeId, string $clockedDate, bool $convertToSeconds = false)
    {
        // get the shift
        $record = $this->db
            ->select("
                start_time,
                end_time
            ")
            ->where([
                "employee_sid" => $employeeId,
                "shift_date" => $clockedDate,
                // "is_published" => 1, // enable this check when publish work is live
            ])
            ->get("cl_shifts")
            ->row_array();
        // when no record found
        if (!$record) {
            return 0;
        }
        // set start date and time
        $startDateTime = $clockedDate . " " . $record["start_time"];
        $endDateTime = $clockedDate . " " . $record["end_time"];
        // get the difference
        $duration = $this->attendance_lib
            ->getDurationInMinutes(
                $startDateTime,
                $endDateTime
            );
        //
        return $convertToSeconds ? $duration : $duration / 60 / 60;
    }




    //
    public function SingleShiftPublishStatusChange(int $companyId, array $post)
    {

        $shiftId = $post['shiftId'];
        $data['is_published'] = $post['publichStatus'];

        $this->db->where('company_sid',  $companyId);
        $this->db->where('sid',  $shiftId);
        $this->db->update('cl_shifts', $data);
    }


    //
    public function SingleMultiPublishStatusChange(int $companyId, array $post)
    {

        $shiftIds = explode(',', $post['shiftIds']);
        $data['is_published'] = $post['publichStatus'];

        $this->db->where('company_sid',  $companyId);
        $this->db->where_in('sid',  $shiftIds);
        $this->db->update('cl_shifts', $data);
    }


    //
    public function getShiftsById($companyId, $shiftId)
    {
        // get the shift
        $record = $this->db
            ->select(
                "cl_shifts.employee_sid,
                cl_shifts.shift_date,
                cl_shifts.start_time,
                cl_shifts.end_time,
                cl_shifts.breaks_json,
                cl_shifts.breaks_count,
                 users.first_name,
                 users.last_name,
                  users.email
                "
            )
            ->join(
                "users",
                "users.sid = cl_shifts.employee_sid",
                "inner"
            )
            ->where_in("cl_shifts.sid", $shiftId)
            ->where("cl_shifts.company_sid", $companyId)
            ->get("cl_shifts")
            ->result_array();

        return $record;
    }

    //
    public function getShiftsForCalendar(

        $company_id,
        $employer_detail,
        $calendarStartDate,
        $calendarEndDate
    ) {
        //
        $this->db
            ->select("
            sid,
            employee_sid,
            shift_date,
            start_time,
            end_time,
            job_sites,
            breaks_json,
            breaks_count
        ");
        //
        $this->db
            ->where("shift_date >= ", $calendarStartDate)
            ->where("shift_date <= ", $calendarEndDate)
            ->where("is_published", 1)
            ->where("company_sid", $company_id);
        //
        if (!isPayrollOrPlus()) {
            $this->db->where("employee_sid", $employer_detail['sid']);
        }
        //
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();
        //
        if ($records) {
            //
            $jobColors = $this->getEmployeesJobColor(
                array_column(
                    $records,
                    "employee_sid"
                )
            );

            foreach ($records as $key => $val) {
                $result = $this->db
                    ->select(getUserFields())
                    ->from('users')
                    ->where('sid', $val['employee_sid'])
                    ->where('active', 1)
                    ->where('terminated_status', 0)
                    ->where('is_executive_admin', 0)
                    ->where('parent_sid', $company_id)
                    ->limit(1)
                    ->get()
                    ->row_array();

                if (!$result) {
                    unset($records[$key]);
                    continue;
                }

                $records[$key]['title'] = remakeEmployeeName($result);
                $records[$key]['start'] = $val['shift_date'];
                $records[$key]['end'] = $val['shift_date'];
                $records[$key]['color'] =  $jobColors[$result["userId"]] ?? '#af4200';
                $records[$key]['status'] = '';
                $records[$key]['img'] = getImageURL(
                    $result["image"]
                );
                $records[$key]['requests'] = 0;
                $records[$key]['type'] = 'shifts';
            }
        }
        return $records;
    }
}
