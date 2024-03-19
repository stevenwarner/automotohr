<?php

use function PHPSTORM_META\map;

defined('BASEPATH') or exit('No direct script access allowed');

class Indeed_disposition_status_map extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(
            "manage_admin/Indeed_disposition_status_map_model",
            "indeed_disposition_status_map_model"
        );
    }

    public function listing()
    {
        // set the title
        $this->data['page_title'] = 'Indeed Disposition Status Map :: ' . (STORE_NAME);
        // get the status
        $this->data["applicantStatus"] = [];
        $this->data["applicantStatus"]["default"] = $this
            ->indeed_disposition_status_map_model
            ->getDefaultStatus();
        $this->data["applicantStatus"]["custom"] = $this
            ->indeed_disposition_status_map_model
            ->getCustomStatus(
                array_keys(
                    $this->data["applicantStatus"]["default"]
                )
            );
        //
        $this->load->library("indeed_lib");
        //
        $this->data["indeedDispositionStatus"] = $this
            ->indeed_lib
            ->getIndeedDispositionStatusList();
        // get the mapped status
        $this->data["mappedStatus"] = $this->indeed_disposition_status_map_model
        ->getMappedStatus();
        //
        $this->render('manage_admin/settings/indeed_disposition_status_map');
    }

    /**
     * save listing
     */
    public function saveListing()
    {
        //
        $post = $this->input->post(null, true);
        //
        $response = $this
            ->indeed_disposition_status_map_model
            ->handleMap(
                $post["status"]
            );

        $this->session->set_flashdata(
            "message",
            "You have successfully mapped {$response["done"]} out of {$response["total"]} applicant statuses."
        );
        //
        return redirect("manage_admin/indeed/disposition/status/map");
    }
}
