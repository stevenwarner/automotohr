<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_complynet extends CI_Controller
{
    //
    private $verifyToken;

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('2022/complynet_model');
        $this->load->library('complynet_lib');
        //
        $this->verifyToken = getCreds('AHR')->VerifyToken;
    }

    function tos()
    {
        //
        $this->common_model->startR();
        $this->common_model->endR();
    }

    //
    function auto_complynet_locations_update($verificationToken = null)
    {

        // if ($this->verifyToken != $verificationToken) {
        //     echo "Failed";
        //     exit(0);
        //   }
        //
        $records = $this->complynet_model->checkOrGetData(
            'complynet_id',
            ['status' => 1],
            'result_array',
            'complynet_companies'
        );

        //
        if (empty($records)) {
            exit(0);
        }

        //
        foreach ($records as $record) {

            $complynetLocations = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->getLocations($record['complynet_id']);
            //
            foreach ($complynetLocations as $locationRecod) {

                //
                $this->complynet_model->updateData(
                    ['complynet_location_name' => $locationRecod['Name']],
                    ['complynet_location_id' => $locationRecod['Id']],
                    'complynet_locations'
                );
            }
        }
        echo "Executed \n";
        //
        exit(0);
    }


    //
    function auto_complynet_departments_update($verificationToken = null)
    {

        // if ($this->verifyToken != $verificationToken) {
        //     echo "Failed";
        //     exit(0);
        //   }
        //
        $records = $this->complynet_model->checkOrGetData(
            'complynet_location_id',
            ['status' => 1],
            'result_array',
            'complynet_locations'
        );

        //
        if (empty($records)) {
            exit(0);
        }

        //
        foreach ($records as $record) {

            $complynetDepartments = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->getDepartments($record["complynet_location_id"]);

            //
            foreach ($complynetDepartments as $departmentRecod) {

                $this->complynet_model->updateData(
                    ['complynet_department_name' => $departmentRecod['Name']],
                    ['complynet_department_id' => $departmentRecod['Id']],
                    'complynet_departments'
                );
            }
        }
        echo "Executed \n";
        //
        exit(0);
    }


    //
    function auto_complynet_jobroles_update($verificationToken = null)
    {

        // if ($this->verifyToken != $verificationToken) {
        //     echo "Failed";
        //     exit(0);
        //   }
        //
        $records = $this->complynet_model->checkOrGetData(
            'complynet_department_id',
            ['status' => 1],
            'result_array',
            'complynet_departments'
        );

        //
        if (empty($records)) {
            exit(0);
        }

        //
        foreach ($records as $record) {

            $complynetJobroles = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->getJobRole($record["complynet_department_id"]);

            //
            foreach ($complynetJobroles as $jobroleRecod) {

                $this->complynet_model->updateData(
                    ['complynet_jobRole_name' => $jobroleRecod['Name']],
                    ['complynet_jobRole_id' => $jobroleRecod['Id']],
                    'complynet_jobRole'
                );
            }
        }
        echo "Executed \n";
        //
        exit(0);
    }


    //
    function auto_complynet_employeesinfo_update($verificationToken = null)
    {

        // if ($this->verifyToken != $verificationToken) {
        //     echo "Failed";
        //     exit(0);
        //   }
        //
        $records = $this->complynet_model->checkOrGetData(
            'email',
            ['status' => 1],
            'result_array',
            'complynet_employees'
        );

        //
        if (empty($records)) {
            exit(0);
        }

        //
        foreach ($records as $record) {

            $complynetEmployees = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->getUser($record["email"]);

            //
            foreach ($complynetEmployees as $employeeRecod) {

                $this->complynet_model->updateData(
                    [
                        'firstName' => $employeeRecod['firstName'], 'lastName' => $employeeRecod['lastName'], 'userName' => $employeeRecod['userName'], 'complynet_location_id' => $employeeRecod['locationId'], 'complynet_department_id' => $employeeRecod['departmentId'], 'complynet_jobRole_id' => $employeeRecod['jobRoleId'], 'PhoneNumber' => $employeeRecod['PhoneNumber']
                    ],

                    ['email' => $record["email"]],
                    'complynet_employees'
                );
            }
        }
        echo "Executed \n";
        //
        exit(0);
    }
}
