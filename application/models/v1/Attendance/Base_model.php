<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Dashboard model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Attendance
 */
class Base_model extends CI_Model
{
    protected $loggedInSession;
    protected $loggedInCompanyId;
    protected $loggedInEmployeeId;
    /**
     * main entry point
     */
    public function __construct()
    {
        parent::__construct();

        $this->loggedInSession = checkAndGetSession("all");
        //
        $this->loggedInCompanyId = $this->loggedInSession["company_detail"]["sid"];
        $this->loggedInEmployeeId = $this->loggedInSession["employer_detail"]["sid"];
    }

    /**
     * get the employee details
     *
     * @param int $employeeId
     * @param array $columns Optional
     * @return array
     */
    public function getEmployeeDetails(int $employeeId, array $columns = ["*"]): array
    {
        return $this->db
            ->select($columns)
            ->where("sid", $employeeId)
            ->get("users")
            ->row_array();
    }
}
