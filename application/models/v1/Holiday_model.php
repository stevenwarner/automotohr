<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Holiday model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Holiday_model extends CI_Model
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
     * get the holidays
     *
     * @param int    $companyId
     * @param string $year Optional
     * @param bool   $getDatePool Optional
     * @return array
     */
    public function get(
        int $companyId,
        string $year = "",
        bool $getDatePool = false
    ): array {
        $records = $this->db
            ->select("
                from_date,
                to_date
            ")
            ->where([
                "company_sid" => $companyId,
                "holiday_year" => $year ? $year : getSystemDate("Y")
            ])
            ->get("timeoff_holidays")
            ->result_array();
        //
        if ($getDatePool) {
            //
            $tmp = [];
            //
            foreach ($records as $v0) {
                //
                if ($v0["from_date"] === $v0["to_date"]) {
                    $tmp[] = $v0["from_date"];
                    continue;
                }
                //
                $tmp = array_merge($tmp, getDatesInRange($v0["from_date"], $v0["to_date"]));
            }
            //
            $records = $tmp;
        }
        //
        return $records;
    }
}
