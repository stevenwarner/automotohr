<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complynet extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('2022/Company_model', 'company_model');

    }

    /**
     * Complynet index page
     * Created on: 28-05-2019
     *
     * @return VOID
     */
    function index($activeTab = 'dashboard'){
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), "refresh");
        $data['session'] = $this->session->userdata('logged_in');
        $employer_detail = $data['session']['employer_detail'];
        $data['access_level'] = $employer_detail['access_level'];
        $security_details = db_get_access_level_details($employer_detail['sid']);
        $employee_comply_status = $employer_detail['complynet_status'];
        $company_comply_status = $data['session']['company_detail']['complynet_status'];
        $ems_status = $data['session']['company_detail']['ems_status'];
        $data['security_details'] = $security_details;
        $data['title'] = 'ComplyNet';
        $employer_detail  = $data['session']['employer_detail'];
        $data['employee'] = $employer_detail;
        $data['employer'] = $employer_detail;
        $data['activeTab'] = $activeTab;
        $data['security_details'] = $security_details;
        if($data['access_level'] == 'Employee' && $ems_status){
            $data['load_view'] = true;
            $data['activeTab'] = 'login';
        } else{
            $data['load_view'] = false;
        }
        check_access_permissions($security_details, 'dashboard', 'complynet'); // Param2: Redirect URL, Param3: Function Name

        if(!$company_comply_status || !$employee_comply_status){
            $this->session->set_flashdata('message', '<b>Error: </b>Not Accessable!');
            redirect(base_url('dashboard'));
        }
        $this->load->view('main/header', $data);
        $this->load->view('complynet/index');
        $this->load->view('main/footer');
    }

    /**
     * Complynet configuration page
     * Created on: 25-10-2022
     *
     * @return VOID
     */
    function complyNetCompanySetting(){
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), "refresh");
        //
        $session = $this->session->userdata('logged_in');
        $employer_detail = $session['employer_detail'];
        //
        $security_details = db_get_access_level_details($employer_detail['sid']);
        $employee_comply_status = $employer_detail['complynet_status'];
        $company_comply_status = $session['company_detail']['complynet_status'];
        $ems_status = $session['company_detail']['ems_status'];
        //
        check_access_permissions($security_details, 'dashboard', 'complynet'); // Param2: Redirect URL, Param3: Function Name
        //
        if($session['employer_detail']['access_level_plus'] == 0 && ($company_comply_status || !$employee_comply_status)){
            $this->session->set_flashdata('message', '<b>Error: </b>Not Accessable!');
            redirect(base_url('dashboard'));
        }
        //
        $complyNetCompanyDetail = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $session['company_detail']['sid']],
            ['automotohr_id', 'automotohr_name', 'complynet_id', 'complynet_name', 'status', 'created_at'],
            'row_array'
        );
        //
        $data['session'] = $session;
        $data['title'] = 'ComplyNet';
        $data['employee'] = $employer_detail;
        $data['employer'] = $employer_detail;
        $data['company_detail'] = $session['company_detail'];
        $data['security_details'] = $security_details;
        $data['complyNetCompanyDetail'] = $complyNetCompanyDetail;
        //
        $this->load->view('main/header', $data);
        $this->load->view('complynet/company_complynet');
        $this->load->view('main/footer');
    }

}
