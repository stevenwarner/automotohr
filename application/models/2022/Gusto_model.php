<?php defined('BASEPATH') or exit('No direct script access allowed');

class Gusto_model extends CI_Model
{

    /**
     * Entry point
     */


    private $employeeColumns;


    function __construct()
    {
        // Inherit parent class properties and methods
        parent::__construct();
    }



    /**
     *
     * @method getEmployeeAssociateOIDAdp,
     * getCompanyAdpMode
     * @param array $oldData
     * @param array $newData
     * @param int   $employeeId
     * @param int   $companyId
     * @param int   $employerId
     * @return bool
     */
    public function handleMultipleColumns(
        array $oldData,
        array $newData,
        int $employeeId,
        int $companyId,
        int $employerId
    ) {
        // get employee payroll
        //
        if (!empty($newData['dob'])) {
            $newData['dob'] = formatDateToDB(
                $newData['dob'],
                'm-d-Y',
                DB_DATE
            );
        }
        //
        $requestBody = [
            'first_name' => $newData['first_name'],
            'last_name' => $newData['last_name'],
            'middle_initial' => $newData['middle_name'],
            'ssn' => $newData['ssn'],
            'email' => $newData['email'],
            'date_of_birth' => $newData['dob'],
            'version' => '',
            'two_percent_shareholder' => false
        ];
        //
        $isChange = 0;
        //
        if ($oldData['first_name'] != $newData['first_name']) {
            $isChange = 1;
        }

        //
        if ($oldData['last_name'] != $newData['last_name']) {
            $isChange = 1;
        }

        //
        if ($oldData['middle_name'] != $newData['middle_name']) {
            $isChange = 1;
        }
        //
        if ($oldData['email'] != $newData['email']) {
            $isChange = 1;
        }
        //
        if ($oldData['ssn'] != $newData['ssn']) {
            $isChange = 1;
        }
        //
        if ($oldData['dob'] != $newData['dob']) {
            $isChange = 1;
        }

        if ($isChange == 0) {
            return;
        }



        $payrolldetails = $this->get_payroll_employee_details($employerId);
        if (empty($payrolldetails)) {
            return;
        }

        $employee_uuid = $payrolldetails['payroll_employee_uuid'];
        $requestBody['version'] = $payrolldetails['version'];

        //
        $ins = [
            'employee_sid' => $employeeId,
            'employee_uuid' => $employee_uuid,
            'request_url' => 'https://api.gusto-demo.com/v1/employees/' . $employee_uuid,
            'request_body' => json_encode($requestBody),
            'request_method' => 'POST',
            'status' => 1
        ];

        $ins['created_at'] = $ins['updated_at'] = getSystemDate();

        $this->db->insert('gusto_queue', $ins);
    }





    // Update an employee home address



    /**
     *
     * @method getEmployeeAssociateOIDAdp,
     * getCompanyAdpMode
     * @param array $oldData
     * @param array $newData
     * @param int   $employeeId
     * @param int   $companyId
     * @param int   $employerId
     * @return bool
     */
    public function gustoUpdateEmployeeAddress(
        array $oldData,
        array $newData,
        int $employeeId,
        int $employerId
    ) {
        // get employee payroll
      
        //
        $requestBody = [
            'street_1' => $newData['Location_Address'],
            'street_2' => $newData['Location_Address_2'],
            'city' => $newData['Location_City'],
            'state' => $newData['Location_State'],
            'zip' => $newData['Location_ZipCode'],
            'version' => '',
            'two_percent_shareholder' => false
        ];
        //
        $isChange = 0;
        //
        if ($oldData['Location_Address'] != $newData['Location_Address']) {
            $isChange = 1;
        }

        //
       if ($oldData['Location_Address_2'] != $newData['Location_Address_2']) {
          $isChange = 1;
        }

        //
        if ($oldData['Location_City'] != $newData['Location_City']) {
            $isChange = 1;
        }
        //
        if ($oldData['Location_State'] != $newData['Location_State']) {
            $isChange = 1;
        }
        //
        if ($oldData['Location_ZipCode'] != $newData['Location_ZipCode']) {
            $isChange = 1;
        }
        

        if ($isChange == 0) {
            return;
        }



        $payrolldetails = $this->get_payroll_employee_details($employerId);
        if (empty($payrolldetails)) {
            return;
        }

        $employee_uuid = $payrolldetails['payroll_employee_uuid'];
        $requestBody['version'] = $payrolldetails['version'];

        //
        $ins = [
            'employee_sid' => $employeeId,
            'employee_uuid' => $employee_uuid,
            'request_url' => 'https://api.gusto-demo.com/v1/employees/' . $employee_uuid . '/home_address',
            'request_body' => json_encode($requestBody),
            'request_method' => 'POST',
            'status' => 1
        ];

        $ins['created_at'] = $ins['updated_at'] = getSystemDate();

        $this->db->insert('gusto_queue', $ins);
    }







    //
    function get_payroll_employee_details($employeeId)
    {
        return $this->db
            ->select('payroll_employee_uuid, version ')
            ->where('employee_sid', $employeeId)
            ->from('payroll_employees')
            ->get()
            ->row_array();
    }
}
