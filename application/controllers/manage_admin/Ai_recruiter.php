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


        if ($this->input->post("conversation_prompt") || $this->input->post("generate_report_prompt")) {
            $this->db
                ->where("slug", 'conversation')
                ->update(
                    "ai_recruiter_config",
                    [
                        "model" => $this->input->post("model"),
                        "prompt" => $this->input->post("conversation_prompt")
                    ]
                );
            $this->db
                ->where("slug", 'generate_report')
                ->update(
                    "ai_recruiter_config",
                    [
                        "prompt" => $this->input->post("generate_report_prompt")
                    ]
                );
            $this->session->set_flashdata('success', 'Configuration updated successfully.');
            redirect('manage_admin/ai_recruiter/config');
        }

        // set the title
        $this->data['page_title'] = 'Ai Recruiter Configuration :: ' . (STORE_NAME);

        $this->data["conversation"] = $this->db
            ->select("prompt, model")
            ->where("slug", 'conversation')
            ->get("ai_recruiter_config")
            ->row_array();

        $this->data["generate_report"] = $this->db
            ->select("prompt, model")
            ->where("slug", 'generate_report')
            ->get("ai_recruiter_config")
            ->row_array();

        $this->render('manage_admin/ai_recruiter/config');
    }
}
