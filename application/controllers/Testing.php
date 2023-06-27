<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }

    /**
     * 
     */
    public function getAllFutureRecord(int $executeProcess = 0)
    {
        $advanceBalances = $this->db
            ->select(
                "*"
            )
            ->where("effective_at > ", date('Y-m-d'))
            ->limit(500)
            ->get('timeoff_allowed_balances')
            ->result_array();
        //
        if ($executeProcess == 1) {
            //
            // $this->db
            // ->where("effective_at > ", date('Y-m-d'))
            // ->delete('timeoff_allowed_balances');
        }
        //
        _e($advanceBalances,true,true);  
    }

    public function getAccural() {
        $this->load->helper('timeoff');
        $this->load->model("timeoff_model", "tom");
        $policy = $this->tom->getSinglePolicyById(20);
        
        $result = getEmployeeAccrual(
            20,
            15753,
            "fulltime",
            "2022-10-12",
            480,
            json_decode($policy['accruals'], true),
            0,
            "06/28/2023",
            "H",
            $policy['category_type']
        );
        
        
        _e($result, true, true);
        echo "we are doing accurals";       
    }

}
