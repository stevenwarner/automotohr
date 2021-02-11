<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Photo_gallery extends Public_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in')) {
            require_once(APPPATH . 'libraries/aws/aws.php');
            $this->load->model('Photo_gallery_model');
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $data['title'] = 'Photo Gallery';
            // module security not applied !!!!
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'photo_gallery');
            $data['pictures'] = $this->Photo_gallery_model->get_pictures($company_sid);
            $this->load->view('main/header', $data);
            $this->load->view('photo_gallery/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
    
    public function gallery_view() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $data['title'] = 'Photo Gallery';
            // module security not applied !!!!
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'appearance');
            $data['pictures'] = $this->Photo_gallery_model->get_pictures($company_sid);
            //$this->load->view('main/header', $data);
            $this->load->view('photo_gallery/gallery_view', $data);
            //$this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder() {
        if ($this->session->userdata('logged_in')) {
            $perform_action = $this->input->post('perform_action');
            $sid = $this->input->post('sid');

            switch ($perform_action) {
                case 'delete_photo':
                    if (empty($sid)) {
                        echo json_encode(false);
                    } else {
                        $this->Photo_gallery_model->delete_attachment($sid);
                        echo json_encode(true);
                    }
                    break;
                case 'get_instructions':
                    $view = $this->load->view('photo_gallery/instructions', null, true);

                    $my_return = array();
                    $my_return['view'] = $view;
                    $my_return['title'] = 'How to add Images in Editor';

                    $this->output->set_content_type('application/json');

                    echo json_encode($my_return);
                    break;
                default:
                    break;
            }
        }
    }

    public function add() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Add New Photo to Photo Gallery';
            // module security not applied !!!!
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'appearance');
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('photo_gallery/add');
                $this->load->view('main/footer');
            } else {
                if (isset($_FILES['pictures']) && !empty($_FILES['pictures']['name'])) {
                    $file = explode(".", $_FILES["pictures"]["name"]);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $letter = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    $aws = new AwsSdk();
                    $aws->putToBucket($letter, $_FILES["pictures"]["tmp_name"], CLOUD_GALLERY);

                    $insert = array();
                    $insert['title'] = $this->input->post('title');
                    $insert['file_name'] = $file[0];
                    $insert['company_sid'] = $company_sid;
                    $insert['employer_sid'] = $employer_sid;
                    $insert['uploaded_name'] = $letter;
                    $insert['uploaded_date'] = date('Y-m-d H:i:s');

                    $this->Photo_gallery_model->save_attachment($insert);
                    $this->session->set_flashdata('message', '<b>Success:</b> Attachment uploaded successfully');
                    redirect("photo_gallery", "refresh");
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> Please upload an attachment');
                    redirect("photo_gallery/add", "refresh");
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}