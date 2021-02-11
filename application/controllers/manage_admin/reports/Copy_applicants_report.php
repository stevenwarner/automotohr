<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Copy_applicants_report extends Admin_Controller {

    private $res;
    private $limit = 100;

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        //
        $this->res = array( 'Status' => FALSE, 'Response' => 'Invalid request.' );
    }

    /**
     *
     *
     */
    function index() {
        //
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_applicants_report'); // Param2: Redirect URL, Param3: Function Name
        //
        $this->data['page_title'] = 'Copy Applicants Report';
        //
        $this->render('manage_admin/reports/copy_applicants_report');
    }


    /**
     * Handles all AJAX requests
     *
     * @accepts POST
     *
     * @return JSON
     */
    function handler(){
        //
        if(!$this->input->is_ajax_request()) exit(0);
        // 
        if($this->input->method(TRUE) != 'POST') $this->resp();
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        if(!sizeof($formpost) || !isset($formpost['action'])) $this->resp();
        //
        switch ($formpost['action']) {
            case 'fetch_records':
                $reports = $this->advanced_report_model->fetchCopyApplicantData(
                    $formpost['startDate'],
                    $formpost['endDate'],
                    $formpost['page'],
                    $this->limit
                );
                //
                if(!$reports){
                    $this->res['Response'] = 'No record found';
                    $this->resp();
                }
                //
                $this->res['Limit'] = $this->limit;
                $this->res['Status'] = TRUE;
                $this->res['ListSize'] = 5;
                $this->res['Response'] = 'Proceed...';
                if($formpost['page'] == 1){
                    $this->res['Records'] = $reports['Data'];
                    $this->res['TotalRecords'] = $reports['Total'];
                    $this->res['TotalPages'] = ceil($reports['Total'] / $this->limit);
                } else $this->res['Records'] = $reports;

                $this->resp();
            break;

            case 'fetch_detail':
                $reports = $this->advanced_report_model->fetchCopyApplicantDetail(
                    $formpost['token']
                );
                //
                if(!$reports){
                    $this->res['Response'] = 'No record found';
                    $this->resp();
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Proceed...';
                $this->res['Records'] = $reports;

                $this->resp();
            break;
        }
        //
        $this->resp();
    }
    
    /**
     * JSON responder
     *
     * @return VOID
     */
    function resp(){
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }


}
