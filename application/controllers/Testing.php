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

  

    public function fix_merge()
    {
        $this->load->model('2022/complynet_model', 'complynet_model');
        echo $this->complynet_model->syncJobRoles(
            '90AE8942-8150-4423-90A1-9FF8160A1376',
            'Body Shop Manager'
        );
        die('END');
        $this->tm->get_merge_employee();
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
}
