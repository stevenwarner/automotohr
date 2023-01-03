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


    // Enable Rehired Employees


    // public function enableRehiredemployees()
    // {

    //     $employeesData = $this->tm->getRehiredemployees();

    //     if (!empty($employeesData)) {
    //         foreach ($employeesData as $employeeRow) {
    //             $this->tm->updateEmployee($employeeRow['sid']);
    //         }
    //     }
    //     echo "Done";
    // }




public function setOnbordingDefaultAddress(){
// Get active companies
$companiesdata = $this->getAllCompanies();

foreach ($companiesdata as $dataRow){

    $inserData['company_sid']=$dataRow['sid'];
    $inserData['location_title']=$dataRow['CompanyName'];
    $inserData['location_address']=$dataRow['Location_Address'];
    $inserData['location_telephone']=$dataRow['PhoneNumber'];
    $inserData['location_status']=1;
    $inserData['is_default']=1;

//
    $this->setObbordingAddress($inserData);

}
echo "Done";

}


//
function getAllCompanies() {

    $this->db->select('sid,CompanyName,Location_Address,PhoneNumber,');
    $this->db->where('active', 1);
    $this->db->where('is_paid', 1);
    $this->db->where('parent_sid', 0);
    $this->db->order_by('CompanyName', 'ASC');
    $this->db->from('users');
    $records_obj = $this->db->get();
    $records_arr = $records_obj->result_array();
    $records_obj->free_result();

    $result = array();
    if (!empty($records_arr)) {
        $result = $records_arr;
    }
    return $result;
}



function setObbordingAddress($inserData){

    //
    $this->db->select('sid');
    $this->db->where('company_sid', $inserData['company_sid']);
    $this->db->where('is_default', 1);
    $records_obj = $this->db->get('onboarding_office_locations');
    $records_arr = $records_obj->result_array();
    //
    if(empty($records_arr)){
        $this->db->insert('onboarding_office_locations', $inserData);
    }

}




}
