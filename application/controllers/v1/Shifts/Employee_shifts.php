<?php defined('BASEPATH') or exit('No direct script access allowed');

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
        // check if the person is logged in
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }
    }

    /**
     * Manage shifts
     */
    public function my()
    {
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
        //
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            [$employeeId]
        );
        //
        // get the unavailability
        $data["unavailability"] = $this->shift_model->getUnavailability(
            $data["filter"],
            [$employeeId]
        );
        // _e($data["unavailability"],true,true);
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
            getPlugin("v1/plugins/ms_modal/main", "css"),
            getPlugin("select2", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("select2", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/plugins/ms_modal/main",
            "v1/schedules/employee/my_availability",
            "v1/settings/shifts/ems_main"
        ], "public/v1/shifts/", "my_ems_shifts", false);
        //
        // _e($data,true,true);

        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/my/listing');
        $this->load->view('main/footer');
    }

    public function getPageContent()
    {
        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "v1/schedules/my/un_availability",
                    [],
                    true
                )
            ]
        );
    }

    public function myUnavailability()
    {
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        //
        $startDate = formatDateToDB($_POST['date'], SITE_DATE, DB_DATE);
        //
        $unavailableDays = [];
        //
        if ($_POST['repeat'] == 'yes') {
            //
            if ($_POST['repeatType'] == 'daily') {
                //
                $unavailableDays = $this->getDailyDaysRange($_POST, $startDate);
            } else if ($_POST['repeatType'] == "weekly") {
                //
                $unavailableDays = $this->getWeeklyDaysRange($_POST, $startDate);
            } else if ($_POST['repeatType'] == "monthly") {
                //
                $unavailableDays = $this->getMonthlyDaysRange($_POST, $startDate);
            }
        } else {
            array_push($unavailableDays, $startDate);
        }
        //
        if ($unavailableDays) {
            //
            // load schedule model
            $this->load->model("v1/Shift_model", "shift_model");
            //
            foreach ($unavailableDays as $day) {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $loggedInCompany['sid'];
                $data_to_insert['employee_sid'] = $loggedInEmployee['sid'];
                $data_to_insert['note'] = $_POST['note'];
                $data_to_insert['date'] = $day;
                //
                if ($_POST['allDay'] == 'no') {
                    $data_to_insert['time'] = serialize($_POST['dailyTimes']);
                }
                //
                $this->shift_model->saveUnavailableSlot($data_to_insert);
            }
        }
        //
        return SendResponse(200, [
            "msg" => "Save Unavailability Successfully."
        ]);
    }

    private function getDailyDaysRange($post, $startDate)
    {
        //
        $unavailableDays = [];
        array_push($unavailableDays, $startDate);
        //
        if ($post['occurrencesType'] == 'after') {
            //
            $end = $post['occurrences'];
            //
            for ($i = 1; $i <= $end; $i++) {
                $newDate = date(DB_DATE, strtotime('+' . ($post['daily'] * $i) . ' day', strtotime($startDate))) . PHP_EOL;
                array_push($unavailableDays, $newDate);
            }
        } else {
            $sd = new DateTime($startDate);
            $oed = new DateTime(DateTime::createFromFormat('m/d/Y', $post['occurrenceEndDate'])->format('Y-m-d'));
            //
            $end = $oed->diff($sd)->format("%a");
            //
            for ($i = 1; $i <= $end; $i++) {
                $newDate = date(DB_DATE, strtotime('+' . ($post['daily'] * $i) . ' day', strtotime($startDate))) . PHP_EOL;
                //
                if ($newDate < date_format($oed, 'Y-m-d')) {
                    array_push($unavailableDays, $newDate);
                }
                
            }
        }
        //
        return $unavailableDays;
    }

    private function getWeeklyDaysRange($post, $startDate)
    {
        //
        $unavailableDays = [];
        array_push($unavailableDays, $startDate);
        //
        $currentWeek = explode(',', date('Y,W', strtotime($startDate)) . PHP_EOL);
        //
        foreach ($post['weekDays'] as  $weekDay) {
            $gendate = new DateTime();
            $gendate->setISODate($currentWeek[0], $currentWeek[1], $weekDay);
            $newDate = $gendate->format('Y-m-d');
            //
            if ($newDate > $startDate) {
                array_push($unavailableDays, $newDate);
            }
        }
        //
        if ($post['occurrencesType'] == 'after') {
            //
            $end = $post['occurrences'];
            //
            for ($i = 1; $i <= $end; $i++) {
                //
                $newWeek = explode(',', date('Y,W', strtotime('+' . ($post['weekly'] * $i) . ' week', strtotime($startDate))) . PHP_EOL);
                //
                foreach ($post['weekDays'] as  $weekDay) {
                    $gendate = new DateTime();
                    $gendate->setISODate($newWeek[0], $newWeek[1], $weekDay);
                    $newDate = $gendate->format('Y-m-d');
                    array_push($unavailableDays, $newDate);
                }
            }
        } else if ($post['occurrencesType'] == 'on') {
            $sd = new DateTime($startDate);
            $oed = new DateTime(DateTime::createFromFormat('m/d/Y', $post['occurrenceEndDate'])->format('Y-m-d'));
            //
            $end = floor($oed->diff($sd)->days/7);
            //
            for ($i = 1; $i <= $end; $i++) {
                //
                $newWeek = explode(',', date('Y,W', strtotime('+' . ($post['weekly'] * $i) . ' week', strtotime($startDate))) . PHP_EOL);
                //
                foreach ($post['weekDays'] as  $weekDay) {
                    $gendate = new DateTime();
                    $gendate->setISODate($newWeek[0], $newWeek[1], $weekDay);
                    $newDate = $gendate->format('Y-m-d');
                    //
                    if ($newDate < date_format($oed, 'Y-m-d')) {
                        array_push($unavailableDays, $newDate);
                    }
                }
            }
        }
      
        //
        return $unavailableDays;
    }

    private function getMonthlyDaysRange($post, $startDate)
    {
        //
        $unavailableDays = [];
        array_push($unavailableDays, $startDate);
        //
        if (date("m", strtotime($startDate)) < $post['monthly']) {
            foreach ($post['monthDays'] as  $monthDay) {
                if (is_numeric($monthDay)) {
                    $dayString = $year . '-' . $post['monthly'] . '-' . $monthDay;
                    $newDate = date("Y-m-d", strtotime($dayString));
                    array_push($unavailableDays, $newDate);
                } else {
                    $dayString = $monthDay . ' of ' . $year . '-' . $post['monthly'];
                    $newDate = date("Y-m-d", strtotime($dayString));
                    array_push($unavailableDays, $newDate);
                }
            }
        }
        //
        if ($post['occurrencesType'] == 'after') {
            //
            $end = $post['occurrences'];
            //
            for ($i = 1; $i <= $end; $i++) {
                $year = formatDateToDB($post['date'], SITE_DATE, 'Y');
                $year = $year + $i;
                //
                foreach ($post['monthDays'] as  $monthDay) {
                    if (is_numeric($monthDay)) {
                        $dayString = $year . '-' . $post['monthly'] . '-' . $monthDay;
                        $newDate = date("Y-m-d", strtotime($dayString));
                        array_push($unavailableDays, $newDate);
                    } else {
                        $dayString = $monthDay . ' of ' . $year . '-' . $post['monthly'];
                        $newDate = date("Y-m-d", strtotime($dayString));
                        array_push($unavailableDays, $newDate);
                    }
                }
            }
            //
        } else if ($post['occurrencesType'] == 'on') {
            $sd = new DateTime($startDate);
            $oed = new DateTime(DateTime::createFromFormat('m/d/Y', $post['occurrenceEndDate'])->format('Y-m-d'));
            //
            $end = $oed->diff($sd)->y;
            //
            for ($i = 1; $i <= $end; $i++) {
                $year = formatDateToDB($post['date'], SITE_DATE, 'Y');
                $year = $year + $i;
                //
                foreach ($post['monthDays'] as  $monthDay) {
                    if (is_numeric($monthDay)) {
                        $dayString = $year . '-' . $post['monthly'] . '-' . $monthDay;
                        $newDate = date("Y-m-d", strtotime($dayString));
                        //
                        if ($newDate < date_format($oed, 'Y-m-d')) {
                            array_push($unavailableDays, $newDate);
                        }
                    } else {
                        $dayString = $monthDay . ' of ' . $year . '-' . $post['monthly'];
                        $newDate = date("Y-m-d", strtotime($dayString));
                        //
                        if ($newDate < date_format($oed, 'Y-m-d')) {
                            array_push($unavailableDays, $newDate);
                        }
                    }
                }
            }
        }    
        //
        return $unavailableDays;
    }
}
