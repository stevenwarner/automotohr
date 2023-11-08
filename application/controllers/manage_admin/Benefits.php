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
        // load the library
        $this->load->library('Api_auth');
        // call the event
        $this->api_auth->checkAndLogin(
            0,
            $this->loggedInId
        );
        //  
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

    public function carrierManagement () {
        // check and set the security group
        $this->data['security_details'] = db_get_admin_access_level_details($this->loggedInId);
        // make sure the system is redirecting
        check_access_permissions($this->data['security_details'], 'manage_admin', 'benefits');
        // set the title
        $this->data['title'] = "Carrier Benefits :: " . STORE_NAME;
        $this->data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        // get access token
        $this->data['apiAccessToken'] = getApiAccessToken(
            0,
            $this->loggedInId
        );
        //
        // load CSS
        $this->data['PageCSS'] = [
            'css/theme-2021',
            'v1/plugins/ms_uploader/main',
            'v1/plugins/ms_modal/main',
            'v1/app/css/loader'
        ];
        // load JS
        $this->data['PageScripts'] = [
            'js/app_helper',
            'v1/plugins/ms_uploader/main',
            'v1/plugins/ms_modal/main',
            'v1/benefits/js/carrier'
        ];
        // render the view
        $this->render('manage_admin/benefits/carrier_management', 'admin_master');
    }

    // API routes
    /**
     * generate carrier view
     */
    public function generateCarrierView()
    {
        //
        $carriers = $this
            ->benefits_model
            ->getBenefitCarriers();
        //
        return SendResponse(
            200,
            [
                'carrierView' => $this->load->view(
                    'manage_admin/benefits/partials/carrier_view',
                    ['carriers' => $carriers],
                    true
                )
            ]
        );
    }

    /**
     * generate carrier add view
     */
    public function generateAddBenefitCarrierView()
    {
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/carrier_add',
                    [],
                    true
                )
            ]
        );
    }

    /**
     * save carrier
     */
    public function saveBenefitCarrier()
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['ein']) {
            $errorArray[] = '"EIN" is missing.';
        }
        //
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if (!$post['code']) {
            $errorArray[] = '"Code" is missing.';
        }
        //
        if (!$post['logo']) {
            $errorArray[] = '"Logo" is missing.';
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
            ->saveBenefitCarrier(
                $post
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * generate carrier edit view
     *
     * @param int $carrierId
     */
   public function generateEditBenefitCarrierView(int $carrierId)
   {
        $carrier = $this
            ->benefits_model
            ->getBenefitCarrierById(
                $carrierId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/benefits/partials/carrier_edit',
                    ['carrier' => $carrier],
                    true
                )
            ]
        );
   }

    /**
     * updates carrier
     *
     * @param int $carrierId
     */
    public function updateBenefitCarrier(int $carrierId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is missing.';
        }
        if (!$post['ein']) {
            $errorArray[] = '"EIN" is missing.';
        }
        //
        if (!$post['name']) {
            $errorArray[] = '"Name" is missing.';
        }
        //
        if (!$post['code']) {
            $errorArray[] = '"Code" is missing.';
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
            ->updateBenefitCarrier(
                $post,
                $carrierId
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    public function plansManagement (int $benefitId) {
        // check and set the security group
        $this->data['security_details'] = db_get_admin_access_level_details($this->loggedInId);
        // make sure the system is redirecting
        check_access_permissions($this->data['security_details'], 'manage_admin', 'benefits');
        // set the title
        $this->data['title'] = "Carrier Plans :: " . STORE_NAME;
        $this->data['benefitId'] = $benefitId;
        //
        $benefit = $this
            ->benefits_model
            ->getBenefitById(
                $benefitId
            );
        //    
        $this->data['benefitName'] = $benefit['name'];
        $this->data['categoryId'] = $benefit['benefit_categories_sid'];    
        //
        $this->data['categoryName'] = $this
        ->benefits_model
        ->getBenefitCategoryById(
            $benefit['benefit_categories_sid']
        )['name'];    
        //
        // load CSS
        $this->data['PageCSS'] = [
            'css/theme-2021',
            'v1/plugins/ms_uploader/main',
            'v1/plugins/ms_modal/main',
            'v1/app/css/loader'
        ];
        // load JS
        $this->data['PageScripts'] = [
            'js/app_helper',
            'v1/plugins/ms_uploader/main',
            'v1/plugins/ms_modal/main',
            'v1/benefits/js/plans'
        ];
        // render the view
        $this->render('manage_admin/benefits/plans_management', 'admin_master');
    }

    public function generatePlansView (int $benefitId) {
        //
        $plans = $this
            ->benefits_model
            ->getBenefitPlans($benefitId);
        //
        return SendResponse(
            200,
            [
                'plansView' => $this->load->view(
                    'manage_admin/benefits/partials/plans/plans_view',
                    [
                        'plans' => $plans
                    ],
                    true
                )
            ]
        );
    }

    /**
    * generate plan add view
    */
    public function generateAddBenefitPlanView()
    {
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                   'manage_admin/benefits/partials/plans/plan_add',
                   [],
                   true
                )
            ]
        );
    }

    /**
    * generate plan partial
    */
    public function generateAddBenefitPlanPartial ($step) {
        //
        $page = '';
        $data = [];
        //
        switch ($step) {
            case 1:
                $carriers = $this->benefits_model->getBenefitCarriers();
                $data['carriers'] = $carriers;
                $page = 'manage_admin/benefits/partials/plans/plan_detail';
                break;
            case 2:
                $page = 'manage_admin/benefits/partials/plans/plan_coverage';
                break;
            case 3;
                $page = 'manage_admin/benefits/partials/plans/plan_eligibility';
                break;
            case 4:
                $page = 'manage_admin/benefits/partials/plans/plan_enrollment';
                break;
            case 5;
                $page = 'manage_admin/benefits/partials/plans/plan_payroll';
                break;    
        }
        //
        return SendResponse(
            200,
            [
                'partialView' => $this->load->view(
                    $page,
                    $data,
                    true
                )
            ]
        );
    }

    public function getCarrierCode ($carrierId) {
        $carrier = $this
            ->benefits_model
            ->getBenefitCarrierById(
                $carrierId
            );

            return SendResponse(
                200,
                ['carrierCode' => $carrier['code']]
            );    
    }

    public function saveBenefitPlanData () {
        //
        $post = $this->input->post(null, true);
        $response = [];
        //
        switch ($post['action']) {
            case 'save_basic_detail':
                $carrierInfo = $this
                    ->benefits_model
                    ->getBenefitCarrierById(
                        $post['planCarrier']
                    );
                //  
                // echo $post['planStart']; die();
                $startDate = empty($post['planStart']) ? null : DateTime::createFromFormat('m/d/Y', $post['planStart'])->format('Y-m-d');  
                $endDate = empty($post['planEnd']) ? null : DateTime::createFromFormat('m/d/Y', $post['planEnd'])->format('Y-m-d'); 
                //
                $dataToInsert = [];
                $dataToInsert['benefit_sid'] = $post['benefitId'];
                $dataToInsert['name'] = $post['planName'];
                $dataToInsert['carrier_sid'] = $post['planCarrier'];
                $dataToInsert['group_number'] = $carrierInfo['code'];
                $dataToInsert['type'] = $post['planType'];
                $dataToInsert['type_id'] = $post['planTypeId'];
                $dataToInsert['summary'] = $post['planSummary'];
                $dataToInsert['description'] = $post['planDescription'];
                $dataToInsert['start_date'] = $startDate;
                $dataToInsert['end_date'] = $endDate;
                $dataToInsert['rate'] = $post['planRate'];
                $dataToInsert['main_url'] = $post['planMainURL'];
                $dataToInsert['attachment_path'] = $post['planAttachment'];
                $dataToInsert['other_link'] = serialize($post['planOtherLink']);
                //
                $response = $this->benefits_model->savePlanDetail($dataToInsert);
                //
                break;
            case 2:
                $page = 'manage_admin/benefits/partials/plans/plan_coverage';
                break;
            case 3;
                $page = 'manage_admin/benefits/partials/plans/plan_eligibility';
                break;
            case 4:
                $page = 'manage_admin/benefits/partials/plans/plan_enrollment';
                break;
            case 5;
                $page = 'manage_admin/benefits/partials/plans/plan_payroll';
                break;    
        }
        //
        return SendResponse(
            200,
            $response
        ); 
    }
    
}
