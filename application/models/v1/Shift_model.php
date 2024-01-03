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
            ]);
        //
        if ($employeeFilter['employees'][0] != 'all' && !empty($employeeFilter['employees'][0])) {
            $this->db->where_in("users.sid", $employeeFilter['employees']);
        }

        if ($employeeFilter['team'] != 0) {
            $this->db->where("users.team_sid", $employeeFilter['team']);
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
        if ($employeeFilter['employees'][0] != 'all' && !empty($employeeFilter['employees'][0])) {
            $this->db->where_in("users.sid", $employeeFilter['employees']);
        }

        if ($employeeFilter['team'] != 0) {
            $this->db->where("users.team_sid", $employeeFilter['team']);
        }

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
    public function getShifts(array $filter, array $employeeIds): array
    {
        //
        if (empty($employeeIds)) {
            $employeeIds = ['0'];
        }

        $this->db
            ->select("sid, employee_sid, shift_date, start_time, end_time, job_sites")
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
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();
        //
        if ($records) {
            //
            $employees = [];
            //
            foreach ($records as $v0) {
                //
                if (!$employees[$v0["employee_sid"]]) {
                    $employees[$v0["employee_sid"]] = [
                        "totalTimeText" => '0h',
                        "totalTime" => 0,
                        "dates" => []
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
        return array_merge($records, getSundaysAndSaturdays($startDate, $endDate));
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

        $offs = getSundaysAndSaturdays($startDate, $endDate);
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
            if ($this->db->where([
                "company_sid" => $companyId,
                "employee_sid" => $post["shift_employee"],
                "shift_date" => $post["shift_date"],
                "start_time" => $post["start_time"],
                "end_time" => $post["end_time"],
            ])->count_all_results("cl_shifts")) {
                $response["msg"] = "Shift already exists.";
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
                "msg" => "You have successfully Copied the shift to the selected employees." . (
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
                "msg" => "You have successfully Deleted the shifts to the selected employees."
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
                "msg" => "You have successfully Deleted the shift to the selected employee."
            ]
        );
    }
}
