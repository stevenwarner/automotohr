<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->library('complynet');
    }

    public function complynet()
    {
        // $companies = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getCompanies();

        // _e($companies, true, true);

        // $locations = $this
        // ->complynet
        // ->setMode('original')
        // ->authenticate()
        // ->getLocations("3A0168AE-4B6F-42F4-8828-92A89D1CFD35");
        // //
        // _e($locations, true, true);

        // $departments = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getDepartments("3A0168AE-4B6F-42F4-8828-92A89D1CFD35");
        // //
        // _e($departments, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->updateDepartments(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "Service Provider",
        //     TRUE
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->createDepartment(
        //     "Software Service Provider"
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteDepartment(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->updateJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "General Manager",
        //     TRUE
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->createJobRole(
        //     "General Manager"
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->getUser(
        //     "requests@ComplyNet.com"
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->createUser(
        //     "Tom",
        //     "Bob",
        //     "tombob@ComplyNet.com",
        //     "tombob@ComplyNet.com",
        //     "password",//leave blank to use default (last name all lower case)
        //     "E4A89DDA-12BB-4341-844A-BBE400451274",
        //     "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F",
        //     "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25",
        //     "FE96FEBA-DE91-4DA1-A809-499351D001F7",
        //     "5556667778"

        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->updateUser(
        //     "Tonny",
        //     "Bomber",
        //     "tombob@ComplyNet.com",
        //     "tombob@ComplyNet.com",
        //     "password",//leave blank to use default (last name all lower case)
        //     "E4A89DDA-12BB-4341-844A-BBE400451274",
        //     "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F",
        //     "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25",
        //     "FE96FEBA-DE91-4DA1-A809-499351D001F7",
        //     "4555666777"

        // );
        // //
        // _e($response, true, true);

        $response = $this
        ->complynet
        ->setMode('fakes')
        ->authenticate()
        ->disableUser(
            "tombob@ComplyNet.com"

        );
        //
        _e($response, true, true);
    }



}
