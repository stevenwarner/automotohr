<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('hr_documents_management_model');
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


    public function setExistingCompanyStatus()
    {
        $companies = $this->db
            ->get("gusto_companies")
            ->result_array();

        foreach ($companies as $value) {
            $this->db->insert("gusto_companies_mode", [
                "company_sid" => $value["company_sid"],
                "stage" =>  "demo",
                "created_at" => getSystemDate()
            ]);
        }
    }
}
