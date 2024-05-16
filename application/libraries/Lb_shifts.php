<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lb_shifts
{


    /**
     * main
     */
    public function __construct()
    {
    }


    //
    public function generateIcsFileForShifts($fileContent, $companyName)
    {

        $uniqueName = STORE_NAME . '-shift-' . strtotime(date('Y-m-d h:iA'));
        $targetFileName = $uniqueName . '.ics';

        // Define the ICS file content
        $ics_content = "BEGIN:VCALENDAR\r\n";
        $ics_content .= "VERSION:2.0\r\n";
        $ics_content .= "PRODID:-//" . $companyName . "//Shifts//EN\r\n";
        $ics_content .= "CALSCALE:GREGORIAN\r\n";
        $ics_content .= "METHOD:PUBLISH\r\n";

        $dateFormat = 'Ymd\THis';

        foreach ($fileContent as $shiftRow) {

            $event_uid = uniqid();
            $event_summary = "Shift";

            $startDate = $shiftRow['shift_date'] . $shiftRow['start_time'];
            $endDate =   $shiftRow['shift_date'] . $shiftRow['end_time'];

            $ics_content .= "BEGIN:VEVENT\r\n";
            $ics_content .= "UID:$event_uid\r\n";
            $ics_content .= "SUMMARY:$event_summary\r\n";

            $ics_content .= "DTSTART:" . date($dateFormat, strtotime($startDate)) . PHP_EOL;
            $ics_content .= "DTEND:" . date($dateFormat, strtotime($endDate)) . PHP_EOL;

            $ics_content .= "STATUS:CONFIRMED\r\n";
            $ics_content .= "SEQUENCE:0\r\n";
            $ics_content .= "TRANSP:OPAQUE\r\n";
            $ics_content .= "END:VEVENT\r\n";
        }

        $ics_content .= "END:VCALENDAR\r\n";

        header('Content-type: text/calendar; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"" . $targetFileName . "\"");

        echo $ics_content;
        exit;
    }

    //
    public function generateVcsFileForShifts($fileContent)
    {

        $uniqueName = STORE_NAME . '-shift-' . strtotime(date('Y-m-d h:iA'));
        $targetFileName = $uniqueName . '.vcs';

        // Define the VCS file content
        $vcs_content = "BEGIN:VCALENDAR\r\n";
        $vcs_content .= "VERSION:1.0\r\n";

        $dateFormat = 'Ymd\THis';

        foreach ($fileContent as $shiftRow) {

            $event_uid = uniqid();
            $event_summary = "Shift";

            $startDate = $shiftRow['shift_date'] . $shiftRow['start_time'];
            $endDate =   $shiftRow['shift_date'] . $shiftRow['end_time'];

            $vcs_content .= "BEGIN:VEVENT\r\n";
            $vcs_content .= "UID:$event_uid\r\n";
            $vcs_content .= "SUMMARY:$event_summary\r\n";
            $vcs_content .= "DTSTART:" . date($dateFormat, strtotime($startDate)) . PHP_EOL;
            $vcs_content .= "DTEND:" . date($dateFormat, strtotime($endDate)) . PHP_EOL;
            $vcs_content .= "END:VEVENT\r\n";
        }

        $vcs_content .= "END:VCALENDAR\r\n";

        // Set headers for file download
        header('Content-type: text/x-vCalendar; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"" . $targetFileName . "\"");

        // Output the VCS file content
        echo $vcs_content;
        exit;
    }
}
