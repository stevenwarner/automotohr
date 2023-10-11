<?php defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
    private $header;
    private $footer;
    private $css;
    private $js;
    private $disableBundleFiles;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        $this->disableBundleFiles = false;
    }

    //
    public function products($pageName)
    {
        if ($this->session->userdata('logged_in')) {
            $session_details = $this->session->userdata('logged_in');
            $sid = $session_details['employer_detail']['sid'];
            $security_details = db_get_access_level_details($sid);
            $data['security_details'] = $security_details;
        }

        //
        $productsContent = getPageContent($pageName, true);
        //
        if (!$productsContent) {
            return load404();
        }
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $productsContent['page']['meta']['title'];
        $data['meta']['description'] = $productsContent['page']['meta']['description'];
        $data['meta']['keywords'] = $productsContent['page']['meta']['keywords'];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css",
            'v1/app/plugins/fontawesome/css/all',
        ];

        $data['pageJs'] = [
            'https://code.jquery.com/jquery-3.5.1.min.js',
            'https://www.google.com/recaptcha/api.js',
        ];
        $data['appCSS'] = bundleCSS([
            'alertifyjs/css/alertify',
            'v1/app/css/main',
            'v1/app/css/products',
        ], $this->css, 'products', $this->disableBundleFiles);

        $data['appJs'] = bundleJs([
            'v1/app/plugins/bootstrap5/js/bootstrap',
            'alertifyjs/alertify',
            'js/jquery.validate',
            'js/additional-methods',
            'js/app_helper',
            "v1/app/js/products"
        ], $this->js, 'products', $this->disableBundleFiles);

        //
        $page = getPageNameBySlug($pageName);

        if (empty($page)) {
            redirect(base_url());
        }
        $data['pageSlug'] = 'products/' . $pageName;
        $data['productsContent'] =    $productsContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/products/' .  $page);
        $this->load->view($this->footer);
    }
}
