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
            employee_can_claim,
            employee_need_approval_for_claim,
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
    public function getCompanyEmployees(int $companyId, $employeeFilter = [])
    {



        if ($employeeFilter['shift_date'] != '') {

            $this->db
                ->select('employee_sid');
            $this->db->where("cl_shifts.shift_date<=", $employeeFilter['shift_date']);
            $this->db->where("cl_shifts.shift_date>=", $employeeFilter['shift_date']);

            $dataRecord = $this->db->get("cl_shifts")->result_array();
            $emplloyeeHaveShift = array_column($dataRecord, 'employee_sid');
        }


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

        if ($employeeFilter['shift_date'] != '') {
            if (!empty($emplloyeeHaveShift)) {
                $this->db->where_not_in("users.sid", $emplloyeeHaveShift);
            }
        }



        if ($employeeFilter['jobtitle'][0] != 'all' && !empty($employeeFilter['jobtitle'][0])) {

            $this->db->group_start();
            for ($i = 0; $i < count($employeeFilter['jobtitle']); $i++) {
                $this->db->or_where("LOWER(job_title) = ", strtolower($employeeFilter['jobtitle'][$i]));
            }
            $this->db->group_end();
        }


        $this->db->order_by("users.first_name", "ASC");
        $data = $this->db->get("users")->result_array();

        return $data;
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
    public function getShifts_old(array $filter, array $employeeIds, $publishedOnly = false): array
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
                $employees[$v0["employee_sid"]]["dates"][$v0["shift_date"]] = [
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
        array $post,
        $sendShift = false
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
            } else {
                // insert
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

                    if ($sendShift == true) {
                        $shiftId = $insertId;
                    } else {
                        $shiftId = 0;
                    }

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
                    $response = ["msg" => "You have successfully add a new shift.", "shiftid" => $shiftId, "shiftdate" => $post["shift_date"]];
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
                    $ins["start_time"] = $shiftDataToCopy["start_time"];
                    $ins["end_time"] = $shiftDataToCopy["end_time"];
                    $ins["breaks_count"] = $shiftDataToCopy["breaks_count"];
                    $ins["breaks_json"] = $shiftDataToCopy['breaks_json'];
                    $ins["job_sites"] = $shiftDataToCopy["job_sites"] ?? "[]";
                    $ins["notes"] = $shiftDataToCopy['notes'];
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

        $shiftId = $post['id'];
        $this->db->where('company_sid',  $companyId);
        $this->db->where('sid',  $shiftId);
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
        $data['is_published'] = $post['publishStatus'];

        $this->db->where('company_sid',  $companyId);
        $this->db->where('sid',  $shiftId);
        $this->db->update('cl_shifts', $data);
    }


    //
    public function SingleMultiPublishStatusChange(int $companyId, array $post)
    {

        $shiftIds = explode(',', $post['shiftIds']);
        $data['is_published'] = $post['publishStatus'];

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
        //
        return $records;
    }

    /**
     * get employee shifts
     *
     * @param array $filter
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeShifts(array $filter, int $employeeId): array
    {
        //
        $this->db
            ->select("cl_shifts.sid, cl_shifts.employee_sid, cl_shifts.shift_date, cl_shifts.start_time, cl_shifts.end_time,cl_shifts_request.request_status,cl_shifts_request.to_employee_sid,cl_shifts_request.created_at,cl_shifts_request.updated_at")
            ->where("employee_sid", $employeeId);

        $this->db
            ->where("shift_date >= ", formatDateToDB($filter["startDate"], SITE_DATE, DB_DATE))
            ->where("shift_date <= ", formatDateToDB($filter["endDate"], SITE_DATE, DB_DATE));

        $this->db->join(
            "cl_shifts_request",
            "cl_shifts_request.shift_sid = cl_shifts.sid",
            "left"
        );

        //
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();

        return  $records;
    }


    //
    public function getShiftsByShiftId(array $shiftIds): array
    {
        //
        $this->db
            ->select("sid, employee_sid, shift_date, start_time, end_time, job_sites,breaks_json")
            ->where_in("sid", $shiftIds);
        //
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();

        return $records;
    }

    //
    public function addShiftsTradeRequest($shiftId, $data)
    {
        //
        $shiftsRecord = $this->getShiftsRequestById(array($shiftId));
        foreach ($shiftsRecord as $dataRow) {
            unset($dataRow['sid']);
            $this->db
                ->insert("cl_shifts_request_history", $dataRow);
        }

        $data['updated_by'] = checkAndGetSession("employee")["sid"];
        $data['company_sid'] = checkAndGetSession("company")['sid'];

        if ($this->db->where("shift_sid", $shiftId)->where("to_employee_sid", $data['to_employee_sid'])->count_all_results("cl_shifts_request")) {
            $this->db
                ->where("shift_sid", $shiftId)
                ->where("to_employee_sid", $data['to_employee_sid'])
                ->update("cl_shifts_request", [
                    "request_status" => 'awaiting confirmation',
                    "to_employee_sid" => $data['to_employee_sid'],
                    "created_at" => getSystemDate(),
                ]);
        } else {
            $this->db
                ->insert("cl_shifts_request", $data);
        }
    }

    //
    public function cancelShiftsTradeRequest($shiftIds, $data)
    {
        //
        $shiftsRecord = $this->getShiftsRequestById($shiftIds);
        //
        $data['updated_by'] = checkAndGetSession("employee")["sid"];
        //
        foreach ($shiftsRecord as $dataRow) {
            unset($dataRow['sid']);
            //
            $this->db
                ->insert("cl_shifts_request_history", $dataRow);
            //
            // update shift to new employee
            if ($data['request_status'] == 'approved') {
                $dataShift['employee_sid'] = $dataRow['to_employee_sid'];
                $this->db
                    ->where("sid", $dataRow['shift_sid'])
                    ->update("cl_shifts", $dataShift);
            }
            //
            if ($dataRow['request_type'] == 'open' && $data['request_status'] == 'approved') {
                //
                $this->db
                    ->where_in("shift_sid", $shiftIds)
                    ->where("to_employee_sid", $data['to_employee_sid'])
                    ->update("cl_shifts_request", $data);
            } else {
                //
                $this->db->where_in("shift_sid", $shiftIds);
                //
                // if ($data['updated_by'] == $dataRow['from_employee_sid']) {
                //     $this->db->where("from_employee_sid", $data['to_employee_sid']);
                // } else {
                //     $this->db->where("to_employee_sid", $data['to_employee_sid']);
                // }
                //
                $this->db->update("cl_shifts_request", $data);
            }
        }
    }

    /**
     * get employee shifts
     *
     * @param array $filter
     * @param int $employeeId
     * @return array
     */
    public function getMySwapShifts(array $filter, int $employeeId): array
    {
        //
        $this->db
            ->select("cl_shifts.sid, cl_shifts.employee_sid, cl_shifts.shift_date, cl_shifts.start_time, cl_shifts.end_time,cl_shifts_request.request_status,cl_shifts_request.to_employee_sid,cl_shifts_request.created_at,cl_shifts_request.from_employee_sid,cl_shifts_request.updated_at")
            ->where("cl_shifts_request.to_employee_sid", $employeeId)
            ->where("cl_shifts_request.request_status!=", 'cancelled')
            ->where("cl_shifts_request.request_status!=", 'rejected');
        $this->db
            ->where("shift_date >= ", formatDateToDB($filter["startDate"], SITE_DATE, DB_DATE))
            ->where("shift_date <= ", formatDateToDB($filter["endDate"], SITE_DATE, DB_DATE));

        $this->db->join(
            "cl_shifts_request",
            "cl_shifts_request.shift_sid = cl_shifts.sid",
            "left"
        );

        //
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();

        return  $records;
    }

    //
    public function getAwaitingSwapShiftsByUserId(int $employeeId)
    {
        //
        $this->db->where("to_employee_sid", $employeeId);
        $this->db->where("request_status", 'awaiting confirmation');
        $requestCount = $this->db->count_all_results('cl_shifts_request');
        //
        return $requestCount;
    }

    //
    public function getAwaitingSwapShiftsApprovals()
    {
        //
        $this->db->where("company_sid", checkAndGetSession("company")['sid']);
        $this->db->where("request_status", 'confirmed');
        $requestCount = $this->db->count_all_results('cl_shifts_request');
        //
        return $requestCount;
    }

    /**
     * get the company employees only 
     *
     * @param int $companyId
     * @param int $logedin employeeId
     * @return array
     */
    public function getCompanyEmployeesForShiftSwap(int $companyId, int $loggedInEmployee): array
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
        $this->db->where("users.sid!=", $loggedInEmployee);

        $this->db->order_by("users.first_name", "ASC");
        return $this->db->get("users")->result_array();
    }

    //
    public function getEmployeesOnLeaveOld(array $leaveDate, int $companyId): array
    {
        //
        $this->db
            ->select("employee_sid")
            ->where_in("request_from_date", $leaveDate)
            ->where_in("status", 'approved')
            ->where_in("company_sid", $companyId);
        //
        $records = $this->db
            ->get("timeoff_requests")
            ->result_array();

        return $records;
    }

    //
    public function getEmployeesOnLeave(array $leaveDate, int $companyId): array
    {
        $employeesIds = [];
        //
        foreach ($leaveDate as $date) {
            $this->db
                ->select("employee_sid")
                ->group_start()
                ->where('timeoff_requests.request_from_date <=', $date)
                ->where('timeoff_requests.request_to_date >=', $date)
                ->group_end()
                ->where("status", 'approved')
                ->where("company_sid", $companyId);
            //
            $records = $this->db
                ->get("timeoff_requests")
                ->result_array();

            //
            foreach ($records as $row) {
                if (!in_array($row['employee_sid'], $employeesIds)) {
                    array_push($employeesIds, $row['employee_sid']);
                }
            }
        }
        //
        return $employeesIds;
    }

    public function getEmployeesWithShifts(array $leaveDate, int $companyId): array
    {
        $employeesIds = [];
        //
        foreach ($leaveDate as $date) {
            $this->db
                ->select("employee_sid")
                ->where("is_published", 1)
                ->where("shift_date", $date);
            //
            $records = $this->db
                ->get("cl_shifts")
                ->result_array();

            //
            foreach ($records as $row) {
                if (!in_array($row['employee_sid'], $employeesIds)) {
                    array_push($employeesIds, $row['employee_sid']);
                }
            }
        }
        //
        return $employeesIds;
    }

    //
    public function getShiftsRequestById($shiftIds, $shiftStatus = '', $toEmployeeId = 0)
    {
        //
        $this->db
            ->where_in("shift_sid", $shiftIds);

        if ($shiftStatus != '') {
            $this->db
                ->where_in("request_status", $shiftStatus);
        }

        if ($toEmployeeId != 0) {
            $this->db
                ->where("to_employee_sid", $toEmployeeId);
        }

        $records =  $this->db->get("cl_shifts_request")
            ->result_array();

        return  $records;
    }

    //
    public function getSwapShiftsRequest($filterData)
    {
        //

        $this->db->select("cl_shifts.sid, cl_shifts.employee_sid, cl_shifts.shift_date, cl_shifts.start_time, cl_shifts.end_time,cl_shifts_request.request_status,cl_shifts_request.to_employee_sid,cl_shifts_request.created_at,,cl_shifts_request.from_employee_sid,cl_shifts_request.shift_sid,cl_shifts_request.updated_at,cl_shifts_request.sid as request_sid,cl_shifts_request.request_type");

        if ($filterData['type'] != 'all') {
            $this->db->where("cl_shifts_request.request_status", $filterData['type']);
        }

        if (!empty($filterData['filter']['dateRange'])) {
            //
            $tmp = explode("-", $filterData['filter']['dateRange']);
            $this->db
                ->where("cl_shifts.shift_date >= ", formatDateToDB(trim($tmp[0]), SITE_DATE, DB_DATE))
                ->where("cl_shifts.shift_date <= ", formatDateToDB(trim($tmp[1]), SITE_DATE, DB_DATE));
        }

        $this->db->where("cl_shifts_request.request_status!=", 'cancelled');

        $this->db->join(
            "cl_shifts",
            "cl_shifts.sid = cl_shifts_request.shift_sid"
        );

        $records = $this->db->get("cl_shifts_request")->result_array();
        //
        if (!empty($records)) {
            foreach ($records as $key => $row) {
                //
                if ($row["request_status"] == "approved" || $row["request_status"] == "admin rejected" || $row["request_status"] == "confirmed") {
                    $records[$key]['from_employee'] = getUserNameBySID($row['from_employee_sid']);
                    $records[$key]['to_employee'] = getUserNameBySID($row['to_employee_sid']);
                    $records[$key]['created_at'] = $row['created_at'] != '' ? date_with_time($row['created_at']) : ' - ';
                    $records[$key]['shift_date'] = $row['shift_date'] != '' ? date_with_time($row['shift_date']) : ' - ';
                    $records[$key]['start_time'] = formatDateToDB($row["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($row["end_time"], "H:i:s", "h:i a");
                    $records[$key]['request_status'] = $row["request_status"] != '' ? ucwords($row["request_status"]) : ' - ';
                    $records[$key]['updated_at'] = $row['updated_at'] != '' ? date_with_time($row['updated_at']) : ' - ';
                } else {
                    unset($records[$key]);
                }
            }
        }
        //
        return $records;
    }


    //
    public function getSwapShiftsRequestById($shiftIds, $shiftStatus = '', $toEmployeeId = 0)
    {
        //
        $this->db->select("
            cl_shifts.sid, 
            cl_shifts.employee_sid, 
            cl_shifts.shift_date, 
            cl_shifts.start_time, 
            cl_shifts.end_time,
            cl_shifts_request.request_status,
            cl_shifts_request.to_employee_sid,
            cl_shifts_request.created_at,
            cl_shifts_request.company_sid,
            cl_shifts_request.from_employee_sid,
            cl_shifts_request.shift_sid, 
            cl_shifts_request.updated_at,
            cl_shifts_request.updated_by,
            cl_shifts_request.admin_sid
        ");
        $this->db->where_in("cl_shifts_request.shift_sid", $shiftIds);

        if ($shiftStatus != '') {
            $this->db->where("cl_shifts_request.request_status", $shiftStatus);
        }

        if ($toEmployeeId > 0) {
            $this->db->where("cl_shifts_request.to_employee_sid", $toEmployeeId);
        }

        $this->db->join(
            "cl_shifts",
            "cl_shifts.sid = cl_shifts_request.shift_sid"
        );

        $records = $this->db->get("cl_shifts_request")->result_array();

        //
        if (!empty($records)) {
            foreach ($records as $key => $row) {

                $fromEmployeeData = getUserNameBySID($row['from_employee_sid'], false);
                $toEmployeeData = getUserNameBySID($row['to_employee_sid'], false);
                $updatedEmployeeData = getUserNameBySID($row['updated_by'], false);
                $companyName = getCompanyNameBySid($row['company_sid']);

                $records[$key]['from_employee'] = $fromEmployeeData[0]['first_name'] . ' ' . $fromEmployeeData[0]['last_name'];
                $records[$key]['to_employee'] = $toEmployeeData[0]['first_name'] . ' ' . $toEmployeeData[0]['last_name'];
                $records[$key]['created_at'] = $row['created_at'] != '' ? date_with_time($row['created_at']) : ' - ';
                $records[$key]['shift_date'] = $row['shift_date'] != '' ? date_with_time($row['shift_date']) : ' - ';
                $records[$key]['start_time'] = formatDateToDB($row["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($row["end_time"], "H:i:s", "h:i a");
                $records[$key]['request_status'] = $row["request_status"] != '' ? ucwords($row["request_status"]) : ' - ';
                $records[$key]['updated_at'] = $row['updated_at'] != '' ? date_with_time($row['updated_at']) : ' - ';
                $records[$key]['from_employee_email'] = $fromEmployeeData[0]['email'];
                $records[$key]['to_employee_email'] = $toEmployeeData[0]['email'];
                $records[$key]['updated_by'] = $updatedEmployeeData[0]['first_name'] . ' ' . $updatedEmployeeData[0]['last_name'];
                $records[$key]['companyName'] = $companyName;
                $records[$key]['companyId'] = $row['company_sid'];
            }
        }

        return $records;
    }

    //
    public function updateShiftsTradeRequest($shiftId, $toEmployeeId, $data)
    {
        $requestData = $this->db
            ->select("
            request_type
            ")
            ->where("shift_sid", $shiftId)
            ->get("cl_shifts_request")
            ->row_array();
        //
        $this->db
            ->where("shift_sid", $shiftId)
            ->where("to_employee_sid", $toEmployeeId)
            ->update("cl_shifts_request", $data);
        //
        if ($data['request_status'] == 'approved') {
            $dataShift['employee_sid'] = $toEmployeeId;
            $this->db
                ->where("sid", $shiftId)
                ->update("cl_shifts", $dataShift);
        }
        //
        if ($data['request_status'] == 'confirmed' && $requestData['request_type'] == 'open') {
            //
            $data['request_status'] = 'rejected';
            //
            $this->db
                ->where("shift_sid", $shiftId)
                ->where("to_employee_sid!=", $toEmployeeId)
                ->update("cl_shifts_request", $data);
        }
    }

    //
    public function getShiftRequestsHistory($shiftId, $countOnly = false)
    {
        //

        $this->db->where("company_sid", checkAndGetSession("company")['sid']);
        $this->db->where("shift_sid", $shiftId);
        if ($countOnly == true) {
            $records =     $this->db->count_all_results('cl_shifts_request_history');
            return  $records;
        } else {
            $records = $this->db->get("cl_shifts_request_history")->result_array();
        }

        //
        if (!empty($records)) {
            foreach ($records as $key => $row) {
                $records[$key]['from_employee'] = getUserNameBySID($row['from_employee_sid']);
                $records[$key]['to_employee'] = getUserNameBySID($row['to_employee_sid']);
                $records[$key]['created_at'] = $row['created_at'] != '' ? date_with_time($row['created_at']) : ' - ';
                $records[$key]['shift_date'] = $row['shift_date'] != '' ? date_with_time($row['shift_date']) : ' - ';
                $records[$key]['start_time'] = formatDateToDB($row["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($row["end_time"], "H:i:s", "h:i a");
                $records[$key]['request_status'] = $row["request_status"] != '' ? ucwords($row["request_status"]) : ' - ';
                $records[$key]['updated_at'] = $row['updated_at'] != '' ? date_with_time($row['updated_at']) : ' - ';
                $records[$key]['updated_by'] = getUserNameBySID($row['updated_by']);
            }
        }
        //
        return  $records;
    }

    //
    public function processCreateOpenSingleShift(
        int $companyId,
        array $post,
        $sendShift = false
    ): array {
        //

        $employee_can_claim =  $post["employee_can_claim"] ? '1' : '0';
        $employee_need_approval_for_claim = $post["employee_need_approval_for_claim"] ? '1' : '0';

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
                        "employee_can_claim" => $employee_can_claim,
                        "employee_need_approval_for_claim" => $employee_need_approval_for_claim,
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
            } else {
                // insert
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
                        "employee_can_claim" => $employee_can_claim,
                        "employee_need_approval_for_claim" => $employee_need_approval_for_claim,
                    ]);
                // check and insert log
                if ($insertId = $this->db->insert_id()) {

                    if ($sendShift == true) {
                        $shiftId = $insertId;
                    } else {
                        $shiftId = 0;
                    }

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
                    $response = ["msg" => "You have successfully add a new shift.", "shiftid" => $shiftId, "shiftdate" => $post["shift_date"]];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
   }

    //
    public function getOpenShifts(array $filter, $employee_can_claim = false): array
    {

  //
  if (empty($employeeIds)) {
    $employeeIds = ['0'];
}
$this->db
    ->select("sid, employee_sid, shift_date, start_time, end_time, job_sites,is_published")
    ->where("employee_sid", 0);

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
  if ($employee_can_claim == true) {
    $this->db
        ->where("employee_can_claim", 1);
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


    //
    public function processClaimOpenSingleShift(
        int $companyId,
        array $post,
        $sendShift = false
    ) {
        //
        $approvalData = $this->db
            ->where("employee_sid", checkAndGetSession("employee")["sid"])
            ->where("company_sid", $companyId)
            ->where("shift_sid", $post["id"])
            ->where("shift_status", 2)
            ->delete("open_shifts_approval");

        //
        $shiftData = $this->db
            ->select("employee_can_claim, employee_need_approval_for_claim,")
            ->where("employee_need_approval_for_claim", 1)
            ->where("company_sid", $companyId)
            ->where("sid", $post["id"])
            ->get("cl_shifts")
            ->row_array();
        if (!empty($shiftData)) {

            $approvalData = $this->db
                ->select("shift_sid")
                ->where("employee_sid", checkAndGetSession("employee")["sid"])
                ->where("company_sid", $companyId)
                ->where("shift_sid", $post["id"])
                ->get("open_shifts_approval")
                ->row_array();

            if (!empty($approvalData)) {
                $status = 200;
                $response = ["msg" => "You have already submitted an approval request for this shift."];
            } else {

                $ins = [];
                $ins["shift_sid"] = $post["id"];
                $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                $ins["company_sid"] = $companyId;
                $ins["created_at"] = getSystemDate();
                //
                $this->db->insert("open_shifts_approval", $ins);

                $status = 200;
                $response = ["msg" => "Shift needs approval. Request sent successfully."];
            }
        } else {
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
            if ($post["id"]) {
                // check if entry already exists

                /*
                if ($this->db->where([
                    "company_sid" => $companyId,
                    "employee_sid" => checkAndGetSession("employee")["sid"],
                    "shift_date" => $post["shift_date"],
                ])->count_all_results("cl_shifts")) {
                    $response["msg"] = "Shift already exists.";
*/

                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("cl_shifts", [
                        "employee_sid" => checkAndGetSession("employee")["sid"],
                        "updated_at" => getSystemDate(),
                        "is_published" => 1,
                        "employee_can_claim" => 0,
                    ]);

                $ins = [];
                $ins["cl_shift_sid"] = $post["id"];
                $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                $ins["action"] = "claim Open Shift";
                $ins["action_json"] = "{}";
                $ins["created_at"] = getSystemDate();
                //
                $this->db->insert("cl_shifts_logs", $ins);

                $status = 200;
                $response = ["msg" => "Shift successfully claimed."];
            }
            //
        }
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    //
    public function getOpenShiftsRequest($filterData)
    {
        //
        $this->db->select("cl_shifts.sid,
           cl_shifts.employee_sid,
           cl_shifts.shift_date, 
           cl_shifts.start_time, 
           cl_shifts.end_time,
           open_shifts_approval.shift_status,
           open_shifts_approval.employee_sid,
           open_shifts_approval.created_at,
           open_shifts_approval.shift_sid,
           open_shifts_approval.updated_at,
           open_shifts_approval.sid as request_sid");


        if (!empty($filterData['filter']['dateRange'])) {
            //
            $tmp = explode("-", $filterData['filter']['dateRange']);
            $this->db
                ->where("cl_shifts.shift_date >= ", formatDateToDB(trim($tmp[0]), SITE_DATE, DB_DATE))
                ->where("cl_shifts.shift_date <= ", formatDateToDB(trim($tmp[1]), SITE_DATE, DB_DATE));
        }

        $this->db->where("open_shifts_approval.shift_status", 0);

        $this->db->join(
            "cl_shifts",
            "cl_shifts.sid = open_shifts_approval.shift_sid"
        );

        $records = $this->db->get("open_shifts_approval")->result_array();

        //
        if (!empty($records)) {
            foreach ($records as $key => $row) {
                //
                $records[$key]['from_employee'] = getUserNameBySID($row['employee_sid']);
                $records[$key]['created_at'] = $row['created_at'] != '' ? date_with_time($row['created_at']) : ' - ';
                $records[$key]['shift_date'] = $row['shift_date'] != '' ? date_with_time($row['shift_date']) : ' - ';
                $records[$key]['start_time'] = formatDateToDB($row["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($row["end_time"], "H:i:s", "h:i a");
                $records[$key]['request_status'] = $row["request_status"] != '' ? ucwords($row["request_status"]) : ' - ';
                $records[$key]['updated_at'] = $row['updated_at'] != '' ? date_with_time($row['updated_at']) : ' - ';
            }
        }
        //
        return $records;
    }


    //
    public function updateOpenShiftsRequest($shiftId, $toEmployeeId, $data)
    {
        $shiftIds = explode(',', $shiftId);


        if (!empty($toEmployeeId)) {
            //
            $shiftExist = $this->db
                ->select("shift_sid")
                ->where("employee_sid", $toEmployeeId)
                ->where_in("shift_sid", $shiftIds)
                ->get("open_shifts_approval")
                ->row_array();

            if (empty($shiftExist)) {
                return 1;
            }
            //
            $this->db->where("employee_sid", $toEmployeeId);
        }
        $this->db
            ->where_in("shift_sid", $shiftIds);
        $this->db->update("open_shifts_approval", $data);

        //
        if ($data['shift_status'] == 1) {
            $data['shift_status'] = '2';
            if (!empty($toEmployeeId)) {
                $this->db->where("employee_sid!=", $toEmployeeId);
            }
            $this->db
                ->where_in("shift_sid", $shiftIds);
            $this->db->update("open_shifts_approval", $data);

            //
            $shiftUpdate = [];
            $shiftUpdate['employee_sid'] = $toEmployeeId;
            $shiftUpdate['is_published'] = 1;
            $shiftUpdate['employee_can_claim'] = 0;
            $shiftUpdate['employee_need_approval_for_claim'] = 0;

            $this->db
                ->where_in("sid", $shiftIds);
            $this->db->update("cl_shifts", $shiftUpdate);
        }
    }


    //
    public function getOpenShiftsRequestById($shiftIds, $shiftstatus = '')
    {
        //
        $this->db->select("
            cl_shifts.sid, 
            cl_shifts.employee_sid, 
            cl_shifts.shift_date, 
            cl_shifts.start_time, 
            cl_shifts.end_time,
            open_shifts_approval.shift_status,
            open_shifts_approval.employee_sid,
            open_shifts_approval.created_at,
            open_shifts_approval.company_sid,
            open_shifts_approval.shift_sid, 
            open_shifts_approval.updated_at,
            open_shifts_approval.action_by
        ");
        $this->db->where_in("open_shifts_approval.shift_sid", $shiftIds);

        //
        if ($shiftstatus == 'approved') {
            $this->db->where_in("open_shifts_approval.shift_status", 1);
        }

        if ($shiftstatus == 'pending') {
            $this->db->where_in("open_shifts_approval.shift_status", 0);
        }

        $this->db->join(
            "cl_shifts",
            "cl_shifts.sid = open_shifts_approval.shift_sid"
        );

        $records = $this->db->get("open_shifts_approval")->result_array();

        //
        if (!empty($records)) {
            foreach ($records as $key => $row) {

                $EmployeeData = getUserNameBySID($row['employee_sid'], false);
                $updatedEmployeeData = getUserNameBySID($row['action_by'], false);
                $companyName = getCompanyNameBySid($row['company_sid']);

                $records[$key]['from_employee'] = $EmployeeData[0]['first_name'] . ' ' . $EmployeeData[0]['last_name'];
                $records[$key]['created_at'] = $row['created_at'] != '' ? date_with_time($row['created_at']) : ' - ';
                $records[$key]['shift_date'] = $row['shift_date'] != '' ? date_with_time($row['shift_date']) : ' - ';
                $records[$key]['start_time'] = formatDateToDB($row["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($row["end_time"], "H:i:s", "h:i a");
                $records[$key]['request_status'] = $row["shift_status"] == '2' ? 'Rejected' : ' Approved ';
                $records[$key]['updated_at'] = $row['updated_at'] != '' ? date_with_time($row['updated_at']) : ' - ';
                $records[$key]['from_employee_email'] = $EmployeeData[0]['email'];
                $records[$key]['updated_by'] = $updatedEmployeeData[0]['first_name'] . ' ' . $updatedEmployeeData[0]['last_name'];
                $records[$key]['companyName'] = $companyName;
                $records[$key]['companyId'] = $row['company_sid'];
            }
        }

        return $records;
    }


    //
    public function isShiftExist($companyId, $shiftId, $employeeId)
    {

        $record = $this->db
            ->select(
                "cl_shifts.employee_sid,
                cl_shifts.shift_date,
                cl_shifts.start_time,
                cl_shifts.end_time,
                cl_shifts.breaks_json,
                cl_shifts.breaks_count,
                "
            )
            ->where("cl_shifts.sid", $shiftId)
            ->where("cl_shifts.company_sid", $companyId)
            ->get("cl_shifts")
            ->row_array();

        $this->db->where('employee_sid', $employeeId);
        $this->db->where('start_time <', $record['end_time']);
        $this->db->where('end_time >', $record['start_time']);

        return $this->db->count_all_results("cl_shifts");
    }

    //
    public function isShifRequestExist($companyId, $shiftId, $employeeId)
    {
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('company_sid', $companyId);
        $this->db->where('shift_sid', $shiftId);
        $this->db->where('shift_status', 0);
        return $this->db->count_all_results("open_shifts_approval");
    }

    //
    public function cancelShiftClaimRequestFromEmployee($companyId, $shiftId, $employeeId)
    {

        $this->db
            ->where("shift_sid", $shiftId)
            ->where("employee_sid", $employeeId)
            ->where("company_sid", $companyId)
            ->delete("open_shifts_approval");
    }
}
