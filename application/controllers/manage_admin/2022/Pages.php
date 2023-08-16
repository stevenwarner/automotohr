<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("2022/pages_model", 'pages');
    }

    //
    public function index()
    {
        $this->data['page_title'] = "CMS Pages";
        $this->data['pagesList'] = $this->pages->getPages();
        $this->render('manage_admin/cms/pages_listing');
    }

    //
    public function editPage($sid = NULL)
    {
        if ($sid == NULL) {
            return redirect('cms/pages');
        }

        $this->form_validation->set_rules('meta_title', 'action', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->data['page_title'] = "CMS Pages";
            $this->data['pageData'] = $this->pages->getPageById($sid);
            $this->data['sliderList'] = $this->pages->getSlidersByPageId($sid);
            $this->render('manage_admin/cms/edit_page');
        } else {
            $sid = $this->input->post('pageid');
            $update_data['meta_title'] = $this->input->post('meta_title');
            $update_data['meta_keyword'] = $this->input->post('meta_keyword');
            $update_data['meta_description'] = $this->input->post('meta_description');
            $this->pages->update_page($sid, $update_data);
            $this->session->set_flashdata('message', 'Meta updated successfully');
            return redirect('cms/pages/edit_page/' . $sid);
        }
    }


    //
    public function addSlider()
    {

        $pageaction = $this->input->post('pageaction');
        $slider_data['description_heading'] = $this->input->post('description_heading');
        $slider_data['description'] = $this->input->post('description');
        $slider_data['button_text'] = $this->input->post('button_text');
        $slider_data['button_link'] = $this->input->post('button_link');

        if ($pageaction == 'addslider') {
            $slider_data['page_id'] =  $sid = $this->input->post('pageid');
            $this->pages->add_slider($slider_data);
            $this->session->set_flashdata('message', 'Slider addeded successfully');
        }
        if ($pageaction == 'updateslider') {
        }

        return redirect('cms/pages/edit_page/' . $sid);
    }
}
