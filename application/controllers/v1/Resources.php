<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 *
 */
class Resources extends Public_Controller
{
    private $header;
    private $footer;
    private $css;
    private $js;
    private $disableMinifiedFiles;

    private $blogCount = 6;

    public function __construct()
    {
        parent::__construct();
        //
        $this->load->model('v1/Resources_model', "resources_model");
        //
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        $this->disableMinifiedFiles = true;
    }

    /**
     * main resources page
     */
    public function index()
    {
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        $pageData = getPageContent("resources", true);
        // meta titles
        $data = [];
        $data['meta'] = [];
        $data['meta']['title'] = $pageData["page"]["meta"]["title"];
        $data['meta']['description'] = $pageData["page"]["meta"]["keyword"];
        $data['meta']['keywords'] = $pageData["page"]["meta"]["description"];
        //
        // get latest blogs
        $data['blogs'] =
            $this->resources_model->getLatestBlogs($this->blogCount, 0);
        //
        // get latest blogs
     //   _e($this->blogCount,true,true);
        $data['resources'] =
            $this->resources_model->getResources($this->blogCount, 0);
        //
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/theme',
            'v1/app/css/resources'
        ], $this->css, 'resources', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min',
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            "v1/plugins/alertifyjs/alertify.min",
            'js/app_helper',
            'v1/resources/main',
        ], $this->js, 'resources', $this->disableMinifiedFiles);


        $this->load->view($this->header, $data);
        $this->load->view('v1/app/resources/resources');
        $this->load->view($this->footer);
    }


    // API routes
    /**
     * get blogs
     */
    public function loadMoreBlogs()
    {
        $page = $this->input->get("page", true) ?? 1;
        $offset = ($page - 1) * $this->blogCount;
        //
        $result = $this->resources_model->getLatestBlogs($this->blogCount, $offset);
        //
        return SendResponse(200, [
            'view' => $this->load->view("v1/app/resources/partials/blogs", [
                'blogs' => $result
            ], true),
            'count' => count($result)
        ]);
    }

    /**
     * get resources
     */
    public function loadMoreResources()
    {
        $page = $this->input->get("page", true) ?? 1;
        $keywords = $this->input->get("keywords", true) ?? "";
        $categories = $this->input->get("categories", true) ?? "";
        $offset = ($page - 1) * $this->blogCount;
        //
        $result = $this->resources_model->getResources(
            $this->blogCount,
            $offset,
            $keywords,
            $categories
        );
        //
        return SendResponse(200, [
            'view' => $this->load->view("v1/app/resources/partials/resources", [
                'resources' => $result
            ], true),
            'count' => count($result)
        ]);
    }

    public function subscribeCommunity()
    {
        if ($this->input->is_ajax_request()) {
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
            echo 'success';
            exit(0);
        }
    }


    /**
     * single 
     */
    public function readMore($slug)
    {

        // meta titles
        $blog =
            $this->resources_model->getBlogDetail($slug);

        $data = [];
        $data['meta'] = [];
        $data['meta']['title'] = $blog['meta_title'];
        $data['meta']['description'] = $blog['meta_description'];
        $data['meta']['keywords'] = $blog['meta_key_word'];

        //
        $data['blog'] = $blog;
        //
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/theme',
            'v1/app/css/resources'
        ], $this->css, 'resources_single', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min',
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            "v1/plugins/alertifyjs/alertify.min",
            'js/app_helper',
            'v1/resources/main',
        ], $this->js, 'resources_single', $this->disableMinifiedFiles);

        $this->load->view($this->header, $data);
        $this->load->view('v1/app/resource_detail');
        $this->load->view($this->footer);
    }
}
