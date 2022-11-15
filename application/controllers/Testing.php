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

    public function addDefaultTemplate(){
        //
        $file_name = ROOTPATH.'engagement.json';
        //
        $file = fopen($file_name, 'r');
        // read data from file
        $file_data =  fread($file, filesize($file_name));
        $file_data = json_decode($file_data, true);
        //
        if (!empty($file_data)) {
            foreach ($file_data as  $template) {
                // _e($template, true);
                $data_to_insert = array();
                $data_to_insert["title"] = $template["title"];
                $data_to_insert["description"] = $template["description"];
                $data_to_insert["questions_count"] = $template["length"];
                $data_to_insert["frequency"] = $template["frequency"];
                $data_to_insert["questions"] = json_encode($template["questions"]);
                //
                $this->tm->insertDefaultTemplate($data_to_insert);
            }
        }
        //
        fclose($file);
        die("ppak");
    }

    public function myfunction () {
        //
        
        //
        $curl = curl_init();
        //
        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost:3000/employee_survey/templates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        //
        $response = json_decode(curl_exec($curl), true);
        //
        curl_close($curl);
        _e($response,true,true);
    }
}
