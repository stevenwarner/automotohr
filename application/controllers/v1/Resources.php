<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 *
 */
class Resources extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
        $data['title'] = "Home";
        $this->load->model('v1/resources_model');
        //                                                                                                                                       
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";  
    }

    public function index()
    {
        //
        // meta titles
        $data = [];
        $data['meta'] = [];
        $data['meta']['title'] = 'Homepage | AutomotoHR.com';
        $data['meta']['description'] = 'AutomotoHR Helps you differentiate your business and Brand from everyone else, with our People Operations platform Everything is in one place on one system Hire to Retire. So HOW DOES YOUR COMPANY STAND OUT? ';
        $data['meta']['keywords'] = 'AutomotoHR,People Operations platform,Business Differentiation,Brand Identity,One System Solution,Hire to Retire,Company Distinctiveness,HR Innovation,Unified HR Management,Branding Strategy,Employee Lifecycle,Streamlined Operations,Personnel Management,HR Efficiency,Competitive Advantage,Employee Experience,Seamless Integration,Organizational Uniqueness,HR Transformation,Comprehensive HR Solution';
        //
        // get latest blogs
        $data['blogs'] =
            $this->resources_model->getLatestBlogs(3,0); 
        //
        // get latest blogs
        $data['resources'] =
            $this->resources_model->getResources(3,0);
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/alertifyjs/css/alertify.min'
        ];

        $data['pageJs'] = [
            'v1/app/js/jquery-1.11.3.min',
            'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js',
            'v1/app/alertifyjs/alertify.min',
            'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js',
            'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js',
        ];


        $data['appCSS'] = bundleCSS([
            'v1/app/css/resources',
            'v1/app/css/main'
        ], $this->css, 'home');

        $data['appJs'] = bundleJs([
            'plugins/bootstrap5/js/bootstrap.bundle',
            'public/v1/js/app/resources',
            'alertifyjs/alertify.min'
        ], $this->js, 'home');

        $this->load->view($this->header, $data);
        $this->load->view('v1/app/resources');
        $this->load->view($this->footer);
    }

    public function subscribeCommunity () {
        if($this->input->is_ajax_request()){
            //
            $checkEmail = $this->resources_model->checkSubscriberAlreadyExist($_POST['scriber_email']);
            //
            if (!$checkEmail) {
                //
                $dataToInsert = [];
                $dataToInsert['email'] = $_POST['scriber_email'];
                $dataToInsert['created_at'] =  date('Y-m-d H:i:s');
                //
                $this->resources_model->addSubscriber($dataToInsert);
            } else {
                //
                $dataToUpdate = [];
                $dataToUpdate['status'] = 1;
                $dataToUpdate['updated_at'] =  date('Y-m-d H:i:s');
                //
                $this->resources_model->updateSubscriber($dataToUpdate, $_POST['scriber_email']);
            }
            //
            echo 'success'; exit(0);
        }    
    }

    public function searchResources () {
        _e($_GET,true,true);
    }

    public function loadMore ($type, $row) {
        if($this->input->is_ajax_request()){
            //
            $result = [];
            $start = 3 * $row;
            //
            if ($type == "blog") {
                $result = $this->resources_model->getLatestBlogs(3, $start); 
            } else if ($type == "resource") {
                $category = isset($_GET['category']) ? implode(',', $_GET['category']) : null;
                $keywords = !empty($_GET['keywords']) ? $_GET['keywords'] : null;
                //
                $result = $this->resources_model->getResources(3, $start, $keywords, $category);
            }
            //
            header('Content-Type: application/json');
            echo json_encode($result); exit(0);
        }    
        
    }

    function readMore ($slug) {
        echo $slug;
    }

    function watchResource ($slug) {
        echo $slug;
    }

}    