<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        require_once(APPPATH . 'libraries/aws/aws.php');
        // Call the model
        $this->load->model("test_model", "tm");
    }



    public function uploadvideofile()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'add_online_videos');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Upload Video';

            $config = array(

                array(
                    'field' => 'perform_action',
                    'label' => 'perform_action',
                    'rules' => 'xss_clean|trim|required'
                )
            );


            $aws = new AwsSdk();
            // $file = $aws->getFromBucket('sample-mp4-file-ExsOs.mp4', AWS_S3_BUCKET_NAME);

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $data['attachments'] = array();
            if ($this->form_validation->run() == false) {
                //
                $this->load->view('main/header', $data);
                $this->load->view('aws_videos_add');
                $this->load->view('main/footer');
            } else {
                $post = $this->input->post(NULL, TRUE);


                if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {

                    if (isset($_FILES['video_upload']) && $_FILES['video_upload']['name'] != '') {
                        $result = put_file_on_aws('video_upload');
                        echo $result;
                    }

                    if ($result != 'error') {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                    }

                    redirect('testing/uploadvideofile', 'refresh');
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
