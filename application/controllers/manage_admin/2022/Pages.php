<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("2022/pages_model", 'pages');
        require_once(APPPATH . 'libraries/aws/aws.php');
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
            $this->data['sectionsList'] = $this->pages->getSectionsByPageId($sid);

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
     

        if ($pageaction == 'addslider') {
            $slider_data['page_id'] =  $sid = $this->input->post('pageid');
            $slider_data['description_heading'] = $this->input->post('description_heading');
            $slider_data['description'] = $this->input->post('description');
            $slider_data['button_text'] = $this->input->post('button_text');
            $slider_data['button_link'] = $this->input->post('button_link');
            $slider_data['created_at'] = date('Y-m-d H:i:s');

            if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                $file = explode(".", $_FILES['pictures']['name']);
                $file_name = str_replace(" ", "-", $file[0]);
                $picture = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                $aws = new AwsSdk();
                $resp = $aws->putToBucket($picture, $_FILES['pictures']['tmp_name'], AWS_S3_BUCKET_NAME);
                $slider_data['background_image'] = $picture;
            }

            $this->pages->add_slider($slider_data);
            $this->session->set_flashdata('message', 'Slider addeded successfully');
        }
        if ($pageaction == 'updateslider') {

            $sid = $this->input->post('sliderid');
            $slider_data['description_heading'] = $this->input->post('description_heading');
            $slider_data['description'] = $this->input->post('description');
            $slider_data['button_text'] = $this->input->post('button_text');
            $slider_data['button_link'] = $this->input->post('button_link');
            $slider_data['updated_at'] = date('Y-m-d H:i:s');


            if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                $file = explode(".", $_FILES['pictures']['name']);
                $file_name = str_replace(" ", "-", $file[0]);
                $picture = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                $aws = new AwsSdk();
                $resp = $aws->putToBucket($picture, $_FILES['pictures']['tmp_name'], AWS_S3_BUCKET_NAME);
                $slider_data['background_image'] = $picture;
            }

            $this->pages->update_slider($sid, $slider_data);
            $this->session->set_flashdata('message', 'Slider updated successfully');
        }


        //
        if ($pageaction == 'addsection') {

            $section_data['page_id'] =  $sid = $this->input->post('pageid');
            $section_data['heading_1'] = $this->input->post('heading_1');
            $section_data['heading_2'] = $this->input->post('heading_2');

            $section_data['description'] = $this->input->post('description');
            $section_data['button_text'] = $this->input->post('button_text');
            $section_data['button_link'] = $this->input->post('button_link');
            $section_data['display_mode'] = $this->input->post('display_mode');
            $section_data['created_at'] = date('Y-m-d H:i:s');

            if (isset($_FILES['sectionpictures']) && $_FILES['sectionpictures']['name'] != '') {
                $file = explode(".", $_FILES['sectionpictures']['name']);
                $file_name = str_replace(" ", "-", $file[0]);
                $picture = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                $aws = new AwsSdk();
                $aws->putToBucket($picture, $_FILES['sectionpictures']['tmp_name'], AWS_S3_BUCKET_NAME);
                $section_data['background_image'] = $picture;
            }

            $this->pages->add_section($section_data);
            $this->session->set_flashdata('message', 'Section addeded successfully');
        }

        return redirect('cms/pages/edit_page/' . $sid);
    }


    /**
     * Handles AJAX requests
     *
     * @accepts POST
     *
     * @return JSON
     */
    function handler()
    {

        $form_data = $this->input->post(NULL, TRUE);

        switch ($form_data['action']) {

            case 'delete_slider':
                $update_data['is_deleted'] = '1';
                $this->pages->delete_slider($form_data['slider_sid'], $update_data);
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed';
                $this->resp($resp);
                break;

            case 'edit_slider':
                $records = $this->pages->getSliderById($form_data['slider_sid']);

                if (!$records) {
                    $resp['Response'] = 'No record found.';
                    $this->resp($resp);
                }
                //
                $resp['Status'] = TRUE;
                $resp['Data'] = $records;
                $this->resp($resp);
                break;
        }
    }

    //
    private function resp($resp)
    {
        header('Content-Type: application/json');
        echo @json_encode($resp);
        exit(0);
    }
}
