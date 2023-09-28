<?php defined('BASEPATH') || exit('No direct script access allowed');

class Benefits extends Admin_Controller
{
    /**
     * for js
     */
    private $js;
    /**
     * for css
     */
    private $css;
    /**
     * logged in person
     */
    private $loggedInId;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model("manage_admin/benefits_model", "benefits_model");
        // set path to CSS file
        $this->css = 'public/v1/css/benefits/';
        // set path to JS file
        $this->js = 'public/v1/js/benefits/';
        // load the ION auth library
        $this->load->library('ion_auth');
        // set the admin id
        $this->loggedInId =
            $this->ion_auth->user()->row()->id;
    }

    /**
     * main benefits listing page
     */
    public function index()
    {
        // check and set the security group
        $this->data['security_details'] = db_get_admin_access_level_details($this->loggedInId);
        // make sure the system is redirecting
        check_access_permissions($this->data['security_details'], 'manage_admin', 'benefits');
        // set the title
        $this->data['title'] = "Benefits :: " . STORE_NAME;
        //
        $this->data['appCSS'] = bundleCSS([
            'css/theme-2021',
            'v1/plugins/ms_modal/main',
            'v1/app/css/loader'
        ], $this->css, 'benefits');
        //
        $this->data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/plugins/ms_modal/main',
            'v1/benefits/js/main',
        ], $this->css, 'benefits');
        // render the view
        $this->render('manage_admin/benefits/index', 'admin_master');
    }

    // API routes
    /**
     * generate view
     */
    public function generateView()
    {
        //
        $categories = $this
            ->benefits_model
            ->getBenefitCategories();
        //
        $benefits = $this
            ->benefits_model
            ->getBenefits();
        //
        return SendResponse(
            200,
            [
                'categoryView' => $this->load->view(
                    'manage_admin/benefits/partials/category_view',
                    ['categories' => $categories],
                    true
                ),
                'benefitView' => !$categories ? '' : $this->load->view(
                    'manage_admin/benefits/partials/benefit_view',
                    ['benefits' => $benefits],
                    true
                )
            ]
        );
    }

    /**
     * generate add view
     */
    public function generateAddBenefitCategoryView()
    {
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/category_add',
                    [],
                    true
                )
            ]
        );
    }

    /**
     * save category
     */
    public function saveBenefitCategory()
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $response = $this
            ->benefits_model
            ->saveBenefitCategory(
                $post
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * generate add view
     *
     * @param int $categoryId
     */
    public function generateEditBenefitCategoryView(int $categoryId)
    {
        $category = $this
            ->benefits_model
            ->getBenefitCategoryById(
                $categoryId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/category_edit',
                    ['category' => $category],
                    true
                )
            ]
        );
    }

    /**
     * updates category
     *
     * @param int $categoryID
     */
    public function updateBenefitCategory(int $categoryId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $response = $this
            ->benefits_model
            ->updateBenefitCategory(
                $post,
                $categoryId
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }


    /**
     * generate benefit add view
     */
    public function generateAddBenefitView()
    {
        // get all categories
        $benefitCategories = $this->benefits_model->getBenefitCategories();
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/benefit_add',
                    [
                        'categories' => $benefitCategories
                    ],
                    true
                )
            ]
        );
    }

    /**
     * save benefit
     */
    public function saveBenefit()
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $response = $this
            ->benefits_model
            ->saveBenefit(
                $post
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * generate benefit add view
     *
     * @param int $benefitId
     */
    public function generateEditBenefitView(int $benefitId)
    {
        // get all categories
        $benefitCategories = $this->benefits_model->getBenefitCategories();
        $benefit = $this->benefits_model->getBenefitById($benefitId);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/benefit_edit',
                    [
                        'categories' => $benefitCategories,
                        'benefit' => $benefit
                    ],
                    true
                )
            ]
        );
    }

    /**
     * updates benefit
     *
     * @param int $benefitId
     */
    public function updateBenefit(int $benefitId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if ($errorArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorArray
                ]
            );
        }
        //
        $response = $this
            ->benefits_model
            ->updateBenefit(
                $post,
                $benefitId
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }
}
