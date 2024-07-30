<?php defined('BASEPATH') || exit('No direct script access allowed');
// add the controller
loadController(
    "modules/payroll/Payroll_base_controller"
);
class Company_benefits extends Payroll_base_controller
{

    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct(true);
        // Call the model
        $this->load->model(
            "v1/Payroll/Company_benefits_model",
            "company_benefits_model"
        );
        // load compulsory plugins
        // plugins
        $this->data['pageCSS'] = [
            base_url("public/v1/plugins/alertifyjs/css/alertify.min.css")
        ];
        $this->data['pageJs'] = [
            base_url("public/v1/plugins/alertifyjs/alertify.min.js")
        ];
    }

    /**
     * main benefits listing page
     */
    public function index()
    {
        // set the title
        $this->data['title'] = "Company Benefits :: " . STORE_NAME;
        //
        $this->data['appCSS'] = $this->loadCssBundle([
            'v1/app/css/loader'
        ], 'benefits');
        //
        $this->data['appJs'] = $this->loadJsBundle([
            'v1/company_benefits/js/main',
        ], 'benefits');
        //
        $this->loadView('v1/benefits/manage');
    }


    // API routes

    /**
     * generate benefit add view
     */
    public function generateBenefitsView()
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $benefits = $this
            ->company_benefits_model
            ->getBenefits(
                $this->companyId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/benefits',
                    ['benefits' => $benefits],
                    true
                )
            ]
        );
    }

    /**
     * generate benefit add view
     */
    public function generateAddView()
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $storeBenefits = $this
            ->company_benefits_model
            ->getStoreBenefits(true);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/add_benefit',
                    ['storeBenefits' => $storeBenefits],
                    true
                )
            ]
        );
    }

    /**
     * generate benefit edit view
     *
     * @param int $benefitId
     */
    public function generateEditView(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $storeBenefits = $this
            ->company_benefits_model
            ->getStoreBenefits(true);
        // get benefit by id
        $benefit = $this
            ->company_benefits_model
            ->getCompanyBenefitById($benefitId);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit',
                    [
                        'storeBenefits' => $storeBenefits,
                        'benefit' => $benefit,
                    ],
                    true
                )
            ]
        );
    }

    /**
     * create benefit
     *
     * @param int $benefitId
     */
    public function updateBenefit(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['description']) {
            $errorArray[] = '"Name" is required.';
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
        // get store benefits
        $gustoResponse = $this
            ->company_benefits_model
            ->updateBenefits(
                $post,
                $benefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }


    /**
     * generate benefit employees edit view
     *
     * @param int $benefitId
     */
    public function generateBenefitEmployeesView(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get payroll employees
        $employees = $this
            ->company_benefits_model
            ->getFilteredPayrollEmployees(
                $this->companyId,
                $benefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit_employees',
                    [
                        'employees' => $employees,
                        'benefitId' => $benefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * updates benefits employees
     *
     * @param int $benefitId
     */
    public function updateBenefitEmployees(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get the sanitized post
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['employees']) {
            $errorArray[] = '"Employees" is required.';
        }
        if (!$post['employee_deductions'] && !$post['company_contribution']) {
            $errorArray[] = '"Employee deductions or company contribution" is required.';
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
        $gustoResponse = $this
            ->company_benefits_model
            ->addEmployeesToBenefit(
                $benefitId,
                $post
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * generate benefit employees listing view
     *
     * @param int $benefitId
     */
    public function generateBenefitEmployeesListingView(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $benefitEmployees = $this
            ->company_benefits_model
            ->getBenefitEmployeeById(
                $benefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/benefit_employees',
                    [
                        'benefitEmployees' => $benefitEmployees,
                        'benefitId' => $benefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * generate benefit employees edit view
     *
     * @param int $employeeBenefitId
     */
    public function generateBenefitEmployeesEditView(int $employeeBenefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $employeeBenefit = $this
            ->company_benefits_model
            ->getEmployeeBenefitById(
                $employeeBenefitId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/benefits/partials/edit_benefit_employee',
                    [
                        'employeeBenefit' => $employeeBenefit,
                        'employeeBenefitId' => $employeeBenefitId
                    ],
                    true
                )
            ]
        );
    }

    /**
     * update employee benefit
     *
     * @param int $employeeBenefitId
     */
    public function updateBenefitEmployee(int $employeeBenefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get the sanitized post
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['employee_deductions'] && !$post['company_contribution']) {
            $errorArray[] = '"Employee deductions or company contribution" is required.';
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
        $gustoResponse = $this
            ->company_benefits_model
            ->updateEmployeeBenefit(
                $this->companyId,
                $employeeBenefitId,
                $post
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * delete employee benefit
     *
     * @param int $employeeBenefitId
     */
    public function deleteBenefitEmployee(int $employeeBenefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        //
        $gustoResponse = $this
            ->company_benefits_model
            ->deleteEmployeeBenefit(
                $this->companyId,
                $employeeBenefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }


    /**
     * create benefit
     */
    public function createBenefit()
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        //
        $post = $this->input->post(null, true);
        //
        $errorArray = [];
        // validation
        if (!$post) {
            $errorArray[] = '"Data" is required.';
        }
        if (!$post['description']) {
            $errorArray[] = '"Name" is required.';
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
        // get store benefits
        $gustoResponse = $this
            ->company_benefits_model
            ->createBenefits(
                $post,
                $this->companyId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }

    /**
     * delete benefit
     *
     * @param int $benefitId
     */
    public function deleteBenefit(int $benefitId)
    {
        // check session and generate proper error
        $this->checkSessionStatus();
        // get store benefits
        $gustoResponse = $this
            ->company_benefits_model
            ->deleteBenefits(
                $benefitId
            );
        //
        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse
        );
    }
}
