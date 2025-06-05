<?php

use function PHPSTORM_META\map;

defined('BASEPATH') or exit('No direct script access allowed');

class Ai_recruiter extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(
            "manage_admin/Indeed_disposition_status_map_model",
            "indeed_disposition_status_map_model"
        );
    }

    public function config()
    {


        if ($this->input->post(null)) {
            $this->db
                ->where("sid", 1)
                ->update(
                    "ai_recruiter_config",
                    [
                        "model" => $this->input->post("model"),
                        "prompt" => $this->input->post("prompt", false)
                    ]
                );
            $this->session->set_flashdata('success', 'Configuration updated successfully.');
            redirect('manage_admin/ai_recruiter/config');
        }

        // set the title
        $this->data['page_title'] = 'Ai Recruiter Configuration :: ' . (STORE_NAME);

        $this->data["result"] = $this->db
            ->select("prompt, model")
            ->where("sid", 1)
            ->get("ai_recruiter_config")
            ->row_array();

        $this->render('manage_admin/ai_recruiter/config');
    }
}
