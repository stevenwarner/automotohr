<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cms extends Admin_Controller
{
    //
    private $resp = [];

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/cms_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->resp = [
            'Status' => false,
            'Response' => 'Reqest Not Authorized'
        ];
    }


    public function index()
    {
        $this->load->library('pagination');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'CMS';
        $page_number = ($this->uri->segment(7)) ? $this->uri->segment(7) : 1;
        $offset           = 0;
        $records_per_page = 50;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }
        $pagination_base = base_url('manage_admin/cms');
        $pages_data = $this->cms_model->get_pages_data();
        $total_records = $this->cms_model->get_pages_data(null, null, true);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $this->uri->total_segments();
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $this->data['groups'] = $this->ion_auth->groups()->result();

        $this->data['pages_data'] = $pages_data;
        $this->render('manage_admin/cms/index');
    }

    //
    public function edit_page($sid)
    {

        $this->load->helper('url');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'Page';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $page_data = $this->cms_model->get_page_data($sid);
        $this->data['page_data'] = $page_data;
        $this->render('manage_admin/cms/' . $page_data['page']);
    }



    //
    public function update_page()
    {
        $pageId = $this->input->post('pageId');
        $content = $this->input->post('content');
        $dataUpdate['content'] = json_encode($content);

        $dataUpdate['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $this->cms_model->update_page_data($pageId, $dataUpdate);
    }
}
