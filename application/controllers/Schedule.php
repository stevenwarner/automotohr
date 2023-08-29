<?php defined('BASEPATH') or exit('No direct script access allowed');

class Schedule extends Public_Controller
{

    function __construct()
    {
        parent::__construct();
        //

    }



    public  function index()
    {

        if (!$this->session->userdata('logged_in')) {
            return redirect(base_url('login'), "refresh");
        }
        if (!checkIfAppIsEnabled('Schedule')) {
            return redirect(base_url('login'), "refresh");
        }
        //
        $data['session']          = $this->session->userdata('logged_in');
        $data['session']          = $this->session->userdata('logged_in');
        $company_sid              = $data['session']['company_detail']['sid'];
        $employers_details        = $data['session']['employer_detail'];
        $employer_sid             = $employers_details['sid'];
        $security_details         = db_get_access_level_details($employer_sid);
        $data['security_details'] = $security_details;
        //
        $data['title']          = 'Schedule';
        $data['employer_sid']   = $employer_sid;
        $data['company_sid']   = $company_sid;
        $data['employer']       = $employer_sid;
        $data['employer']       = $employers_details;
        //
        $data['employers_details'] = $data['session']['employer_detail'];
        $data['load_view'] = false;


        //
        $this->load->view('main/header', $data);
        $this->load->view('schedule/scheduling');
        $this->load->view('main/footer');
    }




    /**
     * Check  user sessiona nd set data
     * Created on: 23-08-2019
     *
     * @param $data     Reference
     * @param $return   Bool
     * Default is 'FALSE'
     *
     * @return VOID
     */
    private function check_login(&$data, $return = FALSE)
    {
        //
        if ($this->input->post('fromPublic', true) && $this->input->post('fromPublic', true) == 1 && !$this->session->userdata('logged_in')) {
            $this->load->config('config');
            $result = $this->timeoff_model->login($this->input->post('employerSid'));

            if ($result) {
                if ($result['employer']['timezone'] == '' || $result['employer']['timezone'] == NULL || !preg_match('/^[A-Z]/', $result['employer']['timezone'])) {
                    if ($result['company']['timezone'] != '' && preg_match('/^[A-Z]/', $result['company']['timezone'])) $result['employer']['timezone'] = $result['company']['timezone'];
                    else $result['employer']['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
                }
                $data['session'] = array(
                    'company_detail' => $result["company"],
                    'employer_detail' => $result["employer"]
                );
            }
        }
        //
        if (!isset($data['session'])) {
            if (!$this->session->userdata('logged_in')) {
                if ($return) return false;
                redirect('login', 'refresh');
            }
            $data['session'] = $this->session->userdata('logged_in');
        }
        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['companyData'] = $data['session']['company_detail'];
        $data['employerData'] = $data['session']['employer_detail'];
        $data['company_name'] = $data['session']['company_detail']['CompanyName'];
        $data['timeoff_format_sid'] = $data['session']['company_detail']['pto_format_sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['is_super_admin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['employee_full_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        if (!$return)
            $data['security_details'] = db_get_access_level_details($data['employer_sid'], NULL, $data['session']);
        if ($return) return true;
    }
}
